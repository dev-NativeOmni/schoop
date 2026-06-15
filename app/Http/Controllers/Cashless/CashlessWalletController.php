<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Models\CashlessWallet;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessWalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashlessWalletController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessWalletService $wallets) {}

    public function index(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $wallets = CashlessWallet::query()->with('student.classRoom')->where('school_id', $schoolId)->latest()->paginate(30);

        return view('cashless.wallets.index', compact('wallets'));
    }

    public function show(CashlessWallet $wallet): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $wallet->school_id);
        $wallet->load(['student.classRoom', 'transactions' => fn ($q) => $q->latest()->limit(50)]);

        return view('cashless.wallets.show', compact('wallet'));
    }

    public function freeze(Request $request, CashlessWallet $wallet): RedirectResponse
    {
        $this->access->canFinance($request->user()) ?: abort(403);
        $this->access->assertSameSchool($request->user(), (int) $wallet->school_id);
        $this->wallets->freezeWallet($wallet, $request->user(), (string) $request->input('reason', 'Dibekukan admin'));

        return back()->with('success', 'Wallet berhasil dibekukan.');
    }

    public function unfreeze(Request $request, CashlessWallet $wallet): RedirectResponse
    {
        $this->access->canFinance($request->user()) ?: abort(403);
        $this->access->assertSameSchool($request->user(), (int) $wallet->school_id);
        $this->wallets->unfreezeWallet($wallet, $request->user());

        return back()->with('success', 'Wallet berhasil diaktifkan.');
    }
}
