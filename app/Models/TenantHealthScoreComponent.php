<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantHealthScoreComponent extends Model
{
    protected $fillable = [
        'tenant_health_score_id',
        'component_key',
        'name',
        'max_score',
        'score',
        'explanation',
        'metadata',
    ];

    protected $casts = [
        'max_score' => 'integer',
        'score' => 'integer',
        'metadata' => 'array',
    ];

    public function tenantHealthScore(): BelongsTo
    {
        return $this->belongsTo(TenantHealthScore::class);
    }
}
