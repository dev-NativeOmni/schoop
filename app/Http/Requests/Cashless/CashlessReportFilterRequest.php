<?php

namespace App\Http\Requests\Cashless;

use App\Services\Cashless\CashlessAccessService;
use Illuminate\Foundation\Http\FormRequest;

class CashlessReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return app(CashlessAccessService::class)->canViewReports($this->user());
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'cashless_merchant_id' => ['nullable', 'exists:cashless_merchants,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'cashier_id' => ['nullable', 'exists:users,id'],
            'type' => ['nullable', 'string', 'max:40'],
            'status' => ['nullable', 'string', 'max:30'],
        ];
    }
}
