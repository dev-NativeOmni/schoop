<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class CloseCashlessPosSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(CashlessAccessService::class)->canUsePos($this->user());
    }

    public function rules(): array
    {
        return [
            'closing_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
