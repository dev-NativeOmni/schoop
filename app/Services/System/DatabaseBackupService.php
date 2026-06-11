<?php

namespace App\Services\System;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DatabaseBackupService
{
    public function backup(): array
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if ($connection !== 'mysql') {
            throw new RuntimeException('Backup database saat ini hanya mendukung koneksi MySQL.');
        }

        $database = $config['database'] ?? null;
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? '';
        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 3306;

        if (! $database || ! $username) {
            throw new RuntimeException('Konfigurasi database tidak lengkap.');
        }

        $backupPath = config('app.backup_path', env('BACKUP_PATH', 'backups'));

        Storage::disk('local')->makeDirectory($backupPath);

        $filename = 'backup-' . $database . '-' . now()->format('Y-m-d-His') . '.sql';
        $relativePath = $backupPath . '/' . $filename;
        $absolutePath = Storage::disk('local')->path($relativePath);

        $mysqldump = env('BACKUP_MYSQLDUMP_PATH') ?: 'mysqldump';

        $command = [
            $mysqldump,
            "--host={$host}",
            "--port={$port}",
            "--user={$username}",
            "--single-transaction",
            "--routines",
            "--triggers",
            $database,
        ];

        $environment = [];

        if ($password !== '') {
            $environment['MYSQL_PWD'] = $password;
        }

        $result = Process::env($environment)
            ->timeout(300)
            ->run(array_merge($command, ['--result-file=' . $absolutePath]));

        if (! $result->successful()) {
            throw new RuntimeException(
                'Backup database gagal: ' . trim($result->errorOutput() ?: $result->output())
            );
        }

        if (! File::exists($absolutePath) || File::size($absolutePath) <= 0) {
            throw new RuntimeException('File backup tidak terbentuk atau kosong.');
        }

        $this->deleteOldBackups();

        return [
            'filename' => $filename,
            'relative_path' => $relativePath,
            'absolute_path' => $absolutePath,
            'size_bytes' => File::size($absolutePath),
            'created_at' => now()->toDateTimeString(),
        ];
    }

    public function deleteOldBackups(): void
    {
        $backupPath = env('BACKUP_PATH', 'backups');
        $keepDays = (int) env('BACKUP_KEEP_DAYS', 14);

        $files = Storage::disk('local')->files($backupPath);

        foreach ($files as $file) {
            if (! str_ends_with($file, '.sql')) {
                continue;
            }

            $lastModified = Storage::disk('local')->lastModified($file);

            if ($lastModified < now()->subDays($keepDays)->timestamp) {
                Storage::disk('local')->delete($file);
            }
        }
    }

    public function listBackups(): array
    {
        $backupPath = env('BACKUP_PATH', 'backups');
        $files = Storage::disk('local')->files($backupPath);

        return collect($files)
            ->filter(fn (string $file): bool => str_ends_with($file, '.sql'))
            ->map(function (string $file): array {
                return [
                    'path' => $file,
                    'name' => basename($file),
                    'size_bytes' => Storage::disk('local')->size($file),
                    'last_modified' => date('Y-m-d H:i:s', Storage::disk('local')->lastModified($file)),
                ];
            })
            ->sortByDesc('last_modified')
            ->values()
            ->all();
    }
}
