<?php

namespace App\Http\Requests\Boarding;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBoardingBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'boarding_room_id' => ['required', 'exists:boarding_rooms,id'],
            'code' => ['required', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:available,occupied,maintenance,inactive'],
            'description' => ['nullable', 'string'],
        ];
    }
}
