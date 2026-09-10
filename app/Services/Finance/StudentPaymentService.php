<?php

namespace App\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Models\StudentPaymentAllocation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentPaymentService
{
    public function __construct(
        private readonly ReceiptNumberGenerator $receiptNumberGenerator,
    ) {
        //
    }

    public function createPayment(array $data, User $user): StudentPayment
    {
        return DB::transaction(function () use ($data, $user): StudentPayment {
            $student = Student::query()->findOrFail($data['student_id']);

            $amount = (int) $data['amount'];

            if ($amount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Nominal pembayaran harus lebih dari 0.',
                ]);
            }

            $payment = StudentPayment::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'receipt_number' => $this->receiptNumberGenerator->generate(),
                'payment_date' => $data['payment_date'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'amount' => $amount,
                'note' => $data['note'] ?? null,
                'status' => StudentPayment::STATUS_POSTED,
                'received_by' => $user->id,
            ]);

            $remaining = $amount;
            $billIds = collect($data['bill_ids'] ?? [])->filter()->values();

            if ($billIds->isNotEmpty()) {
                $bills = StudentBill::query()
                    ->whereIn('id', $billIds)
                    ->where('student_id', $student->id)
                    ->whereIn('status', [
                        StudentBill::STATUS_POSTED,
                        StudentBill::STATUS_PARTIAL,
                        StudentBill::STATUS_OVERDUE,
                    ])
                    ->orderBy('due_date')
                    ->lockForUpdate()
                    ->get();

                foreach ($bills as $bill) {
                    if ($remaining <= 0) {
                        break;
                    }

                    $allocate = min($remaining, (int) $bill->outstanding_amount);

                    if ($allocate <= 0) {
                        continue;
                    }

                    StudentPaymentAllocation::query()->create([
                        'student_payment_id' => $payment->id,
                        'student_bill_id' => $bill->id,
                        'amount' => $allocate,
                    ]);

                    $newPaid = $bill->paid_amount + $allocate;
                    $newOutstanding = max(0, $bill->total_amount - $newPaid);

                    $bill->update([
                        'paid_amount' => $newPaid,
                        'outstanding_amount' => $newOutstanding,
                        'status' => $newOutstanding === 0
                            ? StudentBill::STATUS_PAID
                            : StudentBill::STATUS_PARTIAL,
                    ]);

                    $remaining -= $allocate;
                }
            }

            FinanceLedgerEntry::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'entry_date' => $data['payment_date'],
                'direction' => FinanceLedgerEntry::DIRECTION_CREDIT,
                'amount' => $amount,
                'source_type' => StudentPayment::class,
                'source_id' => $payment->id,
                'description' => 'Pembayaran '.$payment->receipt_number,
                'created_by' => $user->id,
            ]);

            return $payment->fresh(['student', 'allocations.bill']);
        });
    }

    public function voidPayment(StudentPayment $payment, User $user, string $reason): StudentPayment
    {
        return DB::transaction(function () use ($payment, $user, $reason): StudentPayment {
            $payment = StudentPayment::query()
                ->whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($payment->status === StudentPayment::STATUS_VOID) {
                throw ValidationException::withMessages([
                    'payment' => 'Pembayaran sudah dibatalkan.',
                ]);
            }

            foreach ($payment->allocations()->with('bill')->get() as $allocation) {
                $bill = StudentBill::query()
                    ->whereKey($allocation->student_bill_id)
                    ->lockForUpdate()
                    ->first();

                if (! $bill) {
                    continue;
                }

                $newPaid = max(0, $bill->paid_amount - $allocation->amount);
                $newOutstanding = max(0, $bill->total_amount - $newPaid);

                $bill->update([
                    'paid_amount' => $newPaid,
                    'outstanding_amount' => $newOutstanding,
                    'status' => $newPaid === 0
                        ? StudentBill::STATUS_POSTED
                        : StudentBill::STATUS_PARTIAL,
                ]);
            }

            $payment->update([
                'status' => StudentPayment::STATUS_VOID,
                'voided_by' => $user->id,
                'voided_at' => now(),
                'void_reason' => $reason,
            ]);

            FinanceLedgerEntry::query()->create([
                'school_id' => $payment->school_id,
                'student_id' => $payment->student_id,
                'entry_date' => now()->toDateString(),
                'direction' => FinanceLedgerEntry::DIRECTION_DEBIT,
                'amount' => $payment->amount,
                'source_type' => StudentPayment::class,
                'source_id' => $payment->id,
                'description' => 'Void pembayaran '.$payment->receipt_number,
                'created_by' => $user->id,
            ]);

            return $payment->fresh(['student', 'allocations.bill']);
        });
    }
}
