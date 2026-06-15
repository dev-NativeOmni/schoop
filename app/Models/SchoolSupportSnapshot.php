<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSupportSnapshot extends Model
{
    protected $fillable = [
        'school_id',
        'snapshot_date',
        'period_type',
        'open_tickets_count',
        'critical_tickets_count',
        'high_tickets_count',
        'medium_tickets_count',
        'low_tickets_count',
        'sla_breached_tickets_count',
        'incidents_count',
        'sev1_incidents_count',
        'sev2_incidents_count',
        'average_first_response_minutes',
        'average_resolution_minutes',
        'raw_metrics',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'open_tickets_count' => 'integer',
        'critical_tickets_count' => 'integer',
        'high_tickets_count' => 'integer',
        'medium_tickets_count' => 'integer',
        'low_tickets_count' => 'integer',
        'sla_breached_tickets_count' => 'integer',
        'incidents_count' => 'integer',
        'sev1_incidents_count' => 'integer',
        'sev2_incidents_count' => 'integer',
        'average_first_response_minutes' => 'decimal:2',
        'average_resolution_minutes' => 'decimal:2',
        'raw_metrics' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
