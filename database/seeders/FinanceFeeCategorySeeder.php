<?php

namespace Database\Seeders;

use App\Models\FinanceFeeCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FinanceFeeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'SPP', 'description' => 'Biaya SPP bulanan.', 'sort_order' => 10],
            ['name' => 'Tahfizh', 'description' => 'Biaya program tahfizh.', 'sort_order' => 20],
            ['name' => 'Kegiatan', 'description' => 'Biaya kegiatan sekolah.', 'sort_order' => 30],
            ['name' => 'Daftar Ulang', 'description' => 'Biaya daftar ulang.', 'sort_order' => 40],
            ['name' => 'Seragam', 'description' => 'Biaya seragam.', 'sort_order' => 50],
            ['name' => 'Buku', 'description' => 'Biaya buku.', 'sort_order' => 60],
            ['name' => 'Ujian', 'description' => 'Biaya ujian.', 'sort_order' => 70],
            ['name' => 'Lain-lain', 'description' => 'Biaya lain-lain.', 'sort_order' => 999],
        ];

        foreach ($categories as $category) {
            FinanceFeeCategory::query()->updateOrCreate(
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
    }
}
