<?php

namespace App\Http\Requests\SaasOps;

use App\Services\SaasOps\SaasOperationsAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreReleaseNoteRequest extends FormRequest
{
    public function authorize(): bool { return app(SaasOperationsAccessService::class)->canViewDashboard($this->user()); }
    public function rules(): array
    {
        return [
            'version' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
            'summary' => ['nullable', 'string', 'max:2000'],
            'body' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
