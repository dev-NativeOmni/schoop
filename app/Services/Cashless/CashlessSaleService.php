<?php

namespace App\Services\Cashless;

use App\Models\CashlessPosSession;
use App\Models\CashlessProduct;
use App\Models\CashlessSale;
use App\Models\CashlessSaleItem;
use App\Models\CashlessWallet;
use App\Models\CashlessWalletTransaction;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CashlessSaleService
{
    public function __construct(
        private readonly CashlessNumberGenerator $numbers,
        private readonly CashlessAccessService $access,
        private readonly CashlessWalletService $wallets,
        private readonly CashlessAuditService $audit,
    ) {}

    public function createSale(array $payload, User $cashier): CashlessSale
    {
        $session = CashlessPosSession::query()
            ->with('merchant')
            ->findOrFail($payload['cashless_pos_session_id']);

        $this->access->assertMerchantAccess($cashier, $session->merchant);

        if ($session->status !== 'open') {
            throw new InvalidArgumentException('Session POS sudah ditutup.');
        }

        $existing = CashlessSale::query()
            ->where('school_id', $session->school_id)
            ->where('idempotency_key', $payload['idempotency_key'])
            ->first();

        if ($existing) {
            return $existing->load(['items', 'student', 'wallet', 'merchant']);
        }

        return DB::transaction(function () use ($payload, $cashier, $session): CashlessSale {
            $student = Student::query()->findOrFail($payload['student_id']);
            $this->access->assertStudentInSchool($student, (int) $session->school_id);

            $wallet = CashlessWallet::query()
                ->where('school_id', $session->school_id)
                ->where('student_id', $student->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->wallets->assertWalletCanTransact($wallet);

            $items = [];
            $total = 0;

            foreach ($payload['items'] as $item) {
                $product = CashlessProduct::query()
                    ->where('school_id', $session->school_id)
                    ->where('cashless_merchant_id', $session->cashless_merchant_id)
                    ->where('status', 'active')
                    ->whereKey($item['cashless_product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];

                if ($product->track_stock && $product->stock < $quantity) {
                    throw new InvalidArgumentException("Stok {$product->name} tidak cukup.");
                }

                $subtotal = (int) $product->price * $quantity;
                $total += $subtotal;
                $items[] = [$product, $quantity, $subtotal];
            }

            if ($wallet->balance < $total) {
                $this->audit->log((int) $session->school_id, $cashier, 'sale.failed.insufficient_balance', $wallet, [
                    'student_id' => $student->id,
                    'total' => $total,
                    'balance' => $wallet->balance,
                ]);

                throw new InvalidArgumentException('Saldo wallet santri tidak cukup.');
            }

            $sale = CashlessSale::query()->create([
                'school_id' => $session->school_id,
                'cashless_merchant_id' => $session->cashless_merchant_id,
                'cashless_pos_session_id' => $session->id,
                'cashier_id' => $cashier->id,
                'student_id' => $student->id,
                'cashless_wallet_id' => $wallet->id,
                'transaction_number' => $this->numbers->transaction('SAL'),
                'receipt_number' => $this->numbers->transaction('RCP'),
                'idempotency_key' => $payload['idempotency_key'],
                'total_amount' => $total,
                'status' => 'posted',
                'note' => $payload['note'] ?? null,
                'posted_at' => now(),
            ]);

            foreach ($items as [$product, $quantity, $subtotal]) {
                CashlessSaleItem::query()->create([
                    'school_id' => $session->school_id,
                    'cashless_sale_id' => $sale->id,
                    'cashless_product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                if ($product->track_stock) {
                    $product->decrement('stock', $quantity);
                }
            }

            $before = (int) $wallet->balance;
            $after = $before - $total;

            CashlessWalletTransaction::query()->create([
                'school_id' => $session->school_id,
                'cashless_wallet_id' => $wallet->id,
                'student_id' => $student->id,
                'cashless_sale_id' => $sale->id,
                'actor_id' => $cashier->id,
                'transaction_number' => $this->numbers->transaction('PUR'),
                'type' => 'purchase',
                'direction' => 'debit',
                'amount' => $total,
                'balance_before' => $before,
                'balance_after' => $after,
                'note' => 'Pembelian '.$session->merchant->name,
                'status' => 'posted',
                'posted_at' => now(),
            ]);

            $wallet->update(['balance' => $after]);
            $session->increment('total_sales', $total);
            $session->increment('sales_count');
            $this->audit->log((int) $session->school_id, $cashier, 'sale.posted', $sale, ['amount' => $total]);

            return $sale->load(['items', 'student', 'wallet', 'merchant']);
        });
    }
}
