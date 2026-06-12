<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinanceLedgerEntry;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBalanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceLedgerController extends Controller
{
    public function student(
        Request $request,
        Student $student,
        FinanceAccessService $accessService,
        StudentBalanceService $balanceService
    ): View {
        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $entries = FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->orderBy('entry_date')
            ->orderBy('id')
            ->get();

        $balance = $balanceService->balance($student);

        return view('finance.ledgers.student', compact('student', 'entries', 'balance'));
    }
}
