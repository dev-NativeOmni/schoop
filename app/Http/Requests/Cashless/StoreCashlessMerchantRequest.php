<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class StoreCashlessMerchantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(CashlessAccessService::class)->canManage($this->user());
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'code' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:canteen,cooperative,bookstore,laundry,other'],
            'status' => ['required', 'in:active,inactive'],
            'phone' => ['nullable', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cashier_user_ids' => ['nullable', 'array'],
            'cashier_user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}
