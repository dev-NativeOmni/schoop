<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreSlaPolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(SaasOperationsAccessService::class)->canManageSupport($this->user());
    }

    public function rules(): array
    {
        return [
            'priority' => ['required', 'in:critical,high,medium,low'],
            'first_response_minutes' => ['required', 'integer', 'min:1'],
            'resolution_minutes' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
