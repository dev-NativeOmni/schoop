<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCashlessProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(CashlessAccessService::class)->canManage($this->user());
    }

    public function rules(): array
    {
        return [
            'cashless_merchant_id' => ['required', 'exists:cashless_merchants,id'],
            'name' => ['required', 'string', 'max:150'],
            'sku' => ['nullable', 'string', 'max:80'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'track_stock' => ['nullable', 'boolean'],
            'status' => ['required', 'in:active,inactive'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
