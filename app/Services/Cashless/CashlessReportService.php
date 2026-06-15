<?php

namespace App\Services\Cashless;

use App\Models\CashlessSale;
use App\Models\CashlessWallet;
use App\Models\CashlessWalletTransaction;

class CashlessReportService
{
    public function dashboard(int $schoolId): array
    {
        $today = now()->toDateString();

        return [
            'wallet_balance' => (int) CashlessWallet::query()->where('school_id', $schoolId)->sum('balance'),
            'wallet_count' => CashlessWallet::query()->where('school_id', $schoolId)->count(),
            'sales_today' => (int) CashlessSale::query()->where('school_id', $schoolId)->whereDate('created_at', $today)->sum('total_amount'),
            'refunds_today' => (int) CashlessSale::query()->where('school_id', $schoolId)->whereDate('created_at', $today)->sum('refunded_amount'),
            'topups_today' => (int) CashlessWalletTransaction::query()->where('school_id', $schoolId)->where('type', 'top_up')->whereDate('created_at', $today)->sum('amount'),
        ];
    }
}
