<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    /**
     * Start impersonating a user. Only super_admin can initiate this.
     */
    public function impersonate(Request $request, User $user, TenantContextService $tenantContext): RedirectResponse
    {
        $currentUser = Auth::user();

        // Only super_admin can start impersonation (or someone already impersonating)
        if (!$currentUser->isSuperAdmin() && !session()->has('impersonator_id')) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh Super Admin.');
        }

        // Cannot impersonate oneself
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat melakukan impersonasi ke akun sendiri.');
        }

        // Store original superadmin ID in session if not already set
        if (!session()->has('impersonator_id')) {
            session()->put('impersonator_id', $currentUser->id);
            session()->put('impersonator_name', $currentUser->name);
        }

        // Switch active school context to target user's school if applicable
        if ($user->school_id) {
            $tenantContext->setActiveSchool($user, $user->school_id);
        }

        // Log in as target user
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "Mode Simulasi Aktif: Anda sekarang masuk sebagai {$user->name} ({$user->role?->label}).");
    }

    /**
     * Leave impersonation and restore original superadmin session.
     */
    public function leave(Request $request): RedirectResponse
    {
        if (!session()->has('impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $impersonatorId = session()->get('impersonator_id');
        $superAdmin = User::find($impersonatorId);

        if (!$superAdmin) {
            session()->forget(['impersonator_id', 'impersonator_name']);
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sesi Super Admin tidak ditemukan. Silakan login kembali.');
        }

        // Clear impersonation session keys
        session()->forget(['impersonator_id', 'impersonator_name']);

        // Log back in as original superadmin
        Auth::login($superAdmin);

        return redirect()->route('master-data.users.index')
            ->with('success', 'Berhasil kembali ke sesi Super Admin.');
    }
}
