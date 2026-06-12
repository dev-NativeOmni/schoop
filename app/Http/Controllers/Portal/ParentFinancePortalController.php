<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FinanceReportFilterRequest;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBalanceService;
use Illuminate\View\View;

class ParentFinancePortalController extends Controller
{
    public function index(
        FinanceReportFilterRequest $request,
        FinanceAccessService $accessService,
        StudentBalanceService $balanceService
    ): View {
        abort_unless($accessService->isParent($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('full_name'), $request->user())
            ->get();

        $selectedStudent = null;
        $bills = collect();
        $payments = collect();
        $balance = null;

        if ($students->isNotEmpty()) {
            $selectedStudent = $students->firstWhere('id', (int) $request->input('student_id'))
                ?? $students->first();

            $selectedStudent->load([
                'financeBills.items',
                'financePayments.allocations.bill',
            ]);

            $bills = $selectedStudent->financeBills()
                ->orderByDesc('issued_date')
                ->get();

            $payments = $selectedStudent->financePayments()
                ->orderByDesc('payment_date')
                ->get();

            $balance = $balanceService->balance($selectedStudent);
        }

        return view('portal.parent.finance', compact(
            'students',
            'selectedStudent',
            'bills',
            'payments',
            'balance'
        ));
    }
}
