<?php

namespace Tests\Unit\Services\Billing;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\SubscriptionPlan;
use App\Services\Billing\SubscriptionStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    private SubscriptionStatusService $service;

    private School $school;

    private SubscriptionPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(SubscriptionStatusService::class);

        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'TEST01',
            'is_active' => true,
        ]);

        $this->plan = SubscriptionPlan::create([
            'code' => 'test-plan',
            'name' => 'Test Plan',
            'monthly_price' => 0,
            'yearly_price' => 0,
            'is_active' => true,
        ]);
    }

    private function createSubscription(array $overrides = []): SchoolSubscription
    {
        return SchoolSubscription::create(array_merge([
            'school_id' => $this->school->id,
            'subscription_plan_id' => $this->plan->id,
            'status' => 'active',
            'starts_at' => now()->subDay()->toDateString(),
            'current_period_starts_at' => now()->subDay()->toDateString(),
            'current_period_ends_at' => now()->addMonth()->toDateString(),
        ], $overrides));
    }

    public function test_is_active_returns_false_when_no_subscription_exists(): void
    {
        $this->assertFalse($this->service->isActive($this->school));
    }

    public function test_is_active_returns_true_for_active_unexpired_subscription(): void
    {
        $this->createSubscription(['status' => 'active']);

        $this->assertTrue($this->service->isActive($this->school));
    }

    public function test_is_active_returns_true_for_trialing_subscription(): void
    {
        $this->createSubscription(['status' => 'trialing']);

        $this->assertTrue($this->service->isActive($this->school));
    }

    public function test_is_active_returns_false_for_canceled_subscription(): void
    {
        $this->createSubscription(['status' => 'canceled']);

        $this->assertFalse($this->service->isActive($this->school));
    }

    public function test_is_active_returns_false_when_current_period_has_expired(): void
    {
        $this->createSubscription([
            'status' => 'active',
            'current_period_ends_at' => now()->subDay()->toDateString(),
        ]);

        $this->assertFalse($this->service->isActive($this->school));
    }

    public function test_active_subscription_for_returns_the_latest_subscription(): void
    {
        $this->createSubscription(['status' => 'canceled']);
        $latest = $this->createSubscription(['status' => 'active']);

        $result = $this->service->activeSubscriptionFor($this->school);

        $this->assertNotNull($result);
        $this->assertSame($latest->id, $result->id);
    }

    public function test_status_label_returns_none_when_no_subscription_exists(): void
    {
        $this->assertSame('none', $this->service->statusLabel($this->school));
    }

    public function test_status_label_returns_the_latest_subscription_status(): void
    {
        $this->createSubscription(['status' => 'active']);
        $this->createSubscription(['status' => 'suspended']);

        $this->assertSame('suspended', $this->service->statusLabel($this->school));
    }
}
