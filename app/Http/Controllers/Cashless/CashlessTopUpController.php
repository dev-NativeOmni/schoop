<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashless\StoreWalletTopUpRequest;
use App\Models\CashlessWallet;
use App\Models\CashlessWalletTransaction;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessTopUpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashlessTopUpController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessTopUpService $topUps) {}

    public function create(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $wallets = CashlessWallet::query()->with('student')->where('school_id', $schoolId)->orderBy('wallet_number')->get();

        return view('cashless.top-ups.create', compact('wallets'));
    }

    public function store(StoreWalletTopUpRequest $request): RedirectResponse
    {
        $schoolId = $this->access->activeSchoolId($request->user());
        $wallet = CashlessWallet::query()->where('school_id', $schoolId)->findOrFail($request->cashless_wallet_id);
        $transaction = $this->topUps->topUp($wallet, (int) $request->amount, $request->user(), $request->note);

        return redirect()->route('cashless.wallets.show', $wallet)->with('success', 'Top-up berhasil: '.$transaction->transaction_number);
    }

    public function void(Request $request, CashlessWalletTransaction $transaction): RedirectResponse
    {
        $this->access->canFinance($request->user()) ?: abort(403);
        $this->access->assertSameSchool($request->user(), (int) $transaction->school_id);
        $void = $this->topUps->voidTopUp($transaction, $request->user(), (string) $request->input('reason', 'Void top-up'));

        return back()->with('success', 'Void top-up berhasil: '.$void->transaction_number);
    }
}
