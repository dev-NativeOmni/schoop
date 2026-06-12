<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreStudentPaymentRequest;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentPaymentController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $payments = StudentPayment::query()
            ->with(['student.classRoom'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('finance.payments.index', compact('payments'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $students = Student::query()
            ->with('classRoom')
            ->orderBy('full_name')
            ->limit(500)
            ->get();

        $openBills = StudentBill::query()
            ->with('student')
            ->whereIn('status', [
                StudentBill::STATUS_POSTED,
                StudentBill::STATUS_PARTIAL,
                StudentBill::STATUS_OVERDUE,
            ])
            ->orderBy('due_date')
            ->get();

        return view('finance.payments.create', compact('students', 'openBills'));
    }

    public function store(
        StoreStudentPaymentRequest $request,
        StudentPaymentService $paymentService
    ): RedirectResponse {
        $payment = $paymentService->createPayment(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('finance.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(Request $request, StudentPayment $payment, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewStudent($request->user(), $payment->student), 403);

        $payment->load(['student.classRoom', 'allocations.bill']);

        return view('finance.payments.show', compact('payment'));
    }

    public function void(
        Request $request,
        StudentPayment $payment,
        FinanceAccessService $accessService,
        StudentPaymentService $paymentService
    ): RedirectResponse {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'max:2000'],
        ]);

        $paymentService->voidPayment($payment, $request->user(), $validated['void_reason']);

        return redirect()
            ->route('finance.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}
