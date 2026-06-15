<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsQuizQuestion extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'quiz_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'explanation',
        'score_weight',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'score_weight' => 'integer',
        'sort_order' => 'integer',
    ];

    // Important security rule: when exporting to JSON / API responses,
    // correct_answer should be hidden from being serialized
    protected $hidden = [
        'correct_answer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(LmsQuiz::class, 'quiz_id');
    }
}
