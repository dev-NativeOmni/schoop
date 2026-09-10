<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaasTenantInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(SaasOperationsAccessService::class)->canManageTenantInvoices($this->user());
    }

    public function rules(): array
    {
        return [
            'saas_school_subscription_id' => ['required', 'exists:saas_school_subscriptions,id'],
            'due_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:200'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'integer', 'min:0'],
        ];
    }
}
