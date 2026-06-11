<?php

namespace Database\Seeders;

use App\Models\TahsinLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TahsinLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Tahsin Dasar',
                'description' => 'Perbaikan makhraj, huruf, dan kelancaran awal.',
                'minimum_score' => 70,
                'sort_order' => 10,
            ],
            [
                'name' => 'Tahsin Menengah',
                'description' => 'Penguatan tajwid dasar, mad, ghunnah, nun mati, dan mim mati.',
                'minimum_score' => 75,
                'sort_order' => 20,
            ],
            [
                'name' => 'Tahsin Lanjutan',
                'description' => 'Penguatan tartil, waqaf ibtida, dan konsistensi bacaan.',
                'minimum_score' => 80,
                'sort_order' => 30,
            ],
            [
                'name' => 'Siap Tahfizh',
                'description' => 'Bacaan sudah cukup stabil untuk fokus hafalan.',
                'minimum_score' => 85,
                'sort_order' => 40,
            ],
        ];

        foreach ($levels as $level) {
            TahsinLevel::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'slug' => Str::slug($level['name']),
                ],
                [
                    'name' => $level['name'],
                    'description' => $level['description'],
                    'minimum_score' => $level['minimum_score'],
                    'sort_order' => $level['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
