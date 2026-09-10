<?php

namespace App\Http\Requests\Tenancy;

use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantSettingRequest extends FormRequest
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

        return false;
    }

    public function rules(): array
    {
        return [
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable', 'string'],
            'settings.*.type' => ['required', 'string', 'in:string,integer,int,float,double,boolean,bool,array,json'],
            'settings.*.is_public' => ['sometimes', 'boolean'],
            'settings.*.description' => ['nullable', 'string'],
        ];
    }
}
