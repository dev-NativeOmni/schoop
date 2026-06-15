<?php

namespace App\Console\Commands;

use App\Models\CashlessRefund;
use App\Models\CashlessSale;
use App\Models\CashlessWallet;
use App\Models\CashlessWalletTransaction;
use Illuminate\Console\Command;

class CashlessAuditLedgerCommand extends Command
{
    protected $signature = 'app:cashless-audit-ledger {--school_id=} {--fix=no}';

    protected $description = 'Audit cashless wallet balances and ledger consistency.';

    public function handle(): int
    {
        $issues = [];
        $schoolId = $this->option('school_id');
        $fix = $this->option('fix') === 'yes';

        CashlessWallet::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->with('student')
            ->chunkById(100, function ($wallets) use (&$issues, $fix): void {
                foreach ($wallets as $wallet) {
                    if ((int) $wallet->student?->school_id !== (int) $wallet->school_id) {
                        $issues[] = "Wallet {$wallet->id}: student school mismatch";
                    }

                    $credits = CashlessWalletTransaction::query()
                        ->where('cashless_wallet_id', $wallet->id)
                        ->where('direction', 'credit')
                        ->sum('amount');
                    $debits = CashlessWalletTransaction::query()
                        ->where('cashless_wallet_id', $wallet->id)
                        ->where('direction', 'debit')
                        ->sum('amount');
                    $expected = (int) $credits - (int) $debits;

                    if ((int) $wallet->balance !== $expected) {
                        $issues[] = "Wallet {$wallet->id}: balance {$wallet->balance}, expected {$expected}";
                        if ($fix) {
                            $wallet->update(['balance' => max(0, $expected)]);
                        }
                    }
                }
            });

        $zeroTransactions = CashlessWalletTransaction::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->where('amount', 0)
            ->count();
        if ($zeroTransactions > 0) {
            $issues[] = "Zero amount transactions: {$zeroTransactions}";
        }

        $negativeBalances = CashlessWalletTransaction::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->where('balance_after', '<', 0)
            ->count();
        if ($negativeBalances > 0) {
            $issues[] = "Negative balance_after transactions: {$negativeBalances}";
        }

        $salesWithoutLedger = CashlessSale::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->where('status', 'posted')
            ->whereDoesntHave('walletTransactions', fn ($query) => $query->where('type', 'purchase')->where('direction', 'debit'))
            ->count();
        if ($salesWithoutLedger > 0) {
            $issues[] = "Posted sales without purchase debit ledger: {$salesWithoutLedger}";
        }

        $refundsWithoutLedger = CashlessRefund::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->whereDoesntHave('walletTransactions', fn ($query) => $query->whereIn('type', ['refund', 'void_purchase'])->where('direction', 'credit'))
            ->count();
        if ($refundsWithoutLedger > 0) {
            $issues[] = "Refunds without credit ledger: {$refundsWithoutLedger}";
        }

        if ($issues === []) {
            $this->info('Cashless ledger audit: OK');

            return self::SUCCESS;
        }

        foreach ($issues as $issue) {
            $this->error($issue);
        }

        return self::FAILURE;
    }
}
