<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashless\StoreCashlessRefundRequest;
use App\Models\CashlessSale;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessRefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CashlessRefundController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessRefundService $refunds) {}

    public function create(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $sales = CashlessSale::query()->with(['student', 'merchant'])->where('school_id', $schoolId)->whereIn('status', ['posted', 'partially_refunded'])->latest()->limit(50)->get();

        return view('cashless.refunds.create', compact('sales'));
    }

    public function store(StoreCashlessRefundRequest $request): RedirectResponse
    {
        $schoolId = $this->access->activeSchoolId($request->user());
        $sale = CashlessSale::query()->where('school_id', $schoolId)->findOrFail($request->cashless_sale_id);
        $refund = $this->refunds->refundSale($sale, (int) $request->amount, $request->user(), $request->reason);

        return redirect()->route('cashless.pos.receipt', $sale)->with('success', 'Refund berhasil: '.$refund->refund_number);
    }
}
