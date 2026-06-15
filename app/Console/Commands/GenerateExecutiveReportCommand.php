<?php

namespace App\Console\Commands;

use App\Services\Analytics\ExecutiveReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateExecutiveReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-executive-report {--school_id=} {--period=monthly} {--period_start=} {--period_end=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate draft monthly or custom executive reports.';

    /**
     * Execute the console command.
     */
    public function handle(ExecutiveReportService $service): int
    {
        $schoolId = $this->option('school_id');
        $startStr = $this->option('period_start');
        $endStr = $this->option('period_end');

        $start = $startStr ? Carbon::parse($startStr) : Carbon::now()->startOfMonth();
        $end = $endStr ? Carbon::parse($endStr) : Carbon::now()->endOfMonth();

        if ($schoolId) {
            $this->info("Generating monthly school report for school ID: {$schoolId} from {$start->toDateString()} to {$end->toDateString()}");
            $report = $service->generateMonthlySchoolReport((int) $schoolId, $start, $end);
        } else {
            $this->info("Generating monthly internal executive report from {$start->toDateString()} to {$end->toDateString()}");
            $report = $service->generateMonthlyInternalReport($start, $end);
        }

        $this->info("Executive report generated successfully as DRAFT! ID: {$report->id}");

        return self::SUCCESS;
    }
}
