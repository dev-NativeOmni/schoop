<?php

namespace App\Http\Requests\Tenancy;

use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Http\FormRequest;

class TenantAuditLogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $accessService = app(TenantAccessService::class);
        $schoolId = app(TenantContextService::class)->activeSchoolId();

        if (! $schoolId) {
            return false;
        }

        if ($accessService->isSuperAdmin($user)) {
            return true;
        }

        if ($accessService->isTenantAdmin($user)) {
            return $accessService->userCanAccessSchool($user, $schoolId);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole(['kepala_sekolah'])) {
            return $accessService->userCanAccessSchool($user, $schoolId);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'action' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'search' => ['nullable', 'string'],
        ];
    }
}
