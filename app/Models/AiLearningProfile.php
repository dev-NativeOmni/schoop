<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiLearningProfile extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'student_id',
        'profile_date',
        'profile_status',
        'confidence_score',
        'tahfizh_trend',
        'tahsin_trend',
        'mutabaah_trend',
        'attendance_trend',
        'lms_engagement_trend',
        'summary',
        'strengths',
        'focus_areas',
        'evidence',
        'reviewed_by',
        'reviewed_at',
        'published_by',
        'published_at',
    ];

    protected $casts = [
        'profile_date' => 'date',
        'confidence_score' => 'integer',
        'strengths' => 'array',
        'focus_areas' => 'array',
        'evidence' => 'array',
        'reviewed_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function signals(): HasMany
    {
        return $this->hasMany(AiLearningSignal::class, 'ai_learning_profile_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(AiLearningRecommendation::class, 'ai_learning_profile_id');
    }
}
