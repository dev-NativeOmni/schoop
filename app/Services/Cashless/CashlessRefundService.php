<?php

namespace App\Services\Cashless;

use App\Models\CashlessRefund;
use App\Models\CashlessSale;
use App\Models\CashlessWallet;
use App\Models\CashlessWalletTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CashlessRefundService
{
    public function __construct(
        private readonly CashlessNumberGenerator $numbers,
        private readonly CashlessAccessService $access,
        private readonly CashlessAuditService $audit,
    ) {}

    public function refundSale(CashlessSale $sale, int $amount, User $actor, string $reason): CashlessRefund
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal refund harus lebih dari 0.');
        }

        $this->access->assertSameSchool($actor, (int) $sale->school_id);

        return DB::transaction(function () use ($sale, $amount, $actor, $reason): CashlessRefund {
            $lockedSale = CashlessSale::query()->whereKey($sale->id)->lockForUpdate()->firstOrFail();
            $remaining = (int) $lockedSale->total_amount - (int) $lockedSale->refunded_amount;

            if ($amount > $remaining) {
                throw new InvalidArgumentException('Nominal refund melebihi sisa transaksi.');
            }

            $wallet = CashlessWallet::query()->whereKey($lockedSale->cashless_wallet_id)->lockForUpdate()->firstOrFail();
            $before = (int) $wallet->balance;
            $after = $before + $amount;

            $refund = CashlessRefund::query()->create([
                'school_id' => $lockedSale->school_id,
                'cashless_sale_id' => $lockedSale->id,
                'cashless_wallet_id' => $wallet->id,
                'actor_id' => $actor->id,
                'refund_number' => $this->numbers->transaction($amount === $remaining ? 'VOD' : 'REF'),
                'amount' => $amount,
                'type' => $amount === $remaining ? 'void' : 'refund',
                'status' => 'posted',
                'reason' => $reason,
                'posted_at' => now(),
            ]);

            CashlessWalletTransaction::query()->create([
                'school_id' => $lockedSale->school_id,
                'cashless_wallet_id' => $wallet->id,
                'student_id' => $wallet->student_id,
                'cashless_sale_id' => $lockedSale->id,
                'cashless_refund_id' => $refund->id,
                'actor_id' => $actor->id,
                'transaction_number' => $this->numbers->transaction('RFD'),
                'type' => $amount === $remaining ? 'void_purchase' : 'refund',
                'direction' => 'credit',
                'amount' => $amount,
                'balance_before' => $before,
                'balance_after' => $after,
                'note' => $reason,
                'status' => 'posted',
                'posted_at' => now(),
            ]);

            $wallet->update(['balance' => $after]);
            $lockedSale->increment('refunded_amount', $amount);
            $lockedSale->refresh();
            $lockedSale->update([
                'status' => $lockedSale->refunded_amount >= $lockedSale->total_amount ? 'refunded' : 'partially_refunded',
            ]);
            $lockedSale->session?->increment('total_refunds', $amount);

            $this->audit->log((int) $lockedSale->school_id, $actor, 'sale.refunded', $refund, ['sale_id' => $lockedSale->id]);

            return $refund;
        });
    }

    public function voidSale(CashlessSale $sale, User $actor, string $reason): CashlessRefund
    {
        $remaining = (int) $sale->total_amount - (int) $sale->refunded_amount;

        return $this->refundSale($sale, $remaining, $actor, $reason);
    }
}
