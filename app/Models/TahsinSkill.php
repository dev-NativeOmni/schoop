<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahsinSkill extends Model
{
    use BelongsToTenant;

    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'tahsin_level_id',
        'name',
        'code',
        'description',
        'maximum_score',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'maximum_score' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(TahsinLevel::class, 'tahsin_level_id');
    }

    public function assessmentItems(): HasMany
    {
        return $this->hasMany(TahsinAssessmentItem::class);
    }
}
