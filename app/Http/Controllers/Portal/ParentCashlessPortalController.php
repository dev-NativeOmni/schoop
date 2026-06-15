<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CashlessWallet;
use App\Services\Cashless\CashlessAccessService;
use Illuminate\View\View;

class ParentCashlessPortalController extends Controller
{
    public function __invoke(CashlessAccessService $access): View
    {
        $schoolId = $access->activeSchoolId(auth()->user());
        $studentIds = auth()->user()->parentProfile?->students()->where('students.school_id', $schoolId)->pluck('students.id') ?? collect();
        $wallets = CashlessWallet::query()->with(['student', 'transactions' => fn ($q) => $q->latest()->limit(10)])
            ->where('school_id', $schoolId)
            ->whereIn('student_id', $studentIds)
            ->get();

        return view('portal.parent.cashless', compact('wallets'));
    }
}
