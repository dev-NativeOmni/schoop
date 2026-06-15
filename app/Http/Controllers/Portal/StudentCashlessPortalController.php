<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CashlessWallet;
use App\Services\Cashless\CashlessAccessService;
use Illuminate\View\View;

class StudentCashlessPortalController extends Controller
{
    public function __invoke(CashlessAccessService $access): View
    {
        $schoolId = $access->activeSchoolId(auth()->user());
        $student = auth()->user()->studentProfile;
        $wallet = $student ? CashlessWallet::query()->with(['transactions' => fn ($q) => $q->latest()->limit(30)])->where('school_id', $schoolId)->where('student_id', $student->id)->first() : null;

        return view('portal.student.cashless', compact('wallet', 'student'));
    }
}
