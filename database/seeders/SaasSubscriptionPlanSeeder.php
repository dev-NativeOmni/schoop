<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\SaasSubscriptionPlan;
use Illuminate\Database\Seeder;

class SaasSubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['support_staff', 'customer_success', 'sales', 'operations_manager'] as $roleName) {
            Role::query()->firstOrCreate(
                ['name' => $roleName],
                ['label' => str_replace('_', ' ', ucfirst($roleName)), 'description' => 'Internal SaaS operations role', 'is_active' => true]
            );
        }

        $plans = [
            ['code' => 'basic', 'name' => 'Basic', 'billing_cycle' => 'monthly', 'features' => ['Tahfizh', 'Parent Portal'], 'allowed_modules' => ['schoolos', 'tahfizh']],
            ['code' => 'standard', 'name' => 'Standard', 'billing_cycle' => 'monthly', 'features' => ['Reports', 'Notification'], 'allowed_modules' => ['schoolos', 'tahfizh', 'reports', 'notifications', 'exports']],
            ['code' => 'pro', 'name' => 'Pro', 'billing_cycle' => 'monthly', 'features' => ['Mutabaah', 'Attendance', 'Tahsin'], 'allowed_modules' => ['schoolos', 'tahfizh', 'reports', 'notifications', 'exports', 'mutabaah', 'attendance', 'tahsin']],
            ['code' => 'enterprise', 'name' => 'Enterprise', 'billing_cycle' => 'yearly', 'features' => ['Multi-campus', 'White-label', 'Boarding', 'Finance', 'Cashless', 'LMS'], 'allowed_modules' => ['schoolos', 'tahfizh', 'reports', 'notifications', 'exports', 'mutabaah', 'attendance', 'tahsin', 'finance', 'boarding', 'white_label', 'cashless', 'lms']],
        ];

        foreach ($plans as $plan) {
            SaasSubscriptionPlan::query()->updateOrCreate(
                ['code' => $plan['code']],
                array_merge($plan, ['monthly_price' => 0, 'yearly_price' => 0, 'status' => 'active'])
            );
        }
    }
}
