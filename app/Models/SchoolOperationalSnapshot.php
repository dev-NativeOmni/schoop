<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolOperationalSnapshot extends Model
{
    protected $fillable = [
        'school_id',
        'snapshot_date',
        'period_type',
        'attendance_records_count',
        'present_count',
        'late_count',
        'absent_count',
        'sick_count',
        'permission_count',
        'attendance_rate',
        'late_rate',
        'boarding_roll_call_records_count',
        'boarding_leave_requests_count',
        'boarding_health_logs_count',
        'boarding_discipline_logs_count',
        'raw_metrics',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'attendance_records_count' => 'integer',
        'present_count' => 'integer',
        'late_count' => 'integer',
        'absent_count' => 'integer',
        'sick_count' => 'integer',
        'permission_count' => 'integer',
        'attendance_rate' => 'decimal:2',
        'late_rate' => 'decimal:2',
        'boarding_roll_call_records_count' => 'integer',
        'boarding_leave_requests_count' => 'integer',
        'boarding_health_logs_count' => 'integer',
        'boarding_discipline_logs_count' => 'integer',
        'raw_metrics' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
