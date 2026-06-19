<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\SchoolSubscription;

class SubscriptionStatusService
{
    public function activeSubscriptionFor(School $school): ?SchoolSubscription
    {
        return SchoolSubscription::query()
            ->with('plan')
            ->where('school_id', $school->id)
            ->whereIn('status', ['trialing', 'active'])
            ->latest('id')
            ->first();
    }

    public function isActive(School $school): bool
    {
        $subscription = $this->activeSubscriptionFor($school);

        if (! $subscription) {
            return false;
        }

        return $subscription->isActive();
    }

    public function statusLabel(School $school): string
    {
        $subscription = SchoolSubscription::query()
            ->where('school_id', $school->id)
            ->latest('id')
            ->first();

        return $subscription?->status ?? 'none';
    }
}
