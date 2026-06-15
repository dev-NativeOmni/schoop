<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashless\StoreCashlessSaleRequest;
use App\Models\CashlessPosSession;
use App\Models\CashlessProduct;
use App\Models\CashlessSale;
use App\Models\Student;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessSaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CashlessPosController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessSaleService $sales) {}

    public function cashier(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $sessions = CashlessPosSession::query()->with('merchant')->where('school_id', $schoolId)->where('status', 'open')->latest()->get();
        $sessionId = request()->integer('session');
        $activeSession = $sessionId ? $sessions->firstWhere('id', $sessionId) : $sessions->first();
        $products = collect();
        if ($activeSession) {
            $this->access->assertMerchantAccess(auth()->user(), $activeSession->merchant);
            $products = CashlessProduct::query()->where('school_id', $schoolId)->where('cashless_merchant_id', $activeSession->cashless_merchant_id)->where('status', 'active')->orderBy('name')->get();
        }
        $students = Student::query()->with('cashlessWallet')->where('school_id', $schoolId)->where('is_active', true)->orderBy('full_name')->limit(200)->get();

        return view('cashless.pos.cashier', compact('sessions', 'activeSession', 'products', 'students'));
    }

    public function store(StoreCashlessSaleRequest $request): RedirectResponse
    {
        $sale = $this->sales->createSale($request->validated(), $request->user());

        return redirect()->route('cashless.pos.receipt', $sale)->with('success', 'Transaksi berhasil diposting.');
    }

    public function receipt(CashlessSale $sale): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $sale->school_id);
        $sale->load(['items', 'student', 'merchant', 'wallet']);

        return view('cashless.pos.receipt', compact('sale'));
    }
}
