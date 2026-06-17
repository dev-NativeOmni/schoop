<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiFeatureFlag extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'feature_key',
        'label',
        'is_enabled',
        'requires_teacher_review',
        'visible_to_parent',
        'visible_to_student',
        'settings',
        'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'requires_teacher_review' => 'boolean',
        'visible_to_parent' => 'boolean',
        'visible_to_student' => 'boolean',
        'settings' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
