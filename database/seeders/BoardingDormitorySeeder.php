<?php

namespace Database\Seeders;

use App\Models\BoardingBed;
use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Models\BoardingStudentAssignment;
use App\Models\BoardingSupervisorProfile;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class BoardingDormitorySeeder extends Seeder
{
    public function run(): void
    {
        $school = School::query()->where('code', 'ALAZHAR7')->first() ?? School::query()->first();
        if (! $school) {
            return;
        }

        $supervisorRole = Role::query()->where('name', 'boarding_supervisor')->first();
        if (! $supervisorRole) {
            return;
        }

        // 1. Seed Supervisor User
        $plainPassword = 'password';
        $defaultPassword = Hash::make($plainPassword);
        $encryptedPassword = Crypt::encryptString($plainPassword);

        $supervisorUser = User::query()->updateOrCreate(
            ['email' => 'pembina@hafizplus.test'],
            [
                'role_id' => $supervisorRole->id,
                'school_id' => $school->id,
                'name' => 'Pembina Asrama',
                'username' => 'pembina',
                'phone' => '081234567890',
                'password' => $defaultPassword,
                'is_active' => true,
            ]
        );

        // 2. Seed Dormitories
        $dorm1 = BoardingDormitory::query()->updateOrCreate(
            ['school_id' => $school->id, 'name' => 'Asrama Al-Farabi'],
            [
                'gender' => 'male',
                'capacity' => 8,
                'is_active' => true,
                'description' => 'Asrama Putra Tahfizh',
            ]
        );

        $dorm2 = BoardingDormitory::query()->updateOrCreate(
            ['school_id' => $school->id, 'name' => 'Asrama Khadijah'],
            [
                'gender' => 'female',
                'capacity' => 4,
                'is_active' => true,
                'description' => 'Asrama Putri Tahfizh',
            ]
        );

        // Seed Supervisor Profile
        BoardingSupervisorProfile::query()->updateOrCreate(
            ['user_id' => $supervisorUser->id],
            [
                'school_id' => $school->id,
                'boarding_dormitory_id' => $dorm1->id,
                'boarding_room_id' => null,
                'phone' => '081234567890',
                'status' => 'active',
                'notes' => 'Kepala Asrama Putra',
            ]
        );

        // 3. Seed Rooms
        $room1 = BoardingRoom::query()->updateOrCreate(
            ['boarding_dormitory_id' => $dorm1->id, 'name' => 'Kamar A1'],
            [
                'floor' => '1',
                'capacity' => 4,
                'is_active' => true,
                'description' => 'Kamar Utama Lantai 1',
            ]
        );

        $room2 = BoardingRoom::query()->updateOrCreate(
            ['boarding_dormitory_id' => $dorm1->id, 'name' => 'Kamar A2'],
            [
                'floor' => '1',
                'capacity' => 4,
                'is_active' => true,
                'description' => 'Kamar Lantai 1 Sebelah Kanan',
            ]
        );

        $room3 = BoardingRoom::query()->updateOrCreate(
            ['boarding_dormitory_id' => $dorm2->id, 'name' => 'Kamar B1'],
            [
                'floor' => '1',
                'capacity' => 4,
                'is_active' => true,
                'description' => 'Kamar Utama Putri',
            ]
        );

        // 4. Seed Beds
        $rooms = [$room1, $room2, $room3];
        foreach ($rooms as $room) {
            for ($i = 1; $i <= 4; $i++) {
                BoardingBed::query()->updateOrCreate(
                    ['boarding_room_id' => $room->id, 'code' => sprintf('Ranjang %02d', $i)],
                    [
                        'status' => 'available',
                        'description' => 'Ranjang Standar',
                    ]
                );
            }
        }

        // 5. Seed Student Assignments (Assign Santri Contoh)
        $student = Student::query()->where('full_name', 'Santri Contoh')->first();
        if ($student) {
            // Find first bed in Room 1
            $bed = BoardingBed::query()->where('boarding_room_id', $room1->id)->where('code', 'Ranjang 01')->first();
            if ($bed) {
                BoardingStudentAssignment::query()->updateOrCreate(
                    ['student_id' => $student->id, 'status' => 'active'],
                    [
                        'boarding_dormitory_id' => $dorm1->id,
                        'boarding_room_id' => $room1->id,
                        'boarding_bed_id' => $bed->id,
                        'start_date' => now()->toDateString(),
                        'notes' => 'Penempatan awal dari seeder',
                        'created_by' => $supervisorUser->id,
                    ]
                );

                // Update bed status to occupied
                $bed->update(['status' => 'occupied']);
            }
        }
    }
}
