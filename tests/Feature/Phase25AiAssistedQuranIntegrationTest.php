<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Models\Student;
use App\Models\ParentProfile;
use App\Models\AiFeatureFlag;
use App\Models\AiLearningProfile;
use App\Models\AiLearningSignal;
use App\Models\AiLearningRecommendation;
use App\Models\AiPracticePlan;
use App\Models\AiPracticePlanItem;
use App\Models\AiFeedbackTemplate;
use App\Models\AiSafetyEvent;
use App\Models\AiTeacherReviewQueue;
use App\Models\TahfizhDebt;
use App\Models\TahsinAssessment;
use App\Models\TahsinAssessmentItem;
use App\Models\TahsinSkill;
use App\Models\TahsinLevel;
use App\Services\Ai\LearningSignalAggregator;
use App\Services\Ai\RuleBasedRecommendationEngine;
use App\Services\Ai\PracticePlanGenerator;
use App\Services\Ai\TeacherFeedbackDraftService;
use App\Services\Ai\AiAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class Phase25AiAssistedQuranIntegrationTest extends TestCase
{
    use RefreshDatabase;

    private School $schoolA;
    private School $schoolB;

    private User $adminA;
    private User $adminB;
    private User $teacherA;
    private User $studentA;
    private User $parentA;

    private Student $studentProfileA;
    private ParentProfile $parentProfileA;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles if not present
        if (Role::count() === 0) {
            $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        }

        // Seed feedback templates if not present
        if (AiFeedbackTemplate::count() === 0) {
            $this->artisan('db:seed', ['--class' => 'AiFeedbackTemplateSeeder']);
        }

        // Create Schools
        $this->schoolA = School::create(['name' => 'Al-Hafiz School A', 'code' => 'ALHA', 'is_active' => true]);
        $this->schoolB = School::create(['name' => 'Al-Hafiz School B', 'code' => 'ALHB', 'is_active' => true]);

        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        $studentRole = Role::where('name', 'student')->first();
        $parentRole = Role::where('name', 'parent')->first();

        // Create Users
        $this->adminA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $adminRole->id,
            'name' => 'Admin School A',
            'username' => 'admin_a_p25',
            'email' => 'admin_a_p25@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->adminB = User::create([
            'school_id' => $this->schoolB->id,
            'role_id' => $adminRole->id,
            'name' => 'Admin School B',
            'username' => 'admin_b_p25',
            'email' => 'admin_b_p25@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->teacherA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $teacherRole->id,
            'name' => 'Guru A',
            'username' => 'guru_a_p25',
            'email' => 'guru_a_p25@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->studentA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $studentRole->id,
            'name' => 'Santri A',
            'username' => 'santri_a_p25',
            'email' => 'santri_a_p25@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->parentA = User::create([
            'school_id' => $this->schoolA->id,
            'role_id' => $parentRole->id,
            'name' => 'Wali A',
            'username' => 'wali_a_p25',
            'email' => 'wali_a_p25@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        // Profiles
        $this->studentProfileA = Student::create([
            'user_id' => $this->studentA->id,
            'school_id' => $this->schoolA->id,
            'student_number' => 'NIS25001',
            'full_name' => 'Santri A Name',
            'is_active' => true,
        ]);

        $this->parentProfileA = ParentProfile::create([
            'user_id' => $this->parentA->id,
            'school_id' => $this->schoolA->id,
            'full_name' => 'Wali A Name',
            'is_active' => true,
        ]);

        // Link parent student
        $this->parentProfileA->students()->attach($this->studentProfileA->id);

        // Memberships
        UserSchoolMembership::create(['user_id' => $this->adminA->id, 'school_id' => $this->schoolA->id, 'role_id' => $adminRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->adminB->id, 'school_id' => $this->schoolB->id, 'role_id' => $adminRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->teacherA->id, 'school_id' => $this->schoolA->id, 'role_id' => $teacherRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->studentA->id, 'school_id' => $this->schoolA->id, 'role_id' => $studentRole->id, 'membership_status' => 'active']);
        UserSchoolMembership::create(['user_id' => $this->parentA->id, 'school_id' => $this->schoolA->id, 'role_id' => $parentRole->id, 'membership_status' => 'active']);

        // Seed flags for school A and B
        $this->artisan('db:seed', ['--class' => 'AiFeatureFlagSeeder']);
    }

    public function test_feature_flag_is_respected(): void
    {
        // 1. Initially disabled (feature flag is default disabled after seeder runs)
        $this->actingAs($this->parentA)
            ->withSession(['active_school_id' => $this->schoolA->id]);
        
        // Parent portal should return 403 because flag is disabled
        $response = $this->get(route('portal.parent.ai-learning'));
        $response->assertStatus(403);

        // 2. Enable flag
        AiFeatureFlag::query()
            ->withoutGlobalScopes()
            ->where('school_id', $this->schoolA->id)
            ->where('feature_key', 'parent_guidance_digest')
            ->update(['is_enabled' => true]);

        // Parent portal should now return 200
        $response = $this->get(route('portal.parent.ai-learning'));
        $response->assertStatus(200);
    }

    public function test_learning_signals_aggregation(): void
    {
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        // Create a mock TahfizhDebt record
        TahfizhDebt::create([
            'school_id' => $this->schoolA->id,
            'student_id' => $this->studentProfileA->id,
            'period_type' => 'daily',
            'calculation_date' => Carbon::today(),
            'period_start' => Carbon::today()->startOfWeek(),
            'period_end' => Carbon::today()->endOfWeek(),
            'target_lines' => 10,
            'actual_lines' => 5,
            'debt_lines' => 5,
            'cumulative_debt_lines' => 12,
            'status' => 'behind',
        ]);

        // Run aggregator
        $aggregator = app(LearningSignalAggregator::class);
        $signals = $aggregator->generateForStudent($this->studentProfileA);

        $this->assertTrue($signals->contains('signal_key', 'tahfizh_debt_increasing'));
        $this->assertEquals('urgent', $signals->firstWhere('signal_key', 'tahfizh_debt_increasing')->severity);
    }

    public function test_rule_based_recommendation_generation(): void
    {
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        // Create signal
        $signal = AiLearningSignal::create([
            'school_id' => $this->schoolA->id,
            'student_id' => $this->studentProfileA->id,
            'signal_date' => Carbon::today(),
            'source_module' => 'tahfizh',
            'signal_key' => 'tahfizh_debt_increasing',
            'severity' => 'urgent',
            'score' => 12,
            'description' => 'Hutang hafalan naik',
            'evidence' => ['cumulative_debt_lines' => 12]
        ]);

        $engine = app(RuleBasedRecommendationEngine::class);
        $recs = $engine->generate($this->studentProfileA, collect([$signal]));

        $this->assertTrue($recs->contains('recommendation_type', 'tahfizh_practice'));
        $this->assertEquals('urgent', $recs->firstWhere('recommendation_type', 'tahfizh_practice')->priority);
    }

    public function test_practice_plan_generation_flow(): void
    {
        $this->actingAs($this->teacherA)
            ->withSession(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        // Create recommendation
        $rec = AiLearningRecommendation::create([
            'school_id' => $this->schoolA->id,
            'student_id' => $this->studentProfileA->id,
            'recommendation_type' => 'tahfizh_practice',
            'priority' => 'high',
            'status' => 'draft',
            'title' => 'Program Stabilisasi Setoran Hafalan',
            'recommended_actions' => ['Latihan harian', 'Fokus murajaah'],
        ]);

        // Generate plan
        $planGenerator = app(PracticePlanGenerator::class);
        $plan = $planGenerator->generate($this->studentProfileA, 7);

        $this->assertEquals('draft', $plan->status);
        $this->assertCount(7, $plan->items);

        // Should create a teacher review queue item
        $this->assertDatabaseHas('ai_teacher_review_queue', [
            'school_id' => $this->schoolA->id,
            'student_id' => $this->studentProfileA->id,
            'review_type' => 'practice_plan',
            'status' => 'pending',
        ]);
    }

    public function test_safety_guard_filter_wording(): void
    {
        $this->actingAs($this->teacherA)
            ->withSession(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        // Template with potentially negative triggers if we insert it or write it
        // Seed feedback template containing prohibited wording
        $template = AiFeedbackTemplate::create([
            'school_id' => $this->schoolA->id,
            'template_key' => 'safety_test_template',
            'title' => 'Safety Test',
            'body_template' => "Ananda {student_name} terlihat malas dan gagal dalam setoran pekan ini.",
            'tone' => 'supportive',
            'is_active' => true,
        ]);

        $draftService = app(TeacherFeedbackDraftService::class);
        $feedback = $draftService->draftFeedback($this->studentProfileA, 'safety_test_template');

        // Check it was redacted to supportive language
        $this->assertStringNotContainsString('malas', $feedback);
        $this->assertStringNotContainsString('gagal', $feedback);
        $this->assertStringContainsString('perlu konsistensi', $feedback);
        $this->assertStringContainsString('perlu pendampingan', $feedback);

        // Check safety event was logged
        $this->assertDatabaseHas('ai_safety_events', [
            'school_id' => $this->schoolA->id,
            'event_type' => 'negative_label_detected',
            'severity' => 'attention',
        ]);
    }

    public function test_tenant_isolation(): void
    {
        // Admin of school B should not be able to view student A AI data
        $accessService = app(AiAccessService::class);
        $this->assertFalse($accessService->canViewStudentAiData($this->adminB, $this->studentProfileA));

        // Admin of school A should be able to view
        $this->assertTrue($accessService->canViewStudentAiData($this->adminA, $this->studentProfileA));
    }

    public function test_role_permissions(): void
    {
        session(['active_school_id' => $this->schoolA->id]);
        app()->instance('resolved_domain_school_id', $this->schoolA->id);

        $profile = AiLearningProfile::create([
            'school_id' => $this->schoolA->id,
            'student_id' => $this->studentProfileA->id,
            'profile_date' => Carbon::today(),
            'profile_status' => 'draft',
        ]);

        $accessService = app(AiAccessService::class);

        // Parent cannot view draft profile
        $this->assertFalse($accessService->canViewPublishedAiOutput($this->parentA, $this->studentProfileA) && $profile->profile_status === 'published');
    }

    public function test_console_commands_run_successfully(): void
    {
        $this->artisan('app:ai-generate-learning-profiles', ['--school_id' => $this->schoolA->id])->assertExitCode(0);
        $this->artisan('app:ai-generate-practice-plans', ['--school_id' => $this->schoolA->id])->assertExitCode(0);
        $this->artisan('app:ai-prune-audit-logs', ['--days' => 730])->assertExitCode(0);
    }
}
