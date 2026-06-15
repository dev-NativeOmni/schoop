<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsSnapshot extends Model
{
    protected $fillable = [
        'school_id',
        'snapshot_date',
        'period_type',
        'scope',
        'metric_key',
        'metric_value',
        'dimensions',
        'metadata',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'metric_value' => 'decimal:4',
        'dimensions' => 'array',
        'metadata' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
