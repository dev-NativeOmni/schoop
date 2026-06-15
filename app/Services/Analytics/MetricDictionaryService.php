<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsMetricDefinition;

class MetricDictionaryService
{
    public function listAll(): array
    {
        return AnalyticsMetricDefinition::query()
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category')
            ->toArray();
    }

    public function findByKey(string $key): ?AnalyticsMetricDefinition
    {
        return AnalyticsMetricDefinition::query()->where('metric_key', $key)->first();
    }
}
