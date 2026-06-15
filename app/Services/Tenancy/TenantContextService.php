<?php

namespace App\Services\Tenancy;

use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;
use Illuminate\Support\Facades\Session;

class TenantContextService
{
    public const SESSION_KEY = 'active_school_id';

    public function activeSchoolId(): ?int
    {
        $schoolId = Session::get(self::SESSION_KEY);

        return $schoolId ? (int) $schoolId : null;
    }

    public function activeSchool(): ?School
    {
        $schoolId = $this->activeSchoolId();

        if (! $schoolId) {
            return null;
        }

        return School::query()->find($schoolId);
    }

    public function setActiveSchool(User $user, int $schoolId): void
    {
        if (! app(TenantAccessService::class)->userCanAccessSchool($user, $schoolId)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        Session::put(self::SESSION_KEY, $schoolId);

        UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->update(['last_accessed_at' => now()]);
    }

    public function resolveForUser(User $user): ?int
    {
        $current = $this->activeSchoolId();

        if ($current && app(TenantAccessService::class)->userCanAccessSchool($user, $current)) {
            return $current;
        }

        $membership = UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('membership_status', 'active')
            ->orderByDesc('is_default')
            ->orderByDesc('last_accessed_at')
            ->first();

        if ($membership) {
            Session::put(self::SESSION_KEY, $membership->school_id);

            return (int) $membership->school_id;
        }

        if ($user->school_id ?? null) {
            Session::put(self::SESSION_KEY, $user->school_id);

            return (int) $user->school_id;
        }

        return null;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
