<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPracticePlanItem extends Model
{
    protected $fillable = [
        'ai_practice_plan_id',
        'practice_date',
        'item_type',
        'title',
        'description',
        'estimated_minutes',
        'linked_content',
        'completion_status',
        'completed_at',
    ];

    protected $casts = [
        'practice_date' => 'date',
        'estimated_minutes' => 'integer',
        'linked_content' => 'array',
        'completed_at' => 'datetime',
    ];

    public function practicePlan(): BelongsTo
    {
        return $this->belongsTo(AiPracticePlan::class, 'ai_practice_plan_id');
    }
}
