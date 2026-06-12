<?php

namespace App\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentBillItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentBillService
{
    public function __construct(
        private readonly InvoiceNumberGenerator $invoiceNumberGenerator,
    ) {
        //
    }

    public function createPostedBill(array $data, User $user): StudentBill
    {
        return DB::transaction(function () use ($data, $user): StudentBill {
            $student = Student::query()->findOrFail($data['student_id']);
            $items = collect($data['items'] ?? []);

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal satu item tagihan harus diisi.',
                ]);
            }

            $totalAmount = 0;

            $bill = StudentBill::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'class_room_id' => $student->class_room_id ?? null,
                'invoice_number' => $this->invoiceNumberGenerator->generate(),
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'issued_date' => $data['issued_date'],
                'due_date' => $data['due_date'] ?? null,
                'total_amount' => 0,
                'paid_amount' => 0,
                'outstanding_amount' => 0,
                'status' => StudentBill::STATUS_POSTED,
                'created_by' => $user->id,
                'posted_by' => $user->id,
                'posted_at' => now(),
            ]);

            foreach ($items as $item) {
                if (empty($item['name']) && empty($item['unit_amount'])) {
                    continue; // Skip optional empty items
                }

                $quantity = (int) ($item['quantity'] ?? 1);
                $unitAmount = (int) ($item['unit_amount'] ?? 0);
                $lineTotal = $quantity * $unitAmount;

                if ($lineTotal <= 0) {
                    throw ValidationException::withMessages([
                        'items' => 'Nominal item tagihan harus lebih dari 0.',
                    ]);
                }

                StudentBillItem::query()->create([
                    'student_bill_id' => $bill->id,
                    'finance_fee_item_id' => $item['finance_fee_item_id'] ?? null,
                    'name' => $item['name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $quantity,
                    'unit_amount' => $unitAmount,
                    'total_amount' => $lineTotal,
                ]);

                $totalAmount += $lineTotal;
            }

            if ($totalAmount <= 0) {
                throw ValidationException::withMessages([
                    'items' => 'Total tagihan harus lebih dari 0.',
                ]);
            }

            $bill->update([
                'total_amount' => $totalAmount,
                'outstanding_amount' => $totalAmount,
            ]);

            FinanceLedgerEntry::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'entry_date' => $data['issued_date'],
                'direction' => FinanceLedgerEntry::DIRECTION_DEBIT,
                'amount' => $totalAmount,
                'source_type' => StudentBill::class,
                'source_id' => $bill->id,
                'description' => 'Tagihan ' . $bill->invoice_number . ' - ' . $bill->title,
                'created_by' => $user->id,
            ]);

            return $bill->fresh(['student', 'items']);
        });
    }

    public function voidBill(StudentBill $bill, User $user, string $reason): StudentBill
    {
        return DB::transaction(function () use ($bill, $user, $reason): StudentBill {
            $bill = StudentBill::query()
                ->whereKey($bill->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($bill->status === StudentBill::STATUS_VOID) {
                throw ValidationException::withMessages([
                    'bill' => 'Tagihan sudah dibatalkan.',
                ]);
            }

            if ($bill->paid_amount > 0) {
                throw ValidationException::withMessages([
                    'bill' => 'Tagihan yang sudah memiliki pembayaran tidak boleh dibatalkan langsung.',
                ]);
            }

            $bill->update([
                'status' => StudentBill::STATUS_VOID,
                'voided_by' => $user->id,
                'voided_at' => now(),
                'void_reason' => $reason,
            ]);

            FinanceLedgerEntry::query()->create([
                'school_id' => $bill->school_id,
                'student_id' => $bill->student_id,
                'entry_date' => now()->toDateString(),
                'direction' => FinanceLedgerEntry::DIRECTION_CREDIT,
                'amount' => $bill->total_amount,
                'source_type' => StudentBill::class,
                'source_id' => $bill->id,
                'description' => 'Void tagihan ' . $bill->invoice_number,
                'created_by' => $user->id,
            ]);

            return $bill->fresh();
        });
    }
}
