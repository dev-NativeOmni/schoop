<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:2000'],
            'type' => [
                'required',
                'string',
                Rule::in([
                    'info',
                    'success',
                    'warning',
                    'danger',
                ]),
            ],
            'recipient_type' => [
                'required',
                'string',
                Rule::in([
                    'all',
                    'role',
                    'class_room_parents',
                    'student_parents',
                    'specific_users',
                ]),
            ],
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'string',
                Rule::in([
                    'super_admin',
                    'admin',
                    'principal',
                    'teacher',
                    'parent',
                    'student',
                ]),
            ],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'action_url' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'body' => 'isi pengumuman',
            'type' => 'jenis notifikasi',
            'recipient_type' => 'jenis penerima',
            'roles' => 'role penerima',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'user_ids' => 'user penerima',
            'action_url' => 'tautan aksi',
        ];
    }
}
