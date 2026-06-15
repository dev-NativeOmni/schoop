<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:backup-database')
    ->dailyAt('23:30')
    ->withoutOverlapping()
    ->onFailure(function (): void {
        logger()->error('Scheduled database backup failed.');
    });

Schedule::command('app:capture-product-usage-snapshots')
    ->dailyAt('23:30')
    ->withoutOverlapping();

Schedule::command('app:check-support-sla-breaches')
    ->hourly()
    ->withoutOverlapping();

// Phase 22 - External API & Webhooks
Schedule::command('app:retry-failed-webhooks')
    ->everyTenMinutes()
    ->withoutOverlapping();

Schedule::command('app:prune-api-request-logs --days=90')
    ->dailyAt('02:30')
    ->withoutOverlapping();

// Phase 23 - Advanced Analytics & Executive Intelligence
Schedule::command('app:capture-analytics-snapshots')
    ->dailyAt('23:45')
    ->withoutOverlapping();

Schedule::command('app:recalculate-tenant-health-scores')
    ->dailyAt('23:55')
    ->withoutOverlapping();
