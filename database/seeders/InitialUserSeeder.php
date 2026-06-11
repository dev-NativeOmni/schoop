<?php

namespace Database\Seeders;

use App\Models\ParentProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUserSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->where('code', 'ALAZHAR7')->firstOrFail();

        $roles = Role::query()
            ->whereIn('name', [
                'super_admin',
                'admin',
                'principal',
                'teacher',
                'parent',
                'student',
            ])
            ->get()
            ->keyBy('name');

        $defaultPassword = Hash::make('password');

        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@hafizplus.test'],
            [
                'role_id' => $roles['super_admin']->id,
                'school_id' => null,
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@hafizplus.test'],
            [
                'role_id' => $roles['admin']->id,
                'school_id' => $school->id,
                'name' => 'Admin Sekolah',
                'username' => 'admin',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $principal = User::query()->updateOrCreate(
            ['email' => 'kepalasekolah@hafizplus.test'],
            [
                'role_id' => $roles['principal']->id,
                'school_id' => $school->id,
                'name' => 'Kepala Sekolah',
                'username' => 'kepalasekolah',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $teacher = User::query()->updateOrCreate(
            ['email' => 'guru@hafizplus.test'],
            [
                'role_id' => $roles['teacher']->id,
                'school_id' => $school->id,
                'name' => 'Guru Tahfidz',
                'username' => 'guru',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        TeacherProfile::query()->updateOrCreate(
            ['user_id' => $teacher->id],
            [
                'school_id' => $school->id,
                'employee_number' => 'GT-001',
                'specialization' => 'Tahfidz',
                'address' => null,
                'joined_at' => now()->toDateString(),
                'is_active' => true,
            ]
        );

        // Teacher 2
        $teacher2 = User::query()->updateOrCreate(
            ['email' => 'guru2@hafizplus.test'],
            [
                'role_id' => $roles['teacher']->id,
                'school_id' => $school->id,
                'name' => 'Guru Tahfidz Kedua',
                'username' => 'guru2',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        TeacherProfile::query()->updateOrCreate(
            ['user_id' => $teacher2->id],
            [
                'school_id' => $school->id,
                'employee_number' => 'GT-002',
                'specialization' => 'Tahfidz',
                'address' => null,
                'joined_at' => now()->toDateString(),
                'is_active' => true,
            ]
        );

        // Parent 1
        $parentUser = User::query()->updateOrCreate(
            ['email' => 'ortu@hafizplus.test'],
            [
                'role_id' => $roles['parent']->id,
                'school_id' => $school->id,
                'name' => 'Orang Tua Santri',
                'username' => 'ortu',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $parentProfile = ParentProfile::query()->updateOrCreate(
            ['user_id' => $parentUser->id],
            [
                'school_id' => $school->id,
                'relationship' => 'Wali',
                'occupation' => null,
                'address' => null,
                'is_active' => true,
            ]
        );

        // Parent 2
        $parentUser2 = User::query()->updateOrCreate(
            ['email' => 'ortu2@hafizplus.test'],
            [
                'role_id' => $roles['parent']->id,
                'school_id' => $school->id,
                'name' => 'Orang Tua Santri 2',
                'username' => 'ortu2',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $parentProfile2 = ParentProfile::query()->updateOrCreate(
            ['user_id' => $parentUser2->id],
            [
                'school_id' => $school->id,
                'relationship' => 'Wali',
                'occupation' => null,
                'address' => null,
                'is_active' => true,
            ]
        );

        // Student 1 (Parent 1)
        $studentUser = User::query()->updateOrCreate(
            ['email' => 'santri@hafizplus.test'],
            [
                'role_id' => $roles['student']->id,
                'school_id' => $school->id,
                'name' => 'Santri Contoh',
                'username' => 'santri',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $student = Student::query()->updateOrCreate(
            ['user_id' => $studentUser->id],
            [
                'school_id' => $school->id,
                'class_room_id' => null,
                'student_number' => 'S-001',
                'nisn' => null,
                'full_name' => 'Santri Contoh',
                'nickname' => 'Santri',
                'gender' => null,
                'birth_place' => null,
                'birth_date' => null,
                'address' => null,
                'phone' => null,
                'program_type' => 'tahfizh',
                'is_active' => true,
            ]
        );

        $parentProfile->students()->syncWithoutDetaching([
            $student->id => [
                'relationship' => 'Wali',
                'is_primary' => true,
            ],
        ]);

        // Student 2 (Parent 1)
        $studentUser2 = User::query()->updateOrCreate(
            ['email' => 'santri2@hafizplus.test'],
            [
                'role_id' => $roles['student']->id,
                'school_id' => $school->id,
                'name' => 'Santri Contoh 2',
                'username' => 'santri2',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $student2 = Student::query()->updateOrCreate(
            ['user_id' => $studentUser2->id],
            [
                'school_id' => $school->id,
                'class_room_id' => null,
                'student_number' => 'S-002',
                'nisn' => null,
                'full_name' => 'Santri Contoh 2',
                'nickname' => 'Santri 2',
                'gender' => null,
                'birth_place' => null,
                'birth_date' => null,
                'address' => null,
                'phone' => null,
                'program_type' => 'tahfizh',
                'is_active' => true,
            ]
        );

        $parentProfile->students()->syncWithoutDetaching([
            $student2->id => [
                'relationship' => 'Wali',
                'is_primary' => true,
            ],
        ]);

        // Student 3 (Parent 2)
        $studentUser3 = User::query()->updateOrCreate(
            ['email' => 'santri3@hafizplus.test'],
            [
                'role_id' => $roles['student']->id,
                'school_id' => $school->id,
                'name' => 'Santri Contoh 3',
                'username' => 'santri3',
                'phone' => null,
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        $student3 = Student::query()->updateOrCreate(
            ['user_id' => $studentUser3->id],
            [
                'school_id' => $school->id,
                'class_room_id' => null,
                'student_number' => 'S-003',
                'nisn' => null,
                'full_name' => 'Santri Contoh 3',
                'nickname' => 'Santri 3',
                'gender' => null,
                'birth_place' => null,
                'birth_date' => null,
                'address' => null,
                'phone' => null,
                'program_type' => 'tahfizh',
                'is_active' => true,
            ]
        );

        $parentProfile2->students()->syncWithoutDetaching([
            $student3->id => [
                'relationship' => 'Wali',
                'is_primary' => true,
            ],
        ]);
    }
}
