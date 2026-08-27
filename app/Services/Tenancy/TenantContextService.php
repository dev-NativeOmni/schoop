<?php

namespace App\Services\Tenancy;

use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;
use Illuminate\Support\Facades\Session;

class TenantContextService
{
    public const SESSION_KEY = 'active_school_id';

    protected ?int $resolvedSchoolId = null;
    protected ?School $resolvedSchool = null;

    public function activeSchoolId(): ?int
    {
        if ($this->resolvedSchoolId !== null) {
            return $this->resolvedSchoolId;
        }

        if (app()->bound('resolved_domain_school_id')) {
            return $this->resolvedSchoolId = (int) app('resolved_domain_school_id');
        }

        $schoolId = Session::get(self::SESSION_KEY);

        if (! $schoolId && auth()->check()) {
            $user = auth()->user();
            if ($user->school_id) {
                $schoolId = (int) $user->school_id;
            } elseif (method_exists($user, 'hasRole') && $user->hasRole(['super_admin', 'operations_manager'])) {
                $firstSchoolId = School::query()->value('id');
                if ($firstSchoolId) {
                    Session::put(self::SESSION_KEY, $firstSchoolId);
                    $schoolId = (int) $firstSchoolId;
                }
            }
        }

        return $this->resolvedSchoolId = ($schoolId ? (int) $schoolId : null);
    }

    public function activeSchool(): ?School
    {
        if ($this->resolvedSchool !== null) {
            return $this->resolvedSchool;
        }

        $schoolId = $this->activeSchoolId();

        if (! $schoolId) {
            return null;
        }

        return $this->resolvedSchool = School::query()->with('brandProfile')->find($schoolId);
    }

    public function setActiveSchool(User $user, int $schoolId): void
    {
        if (! app(TenantAccessService::class)->userCanAccessSchool($user, $schoolId)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        Session::put(self::SESSION_KEY, $schoolId);
        $this->resolvedSchoolId = $schoolId;
        $this->resolvedSchool = null;

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

        if (method_exists($user, 'hasRole') && $user->hasRole(['super_admin', 'operations_manager'])) {
            $firstSchoolId = School::query()->value('id');
            if ($firstSchoolId) {
                Session::put(self::SESSION_KEY, $firstSchoolId);
                return (int) $firstSchoolId;
            }
        }

        return null;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
        $this->resolvedSchoolId = null;
        $this->resolvedSchool = null;
    }
}
