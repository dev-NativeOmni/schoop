<?php

namespace Tests\Unit\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\User;
use App\Services\Finance\StudentBillService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StudentBillServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentBillService $service;

    private School $school;

    private Student $student;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(StudentBillService::class);

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

    public function test_creates_posted_bill_with_correct_total_and_ledger_entry(): void
    {
        $bill = $this->service->createPostedBill([
            'student_id' => $this->student->id,
            'title' => 'SPP Bulan Januari',
            'issued_date' => '2026-01-01',
            'items' => [
                ['name' => 'SPP', 'quantity' => 1, 'unit_amount' => 500000],
                ['name' => 'Uang Buku', 'quantity' => 2, 'unit_amount' => 50000],
            ],
        ], $this->user);

        $this->assertSame(600000, $bill->total_amount);
        $this->assertSame(600000, $bill->outstanding_amount);
        $this->assertSame(0, $bill->paid_amount);
        $this->assertSame(StudentBill::STATUS_POSTED, $bill->status);
        $this->assertCount(2, $bill->items);

        $ledgerEntry = FinanceLedgerEntry::query()->where('source_id', $bill->id)->first();
        $this->assertNotNull($ledgerEntry);
        $this->assertSame(FinanceLedgerEntry::DIRECTION_DEBIT, $ledgerEntry->direction);
        $this->assertSame(600000, $ledgerEntry->amount);
    }

    public function test_throws_when_no_items_provided(): void
    {
        $this->expectException(ValidationException::class);

        $this->service->createPostedBill([
            'student_id' => $this->student->id,
            'title' => 'Tagihan Kosong',
            'issued_date' => '2026-01-01',
            'items' => [],
        ], $this->user);
    }

    public function test_throws_when_item_line_total_is_zero(): void
    {
        $this->expectException(ValidationException::class);

        $this->service->createPostedBill([
            'student_id' => $this->student->id,
            'title' => 'Tagihan Invalid',
            'issued_date' => '2026-01-01',
            'items' => [
                ['name' => 'Item Gratis', 'quantity' => 1, 'unit_amount' => 0],
            ],
        ], $this->user);
    }

    public function test_rolls_back_bill_creation_when_items_are_invalid(): void
    {
        try {
            $this->service->createPostedBill([
                'student_id' => $this->student->id,
                'title' => 'Tagihan Invalid',
                'issued_date' => '2026-01-01',
                'items' => [
                    ['name' => 'Item Gratis', 'quantity' => 1, 'unit_amount' => 0],
                ],
            ], $this->user);
        } catch (ValidationException) {
            // expected
        }

        $this->assertSame(0, StudentBill::query()->count());
    }

    public function test_void_bill_marks_status_and_creates_reversal_ledger_entry(): void
    {
        $bill = $this->service->createPostedBill([
            'student_id' => $this->student->id,
            'title' => 'SPP Bulan Januari',
            'issued_date' => '2026-01-01',
            'items' => [
                ['name' => 'SPP', 'quantity' => 1, 'unit_amount' => 500000],
            ],
        ], $this->user);

        $voided = $this->service->voidBill($bill, $this->user, 'Salah input');

        $this->assertSame(StudentBill::STATUS_VOID, $voided->status);
        $this->assertSame('Salah input', $voided->void_reason);
        $this->assertSame($this->user->id, $voided->voided_by);

        $creditEntry = FinanceLedgerEntry::query()
            ->where('source_id', $bill->id)
            ->where('direction', FinanceLedgerEntry::DIRECTION_CREDIT)
            ->first();

        $this->assertNotNull($creditEntry);
        $this->assertSame(500000, $creditEntry->amount);
    }

    public function test_cannot_void_an_already_voided_bill(): void
    {
        $bill = $this->service->createPostedBill([
            'student_id' => $this->student->id,
            'title' => 'SPP Bulan Januari',
            'issued_date' => '2026-01-01',
            'items' => [
                ['name' => 'SPP', 'quantity' => 1, 'unit_amount' => 500000],
            ],
        ], $this->user);

        $this->service->voidBill($bill, $this->user, 'Pertama kali');

        $this->expectException(ValidationException::class);
        $this->service->voidBill($bill->fresh(), $this->user, 'Kedua kali');
    }

    public function test_cannot_void_a_bill_that_already_has_payments(): void
    {
        $bill = $this->service->createPostedBill([
            'student_id' => $this->student->id,
            'title' => 'SPP Bulan Januari',
            'issued_date' => '2026-01-01',
            'items' => [
                ['name' => 'SPP', 'quantity' => 1, 'unit_amount' => 500000],
            ],
        ], $this->user);

        $bill->update(['paid_amount' => 100000]);

        $this->expectException(ValidationException::class);
        $this->service->voidBill($bill->fresh(), $this->user, 'Coba batalkan');
    }
}
