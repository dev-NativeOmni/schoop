<?php

namespace Tests\Unit\Services\Billing;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use App\Models\SubscriptionPlan;
use App\Services\Billing\PlanLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanLimitServiceTest extends TestCase
{
    use RefreshDatabase;

    private PlanLimitService $service;

    private School $school;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(PlanLimitService::class);

        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'TEST01',
            'is_active' => true,
        ]);
    }

    private function subscribeToPlan(array $limits): void
    {
        $plan = SubscriptionPlan::create([
            'code' => 'test-plan-'.uniqid(),
            'name' => 'Test Plan',
            'monthly_price' => 0,
            'yearly_price' => 0,
            'is_active' => true,
            'limits' => $limits,
        ]);

        SchoolSubscription::create([
            'school_id' => $this->school->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay()->toDateString(),
            'current_period_starts_at' => now()->subDay()->toDateString(),
            'current_period_ends_at' => now()->addMonth()->toDateString(),
        ]);
    }

    public function test_get_plan_limit_returns_null_without_active_subscription(): void
    {
        $this->assertNull($this->service->getPlanLimit($this->school, 'max_students'));
    }

    public function test_get_plan_limit_returns_configured_value(): void
    {
        $this->subscribeToPlan(['max_students' => 50]);

        $this->assertSame(50, $this->service->getPlanLimit($this->school, 'max_students'));
    }

    public function test_get_plan_limit_returns_null_for_unset_key(): void
    {
        $this->subscribeToPlan(['max_students' => 50]);

        $this->assertNull($this->service->getPlanLimit($this->school, 'max_teachers'));
    }

    public function test_get_usage_counts_students_for_the_school(): void
    {
        Student::create(['school_id' => $this->school->id, 'full_name' => 'Student A', 'is_active' => true]);
        Student::create(['school_id' => $this->school->id, 'full_name' => 'Student B', 'is_active' => true]);

        $otherSchool = School::create(['name' => 'Other School', 'code' => 'OTHER01', 'is_active' => true]);
        Student::create(['school_id' => $otherSchool->id, 'full_name' => 'Student C', 'is_active' => true]);

        $this->assertSame(2, $this->service->getUsage($this->school, 'students_count'));
    }

    public function test_is_within_limit_is_true_when_no_limit_configured(): void
    {
        $this->assertTrue($this->service->isWithinLimit($this->school, 'max_students'));
    }

    public function test_is_within_limit_is_true_when_under_the_limit(): void
    {
        $this->subscribeToPlan(['max_students' => 10]);

        Student::create(['school_id' => $this->school->id, 'full_name' => 'Student A', 'is_active' => true]);

        $this->assertTrue($this->service->isWithinLimit($this->school, 'max_students'));
    }

    public function test_is_within_limit_is_false_when_adding_would_exceed_the_limit(): void
    {
        $this->subscribeToPlan(['max_students' => 1]);

        Student::create(['school_id' => $this->school->id, 'full_name' => 'Student A', 'is_active' => true]);

        $this->assertFalse($this->service->isWithinLimit($this->school, 'max_students'));
    }

    public function test_is_within_limit_is_true_when_exactly_at_capacity_boundary(): void
    {
        $this->subscribeToPlan(['max_students' => 2]);

        Student::create(['school_id' => $this->school->id, 'full_name' => 'Student A', 'is_active' => true]);

        $this->assertTrue($this->service->isWithinLimit($this->school, 'max_students'));
    }
}
