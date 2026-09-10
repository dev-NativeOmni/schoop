<?php

namespace Database\Seeders;

use App\Models\MobileAppVersion;
use Illuminate\Database\Seeder;

class MobileAppVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $versions = [
            [
                'platform' => 'android',
                'version' => '1.0.0',
                'build_number' => 1,
                'minimum_supported_version' => '1.0.0',
                'release_notes' => 'Initial mobile companion API release.',
            ],
            [
                'platform' => 'ios',
                'version' => '1.0.0',
                'build_number' => 1,
                'minimum_supported_version' => '1.0.0',
                'release_notes' => 'Initial mobile companion API release.',
            ],
            [
                'platform' => 'web',
                'version' => '1.0.0',
                'build_number' => 1,
                'minimum_supported_version' => '1.0.0',
                'release_notes' => 'Initial mobile-compatible client release.',
            ],
        ];

        foreach ($versions as $version) {
            MobileAppVersion::query()->updateOrCreate(
                [
                    'platform' => $version['platform'],
                    'version' => $version['version'],
                ],
                [
                    'build_number' => $version['build_number'],
                    'minimum_supported_version' => $version['minimum_supported_version'],
                    'is_force_update' => false,
                    'is_active' => true,
                    'release_notes' => $version['release_notes'],
                    'released_at' => now(),
                ]
            );
        }
    }
}
