<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SaasSchoolSubscription;
use App\Models\SaasSubscriptionPlan;
use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\SubscriptionPlan;
use App\Models\TenantModule;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Services\SaasOps\PlanModuleAccessService;
use Database\Seeders\RoleSeeder;
use Database\Seeders\SystemModuleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionPlanModuleEnforcementTest extends TestCase
{
    use RefreshDatabase;

    private School $school;

    private User $admin;

    private Role $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
        $this->seed(SystemModuleSeeder::class);

        $this->adminRole = Role::query()->where('name', 'admin')->firstOrFail();

        $this->school = School::query()->create([
            'name' => 'Plan Test School',
            'code' => 'PTS',
            'is_active' => true,
        ]);

        $this->admin = User::query()->create([
            'school_id' => $this->school->id,
            'role_id' => $this->adminRole->id,
            'name' => 'Plan Admin',
            'username' => 'plan_admin',
            'email' => 'plan_admin@example.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $this->admin->id,
            'school_id' => $this->school->id,
            'role_id' => $this->adminRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);
    }

    public function test_plan_sync_disables_modules_outside_entitlement(): void
    {
        $subscription = $this->createSubscription(['schoolos', 'tahfizh']);

        app(PlanModuleAccessService::class)->syncTenantModulesForSubscription($subscription);

        $this->assertDatabaseHas('tenant_modules', [
            'school_id' => $this->school->id,
            'module_key' => 'tahfizh',
            'is_enabled' => true,
        ]);

        $this->assertDatabaseHas('tenant_modules', [
            'school_id' => $this->school->id,
            'module_key' => 'attendance',
            'is_enabled' => false,
        ]);
    }

    public function test_tenant_admin_cannot_enable_module_outside_plan(): void
    {
        $subscription = $this->createSubscription(['schoolos', 'tahfizh']);
        app(PlanModuleAccessService::class)->syncTenantModulesForSubscription($subscription);

        $attendanceModule = TenantModule::query()
            ->where('school_id', $this->school->id)
            ->where('module_key', 'attendance')
            ->firstOrFail();

        $response = $this
            ->actingAs($this->admin)
            ->withSession(['active_school_id' => $this->school->id])
            ->put(route('tenancy.modules.update', $attendanceModule), [
                'is_enabled' => '1',
            ]);

        $response->assertForbidden();

        $this->assertFalse($attendanceModule->refresh()->is_enabled);
    }

    public function test_direct_route_to_locked_module_redirects_admin(): void
    {
        $subscription = $this->createSubscription(['schoolos', 'tahfizh']);
        app(PlanModuleAccessService::class)->syncTenantModulesForSubscription($subscription);

        // Admins should be redirected to the billing lock page
        $response = $this
            ->actingAs($this->admin)
            ->withSession(['active_school_id' => $this->school->id])
            ->get(route('attendance.reports.dashboard'));

        $response->assertRedirect(route('billing.locked.module', ['moduleKey' => 'attendance']));

        // Non-admins (e.g. teachers) should get a 403 Forbidden
        $teacherRole = Role::query()->where('name', 'teacher')->firstOrFail();
        $teacher = User::query()->create([
            'school_id' => $this->school->id,
            'role_id' => $teacherRole->id,
            'name' => 'Plan Teacher',
            'username' => 'plan_teacher',
            'email' => 'plan_teacher@example.test',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        UserSchoolMembership::query()->create([
            'user_id' => $teacher->id,
            'school_id' => $this->school->id,
            'role_id' => $teacherRole->id,
            'membership_status' => 'active',
            'is_default' => true,
        ]);

        $response = $this
            ->actingAs($teacher)
            ->withSession(['active_school_id' => $this->school->id])
            ->get(route('attendance.reports.dashboard'));

        $response->assertForbidden();
    }

    public function test_suspended_subscription_blocks_tenant_access(): void
    {
        $subscription = $this->createSubscription(['schoolos', 'tahfizh'], 'suspended');
        app(PlanModuleAccessService::class)->syncTenantModulesForSubscription($subscription);

        $response = $this
            ->actingAs($this->admin)
            ->withSession(['active_school_id' => $this->school->id])
            ->get(route('tenancy.modules.index'));

        $response->assertForbidden();
    }

    private function createSubscription(array $allowedModules, string $status = 'active'): SaasSchoolSubscription
    {
        $plan = SaasSubscriptionPlan::query()->create([
            'code' => 'basic-test-'.strtolower($status),
            'name' => 'Basic Test',
            'billing_cycle' => 'monthly',
            'monthly_price' => 0,
            'yearly_price' => 0,
            'features' => ['Tahfizh'],
            'allowed_modules' => $allowedModules,
            'status' => 'active',
        ]);

        $tenantPlan = SubscriptionPlan::query()->firstOrCreate(
            ['code' => 'test-plan'],
            [
                'name' => 'Test Plan',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'is_active' => true,
            ]
        );

        SchoolSubscription::query()->create([
            'school_id' => $this->school->id,
            'subscription_plan_id' => $tenantPlan->id,
            'status' => $status === 'active' ? 'active' : 'canceled',
            'starts_at' => now()->subDay()->toDateString(),
            'current_period_starts_at' => now()->subDay()->toDateString(),
            'current_period_ends_at' => now()->addMonth()->toDateString(),
        ]);

        return SaasSchoolSubscription::query()->create([
            'school_id' => $this->school->id,
            'saas_subscription_plan_id' => $plan->id,
            'status' => $status,
            'billing_cycle' => 'monthly',
            'starts_at' => now()->toDateString(),
            'current_period_start' => now()->toDateString(),
            'current_period_end' => now()->addMonth()->toDateString(),
        ]);
    }
}
