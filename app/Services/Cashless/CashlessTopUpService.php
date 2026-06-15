<?php

namespace App\Services\Cashless;

use App\Models\CashlessWallet;
use App\Models\CashlessWalletTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CashlessTopUpService
{
    public function __construct(
        private readonly CashlessNumberGenerator $numbers,
        private readonly CashlessWalletService $wallets,
        private readonly CashlessAuditService $audit,
    ) {}

    public function topUp(CashlessWallet $wallet, int $amount, User $actor, ?string $note = null): CashlessWalletTransaction
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal top-up harus lebih dari 0.');
        }

        return DB::transaction(function () use ($wallet, $amount, $actor, $note): CashlessWalletTransaction {
            $locked = CashlessWallet::query()->whereKey($wallet->id)->lockForUpdate()->firstOrFail();
            $this->wallets->assertWalletCanTransact($locked);

            $before = (int) $locked->balance;
            $after = $before + $amount;

            $transaction = CashlessWalletTransaction::query()->create([
                'school_id' => $locked->school_id,
                'cashless_wallet_id' => $locked->id,
                'student_id' => $locked->student_id,
                'actor_id' => $actor->id,
                'transaction_number' => $this->numbers->transaction('TOP'),
                'type' => 'top_up',
                'direction' => 'credit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'note' => $note,
                'status' => 'posted',
                'posted_at' => now(),
            ]);

            $locked->update(['balance' => $after]);
            $this->audit->log((int) $locked->school_id, $actor, 'top_up.posted', $transaction, ['amount' => $amount]);

            return $transaction;
        });
    }

    public function voidTopUp(CashlessWalletTransaction $transaction, User $actor, string $reason): CashlessWalletTransaction
    {
        if ($transaction->type !== 'top_up' || $transaction->direction !== 'credit') {
            throw new InvalidArgumentException('Hanya transaksi top-up yang bisa dibatalkan lewat fitur ini.');
        }

        return DB::transaction(function () use ($transaction, $actor, $reason): CashlessWalletTransaction {
            $wallet = CashlessWallet::query()->whereKey($transaction->cashless_wallet_id)->lockForUpdate()->firstOrFail();

            if ($wallet->balance < $transaction->amount) {
                throw new InvalidArgumentException('Saldo tidak cukup untuk void top-up.');
            }

            $before = (int) $wallet->balance;
            $after = $before - (int) $transaction->amount;

            $void = CashlessWalletTransaction::query()->create([
                'school_id' => $wallet->school_id,
                'cashless_wallet_id' => $wallet->id,
                'student_id' => $wallet->student_id,
                'actor_id' => $actor->id,
                'transaction_number' => $this->numbers->transaction('VTP'),
                'type' => 'void_top_up',
                'direction' => 'debit',
                'amount' => $transaction->amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'note' => $reason,
                'status' => 'posted',
                'posted_at' => now(),
            ]);

            $wallet->update(['balance' => $after]);
            $this->audit->log((int) $wallet->school_id, $actor, 'top_up.voided', $void, ['source_id' => $transaction->id]);

            return $void;
        });
    }
}
