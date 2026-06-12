<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\FinanceReportFilterRequest;
use App\Models\Student;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBalanceService;
use Illuminate\View\View;

class StudentFinancePortalController extends Controller
{
    public function index(
        FinanceReportFilterRequest $request,
        FinanceAccessService $accessService,
        StudentBalanceService $balanceService
    ): View {
        abort_unless($accessService->isStudent($request->user()), 403);

        $student = Student::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $student) {
            return view('portal.student.finance', [
                'student' => null,
                'bills' => collect(),
                'payments' => collect(),
                'balance' => null,
            ]);
        }

        $bills = $student->financeBills()
            ->orderByDesc('issued_date')
            ->get();

        $payments = $student->financePayments()
            ->orderByDesc('payment_date')
            ->get();

        $balance = $balanceService->balance($student);

        return view('portal.student.finance', compact(
            'student',
            'bills',
            'payments',
            'balance'
        ));
    }
}
