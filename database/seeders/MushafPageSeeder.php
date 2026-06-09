<?php

namespace Database\Seeders;

use App\Models\MushafPage;
use Illuminate\Database\Seeder;

class MushafPageSeeder extends Seeder
{
    public function run(): void
    {
        for ($page = 1; $page <= 604; $page++) {
            MushafPage::query()->updateOrCreate(
                ['page_number' => $page],
                [
                    'total_lines' => 15,
                    'start_surah_id' => null,
                    'start_ayah' => null,
                    'end_surah_id' => null,
                    'end_ayah' => null,
                    'juz_id' => null,
                ]
            );
        }
    }
}
