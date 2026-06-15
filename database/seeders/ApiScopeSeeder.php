<?php

namespace Database\Seeders;

use App\Models\ApiScope;
use Illuminate\Database\Seeder;

class ApiScopeSeeder extends Seeder
{
    public function run(): void
    {
        $scopes = [
            ['code' => 'students:read', 'name' => 'Read Students', 'description' => 'Read student profiles for the assigned tenant.', 'category' => 'student', 'is_sensitive' => false],
            ['code' => 'classes:read', 'name' => 'Read Classes', 'description' => 'Read class room and homeroom metadata.', 'category' => 'school', 'is_sensitive' => false],
            ['code' => 'attendance:read', 'name' => 'Read Attendance', 'description' => 'Read attendance records for students.', 'category' => 'attendance', 'is_sensitive' => false],
            ['code' => 'attendance:write', 'name' => 'Write Attendance', 'description' => 'Create attendance records through the external API.', 'category' => 'attendance', 'is_sensitive' => true],
            ['code' => 'tahfizh:read', 'name' => 'Read Tahfizh Progress', 'description' => 'Read Quran memorization progress summaries.', 'category' => 'tahfizh', 'is_sensitive' => false],
            ['code' => 'finance:read', 'name' => 'Read Finance Bills', 'description' => 'Read student finance bills and payment summaries.', 'category' => 'finance', 'is_sensitive' => true],
            ['code' => 'cashless:read', 'name' => 'Read Cashless Transactions', 'description' => 'Read cashless merchant transaction records.', 'category' => 'cashless', 'is_sensitive' => true],
            ['code' => 'webhooks:manage', 'name' => 'Manage Webhooks', 'description' => 'Test and manage webhook delivery events.', 'category' => 'webhook', 'is_sensitive' => true],
        ];

        foreach ($scopes as $index => $scope) {
            ApiScope::query()->updateOrCreate(
                ['code' => $scope['code']],
                $scope + [
                    'is_active' => true,
                    'sort_order' => ($index + 1) * 10,
                ],
            );
        }
    }
}
