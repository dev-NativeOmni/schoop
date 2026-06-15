<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashless\CashlessReportFilterRequest;
use App\Models\CashlessSale;
use App\Models\CashlessWalletTransaction;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessReportService;
use Illuminate\View\View;

class CashlessReportController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessReportService $reports) {}

    public function dashboard(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $summary = $this->reports->dashboard($schoolId);
        $recentSales = CashlessSale::query()->with(['student', 'merchant'])->where('school_id', $schoolId)->latest()->limit(10)->get();

        return view('cashless.reports.dashboard', compact('summary', 'recentSales'));
    }

    public function walletTransactions(CashlessReportFilterRequest $request): View
    {
        $schoolId = $this->access->activeSchoolId($request->user());
        $transactions = CashlessWalletTransaction::query()->with('student')->where('school_id', $schoolId)->latest()->paginate(30);

        return view('cashless.reports.wallet-transactions', compact('transactions'));
    }

    public function merchantSales(CashlessReportFilterRequest $request): View
    {
        $schoolId = $this->access->activeSchoolId($request->user());
        $sales = CashlessSale::query()->with(['student', 'merchant'])->where('school_id', $schoolId)->latest()->paginate(30);

        return view('cashless.reports.merchant-sales', compact('sales'));
    }
}
