<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaasSubscriptionPlanRequest extends FormRequest
{
    public function authorize(): bool { return app(SaasOperationsAccessService::class)->canManageSubscriptions($this->user()); }
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:120'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            'monthly_price' => ['nullable', 'integer', 'min:0'],
            'yearly_price' => ['nullable', 'integer', 'min:0'],
            'features' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
