<?php

namespace App\Services\SchoolOs;

use App\Models\AttendanceRecord;
use App\Models\FinanceLedgerEntry;
use App\Models\HafalanRecord;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\TahsinAssessment;
use Illuminate\Support\Facades\Schema;

class Student360SnapshotService
{
    public function snapshot(Student $student): array
    {
        return [
            'student' => $student->loadMissing(['classRoom', 'parents']),
            'tahfizh' => $this->tahfizh($student),
            'mutabaah' => $this->mutabaah($student),
            'attendance' => $this->attendance($student),
            'tahsin' => $this->tahsin($student),
            'finance' => $this->finance($student),
        ];
    }

    private function tahfizh(Student $student): array
    {
        if (! Schema::hasTable('hafalan_records')) {
            return ['records_count' => 0, 'total_lines' => 0, 'latest_record' => null];
        }

        $query = HafalanRecord::query()->where('student_id', $student->id);

        return [
            'records_count' => (clone $query)->count(),
            'total_lines' => (int) (clone $query)->sum('total_lines'),
            'latest_record' => (clone $query)->latest('record_date')->first(),
        ];
    }

    private function mutabaah(Student $student): array
    {
        if (! Schema::hasTable('mutabaah_records')) {
            return ['records_today' => 0, 'done_today' => 0];
        }

        $query = MutabaahRecord::query()
            ->where('student_id', $student->id)
            ->whereDate('record_date', today());

        return [
            'records_today' => (clone $query)->count(),
            'done_today' => (clone $query)->where('status', 'done')->count(),
        ];
    }

    private function attendance(Student $student): array
    {
        if (! Schema::hasTable('attendance_records')) {
            return ['records_this_month' => 0, 'late_this_month' => 0];
        }

        $query = AttendanceRecord::query()
            ->where('student_id', $student->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year);

        return [
            'records_this_month' => (clone $query)->count(),
            'late_this_month' => (clone $query)->where('status', 'late')->count(),
        ];
    }

    private function tahsin(Student $student): array
    {
        if (! Schema::hasTable('tahsin_assessments')) {
            return ['assessments_count' => 0, 'latest_assessment' => null];
        }

        $query = TahsinAssessment::query()->where('student_id', $student->id);

        return [
            'assessments_count' => (clone $query)->count(),
            'latest_assessment' => (clone $query)->latest('assessment_date')->first(),
        ];
    }

    private function finance(Student $student): array
    {
        if (! Schema::hasTable('finance_ledger_entries')) {
            return ['debit' => 0, 'credit' => 0, 'balance' => 0];
        }

        $debit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', 'debit')
            ->sum('amount');

        $credit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', 'credit')
            ->sum('amount');

        return [
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $debit - $credit,
        ];
    }
}
