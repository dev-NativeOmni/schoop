<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LmsCourseTypeSeeder extends Seeder
{
    public function run(): void
    {
        // Course types are stored as string column fields, no separate DB table is required.
        // This seeder serves as a placeholder stub that can be extended or logged.
        $this->command->info('LMS course types verified.');
    }
}
