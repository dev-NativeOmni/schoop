<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::query()->updateOrCreate(
            ['code' => 'ALAZHAR7'],
            [
                'name' => 'SMA Islam Al Azhar 7',
                'npsn' => null,
                'email' => null,
                'phone' => null,
                'address' => null,
                'logo_path' => null,
                'primary_color' => '#0f172a',
                'secondary_color' => '#f59e0b',
                'is_active' => true,
            ]
        );
    }
}
