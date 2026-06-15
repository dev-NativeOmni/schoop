<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileApiUsageSnapshot extends Model
{
    protected $fillable = [
        'school_id',
        'snapshot_date',
        'period_type',
        'mobile_devices_count',
        'active_mobile_devices_count',
        'android_devices_count',
        'ios_devices_count',
        'force_update_devices_count',
        'api_requests_count',
        'api_error_count',
        'api_rate_limit_hits_count',
        'api_failed_auth_count',
        'webhook_success_count',
        'webhook_failed_count',
        'raw_metrics',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'mobile_devices_count' => 'integer',
        'active_mobile_devices_count' => 'integer',
        'android_devices_count' => 'integer',
        'ios_devices_count' => 'integer',
        'force_update_devices_count' => 'integer',
        'api_requests_count' => 'integer',
        'api_error_count' => 'integer',
        'api_rate_limit_hits_count' => 'integer',
        'api_failed_auth_count' => 'integer',
        'webhook_success_count' => 'integer',
        'webhook_failed_count' => 'integer',
        'raw_metrics' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
