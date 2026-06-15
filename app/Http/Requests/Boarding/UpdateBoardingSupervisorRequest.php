<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBoardingSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supervisorId = $this->route('supervisor')?->id ?? $this->route('supervisor');

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('boarding_supervisor_profiles', 'user_id')->ignore($supervisorId),
            ],
            'boarding_dormitory_id' => ['nullable', 'exists:boarding_dormitories,id'],
            'boarding_room_id' => ['nullable', 'exists:boarding_rooms,id'],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
