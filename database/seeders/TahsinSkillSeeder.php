<?php

namespace Database\Seeders;

use App\Models\TahsinLevel;
use App\Models\TahsinSkill;
use Illuminate\Database\Seeder;

class TahsinSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelMap = TahsinLevel::query()
            ->whereNull('school_id')
            ->pluck('id', 'name');

        $skills = [
            [
                'level' => 'Tahsin Dasar',
                'name' => 'Makharijul Huruf',
                'code' => 'MAKHRAJ',
                'description' => 'Ketepatan tempat keluarnya huruf.',
                'sort_order' => 10,
            ],
            [
                'level' => 'Tahsin Dasar',
                'name' => 'Sifat Huruf',
                'code' => 'SIFAT',
                'description' => 'Ketepatan sifat huruf ketika dibaca.',
                'sort_order' => 20,
            ],
            [
                'level' => 'Tahsin Dasar',
                'name' => 'Kelancaran Bacaan',
                'code' => 'FLUENCY',
                'description' => 'Kelancaran membaca tanpa banyak berhenti salah.',
                'sort_order' => 30,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Mad',
                'code' => 'MAD',
                'description' => 'Ketepatan panjang pendek bacaan.',
                'sort_order' => 40,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Ghunnah',
                'code' => 'GHUNNAH',
                'description' => 'Ketepatan dengung pada bacaan.',
                'sort_order' => 50,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Hukum Nun Mati dan Tanwin',
                'code' => 'NUN_TANWIN',
                'description' => 'Ketepatan idzhar, idgham, iqlab, dan ikhfa.',
                'sort_order' => 60,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Hukum Mim Mati',
                'code' => 'MIM_MATI',
                'description' => 'Ketepatan hukum mim mati.',
                'sort_order' => 70,
            ],
            [
                'level' => 'Tahsin Lanjutan',
                'name' => 'Qalqalah',
                'code' => 'QALQALAH',
                'description' => 'Ketepatan pantulan qalqalah.',
                'sort_order' => 80,
            ],
            [
                'level' => 'Tahsin Lanjutan',
                'name' => 'Waqaf dan Ibtida',
                'code' => 'WAQAF_IBTIDA',
                'description' => 'Ketepatan berhenti dan memulai bacaan.',
                'sort_order' => 90,
            ],
            [
                'level' => 'Siap Tahfizh',
                'name' => 'Tartil dan Konsistensi',
                'code' => 'TARTIL',
                'description' => 'Konsistensi bacaan tartil untuk tahfizh.',
                'sort_order' => 100,
            ],
        ];

        foreach ($skills as $skill) {
            TahsinSkill::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'code' => $skill['code'],
                ],
                [
                    'tahsin_level_id' => $levelMap[$skill['level']] ?? null,
                    'name' => $skill['name'],
                    'description' => $skill['description'],
                    'maximum_score' => 100,
                    'sort_order' => $skill['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
