<?php

namespace Tests\Unit\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Models\User;
use App\Services\Finance\StudentBillService;
use App\Services\Finance\StudentPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StudentPaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentPaymentService $service;

    private StudentBillService $billService;

    private School $school;

    private Student $student;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(StudentPaymentService::class);
        $this->billService = app(StudentBillService::class);

        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'TEST01',
            'is_active' => true,
        ]);

        $this->user = User::factory()->create(['school_id' => $this->school->id]);

        $this->student = Student::create([
            'school_id' => $this->school->id,
            'full_name' => 'Test Student',
            'is_active' => true,
        ]);
    }

    private function createBill(int $amount, string $dueDate = '2026-01-31'): StudentBill
    {
        return $this->billService->createPostedBill([
            'student_id' => $this->student->id,
            'title' => 'Tagihan',
            'issued_date' => '2026-01-01',
            'due_date' => $dueDate,
            'items' => [
                ['name' => 'Item', 'quantity' => 1, 'unit_amount' => $amount],
            ],
        ], $this->user);
    }

    public function test_creates_payment_and_credit_ledger_entry_without_allocation(): void
    {
        $payment = $this->service->createPayment([
            'student_id' => $this->student->id,
            'payment_date' => '2026-01-05',
            'payment_method' => 'cash',
            'amount' => 100000,
        ], $this->user);

        $this->assertSame(100000, $payment->amount);
        $this->assertSame(StudentPayment::STATUS_POSTED, $payment->status);

        $ledgerEntry = FinanceLedgerEntry::query()->where('source_id', $payment->id)->first();
        $this->assertSame(FinanceLedgerEntry::DIRECTION_CREDIT, $ledgerEntry->direction);
        $this->assertSame(100000, $ledgerEntry->amount);
    }

    public function test_throws_when_amount_is_zero_or_negative(): void
    {
        $this->expectException(ValidationException::class);

        $this->service->createPayment([
            'student_id' => $this->student->id,
            'payment_date' => '2026-01-05',
            'payment_method' => 'cash',
            'amount' => 0,
        ], $this->user);
    }

    public function test_full_payment_marks_bill_as_paid(): void
    {
        $bill = $this->createBill(500000);

        $this->service->createPayment([
            'student_id' => $this->student->id,
            'payment_date' => '2026-01-05',
            'payment_method' => 'cash',
            'amount' => 500000,
            'bill_ids' => [$bill->id],
        ], $this->user);

        $bill->refresh();
        $this->assertSame(StudentBill::STATUS_PAID, $bill->status);
        $this->assertSame(500000, $bill->paid_amount);
        $this->assertSame(0, $bill->outstanding_amount);
    }

    public function test_partial_payment_marks_bill_as_partial(): void
    {
        $bill = $this->createBill(500000);

        $this->service->createPayment([
            'student_id' => $this->student->id,
            'payment_date' => '2026-01-05',
            'payment_method' => 'cash',
            'amount' => 200000,
            'bill_ids' => [$bill->id],
        ], $this->user);

        $bill->refresh();
        $this->assertSame(StudentBill::STATUS_PARTIAL, $bill->status);
        $this->assertSame(200000, $bill->paid_amount);
        $this->assertSame(300000, $bill->outstanding_amount);
    }

    public function test_payment_is_allocated_across_multiple_bills_in_due_date_order(): void
    {
        $earlierBill = $this->createBill(300000, '2026-01-10');
        $laterBill = $this->createBill(300000, '2026-02-10');

        // Enough to fully pay the earlier bill and partially pay the later one.
        $this->service->createPayment([
            'student_id' => $this->student->id,
            'payment_date' => '2026-01-05',
            'payment_method' => 'cash',
            'amount' => 400000,
            'bill_ids' => [$laterBill->id, $earlierBill->id],
        ], $this->user);

        $earlierBill->refresh();
        $laterBill->refresh();

        $this->assertSame(StudentBill::STATUS_PAID, $earlierBill->status);
        $this->assertSame(0, $earlierBill->outstanding_amount);

        $this->assertSame(StudentBill::STATUS_PARTIAL, $laterBill->status);
        $this->assertSame(100000, $laterBill->paid_amount);
        $this->assertSame(200000, $laterBill->outstanding_amount);
    }

    public function test_void_payment_reverses_bill_allocation_and_creates_debit_entry(): void
    {
        $bill = $this->createBill(500000);

        $payment = $this->service->createPayment([
            'student_id' => $this->student->id,
            'payment_date' => '2026-01-05',
            'payment_method' => 'cash',
            'amount' => 500000,
            'bill_ids' => [$bill->id],
        ], $this->user);

        $this->service->voidPayment($payment, $this->user, 'Salah input nominal');

        $bill->refresh();
        $this->assertSame(StudentBill::STATUS_POSTED, $bill->status);
        $this->assertSame(0, $bill->paid_amount);
        $this->assertSame(500000, $bill->outstanding_amount);

        $payment->refresh();
        $this->assertSame(StudentPayment::STATUS_VOID, $payment->status);

        $reversalEntry = FinanceLedgerEntry::query()
            ->where('source_id', $payment->id)
            ->where('direction', FinanceLedgerEntry::DIRECTION_DEBIT)
            ->first();

        $this->assertNotNull($reversalEntry);
        $this->assertSame(500000, $reversalEntry->amount);
    }

    public function test_cannot_void_an_already_voided_payment(): void
    {
        $payment = $this->service->createPayment([
            'student_id' => $this->student->id,
            'payment_date' => '2026-01-05',
            'payment_method' => 'cash',
            'amount' => 100000,
        ], $this->user);

        $this->service->voidPayment($payment, $this->user, 'Pertama');

        $this->expectException(ValidationException::class);
        $this->service->voidPayment($payment->fresh(), $this->user, 'Kedua');
    }
}
