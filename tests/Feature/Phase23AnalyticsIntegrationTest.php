<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Models\AnalyticsMetricDefinition;
use App\Models\AnalyticsSnapshot;
use App\Models\TenantHealthScore;
use App\Models\ExecutiveReportRun;
use App\Models\AnalyticsAccessLog;
use App\Services\Analytics\AnalyticsPrivacyGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase23AnalyticsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;

    private User $superAdmin;
    private User $adminA;
    private User $adminB;
    private User $studentA;
    private User $parentA;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles if empty
        if (Role::query()->count() === 0) {
            $this->artisan('db:seed', ['--class' => 'RoleSeeder'])->assertExitCode(0);
        }

        // Seed metrics if empty
        if (AnalyticsMetricDefinition::query()->count() === 0) {
            $this->artisan('db:seed', ['--class' => 'AnalyticsMetricDefinitionSeeder'])->assertExitCode(0);
        }

        // Create schools
        $this->schoolA = School::query()->create([
            'name' => 'School Tenant A Test',
            'code' => 'SCHATST',
            'is_active' => true,
        ]);

        $this->schoolB = School::query()->create([
            'name' => 'School Tenant B Test',
            'code' => 'SCHBTST',
            'is_active' => true,
        ]);

        // Get roles
        $superAdminRole = Role::query()->where('name', 'super_admin')->first();
        $adminRole = Role::query()->where('name', 'admin')->first();
        $studentRole = Role::query()->where('name', 'student')->first();
        $parentRole = Role::query()->where('name', 'parent')->first();

        // Create users with unique usernames and emails to avoid collisions with seeders
        $this->superAdmin = User::query()->create([
            'name' => 'Super Admin User Test',
            'username' => 'superadmin_test_p23',
            'email' => 'superadmin_test_p23@example.com',
            'password' => bcrypt('password'),
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);

        $this->adminA = User::query()->create([
            'name' => 'Admin School A Test',
            'username' => 'admina_test_p23',
            'email' => 'admina_test_p23@example.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);

        $this->adminB = User::query()->create([
            'name' => 'Admin School B Test',
            'username' => 'adminb_test_p23',
            'email' => 'adminb_test_p23@example.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'school_id' => $this->schoolB->id,
            'is_active' => true,
        ]);

        $this->studentA = User::query()->create([
            'name' => 'Student School A Test',
            'username' => 'studenta_test_p23',
            'email' => 'studenta_test_p23@example.com',
            'password' => bcrypt('password'),
            'role_id' => $studentRole->id,
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);

        $this->parentA = User::query()->create([
            'name' => 'Parent School A Test',
            'username' => 'parenta_test_p23',
            'email' => 'parenta_test_p23@example.com',
            'password' => bcrypt('password'),
            'role_id' => $parentRole->id,
            'school_id' => $this->schoolA->id,
            'is_active' => true,
        ]);

        // Create memberships
        UserSchoolMembership::query()->create([
            'user_id' => $this->adminA->id,
            'school_id' => $this->schoolA->id,
            'role_id' => $adminRole->id,
            'membership_status' => 'active',
            'is_default' => true,
            'joined_at' => now(),
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->adminB->id,
            'school_id' => $this->schoolB->id,
            'role_id' => $adminRole->id,
            'membership_status' => 'active',
            'is_default' => true,
            'joined_at' => now(),
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->studentA->id,
            'school_id' => $this->schoolA->id,
            'role_id' => $studentRole->id,
            'membership_status' => 'active',
            'is_default' => true,
            'joined_at' => now(),
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->parentA->id,
            'school_id' => $this->schoolA->id,
            'role_id' => $parentRole->id,
            'membership_status' => 'active',
            'is_default' => true,
            'joined_at' => now(),
        ]);
    }

    public function test_super_admin_can_access_executive_dashboard(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get('/analytics/executive');

        $response->assertStatus(200);
        $response->assertViewIs('analytics.executive.dashboard');

        // Verify log is written
        $this->assertDatabaseHas('analytics_access_logs', [
            'user_id' => $this->superAdmin->id,
            'analytics_area' => 'executive_dashboard',
        ]);
    }

    public function test_school_admin_cannot_access_executive_dashboard(): void
    {
        $response = $this->actingAs($this->adminA)
            ->get('/analytics/executive');

        $response->assertStatus(403);
    }

    public function test_school_admin_can_access_own_school_dashboard(): void
    {
        $response = $this->actingAs($this->adminA)
            ->withSession(['active_school_id' => $this->schoolA->id])
            ->get('/analytics/school?school_id=' . $this->schoolA->id);

        $response->assertStatus(200);
        $response->assertViewIs('analytics.school.dashboard');

        // Verify log is written
        $this->assertDatabaseHas('analytics_access_logs', [
            'user_id' => $this->adminA->id,
            'school_id' => $this->schoolA->id,
            'analytics_area' => 'school_dashboard',
        ]);
    }

    public function test_school_admin_cannot_access_other_school_dashboard(): void
    {
        $response = $this->actingAs($this->adminA)
            ->withSession(['active_school_id' => $this->schoolA->id])
            ->get('/analytics/school?school_id=' . $this->schoolB->id);

        $response->assertStatus(403);
    }

    public function test_student_and_parent_cannot_access_any_analytics(): void
    {
        $response = $this->actingAs($this->studentA)
            ->get('/analytics/school?school_id=' . $this->schoolA->id);
        $response->assertStatus(403);

        $response = $this->actingAs($this->parentA)
            ->get('/analytics/school?school_id=' . $this->schoolA->id);
        $response->assertStatus(403);
    }

    public function test_privacy_guard_sanitizes_sensitive_keys(): void
    {
        $guard = app(AnalyticsPrivacyGuard::class);

        $sensitiveData = [
            'password' => 'secret123',
            'pin' => '1234',
            'token' => 'abcde12345',
            'school_name' => 'Pesantren Al-Hafiz',
            'metadata' => [
                'api_token' => 'xyz',
                'active_count' => 10,
            ]
        ];

        $sanitized = $guard->removeSensitiveKeys($sensitiveData);

        $this->assertArrayNotHasKey('password', $sanitized);
        $this->assertArrayNotHasKey('pin', $sanitized);
        $this->assertArrayNotHasKey('token', $sanitized);
        $this->assertEquals('Pesantren Al-Hafiz', $sanitized['school_name']);
        $this->assertArrayNotHasKey('api_token', $sanitized['metadata']);
        $this->assertEquals(10, $sanitized['metadata']['active_count']);
    }

    public function test_capture_snapshots_command(): void
    {
        $this->artisan('app:capture-analytics-snapshots')->assertExitCode(0);

        // Check that at least some academic snapshot records were created
        $this->assertDatabaseHas('school_academic_snapshots', [
            'school_id' => $this->schoolA->id,
        ]);
        $this->assertDatabaseHas('school_operational_snapshots', [
            'school_id' => $this->schoolA->id,
        ]);
    }

    public function test_recalculate_health_scores_command(): void
    {
        $this->artisan('app:recalculate-tenant-health-scores')->assertExitCode(0);

        $this->assertDatabaseHas('tenant_health_scores', [
            'school_id' => $this->schoolA->id,
        ]);
    }

    public function test_generate_executive_report_command(): void
    {
        $this->artisan('app:generate-executive-report', ['--period' => 'monthly'])->assertExitCode(0);

        $this->assertDatabaseHas('executive_report_runs', [
            'report_type' => 'monthly',
        ]);
    }

    public function test_analytics_health_check_command(): void
    {
        $this->artisan('app:analytics-health-check')->assertExitCode(0);
    }
}
