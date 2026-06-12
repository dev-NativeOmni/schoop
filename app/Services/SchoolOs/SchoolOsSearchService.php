<?php

namespace App\Services\SchoolOs;

use App\Models\Student;
use App\Models\User;

class SchoolOsSearchService
{
    public function search(string $keyword, User $user, SchoolOsAccessService $accessService): array
    {
        $students = $accessService
            ->applyStudentScope(
                Student::query()
                    ->with('classRoom')
                    ->where(function ($query) use ($keyword): void {
                        $query->where('full_name', 'like', '%' . $keyword . '%')
                            ->orWhere('nickname', 'like', '%' . $keyword . '%')
                            ->orWhere('nisn', 'like', '%' . $keyword . '%')
                            ->orWhere('student_number', 'like', '%' . $keyword . '%');
                    }),
                $user
            )
            ->limit(20)
            ->get();

        return [
            'students' => $students,
        ];
    }
}
