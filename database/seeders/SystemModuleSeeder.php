<?php

namespace Database\Seeders;

use App\Models\SystemModule;
use Illuminate\Database\Seeder;

class SystemModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'module_key' => 'schoolos',
                'name' => 'SchoolOS',
                'description' => 'Dashboard pusat dan integrasi modul sekolah.',
                'route_name' => 'schoolos.dashboard',
                'sort_order' => 1,
                'is_core' => true,
            ],
            [
                'module_key' => 'tahfizh',
                'name' => 'Tahfizh',
                'description' => 'Monitoring setoran, target, hutang, dan laporan tahfizh.',
                'route_name' => 'reports.tahfizh.dashboard',
                'sort_order' => 10,
                'is_core' => true,
            ],
            [
                'module_key' => 'mutabaah',
                'name' => 'Mutabaah',
                'description' => 'Tracker ibadah dan karakter harian santri.',
                'route_name' => 'mutabaah.activities.index',
                'sort_order' => 20,
                'is_core' => false,
            ],
            [
                'module_key' => 'attendance',
                'name' => 'Attendance',
                'description' => 'QR attendance dan laporan presensi.',
                'route_name' => 'attendance.sessions.index',
                'sort_order' => 30,
                'is_core' => false,
            ],
            [
                'module_key' => 'tahsin',
                'name' => 'Tahsin',
                'description' => 'Level, skill, asesmen, dan progress tahsin.',
                'route_name' => 'tahsin.levels.index',
                'sort_order' => 40,
                'is_core' => false,
            ],
            [
                'module_key' => 'finance',
                'name' => 'Finance',
                'description' => 'Tagihan, pembayaran, ledger, dan laporan finance.',
                'route_name' => 'finance.reports.dashboard',
                'sort_order' => 50,
                'is_core' => false,
            ],
            [
                'module_key' => 'notifications',
                'name' => 'Notifications',
                'description' => 'Notification center berbasis database.',
                'route_name' => 'notifications.index',
                'sort_order' => 60,
                'is_core' => false,
            ],
            [
                'module_key' => 'exports',
                'name' => 'Exports',
                'description' => 'Export PDF dan Excel.',
                'route_name' => 'exports.tahfizh.index',
                'sort_order' => 70,
                'is_core' => false,
            ],
            [
                'module_key' => 'boarding',
                'name' => 'Boarding',
                'description' => 'Boarding school management, dormitory, room assignment, leave request, health log, discipline log, and roll call.',
                'route_name' => 'boarding.dashboard',
                'sort_order' => 80,
                'is_core' => false,
            ],
            [
                'module_key' => 'white_label',
                'name' => 'White Label',
                'description' => 'Visual branding profile, theme builder, PWA manifests, and custom domain resolver.',
                'route_name' => 'white-label.dashboard',
                'sort_order' => 95,
                'is_core' => false,
            ],
            [
                'module_key' => 'cashless',
                'name' => 'Cashless Kantin / Merchant POS',
                'description' => 'Closed-loop wallet santri, POS kantin, refund, settlement, dan laporan merchant.',
                'route_name' => 'cashless.reports.dashboard',
                'sort_order' => 100,
                'is_core' => false,
            ],
            [
                'module_key' => 'lms',
                'name' => 'LMS Lite',
                'description' => 'Kursus, materi, tugas, kuis, dan progres belajar santri.',
                'route_name' => 'lms.dashboard',
                'sort_order' => 105,
                'is_core' => false,
            ],
            [
                'module_key' => 'saas_ops',
                'name' => 'SaaS Operations',
                'description' => 'Subscription, tenant billing, onboarding, support, incident, release, knowledge base, and usage health operations.',
                'route_name' => 'saas-ops.dashboard',
                'sort_order' => 110,
                'is_core' => false,
            ],
        ];

        foreach ($modules as $module) {
            SystemModule::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'module_key' => $module['module_key'],
                ],
                [
                    'name' => $module['name'],
                    'description' => $module['description'],
                    'route_name' => $module['route_name'],
                    'sort_order' => $module['sort_order'],
                    'is_active' => true,
                    'is_core' => $module['is_core'],
                ]
            );
        }
    }
}
