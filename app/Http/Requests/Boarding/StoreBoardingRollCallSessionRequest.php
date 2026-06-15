<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingRollCallSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'boarding_dormitory_id' => ['nullable', 'exists:boarding_dormitories,id'],
            'boarding_room_id' => ['nullable', 'exists:boarding_rooms,id'],
            'session_date' => ['required', 'date'],
            'session_type' => ['required', 'string', 'in:morning,afternoon,night,custom'],
        ];
    }
}
