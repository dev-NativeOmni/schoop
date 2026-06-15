<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupportTicketMessageRequest extends FormRequest
{
    public function authorize(): bool { return app(SaasOperationsAccessService::class)->canManageSupport($this->user()); }
    public function rules(): array
    {
        return ['message' => ['required', 'string', 'max:5000'], 'visibility' => ['required', 'in:public,internal']];
    }
}
