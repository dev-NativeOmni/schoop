<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaasTenantPaymentRequest extends FormRequest
{
    public function authorize(): bool { return app(SaasOperationsAccessService::class)->canManageTenantInvoices($this->user()); }
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1'],
            'payment_date' => ['nullable', 'date'],
            'method' => ['nullable', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:120'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
