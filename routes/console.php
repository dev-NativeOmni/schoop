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
