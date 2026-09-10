<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupportTicketStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(SaasOperationsAccessService::class)->canManageSupport($this->user());
    }

    public function rules(): array
    {
        return ['status' => ['required', 'in:open,in_progress,resolved,closed']];
    }
}
