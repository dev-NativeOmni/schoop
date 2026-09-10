<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahsinAssessmentItem extends Model
{
    public const STATUS_MASTERED = 'mastered';

    public const STATUS_PROGRESS = 'progress';

    public const STATUS_WEAK = 'weak';

    public const STATUS_NOT_TESTED = 'not_tested';

    protected $fillable = [
        'tahsin_assessment_id',
        'tahsin_skill_id',
        'score',
        'status',
        'note',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(TahsinAssessment::class, 'tahsin_assessment_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(TahsinSkill::class, 'tahsin_skill_id');
    }
}
