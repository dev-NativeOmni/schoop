<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenancy\StoreTenantMembershipRequest;
use App\Http\Requests\Tenancy\UpdateTenantMembershipRequest;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Services\Tenancy\TenantContextService;
use App\Services\Tenancy\TenantMembershipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantMembershipController extends Controller
{
    protected TenantMembershipService $membershipService;
    protected TenantContextService $contextService;

    public function __construct(TenantMembershipService $membershipService, TenantContextService $contextService)
    {
        $this->membershipService = $membershipService;
        $this->contextService = $contextService;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->contextService->activeSchoolId();
        
        $memberships = UserSchoolMembership::query()
            ->with(['user', 'school', 'role'])
            ->where('school_id', $schoolId)
            ->latest()
            ->paginate(15);

        return view('tenancy.memberships.index', compact('memberships'));
    }

    public function create(): View
    {
        $schoolId = $this->contextService->activeSchoolId();
        
        $existingUserIds = UserSchoolMembership::query()
            ->where('school_id', $schoolId)
            ->pluck('user_id')
            ->toArray();

        $users = User::query()
            ->whereNotIn('id', $existingUserIds)
            ->orderBy('name')
            ->get();

        $roles = Role::query()->orderBy('name')->get();
        $school = School::query()->findOrFail($schoolId);

        return view('tenancy.memberships.create', compact('users', 'roles', 'school'));
    }

    public function store(StoreTenantMembershipRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->membershipService->createMembership($data);

        return redirect()->route('tenancy.memberships.index')
            ->with('success', 'User membership berhasil ditambahkan.');
    }

    public function edit(UserSchoolMembership $membership): View
    {
        $schoolId = $this->contextService->activeSchoolId();
        if (!auth()->user()->hasRole('super_admin') && (int) $membership->school_id !== $schoolId) {
            abort(403, 'Akses tidak sah ke data sekolah lain.');
        }

        $roles = Role::query()->orderBy('name')->get();
        $school = School::query()->findOrFail($membership->school_id);

        return view('tenancy.memberships.edit', compact('membership', 'roles', 'school'));
    }

    public function update(UpdateTenantMembershipRequest $request, UserSchoolMembership $membership): RedirectResponse
    {
        $data = $request->validated();
        $this->membershipService->updateMembership($membership, $data);

        return redirect()->route('tenancy.memberships.index')
            ->with('success', 'User membership berhasil diperbarui.');
    }

    public function destroy(UserSchoolMembership $membership): RedirectResponse
    {
        $schoolId = $this->contextService->activeSchoolId();
        if (!auth()->user()->hasRole('super_admin') && (int) $membership->school_id !== $schoolId) {
            abort(403, 'Akses tidak sah.');
        }

        $this->membershipService->deleteMembership($membership);

        return redirect()->route('tenancy.memberships.index')
            ->with('success', 'User membership berhasil dihapus.');
    }
}
