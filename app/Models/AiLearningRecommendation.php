<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiLearningRecommendation extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'student_id',
        'ai_learning_profile_id',
        'recommendation_type',
        'priority',
        'status',
        'title',
        'description',
        'recommended_actions',
        'evidence',
        'related_content',
        'reviewed_by',
        'reviewed_at',
        'published_at',
    ];

    protected $casts = [
        'recommended_actions' => 'array',
        'evidence' => 'array',
        'related_content' => 'array',
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

    public function profile(): BelongsTo
    {
        return $this->belongsTo(AiLearningProfile::class, 'ai_learning_profile_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function practicePlans(): HasMany
    {
        return $this->hasMany(AiPracticePlan::class, 'ai_learning_recommendation_id');
    }
}
