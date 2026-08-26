<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\SaasSchoolSubscription;
use Illuminate\Database\Seeder;

class SchoolSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $schools = School::all();

        foreach ($schools as $school) {
            SchoolSubscription::firstOrCreate(
                ['school_id' => $school->id],
                [
                    'subscription_plan_id' => 4, // Enterprise
                    'status' => 'active',
                    'starts_at' => now()->subMonth(),
                    'current_period_starts_at' => now()->subMonth(),
                    'current_period_ends_at' => now()->addYears(5),
                ]
            );

            SaasSchoolSubscription::firstOrCreate(
                ['school_id' => $school->id],
                [
                    'saas_subscription_plan_id' => 4, // Enterprise
                    'status' => 'active',
                    'billing_cycle' => 'yearly',
                    'starts_at' => now()->subMonth(),
                    'current_period_start' => now()->subMonth(),
                    'current_period_end' => now()->addYears(5),
                ]
            );
        }
    }
}
