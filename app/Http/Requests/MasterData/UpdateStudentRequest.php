<?php

namespace App\Http\Requests\MasterData;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $user = $student?->user;

        return [
            'school_id' => ['required', 'exists:schools,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user?->id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'student_number' => ['nullable', 'string', 'max:100'],
            'nisn' => ['nullable', 'string', 'max:100'],
            'full_name' => ['required', 'string', 'max:255'],
            'nickname' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'string', 'max:20'],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'program_type' => ['nullable', 'string', 'max:100'],
            'parent_profile_ids' => ['nullable', 'array'],
            'parent_profile_ids.*' => ['integer', 'exists:parent_profiles,id'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
