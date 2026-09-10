<?php

namespace App\Actions\MasterData;

use App\Http\Requests\MasterData\StoreStudentRequest;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudentAction
{
    public function execute(StoreStudentRequest $request, int $schoolId): void
    {
        DB::transaction(function () use ($request, $schoolId): void {
            $userId = null;

            if ($request->boolean('create_login_account')) {
                $role = Role::query()->where(['name' => 'student'])->firstOrFail();

                $user = User::query()->create([
                    'role_id' => $role->id,
                    'school_id' => $schoolId,
                    'name' => $request->input('name') ?: $request->string('full_name'),
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'password' => Hash::make($request->input('password')),
                    'is_active' => $request->boolean('is_active'),
                ]);

                $userId = $user->id;
            }

            $student = Student::query()->create([
                'school_id' => $schoolId,
                'user_id' => $userId,
                'class_room_id' => $request->input('class_room_id'),
                'student_number' => $request->input('student_number'),
                'nisn' => $request->input('nisn'),
                'full_name' => $request->string('full_name'),
                'nickname' => $request->input('nickname'),
                'gender' => $request->input('gender'),
                'birth_place' => $request->input('birth_place'),
                'birth_date' => $request->input('birth_date'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'program_type' => $request->input('program_type'),
                'is_active' => $request->boolean('is_active'),
            ]);

            $student->parents()->sync($request->input('parent_profile_ids', []));
        });
    }
}
