<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'label' => 'Super Admin',
                'description' => 'Kelola sistem penuh.',
            ],
            [
                'name' => 'admin',
                'label' => 'Admin Sekolah',
                'description' => 'Kelola data sekolah, kelas, santri, dan laporan.',
            ],
            [
                'name' => 'principal',
                'label' => 'Kepala Sekolah',
                'description' => 'Monitoring aktivitas guru, santri, dan setoran.',
            ],
            [
                'name' => 'teacher',
                'label' => 'Guru Tahfidz',
                'description' => 'Input setoran dan pantau target.',
            ],
            [
                'name' => 'parent',
                'label' => 'Orang Tua',
                'description' => 'Lihat progres anak.',
            ],
            [
                'name' => 'student',
                'label' => 'Santri',
                'description' => 'Lihat progres pribadi.',
            ],
        ];

        foreach ($roles as $role) {
            Role::query()->updateOrCreate(
                ['name' => $role['name']],
                [
                    'label' => $role['label'],
                    'description' => $role['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
