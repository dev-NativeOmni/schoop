<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasSchoolSubscription extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'saas_subscription_plan_id', 'status', 'billing_cycle', 'starts_at', 'trial_ends_at',
        'current_period_start', 'current_period_end', 'grace_until', 'suspended_at', 'suspended_by',
        'suspend_reason', 'cancelled_at', 'cancelled_by', 'cancel_reason', 'internal_note',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'trial_ends_at' => 'date',
            'current_period_start' => 'date',
            'current_period_end' => 'date',
            'grace_until' => 'date',
            'suspended_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SaasSubscriptionPlan::class, 'saas_subscription_plan_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(SaasTenantInvoice::class);
    }
}
