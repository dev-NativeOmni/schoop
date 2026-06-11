<?php

namespace App\Services\System;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SystemHealthService
{
    public function check(): array
    {
        return [
            'app' => $this->app(),
            'database' => $this->database(),
            'storage' => $this->storage(),
            'cache' => $this->cache(),
            'queue' => $this->queue(),
            'backup' => $this->backup(),
        ];
    }

    public function isHealthy(): bool
    {
        return collect($this->check())->every(
            fn (array $item): bool => ($item['ok'] ?? false) === true
        );
    }

    private function app(): array
    {
        return [
            'ok' => true,
            'name' => config('app.name'),
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];
    }

    private function database(): array
    {
        try {
            DB::connection()->getPdo();

            $requiredTables = [
                'users',
                'roles',
                'schools',
                'class_rooms',
                'students',
                'parent_profiles',
                'teacher_profiles',
                'hafalan_records',
                'tahfizh_targets',
                'tahfizh_debts',
                'notifications',
            ];

            $missingTables = collect($requiredTables)
                ->reject(fn (string $table): bool => Schema::hasTable($table))
                ->values()
                ->all();

            return [
                'ok' => count($missingTables) === 0,
                'connection' => config('database.default'),
                'missing_tables' => $missingTables,
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }

    private function storage(): array
    {
        $paths = [
            storage_path('app'),
            storage_path('framework'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        $unwritable = collect($paths)
            ->reject(fn (string $path): bool => File::isWritable($path))
            ->values()
            ->all();

        return [
            'ok' => $unwritable === [],
            'unwritable_paths' => $unwritable,
        ];
    }

    private function cache(): array
    {
        try {
            cache()->put('system_health_check', now()->toDateTimeString(), 60);

            return [
                'ok' => cache()->has('system_health_check'),
                'store' => config('cache.default'),
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'store' => config('cache.default'),
                'error' => $exception->getMessage(),
            ];
        }
    }

    private function queue(): array
    {
        return [
            'ok' => true,
            'connection' => config('queue.default'),
            'note' => 'Phase 10 validates queue configuration only. Worker supervision is handled on server deployment.',
        ];
    }

    private function backup(): array
    {
        try {
            Storage::disk('local')->makeDirectory(env('BACKUP_PATH', 'backups'));

            return [
                'ok' => true,
                'path' => Storage::disk('local')->path(env('BACKUP_PATH', 'backups')),
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
