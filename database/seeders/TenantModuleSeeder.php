<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\TenantModule;
use Illuminate\Database\Seeder;

class TenantModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['module_key' => 'tahfizh', 'module_name' => 'Tahfizh'],
            ['module_key' => 'reports', 'module_name' => 'Reports'],
            ['module_key' => 'notifications', 'module_name' => 'Notifications'],
            ['module_key' => 'exports', 'module_name' => 'Exports'],
            ['module_key' => 'mutabaah', 'module_name' => 'Mutabaah'],
            ['module_key' => 'attendance', 'module_name' => 'Attendance'],
            ['module_key' => 'tahsin', 'module_name' => 'Tahsin'],
            ['module_key' => 'finance', 'module_name' => 'Finance'],
            ['module_key' => 'schoolos', 'module_name' => 'SchoolOS'],
            ['module_key' => 'boarding', 'module_name' => 'Boarding'],
            ['module_key' => 'white_label', 'module_name' => 'White Label'],
            ['module_key' => 'cashless', 'module_name' => 'Cashless Kantin / Merchant POS'],
            ['module_key' => 'lms', 'module_name' => 'LMS Lite'],
        ];

        School::query()->each(function (School $school) use ($modules): void {
            foreach ($modules as $module) {
                TenantModule::query()->updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'module_key' => $module['module_key'],
                    ],
                    [
                        'module_name' => $module['module_name'],
                        'is_enabled' => true,
                    ]
                );
            }
        });
    }
}
