<?php

namespace App\Services\SaasOps;

use App\Models\SaasSchoolSubscription;
use App\Models\User;

class SubscriptionStatusService
{
    public function __construct(private readonly SaasOpsAuditService $audit) {}

    public function activate(SaasSchoolSubscription $subscription, ?User $actor = null): SaasSchoolSubscription
    {
        $subscription->update([
            'status' => 'active',
            'starts_at' => $subscription->starts_at ?: now()->toDateString(),
            'current_period_start' => $subscription->current_period_start ?: now()->toDateString(),
            'current_period_end' => $subscription->current_period_end ?: now()->addMonth()->toDateString(),
            'grace_until' => null,
        ]);

        $this->audit->log((int) $subscription->school_id, $actor, 'saas.subscription.activated', $subscription);

        return $subscription;
    }

    public function markGracePeriod(SaasSchoolSubscription $subscription, ?User $actor = null): SaasSchoolSubscription
    {
        $subscription->update([
            'status' => 'grace_period',
            'grace_until' => $subscription->grace_until ?: now()->addDays(7)->toDateString(),
        ]);

        $this->audit->log((int) $subscription->school_id, $actor, 'saas.subscription.grace_period', $subscription);

        return $subscription;
    }

    public function suspend(SaasSchoolSubscription $subscription, string $reason, User $actor): SaasSchoolSubscription
    {
        $subscription->update([
            'status' => 'suspended',
            'suspended_at' => now(),
            'suspended_by' => $actor->id,
            'suspend_reason' => $reason,
        ]);

        $this->audit->log((int) $subscription->school_id, $actor, 'saas.subscription.suspended', $subscription, ['reason' => $reason]);

        return $subscription;
    }

    public function cancel(SaasSchoolSubscription $subscription, string $reason, User $actor): SaasSchoolSubscription
    {
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => $actor->id,
            'cancel_reason' => $reason,
        ]);

        $this->audit->log((int) $subscription->school_id, $actor, 'saas.subscription.cancelled', $subscription, ['reason' => $reason]);

        return $subscription;
    }

    public function isAccessible(SaasSchoolSubscription $subscription): bool
    {
        return in_array($subscription->status, ['trial', 'active', 'grace_period'], true);
    }
}
