<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Carbon|null $current_period_ends_at
 */
class SchoolSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'subscription_plan_id',
        'status',
        'starts_at',
        'trial_ends_at',
        'current_period_starts_at',
        'current_period_ends_at',
        'canceled_at',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'trial_ends_at' => 'date',
        'current_period_starts_at' => 'date',
        'current_period_ends_at' => 'date',
        'canceled_at' => 'date',
        'metadata' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['trialing', 'active'], true)
            && (
                is_null($this->current_period_ends_at)
                || $this->current_period_ends_at->isFuture()
                || $this->current_period_ends_at->isToday()
            );
    }
}
