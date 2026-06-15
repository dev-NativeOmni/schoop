<?php

namespace App\Http\Requests\Tenancy;

use App\Services\Tenancy\TenantAccessService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenantMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $accessService = app(TenantAccessService::class);
        $membership = $this->route('membership');

        if (!$membership) {
            return false;
        }

        if ($accessService->isSuperAdmin($user)) {
            return true;
        }

        if ($accessService->isTenantAdmin($user)) {
            return $accessService->userCanAccessSchool($user, (int) $membership->school_id);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'membership_status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'is_default' => ['sometimes', 'boolean'],
            'joined_at' => ['nullable', 'date'],
        ];
    }
}
