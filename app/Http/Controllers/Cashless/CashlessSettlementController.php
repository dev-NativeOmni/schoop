<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Models\CashlessMerchant;
use App\Models\CashlessSettlement;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessSettlementService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashlessSettlementController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessSettlementService $settlements) {}

    public function index(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $settlements = CashlessSettlement::query()->with('merchant')->where('school_id', $schoolId)->latest()->paginate(20);
        $merchants = CashlessMerchant::query()->where('school_id', $schoolId)->orderBy('name')->get();

        return view('cashless.settlements.index', compact('settlements', 'merchants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'cashless_merchant_id' => ['required', 'exists:cashless_merchants,id'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        $schoolId = $this->access->activeSchoolId($request->user());
        $merchant = CashlessMerchant::query()->where('school_id', $schoolId)->findOrFail($request->cashless_merchant_id);
        $settlement = $this->settlements->createSettlement($merchant, Carbon::parse($request->period_start), Carbon::parse($request->period_end), $request->user());

        return redirect()->route('cashless.settlements.show', $settlement)->with('success', 'Settlement berhasil dibuat.');
    }

    public function show(CashlessSettlement $settlement): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $settlement->school_id);
        $settlement->load('merchant');

        return view('cashless.settlements.show', compact('settlement'));
    }

    public function approve(Request $request, CashlessSettlement $settlement): RedirectResponse
    {
        $this->settlements->approveSettlement($settlement, $request->user());

        return back()->with('success', 'Settlement disetujui.');
    }

    public function void(Request $request, CashlessSettlement $settlement): RedirectResponse
    {
        $this->settlements->voidSettlement($settlement, $request->user(), (string) $request->input('reason', 'Settlement dibatalkan'));

        return back()->with('success', 'Settlement dibatalkan.');
    }
}
