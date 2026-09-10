<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerSuccessNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(SaasOperationsAccessService::class)->canViewDashboard($this->user());
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'note_type' => ['required', 'string', 'max:50'],
            'content' => ['required', 'string', 'max:5000'],
            'next_follow_up_at' => ['nullable', 'date'],
            'health_status' => ['nullable', 'in:healthy,watch,risk,critical'],
        ];
    }
}
