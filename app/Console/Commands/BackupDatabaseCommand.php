<?php

namespace App\Console\Commands;

use App\Services\System\DatabaseBackupService;
use Illuminate\Console\Command;
use Throwable;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'app:backup-database';

    protected $description = 'Create a MySQL database backup for HafizPlus School Platform.';

    public function handle(DatabaseBackupService $backupService): int
    {
        $this->info('Starting database backup...');

        try {
            $backup = $backupService->backup();

            $this->info('Database backup completed.');
            $this->line('File: ' . $backup['filename']);
            $this->line('Path: ' . $backup['relative_path']);
            $this->line('Size: ' . number_format($backup['size_bytes']) . ' bytes');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Database backup failed.');
            $this->error($exception->getMessage());

            report($exception);

            return self::FAILURE;
        }
    }
}
