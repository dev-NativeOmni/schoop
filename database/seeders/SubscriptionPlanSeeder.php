<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => 'trial',
                'name' => 'Trial',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'description' => 'Trial terbatas untuk demo sekolah.',
                'limits' => [
                    'max_students' => 50,
                    'max_teachers' => 5,
                    'max_parents' => 100,
                    'max_exports_per_month' => 10,
                    'storage_mb' => 256,
                ],
                'sort_order' => 10,
            ],
            [
                'code' => 'basic',
                'name' => 'Basic',
                'monthly_price' => 299000,
                'yearly_price' => 2990000,
                'description' => 'Plan awal untuk sekolah kecil.',
                'limits' => [
                    'max_students' => 300,
                    'max_teachers' => 30,
                    'max_parents' => 600,
                    'max_exports_per_month' => 100,
                    'storage_mb' => 1024,
                ],
                'sort_order' => 20,
            ],
            [
                'code' => 'pro',
                'name' => 'Pro',
                'monthly_price' => 699000,
                'yearly_price' => 6990000,
                'description' => 'Plan lengkap untuk sekolah aktif.',
                'limits' => [
                    'max_students' => 1000,
                    'max_teachers' => 100,
                    'max_parents' => 2000,
                    'max_exports_per_month' => 1000,
                    'storage_mb' => 5120,
                ],
                'sort_order' => 30,
            ],
            [
                'code' => 'enterprise',
                'name' => 'Enterprise',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'description' => 'Plan custom untuk sekolah besar dan kebutuhan khusus.',
                'limits' => [
                    'max_students' => 999999,
                    'max_teachers' => 999999,
                    'max_parents' => 999999,
                    'max_exports_per_month' => 999999,
                    'storage_mb' => 51200,
                ],
                'sort_order' => 40,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::query()->updateOrCreate(
                ['code' => $plan['code']],
                array_merge($plan, ['is_active' => true])
            );
        }
    }
}
