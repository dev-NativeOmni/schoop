<?php

namespace Database\Seeders;

use App\Models\MutabaahActivity;
use App\Models\MutabaahCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MutabaahActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryMap = MutabaahCategory::query()
            ->whereNull('school_id')
            ->pluck('id', 'name');

        $activities = [
            // Ibadah Wajib
            [
                'category'   => 'Ibadah Wajib',
                'name'       => 'Shalat Subuh',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 10,
            ],
            [
                'category'   => 'Ibadah Wajib',
                'name'       => 'Shalat Dzuhur',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 20,
            ],
            [
                'category'   => 'Ibadah Wajib',
                'name'       => 'Shalat Ashar',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 30,
            ],
            [
                'category'   => 'Ibadah Wajib',
                'name'       => 'Shalat Maghrib',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 40,
            ],
            [
                'category'   => 'Ibadah Wajib',
                'name'       => 'Shalat Isya',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 50,
            ],
            // Ibadah Sunnah
            [
                'category'   => 'Ibadah Sunnah',
                'name'       => 'Shalat Dhuha',
                'input_type' => 'checklist',
                'is_required' => false,
                'sort_order' => 60,
            ],
            [
                'category'   => 'Ibadah Sunnah',
                'name'       => 'Puasa Sunnah',
                'input_type' => 'checklist',
                'is_required' => false,
                'sort_order' => 70,
            ],
            // Quran dan Dzikir
            [
                'category'    => 'Quran dan Dzikir',
                'name'        => 'Tilawah Harian',
                'input_type'  => 'count',
                'target_count' => 1,
                'target_unit' => 'halaman',
                'is_required' => false,
                'sort_order'  => 80,
            ],
            [
                'category'   => 'Quran dan Dzikir',
                'name'       => 'Dzikir Pagi',
                'input_type' => 'checklist',
                'is_required' => false,
                'sort_order' => 90,
            ],
            [
                'category'   => 'Quran dan Dzikir',
                'name'       => 'Dzikir Petang',
                'input_type' => 'checklist',
                'is_required' => false,
                'sort_order' => 100,
            ],
            // Adab dan Karakter
            [
                'category'    => 'Adab dan Karakter',
                'name'        => 'Adab kepada Guru',
                'input_type'  => 'score',
                'target_score' => 80,
                'is_required' => false,
                'sort_order'  => 110,
            ],
            [
                'category'    => 'Adab dan Karakter',
                'name'        => 'Kedisiplinan Pribadi',
                'input_type'  => 'score',
                'target_score' => 80,
                'is_required' => false,
                'sort_order'  => 120,
            ],
        ];

        foreach ($activities as $activity) {
            $categoryId = $categoryMap[$activity['category']] ?? null;

            MutabaahActivity::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'slug'      => Str::slug($activity['name']),
                ],
                [
                    'mutabaah_category_id' => $categoryId,
                    'name'                 => $activity['name'],
                    'description'          => null,
                    'input_type'           => $activity['input_type'],
                    'target_score'         => $activity['target_score'] ?? null,
                    'target_count'         => $activity['target_count'] ?? null,
                    'target_unit'          => $activity['target_unit'] ?? null,
                    'is_required'          => $activity['is_required'],
                    'is_active'            => true,
                    'allow_teacher_input'  => true,
                    'allow_parent_input'   => false,
                    'allow_student_input'  => false,
                    'sort_order'           => $activity['sort_order'],
                ]
            );
        }

        $this->command->info('MutabaahActivitySeeder: ' . count($activities) . ' activities seeded.');
    }
}
