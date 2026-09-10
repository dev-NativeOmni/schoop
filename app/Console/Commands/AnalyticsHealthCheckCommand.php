<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnalyticsHealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:analytics-health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform diagnostic health checks on the analytics module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Analytics Health Check');

        $tables = [
            'analytics_metric_definitions',
            'analytics_snapshots',
            'tenant_health_scores',
            'tenant_health_score_components',
            'school_academic_snapshots',
            'school_operational_snapshots',
            'school_finance_snapshots',
            'school_support_snapshots',
            'mobile_api_usage_snapshots',
            'executive_report_runs',
            'executive_report_sections',
            'analytics_access_logs',
        ];

        $allOk = true;

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->line("- Table {$table}: OK");
            } else {
                $this->error("- Table {$table}: MISSING");
                $allOk = false;
            }
        }

        if ($allOk) {
            $metricsCount = DB::table('analytics_metric_definitions')->count();
            $this->line("- Metric definitions: OK ({$metricsCount} loaded)");

            $snapshotsCount = DB::table('analytics_snapshots')->count();
            $this->line("- Daily snapshots: OK ({$snapshotsCount} records)");

            $healthCount = DB::table('tenant_health_scores')->count();
            $this->line("- Tenant health scores: OK ({$healthCount} calculated)");

            $logsWritable = true;
            try {
                // simple write test
                DB::table('analytics_access_logs')->insert([
                    'analytics_area' => 'health_check',
                    'action' => 'diagnostic',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('analytics_access_logs')->where('analytics_area', 'health_check')->delete();
            } catch (\Exception $e) {
                $logsWritable = false;
            }
            $this->line('- Access logs: '.($logsWritable ? 'OK' : 'ERROR (unwritable)'));

            $cashlessActive = Schema::hasTable('wallet_transactions');
            $this->line('- Optional module cashless: '.($cashlessActive ? 'OK (detected)' : 'WARNING (not active)'));

            $mobileActive = Schema::hasTable('mobile_devices');
            $this->line('- Optional module mobile: '.($mobileActive ? 'OK (detected)' : 'WARNING (not active)'));

            $this->info('Status: OK');

            return self::SUCCESS;
        }

        $this->error('Status: ERROR');

        return self::FAILURE;
    }
}
