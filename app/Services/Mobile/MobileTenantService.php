<?php

namespace App\Services\Mobile;

use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;
use App\Services\Tenancy\TenantAccessService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MobileTenantService
{
    public function __construct(
        private readonly TenantAccessService $tenantAccess,
    ) {}

    public function accessibleSchools(User $user): Collection
    {
        if ($user->isSuperAdmin()) {
            return School::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        $membershipSchools = School::query()
            ->whereHas('memberships', function ($query) use ($user): void {
                $query->where('user_id', $user->id)
                    ->where('membership_status', 'active');
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        if ($user->school_id && ! $membershipSchools->contains('id', $user->school_id)) {
            $fallback = School::query()
                ->whereKey($user->school_id)
                ->where('is_active', true)
                ->first();

            if ($fallback) {
                $membershipSchools->push($fallback);
            }
        }

        return $membershipSchools->unique('id')->values();
    }

    public function defaultSchoolForUser(User $user, ?int $requestedSchoolId = null): ?School
    {
        if ($requestedSchoolId) {
            $this->tenantAccess->ensureUserCanAccessSchool($user, $requestedSchoolId);

            return School::query()->find($requestedSchoolId);
        }

        $membership = UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('membership_status', 'active')
            ->orderByDesc('is_default')
            ->orderByDesc('last_accessed_at')
            ->first();

        if ($membership) {
            return School::query()->find($membership->school_id);
        }

        if ($user->school_id) {
            return School::query()->find($user->school_id);
        }

        return $this->accessibleSchools($user)->first();
    }

    public function resolveActiveSchool(Request $request): School
    {
        $user = $request->user();
        abort_unless($user, 401, 'Unauthorized');

        $schoolId = $request->header('X-School-Id')
            ?: $request->query('school_id')
            ?: $request->attributes->get('mobile_access_token')?->school_id
            ?: $request->attributes->get('mobile_device')?->school_id;

        $school = $this->defaultSchoolForUser($user, $schoolId ? (int) $schoolId : null);

        abort_unless($school, 403, 'Tenant sekolah belum tersedia.');

        $this->tenantAccess->ensureUserCanAccessSchool($user, (int) $school->id);
        $this->bindActiveSchool((int) $school->id);

        return $school;
    }

    public function switchTenant(User $user, int $schoolId): School
    {
        $this->tenantAccess->ensureUserCanAccessSchool($user, $schoolId);

        UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->update(['last_accessed_at' => now()]);

        $this->bindActiveSchool($schoolId);

        return School::query()->findOrFail($schoolId);
    }

    public function bindActiveSchool(int $schoolId): void
    {
        app()->instance('resolved_domain_school_id', $schoolId);
    }
}
