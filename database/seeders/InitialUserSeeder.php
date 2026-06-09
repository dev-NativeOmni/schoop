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
    }
}
