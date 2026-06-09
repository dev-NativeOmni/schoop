<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SchoolSeeder::class,
            InitialUserSeeder::class,
            QuranJuzSeeder::class,
            QuranSurahSeeder::class,
            MushafPageSeeder::class,
        ]);
    }
}
