<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\StoreStudentBillRequest;
use App\Models\FinanceFeeItem;
use App\Models\Student;
use App\Models\StudentBill;
use App\Services\Finance\FinanceAccessService;
use App\Services\Finance\StudentBillService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentBillController extends Controller
{
    public function index(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewFinanceReport($request->user()), 403);

        $bills = StudentBill::query()
            ->with(['student.classRoom'])
            ->orderByDesc('issued_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('finance.bills.index', compact('bills'));
    }

    public function create(Request $request, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $students = Student::query()
            ->with('classRoom')
            ->orderBy('full_name')
            ->limit(500)
            ->get();

        $feeItems = FinanceFeeItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('finance.bills.create', compact('students', 'feeItems'));
    }

    public function store(
        StoreStudentBillRequest $request,
        StudentBillService $billService
    ): RedirectResponse {
        $bill = $billService->createPostedBill(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('finance.bills.show', $bill)
            ->with('success', 'Tagihan santri berhasil dibuat.');
    }

    public function show(Request $request, StudentBill $bill, FinanceAccessService $accessService): View
    {
        abort_unless($accessService->canViewStudent($request->user(), $bill->student), 403);

        $bill->load(['student.classRoom', 'items.feeItem', 'allocations.payment']);

        return view('finance.bills.show', compact('bill'));
    }

    public function void(
        Request $request,
        StudentBill $bill,
        FinanceAccessService $accessService,
        StudentBillService $billService
    ): RedirectResponse {
        abort_unless($accessService->canManageFinance($request->user()), 403);

        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'max:2000'],
        ]);

        $billService->voidBill($bill, $request->user(), $validated['void_reason']);

        return redirect()
            ->route('finance.bills.show', $bill)
            ->with('success', 'Tagihan berhasil dibatalkan.');
    }
}
