<?php

namespace App\Services\SaasOps;

use App\Models\SaasSchoolSubscription;
use App\Models\SaasTenantInvoice;
use App\Models\SaasTenantInvoiceItem;
use App\Models\SaasTenantPayment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TenantBillingService
{
    public function __construct(
        private readonly SaasOpsNumberGenerator $numbers,
        private readonly SaasOpsAuditService $audit,
    ) {}

    public function createDraftInvoice(SaasSchoolSubscription $subscription, array $items, ?string $dueDate, User $actor, ?string $note = null): SaasTenantInvoice
    {
        return DB::transaction(function () use ($subscription, $items, $dueDate, $actor, $note): SaasTenantInvoice {
            $invoice = SaasTenantInvoice::query()->create([
                'school_id' => $subscription->school_id,
                'saas_school_subscription_id' => $subscription->id,
                'invoice_number' => $this->numbers->make('INV'),
                'status' => 'draft',
                'due_date' => $dueDate,
                'note' => $note,
            ]);

            $total = 0;
            foreach ($items as $item) {
                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $unitPrice = max(0, (int) ($item['unit_price'] ?? 0));
                $amount = $quantity * $unitPrice;
                $total += $amount;
                SaasTenantInvoiceItem::query()->create([
                    'saas_tenant_invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'amount' => $amount,
                ]);
            }

            $invoice->update([
                'subtotal_amount' => $total,
                'total_amount' => $total,
                'balance_amount' => $total,
            ]);

            $this->audit->log((int) $invoice->school_id, $actor, 'saas.invoice.drafted', $invoice, ['total' => $total]);

            return $invoice->load('items');
        });
    }

    public function issueInvoice(SaasTenantInvoice $invoice, User $actor): SaasTenantInvoice
    {
        if ($invoice->status !== 'draft') {
            throw new InvalidArgumentException('Hanya draft invoice yang bisa di-issue.');
        }

        $invoice->update(['status' => 'issued', 'issued_at' => now()->toDateString()]);
        $this->audit->log((int) $invoice->school_id, $actor, 'saas.invoice.issued', $invoice);

        return $invoice;
    }

    public function postManualPayment(SaasTenantInvoice $invoice, int $amount, User $actor, array $payload = []): SaasTenantPayment
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Nominal pembayaran harus lebih dari 0.');
        }

        return DB::transaction(function () use ($invoice, $amount, $actor, $payload): SaasTenantPayment {
            $locked = SaasTenantInvoice::query()->whereKey($invoice->id)->lockForUpdate()->firstOrFail();
            if ($locked->status === 'void') {
                throw new InvalidArgumentException('Invoice void tidak bisa dibayar.');
            }

            $payment = SaasTenantPayment::query()->create([
                'school_id' => $locked->school_id,
                'saas_tenant_invoice_id' => $locked->id,
                'payment_number' => $this->numbers->make('PAY'),
                'amount' => $amount,
                'payment_date' => $payload['payment_date'] ?? now()->toDateString(),
                'method' => $payload['method'] ?? 'manual_transfer',
                'reference' => $payload['reference'] ?? null,
                'status' => 'posted',
                'received_by' => $actor->id,
                'note' => $payload['note'] ?? null,
            ]);

            $paid = (int) $locked->paid_amount + $amount;
            $balance = max(0, (int) $locked->total_amount - $paid);
            $locked->update([
                'paid_amount' => min($paid, (int) $locked->total_amount),
                'balance_amount' => $balance,
                'status' => $balance === 0 ? 'paid' : 'partial',
            ]);

            $this->audit->log((int) $locked->school_id, $actor, 'saas.payment.posted', $payment, ['amount' => $amount]);

            return $payment;
        });
    }

    public function voidInvoice(SaasTenantInvoice $invoice, User $actor, string $reason): SaasTenantInvoice
    {
        $invoice->update([
            'status' => 'void',
            'voided_at' => now(),
            'voided_by' => $actor->id,
            'void_reason' => $reason,
        ]);
        $this->audit->log((int) $invoice->school_id, $actor, 'saas.invoice.voided', $invoice, ['reason' => $reason]);

        return $invoice;
    }
}
