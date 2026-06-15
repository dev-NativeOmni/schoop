<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LmsQuizAnswer extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'quiz_attempt_id',
        'quiz_question_id',
        'student_answer',
        'is_correct',
        'score_obtained',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score_obtained' => 'decimal:2',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(LmsQuizAttempt::class, 'quiz_attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(LmsQuizQuestion::class, 'quiz_question_id');
    }
}
