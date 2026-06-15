<?php

namespace App\Services\Cashless;

use App\Models\CashlessMerchant;
use App\Models\CashlessSale;
use App\Models\CashlessSettlement;
use App\Models\User;
use Carbon\CarbonInterface;
use InvalidArgumentException;

class CashlessSettlementService
{
    public function __construct(
        private readonly CashlessNumberGenerator $numbers,
        private readonly CashlessAccessService $access,
        private readonly CashlessAuditService $audit,
    ) {}

    public function createSettlement(CashlessMerchant $merchant, CarbonInterface $start, CarbonInterface $end, User $actor): CashlessSettlement
    {
        $this->access->assertSameSchool($actor, (int) $merchant->school_id);

        $sales = CashlessSale::query()
            ->where('school_id', $merchant->school_id)
            ->where('cashless_merchant_id', $merchant->id)
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->get();

        $totalSales = (int) $sales->sum('total_amount');
        $totalRefunds = (int) $sales->sum('refunded_amount');

        $settlement = CashlessSettlement::query()->create([
            'school_id' => $merchant->school_id,
            'cashless_merchant_id' => $merchant->id,
            'created_by' => $actor->id,
            'settlement_number' => $this->numbers->transaction('SET'),
            'period_start' => $start->toDateString(),
            'period_end' => $end->toDateString(),
            'total_sales' => $totalSales,
            'total_refunds' => $totalRefunds,
            'net_sales' => $totalSales - $totalRefunds,
            'sales_count' => $sales->count(),
            'status' => 'draft',
        ]);

        $this->audit->log((int) $merchant->school_id, $actor, 'settlement.created', $settlement);

        return $settlement;
    }

    public function approveSettlement(CashlessSettlement $settlement, User $actor): void
    {
        $this->access->assertSameSchool($actor, (int) $settlement->school_id);

        if ($settlement->status !== 'draft' && $settlement->status !== 'submitted') {
            throw new InvalidArgumentException('Settlement tidak bisa disetujui.');
        }

        $settlement->update([
            'status' => 'approved',
            'approved_by' => $actor->id,
            'approved_at' => now(),
        ]);

        $this->audit->log((int) $settlement->school_id, $actor, 'settlement.approved', $settlement);
    }

    public function voidSettlement(CashlessSettlement $settlement, User $actor, string $reason): void
    {
        $this->access->assertSameSchool($actor, (int) $settlement->school_id);

        $settlement->update([
            'status' => 'void',
            'note' => $reason,
        ]);

        $this->audit->log((int) $settlement->school_id, $actor, 'settlement.voided', $settlement, ['reason' => $reason]);
    }
}
