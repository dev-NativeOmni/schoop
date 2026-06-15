<?php

namespace Database\Seeders;

use App\Models\SlaPolicy;
use Illuminate\Database\Seeder;

class SlaPolicySeeder extends Seeder
{
    public function run(): void
    {
        $policies = [
            ['priority' => 'critical', 'first_response_minutes' => 30, 'resolution_minutes' => 240],
            ['priority' => 'high', 'first_response_minutes' => 120, 'resolution_minutes' => 1440],
            ['priority' => 'medium', 'first_response_minutes' => 1440, 'resolution_minutes' => 4320],
            ['priority' => 'low', 'first_response_minutes' => 2880, 'resolution_minutes' => 10080],
        ];

        foreach ($policies as $policy) {
            SlaPolicy::query()->updateOrCreate(['priority' => $policy['priority']], array_merge($policy, ['is_active' => true]));
        }
    }
}
