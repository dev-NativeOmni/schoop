<?php

namespace App\Services\Analytics;

use App\Models\SchoolFinanceSnapshot;
use Carbon\CarbonInterface;

class FinanceAnalyticsService
{
    public function data(int $schoolId, CarbonInterface $dateFrom, CarbonInterface $dateUntil): array
    {
        $snapshots = SchoolFinanceSnapshot::query()
            ->where('school_id', $schoolId)
            ->whereBetween('snapshot_date', [$dateFrom->toDateString(), $dateUntil->toDateString()])
            ->orderBy('snapshot_date')
            ->get();

        return [
            'snapshots' => $snapshots->toArray(),
            'total_bills' => $snapshots->sum('student_bills_total'),
            'total_payments' => $snapshots->sum('student_payments_total'),
            'total_cashless_purchases' => $snapshots->sum('cashless_purchase_total'),
            'total_cashless_refunds' => $snapshots->sum('cashless_refund_total'),
        ];
    }
}
