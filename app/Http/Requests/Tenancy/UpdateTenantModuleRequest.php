<?php

namespace App\Http\Requests\Tenancy;

use App\Services\Tenancy\TenantAccessService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $accessService = app(TenantAccessService::class);
        $module = $this->route('tenantModule');

        if (!$module) {
            return false;
        }

        if ($accessService->isSuperAdmin($user)) {
            return true;
        }

        if ($accessService->isTenantAdmin($user)) {
            return $accessService->userCanAccessSchool($user, (int) $module->school_id);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'is_enabled' => ['required', 'boolean'],
            'configuration' => ['nullable', 'array'],
        ];
    }
}
