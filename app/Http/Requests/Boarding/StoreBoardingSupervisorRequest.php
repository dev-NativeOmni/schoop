<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class StoreBoardingSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', 'unique:boarding_supervisor_profiles,user_id'],
            'boarding_dormitory_id' => ['nullable', 'exists:boarding_dormitories,id'],
            'boarding_room_id' => ['nullable', 'exists:boarding_rooms,id'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
