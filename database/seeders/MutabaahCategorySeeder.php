<?php

namespace Database\Seeders;

use App\Models\MutabaahCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MutabaahCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ibadah Wajib',
                'description' => 'Aktivitas ibadah wajib harian.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Ibadah Sunnah',
                'description' => 'Aktivitas ibadah sunnah harian atau pekanan.',
                'sort_order' => 20,
            ],
            [
                'name' => 'Quran dan Dzikir',
                'description' => 'Tilawah, dzikir, dan aktivitas Quran harian.',
                'sort_order' => 30,
            ],
            [
                'name' => 'Adab dan Karakter',
                'description' => 'Pembiasaan adab dan karakter santri.',
                'sort_order' => 40,
            ],
        ];

        foreach ($categories as $category) {
            MutabaahCategory::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'slug' => Str::slug($category['name']),
                ],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('MutabaahCategorySeeder: '.count($categories).' categories seeded.');
    }
}
