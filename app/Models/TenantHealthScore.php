<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantHealthScore extends Model
{
    protected $fillable = [
        'school_id',
        'score_date',
        'score',
        'status',
        'summary',
        'risk_flags',
        'recommendations',
    ];

    protected $casts = [
        'score_date' => 'date',
        'score' => 'integer',
        'risk_flags' => 'array',
        'recommendations' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(TenantHealthScoreComponent::class);
    }
}
