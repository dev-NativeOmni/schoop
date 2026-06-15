<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahsinAssessment extends Model
{
    use BelongsToTenant;

    use SoftDeletes;

    public const TYPE_PLACEMENT = 'placement';
    public const TYPE_DAILY = 'daily';
    public const TYPE_WEEKLY = 'weekly';
    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_FINAL = 'final';

    public const GRADE_EXCELLENT = 'excellent';
    public const GRADE_GOOD = 'good';
    public const GRADE_FAIR = 'fair';
    public const GRADE_NEEDS_IMPROVEMENT = 'needs_improvement';

    protected $fillable = [
        'school_id',
        'student_id',
        'teacher_id',
        'tahsin_level_id',
        'assessment_date',
        'assessment_type',
        'overall_score',
        'grade',
        'status',
        'note',
        'recommendation',
        'created_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'overall_score' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(TahsinLevel::class, 'tahsin_level_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TahsinAssessmentItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
