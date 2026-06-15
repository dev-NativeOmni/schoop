<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolAcademicSnapshot extends Model
{
    protected $fillable = [
        'school_id',
        'snapshot_date',
        'period_type',
        'active_students_count',
        'active_teachers_count',
        'hafalan_records_count',
        'hafalan_total_lines',
        'tahfizh_target_achievement_rate',
        'students_behind_target_count',
        'mutabaah_records_count',
        'mutabaah_completion_rate',
        'tahsin_assessments_count',
        'tahsin_average_score',
        'raw_metrics',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'active_students_count' => 'integer',
        'active_teachers_count' => 'integer',
        'hafalan_records_count' => 'integer',
        'hafalan_total_lines' => 'integer',
        'tahfizh_target_achievement_rate' => 'decimal:2',
        'students_behind_target_count' => 'integer',
        'mutabaah_records_count' => 'integer',
        'mutabaah_completion_rate' => 'decimal:2',
        'tahsin_assessments_count' => 'integer',
        'tahsin_average_score' => 'decimal:2',
        'raw_metrics' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
