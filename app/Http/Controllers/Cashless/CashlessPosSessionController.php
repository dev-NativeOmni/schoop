<?php

namespace App\Http\Controllers\Cashless;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cashless\CloseCashlessPosSessionRequest;
use App\Http\Requests\Cashless\OpenCashlessPosSessionRequest;
use App\Models\CashlessMerchant;
use App\Models\CashlessPosSession;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessPosSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CashlessPosSessionController extends Controller
{
    public function __construct(private readonly CashlessAccessService $access, private readonly CashlessPosSessionService $sessions) {}

    public function index(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $sessions = CashlessPosSession::query()->with(['merchant', 'cashier'])->where('school_id', $schoolId)->latest()->paginate(20);

        return view('cashless.pos-sessions.index', compact('sessions'));
    }

    public function open(): View
    {
        $schoolId = $this->access->activeSchoolId(auth()->user());
        $merchants = CashlessMerchant::query()->where('school_id', $schoolId)->where('status', 'active')->orderBy('name')->get();

        return view('cashless.pos-sessions.open', compact('merchants'));
    }

    public function store(OpenCashlessPosSessionRequest $request): RedirectResponse
    {
        $schoolId = $this->access->activeSchoolId($request->user());
        $merchant = CashlessMerchant::query()->where('school_id', $schoolId)->findOrFail($request->cashless_merchant_id);
        $session = $this->sessions->open($merchant, $request->user(), $request->shift_name, $request->opening_note);

        return redirect()->route('cashless.pos.cashier', ['session' => $session->id])->with('success', 'Session POS dibuka.');
    }

    public function show(CashlessPosSession $posSession): View
    {
        $this->access->assertSameSchool(auth()->user(), (int) $posSession->school_id);
        $posSession->load(['merchant', 'cashier', 'sales.student']);

        return view('cashless.pos-sessions.show', ['session' => $posSession]);
    }

    public function close(CloseCashlessPosSessionRequest $request, CashlessPosSession $posSession): RedirectResponse
    {
        $this->sessions->close($posSession, $request->user(), $request->closing_note);

        return redirect()->route('cashless.pos-sessions.show', $posSession)->with('success', 'Session POS ditutup.');
    }
}
