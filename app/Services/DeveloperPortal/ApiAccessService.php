<?php

namespace App\Services\DeveloperPortal;

use App\Models\ApiClient;
use App\Models\User;
use App\Services\Tenancy\TenantAccessService;
use Illuminate\Http\Request;

class ApiAccessService
{
    public function canAccessDeveloperPortal(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success', 'admin', 'admin_sekolah']);
    }

    public function canManageClients(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager', 'admin', 'admin_sekolah']);
    }

    public function canManageScopes(User $user): bool
    {
        return $user->hasRole(['super_admin']);
    }

    public function canManageDocs(User $user): bool
    {
        return $user->hasRole(['super_admin', 'operations_manager']);
    }

    public function assertDeveloperPortalAccess(User $user): void
    {
        abort_unless($this->canAccessDeveloperPortal($user), 403, 'Anda tidak memiliki akses Developer Portal.');
    }

    public function assertCanManageClients(User $user): void
    {
        abort_unless($this->canManageClients($user), 403, 'Anda tidak memiliki akses mengelola API client.');
    }

    public function assertClientVisible(User $user, ApiClient $client): void
    {
        if ($user->hasRole(['super_admin', 'operations_manager', 'support_staff', 'customer_success'])) {
            return;
        }

        abort_unless($client->school_id && app(TenantAccessService::class)->userCanAccessSchool($user, (int) $client->school_id), 403);
    }

    public function bindApiTenant(ApiClient $client): void
    {
        if ($client->school_id) {
            app()->instance('resolved_domain_school_id', (int) $client->school_id);
        }
    }

    public function externalSchoolId(Request $request): int
    {
        $client = $request->attributes->get('api_client');
        $schoolId = $client?->school_id ?: $request->integer('school_id');

        abort_unless($schoolId, 403, 'External API request must resolve a tenant.');

        return (int) $schoolId;
    }
}
