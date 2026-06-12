<?php

namespace App\Services\SchoolOs;

use App\Models\AttendanceRecord;
use App\Models\FinanceLedgerEntry;
use App\Models\HafalanRecord;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\TahsinAssessment;
use Illuminate\Support\Facades\Schema;

class SchoolOsDashboardService
{
    public function summary(): array
    {
        return [
            'students_count' => Schema::hasTable('students')
                ? Student::query()->count()
                : 0,

            'hafalan_records_today' => Schema::hasTable('hafalan_records')
                ? HafalanRecord::query()->whereDate('created_at', today())->count()
                : 0,

            'mutabaah_records_today' => Schema::hasTable('mutabaah_records')
                ? MutabaahRecord::query()->whereDate('created_at', today())->count()
                : 0,

            'attendance_records_today' => Schema::hasTable('attendance_records')
                ? AttendanceRecord::query()->whereDate('attendance_date', today())->count()
                : 0,

            'tahsin_assessments_this_month' => Schema::hasTable('tahsin_assessments')
                ? TahsinAssessment::query()->whereMonth('assessment_date', now()->month)->whereYear('assessment_date', now()->year)->count()
                : 0,

            'open_finance_bills' => Schema::hasTable('student_bills')
                ? StudentBill::query()->whereIn('status', ['posted', 'partial', 'overdue'])->count()
                : 0,

            'finance_balance_total' => Schema::hasTable('finance_ledger_entries')
                ? $this->financeBalanceTotal()
                : 0,
        ];
    }

    private function financeBalanceTotal(): int
    {
        $debit = (int) FinanceLedgerEntry::query()
            ->where('direction', 'debit')
            ->sum('amount');

        $credit = (int) FinanceLedgerEntry::query()
            ->where('direction', 'credit')
            ->sum('amount');

        return $debit - $credit;
    }
}
