<?php

namespace App\Http\Requests\Tenancy;

use App\Services\Tenancy\TenantAccessService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenantMembershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $accessService = app(TenantAccessService::class);

        if ($accessService->isSuperAdmin($user)) {
            return true;
        }

        if ($accessService->isTenantAdmin($user)) {
            $schoolId = (int) $this->input('school_id');
            return $accessService->userCanAccessSchool($user, $schoolId);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required', 
                'integer', 
                'exists:users,id',
                Rule::unique('user_school_memberships', 'user_id')->where('school_id', $this->input('school_id'))
            ],
            'school_id' => ['required', 'integer', 'exists:schools,id'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'membership_status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'is_default' => ['sometimes', 'boolean'],
            'joined_at' => ['nullable', 'date'],
        ];
    }
}
