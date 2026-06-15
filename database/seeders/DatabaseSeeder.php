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
            // Phase 11 — Mutabaah Yaumiyah Tracker
            MutabaahCategorySeeder::class,
            MutabaahActivitySeeder::class,
            // Phase 13 — Tahsin Management App
            TahsinLevelSeeder::class,
            TahsinSkillSeeder::class,
            // Phase 14 — Student Finance Ledger
            FinanceFeeCategorySeeder::class,
            // Phase 15 — SchoolOS Mini
            SystemModuleSeeder::class,
            // Phase 16 — Boarding School Management System
            BoardingDormitorySeeder::class,
            // Phase 17 — Multi-Tenant Foundation
            TenantModuleSeeder::class,
        ]);
    }
}
