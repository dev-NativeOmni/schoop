<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class OpenCashlessPosSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(CashlessAccessService::class)->canUsePos($this->user());
    }

    public function rules(): array
    {
        return [
            'cashless_merchant_id' => ['required', 'exists:cashless_merchants,id'],
            'shift_name' => ['nullable', 'string', 'max:100'],
            'opening_note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
