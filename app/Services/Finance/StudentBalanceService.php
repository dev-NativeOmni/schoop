<?php

namespace App\Services\Finance;

use App\Models\FinanceLedgerEntry;
use App\Models\Student;

class StudentBalanceService
{
    public function balance(Student $student): array
    {
        $debit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', FinanceLedgerEntry::DIRECTION_DEBIT)
            ->sum('amount');

        $credit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', FinanceLedgerEntry::DIRECTION_CREDIT)
            ->sum('amount');

        return [
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $debit - $credit,
        ];
    }
}
