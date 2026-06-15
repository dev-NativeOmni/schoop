<?php

namespace App\Services\SaasOps;

use App\Models\CashlessSale;
use App\Models\HafalanRecord;
use App\Models\ProductUsageSnapshot;
use App\Models\School;
use App\Models\SupportTicket;
use App\Models\User;

class UsageSnapshotService
{
    public function __construct(private readonly CustomerSuccessHealthService $health) {}

    public function captureForSchool(School $school, ?string $date = null): ProductUsageSnapshot
    {
        $date ??= now()->toDateString();
        $metrics = [
            'active_users' => User::query()->where('school_id', $school->id)->where('is_active', true)->count(),
            'daily_hafalan_records' => class_exists(HafalanRecord::class) ? HafalanRecord::query()->where('school_id', $school->id)->whereDate('created_at', $date)->count() : 0,
            'open_support_tickets' => SupportTicket::query()->where('school_id', $school->id)->whereIn('status', ['open', 'in_progress'])->count(),
            'daily_cashless_sales' => class_exists(CashlessSale::class) ? CashlessSale::query()->where('school_id', $school->id)->whereDate('created_at', $date)->count() : 0,
        ];
        $metrics['daily_activity_count'] = $metrics['daily_hafalan_records'] + $metrics['daily_cashless_sales'];
        $health = $this->health->calculate((int) $school->id, $metrics);

        return ProductUsageSnapshot::query()->updateOrCreate(
            ['school_id' => $school->id, 'snapshot_date' => $date],
            ['metrics' => $metrics, 'health_score' => $health['score'], 'health_status' => $health['status']]
        );
    }
}
