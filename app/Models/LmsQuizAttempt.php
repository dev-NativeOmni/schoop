<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LmsQuizAttempt extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'quiz_id',
        'student_id',
        'attempt_number',
        'started_at',
        'completed_at',
        'score',
        'is_passed',
        'status',
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score' => 'decimal:2',
        'is_passed' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(LmsQuiz::class, 'quiz_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(LmsQuizAnswer::class, 'quiz_attempt_id');
    }
}
