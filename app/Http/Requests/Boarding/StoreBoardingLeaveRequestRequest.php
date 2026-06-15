<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingLeaveRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'type' => ['required', 'string', 'in:short_leave,overnight_leave,home_visit,medical_leave,emergency_leave'],
            'destination' => ['required', 'string', 'max:255'],
            'reason' => ['required', 'string'],
            'leave_start_at' => ['required', 'date_format:Y-m-d\TH:i'],
            'leave_end_at' => ['nullable', 'date_format:Y-m-d\TH:i', 'after:leave_start_at'],
        ];
    }
}
