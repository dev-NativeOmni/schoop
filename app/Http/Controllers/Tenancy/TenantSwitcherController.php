<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenancy\SwitchTenantRequest;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantSwitcherController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $schools = $user->hasRole(['super_admin'])
            ? School::query()->orderByRaw('name')->get()
            : $user->accessibleSchools()
                ->wherePivot('membership_status', 'active')
                ->orderByRaw('name')
                ->get();

        return view('tenancy.switcher', compact('schools'));
    }

    public function switch(SwitchTenantRequest $request, TenantContextService $tenantContext): RedirectResponse
    {
        $tenantContext->setActiveSchool($request->user(), (int) $request->validated('school_id'));

        return redirect()->route('dashboard')
            ->with('success', 'Sekolah aktif berhasil diganti.');
    }
}
