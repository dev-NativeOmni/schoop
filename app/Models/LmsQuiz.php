<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsQuiz extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'course_id',
        'lesson_id',
        'title',
        'description',
        'time_limit_minutes',
        'max_attempts',
        'passing_score',
        'is_randomized',
    ];

    protected $casts = [
        'time_limit_minutes' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'decimal:2',
        'is_randomized' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(LmsCourse::class, 'course_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(LmsLesson::class, 'lesson_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(LmsQuizQuestion::class, 'quiz_id')->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(LmsQuizAttempt::class, 'quiz_id');
    }
}
