<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        $classRoom = $this->route('class_room');

        return [
            'school_id' => ['required', 'exists:schools,id'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('class_rooms', 'name')
                    ->where('school_id', $this->input('school_id'))
                    ->where('academic_year', $this->input('academic_year'))
                    ->ignore($classRoom?->id),
            ],
            'level' => ['nullable', 'string', 'max:50'],
            'academic_year' => ['nullable', 'string', 'max:20'],
            'homeroom_teacher_id' => ['nullable', 'exists:users,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
