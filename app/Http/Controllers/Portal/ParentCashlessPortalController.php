<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\CashlessWallet;
use App\Services\Cashless\CashlessAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ParentCashlessPortalController extends Controller
{
    public function index(CashlessAccessService $access): View
    {
        $schoolId = $access->activeSchoolId(auth()->user());
        $studentIds = auth()->user()->parentProfile?->students()->where('students.school_id', $schoolId)->pluck('students.id') ?? collect();
        $wallets = CashlessWallet::query()->with(['student', 'transactions' => fn ($q) => $q->latest()->limit(10)])
            ->where('school_id', $schoolId)
            ->whereIn('student_id', $studentIds)
            ->get();

        return view('portal.parent.cashless', compact('wallets'));
    }

    public function update(Request $request, CashlessWallet $wallet, CashlessAccessService $access): RedirectResponse
    {
        $schoolId = $access->activeSchoolId(auth()->user());
        $studentIds = auth()->user()->parentProfile?->students()->where('students.school_id', $schoolId)->pluck('students.id') ?? collect();

        if (!$studentIds->contains($wallet->student_id) || (int) $wallet->school_id !== (int) $schoolId) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'pin' => ['nullable', 'string', 'regex:/^\d{4,6}$/'],
            'daily_limit' => ['nullable', 'integer', 'min:0'],
        ], [
            'pin.regex' => 'PIN harus berupa 4 sampai 6 digit angka.',
            'daily_limit.integer' => 'Limit harian harus berupa angka.',
            'daily_limit.min' => 'Limit harian tidak boleh negatif.',
        ]);

        $data = [];
        if ($request->has('daily_limit')) {
            $limit = $request->input('daily_limit');
            $data['daily_limit'] = ($limit === null || $limit === '') ? null : (int) $limit;
        }

        if ($request->filled('pin')) {
            $data['pin'] = \Illuminate\Support\Facades\Hash::make($request->input('pin'));
        }

        $wallet->update($data);

        return back()->with('success', 'Pengaturan wallet ' . $wallet->student?->full_name . ' berhasil diperbarui.');
    }
}
