<?php

namespace App\Http\Requests\WhiteLabel;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolDomainMappingRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('domain')) {
            $this->merge([
                'domain' => $this->normalizeDomain((string) $this->input('domain')),
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'domain' => ['required', 'string', 'max:255', 'unique:school_domain_mappings,domain'],
            'type' => ['required', 'in:subdomain,custom_domain'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    private function normalizeDomain(string $domain): string
    {
        $domain = strtolower(trim($domain));
        $domain = preg_replace('/^https?:\/\//i', '', $domain) ?? $domain;
        $domain = explode('/', $domain)[0];

        return rtrim($domain, '.:/');
    }
}
