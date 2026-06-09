<?php

namespace Database\Seeders;

use App\Models\QuranJuz;
use Illuminate\Database\Seeder;

class QuranJuzSeeder extends Seeder
{
    public function run(): void
    {
        for ($number = 1; $number <= 30; $number++) {
            QuranJuz::query()->updateOrCreate(
                ['number' => $number],
                [
                    'name' => 'Juz '.$number,
                    'description' => null,
                ]
            );
        }
    }
}
