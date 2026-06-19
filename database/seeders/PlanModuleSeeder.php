<?php

namespace Database\Seeders;

use App\Models\PlanModule;
use App\Models\SubscriptionPlan;
use App\Models\SystemModule;
use Illuminate\Database\Seeder;

class PlanModuleSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'trial' => [
                'tahfizh',
                'reports',
                'notifications',
            ],
            'basic' => [
                'tahfizh',
                'reports',
                'notifications',
                'exports',
            ],
            'pro' => [
                'tahfizh',
                'reports',
                'notifications',
                'exports',
                'mutabaah',
                'attendance',
                'tahsin',
                'finance',
                'schoolos',
            ],
            'enterprise' => SystemModule::query()
                ->where('is_active', true)
                ->pluck('module_key')
                ->all(),
        ];

        foreach ($mapping as $planCode => $moduleKeys) {
            $plan = SubscriptionPlan::query()->where('code', $planCode)->first();

            if (! $plan) {
                continue;
            }

            foreach ($moduleKeys as $moduleKey) {
                $module = SystemModule::query()->where('module_key', $moduleKey)->first();

                if (! $module) {
                    continue;
                }

                PlanModule::query()->updateOrCreate(
                    [
                        'subscription_plan_id' => $plan->id,
                        'system_module_id' => $module->id,
                    ],
                    [
                        'is_included' => true,
                        'limits' => null,
                        'features' => null,
                    ]
                );
            }
        }
    }
}
