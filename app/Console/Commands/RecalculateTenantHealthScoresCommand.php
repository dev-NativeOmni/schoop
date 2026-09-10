<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Services\Analytics\TenantHealthScoreService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecalculateTenantHealthScoresCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:recalculate-tenant-health-scores {--date=} {--school_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate tenant health scores for schools.';

    /**
     * Execute the console command.
     */
    public function handle(TenantHealthScoreService $service): int
    {
        $dateStr = $this->option('date');
        $date = $dateStr ? Carbon::parse($dateStr) : Carbon::today();
        $schoolId = $this->option('school_id');

        if ($schoolId) {
            $this->info("Recalculating health score for school ID: {$schoolId} on date: {$date->toDateString()}");
            $service->calculateForSchool((int) $schoolId, $date);
        } else {
            $this->info("Recalculating health scores for all active schools on date: {$date->toDateString()}");
            $schools = School::query()->where('is_active', true)->get();
            $service->calculateForSchools($schools, $date);
        }

        $this->info('Tenant health scores successfully recalculated!');

        return self::SUCCESS;
    }
}
