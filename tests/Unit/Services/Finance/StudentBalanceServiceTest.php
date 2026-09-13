<?php

namespace Tests\Unit\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\School;
use App\Models\Student;
use App\Services\Finance\StudentBalanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentBalanceServiceTest extends TestCase
{
    use RefreshDatabase;

    private StudentBalanceService $service;

    private Student $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(StudentBalanceService::class);

        $school = School::create([
            'name' => 'Test School',
            'code' => 'TEST01',
            'is_active' => true,
        ]);

        $this->student = Student::create([
            'school_id' => $school->id,
            'full_name' => 'Test Student',
            'is_active' => true,
        ]);
    }

    public function test_balance_is_zero_with_no_ledger_entries(): void
    {
        $balance = $this->service->balance($this->student);

        $this->assertSame(['debit' => 0, 'credit' => 0, 'balance' => 0], $balance);
    }

    public function test_balance_nets_debits_and_credits(): void
    {
        FinanceLedgerEntry::create([
            'student_id' => $this->student->id,
            'entry_date' => '2026-01-01',
            'direction' => FinanceLedgerEntry::DIRECTION_DEBIT,
            'amount' => 500000,
            'source_type' => 'test',
            'source_id' => 1,
            'description' => 'Tagihan',
        ]);

        FinanceLedgerEntry::create([
            'student_id' => $this->student->id,
            'entry_date' => '2026-01-05',
            'direction' => FinanceLedgerEntry::DIRECTION_CREDIT,
            'amount' => 200000,
            'source_type' => 'test',
            'source_id' => 2,
            'description' => 'Pembayaran',
        ]);

        $balance = $this->service->balance($this->student);

        $this->assertSame(500000, $balance['debit']);
        $this->assertSame(200000, $balance['credit']);
        $this->assertSame(300000, $balance['balance']);
    }

    public function test_balance_only_counts_entries_for_the_given_student(): void
    {
        $otherStudent = Student::create([
            'school_id' => $this->student->school_id,
            'full_name' => 'Other Student',
            'is_active' => true,
        ]);

        FinanceLedgerEntry::create([
            'student_id' => $otherStudent->id,
            'entry_date' => '2026-01-01',
            'direction' => FinanceLedgerEntry::DIRECTION_DEBIT,
            'amount' => 999999,
            'source_type' => 'test',
            'source_id' => 1,
            'description' => 'Tagihan siswa lain',
        ]);

        $balance = $this->service->balance($this->student);

        $this->assertSame(0, $balance['balance']);
    }
}
