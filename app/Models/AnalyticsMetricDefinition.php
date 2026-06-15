<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnalyticsMetricDefinition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'metric_key',
        'name',
        'category',
        'description',
        'formula',
        'unit',
        'aggregation_type',
        'is_sensitive',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_sensitive' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
