<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\User;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\Schema;

class CurrentSchoolResolver
{
    public function resolve(?User $user = null): ?School
    {
        $user ??= auth()->user();

        if (! $user) {
            return null;
        }

        // 1. Try to resolve via existing TenantContextService first
        $tenantContext = app(TenantContextService::class);
        $activeSchool = $tenantContext->activeSchool();
        if ($activeSchool) {
            return $activeSchool;
        }

        // 2. If user is super admin and requested school_id is provided
        if (method_exists($user, 'hasRole') && $user->hasRole(['super_admin'])) {
            $requestedSchoolId = request()->input('school_id');

            if ($requestedSchoolId) {
                return School::query()->find($requestedSchoolId);
            }
        }

        // 3. Fallback to school_id attribute on user model
        if (isset($user->school_id) && $user->school_id) {
            return School::query()->find($user->school_id);
        }

        if (Schema::hasColumn('users', 'school_id')) {
            $schoolId = $user->getAttribute('school_id');

            if ($schoolId) {
                return School::query()->find($schoolId);
            }
        }

        return null;
    }
}
