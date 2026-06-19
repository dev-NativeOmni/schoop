<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class SyncPlanModulesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin', 'admin_sekolah']) ?? false;
    }

    public function rules(): array
    {
        return [
            'modules' => ['nullable', 'array'],
            'modules.*.system_module_id' => ['required', 'exists:system_modules,id'],
            'modules.*.is_included' => ['nullable', 'boolean'],
            'modules.*.limits' => ['nullable', 'array'],
            'modules.*.features' => ['nullable', 'array'],
        ];
    }
}
