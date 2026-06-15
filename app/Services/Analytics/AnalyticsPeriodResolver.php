<?php

namespace App\Services\Analytics;

use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsPeriodResolver
{
    public function resolve(Request $request): array
    {
        $dateFrom = $request->input('date_from');
        $dateUntil = $request->input('date_until');
        $periodType = $request->input('period_type', 'daily');

        if (! $dateFrom) {
            $dateFrom = Carbon::now()->startOfMonth()->toDateString();
        }

        if (! $dateUntil) {
            $dateUntil = Carbon::now()->toDateString();
        }

        return [
            'date_from' => Carbon::parse($dateFrom),
            'date_until' => Carbon::parse($dateUntil),
            'period_type' => $periodType,
        ];
    }
}
