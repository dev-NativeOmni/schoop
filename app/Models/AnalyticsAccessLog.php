<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsAccessLog extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'analytics_area',
        'action',
        'route_name',
        'ip_address',
        'user_agent',
        'filters',
        'metadata',
    ];

    protected $casts = [
        'filters' => 'array',
        'metadata' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
