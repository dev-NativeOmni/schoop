<?php

namespace App\Http\Requests\Tenancy;

use App\Services\Tenancy\TenantAccessService;
use Illuminate\Foundation\Http\FormRequest;

class SwitchTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        $schoolId = (int) $this->input('school_id');

        return $schoolId > 0
            && app(TenantAccessService::class)->userCanAccessSchool($this->user(), $schoolId);
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
        ];
    }
}
