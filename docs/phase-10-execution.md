# Phase 10 Execution Guide — Production Hardening

## 0. Identitas Phase

Dokumen ini adalah instruksi eksekusi untuk AI coding agent di code editor.

Project:

```text
HafizPlus School Platform
```

Produk pertama:

```text
Tahfizh Monitoring App
```

Framework:

```text
Laravel 12
```

Database:

```text
MySQL
```

Project folder lokal:

```text
C:\xampp\htdocs\hafizplus-school-platform
```

Database local:

```text
hafizplus_school_platform
```

Status:

```text
Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.
```

---

# 1. Tujuan Phase 10

Phase 10 bertujuan membuat aplikasi siap masuk tahap uji production terbatas.

Fokus Phase 10:

1. Production environment checklist.
2. Security hardening.
3. App configuration hardening.
4. Database backup command.
5. Backup scheduler.
6. Log maintenance.
7. Health check internal.
8. Basic system status page.
9. Queue readiness.
10. Scheduler readiness.
11. Deployment checklist.
12. Rollback checklist.
13. Post-deploy verification.
14. Dokumentasi production.

Phase 10 tidak menambah modul bisnis baru.

---

# 2. Batasan Phase 10

AI agent tidak boleh membuat fitur berikut pada Phase 10:

1. Attendance.
2. Mutabaah.
3. Tahsin.
4. Finance.
5. Cashless.
6. Payment gateway.
7. Wallet.
8. Multi-tenant penuh.
9. White-label builder.
10. Native Android.
11. Native iOS.
12. API mobile lengkap.
13. WhatsApp gateway.
14. Firebase push notification.
15. Websocket.
16. LMS.
17. Boarding school module.

Phase 10 hanya memperkuat aplikasi yang sudah dibuat dari Phase 0–9.

---

# 3. Target Output Phase 10

Setelah Phase 10 selesai, aplikasi harus punya:

1. File `.env.production.example`.
2. File `docs/production-checklist.md`.
3. File `docs/deployment-checklist.md`.
4. File `docs/rollback-plan.md`.
5. File `docs/backup-restore-policy.md`.
6. File `docs/security-hardening.md`.
7. Command backup database:

   * `php artisan app:backup-database`
8. Command system health check:

   * `php artisan app:system-health-check`
9. Controller:

   * `SystemStatusController`
10. View:

* `admin/system/status.blade.php`

11. Route:

* `admin.system.status`

12. Scheduler entry untuk backup database harian.
13. Storage folder backup.
14. Validasi permission storage.
15. Production hardening checklist.
16. Dokumentasi Phase 10.

---

# 4. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
php artisan --version
php -v
composer -V
npm -v
php artisan migrate:status
php artisan route:list
git status
```

Target:

1. Laravel 12 berjalan.
2. Phase 0 selesai.
3. Phase 1 selesai.
4. Phase 2 selesai.
5. Phase 3 selesai.
6. Phase 4 selesai.
7. Phase 5 selesai.
8. Phase 6 selesai.
9. Phase 7 selesai.
10. Phase 8 selesai.
11. Phase 9 selesai.
12. Export PDF/Excel sudah berjalan.
13. Working tree bersih atau semua perubahan diketahui.

Jika Phase 9 belum selesai, hentikan eksekusi.

---

# 5. Buat Branch Git Phase 10

Jalankan:

```powershell
git checkout -b phase-10-production-hardening
```

Jika branch sudah ada:

```powershell
git checkout phase-10-production-hardening
```

---

# 6. Struktur File yang Akan Dibuat

AI agent harus membuat atau mengubah file berikut:

```text
app/
├── Console/
│   └── Commands/
│       ├── BackupDatabaseCommand.php
│       └── SystemHealthCheckCommand.php
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── SystemStatusController.php
├── Services/
│   └── System/
│       ├── DatabaseBackupService.php
│       └── SystemHealthService.php

resources/
└── views/
    └── admin/
        └── system/
            └── status.blade.php

storage/
└── app/
    └── backups/
        └── .gitkeep

docs/
├── phase-10-production-hardening.md
├── production-checklist.md
├── deployment-checklist.md
├── rollback-plan.md
├── backup-restore-policy.md
└── security-hardening.md

.env.production.example

routes/
└── web.php

routes/
└── console.php
```

---

# 7. Buat Folder dan File

Jalankan:

```powershell
mkdir app\Services\System
mkdir resources\views\admin
mkdir resources\views\admin\system
mkdir storage\app\backups

New-Item storage\app\backups\.gitkeep
New-Item app\Services\System\DatabaseBackupService.php
New-Item app\Services\System\SystemHealthService.php
New-Item .env.production.example
```

Buat command:

```powershell
php artisan make:command BackupDatabaseCommand
php artisan make:command SystemHealthCheckCommand
```

Buat controller:

```powershell
php artisan make:controller Admin/SystemStatusController
```

---

# 8. File `.env.production.example`

Buka:

```text
.env.production.example
```

Isi lengkap:

```env
APP_NAME="HafizPlus School Platform"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://school.hafizplus.id

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hafizplus_school_platform
DB_USERNAME=hafizplus_user
DB_PASSWORD=CHANGE_THIS_STRONG_PASSWORD

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

CACHE_STORE=database
QUEUE_CONNECTION=database

FILESYSTEM_DISK=local

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@hafizplus.id"
MAIL_FROM_NAME="${APP_NAME}"

BACKUP_DISK=local
BACKUP_PATH=backups
BACKUP_KEEP_DAYS=14
BACKUP_MYSQLDUMP_PATH=
```

Catatan:

1. Jangan commit file `.env` production asli.
2. File `.env.production.example` boleh dicommit.
3. `APP_KEY` production harus dibuat sekali dan disimpan aman.
4. Jangan mengganti `APP_KEY` production sembarangan setelah aplikasi dipakai.

---

# 9. Update `.gitignore`

Buka:

```text
.gitignore
```

Pastikan ada:

```gitignore
.env
.env.backup
.env.production
.env.staging
/storage/app/backups/*
!/storage/app/backups/.gitkeep
/storage/logs/*.log
```

Catatan:

1. Backup database tidak boleh masuk Git.
2. Log production tidak boleh masuk Git.
3. `.env` production tidak boleh masuk Git.

---

# 10. Service `DatabaseBackupService`

Buka:

```text
app/Services/System/DatabaseBackupService.php
```

Isi lengkap:

```php
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
```

Catatan:

Jika `Process` belum tersedia karena versi dependency berbeda, gunakan `Symfony\Component\Process\Process`. Namun Laravel 12 normalnya sudah menyediakan facade `Process`.

---

# 11. Command `BackupDatabaseCommand`

Buka:

```text
app/Console/Commands/BackupDatabaseCommand.php
```

Isi lengkap:

```php
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
```

---

# 12. Service `SystemHealthService`

Buka:

```text
app/Services/System/SystemHealthService.php
```

Isi lengkap:

```php
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
                'path' => storage_path('app/' . env('BACKUP_PATH', 'backups')),
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
```

---

# 13. Command `SystemHealthCheckCommand`

Buka:

```text
app/Console/Commands/SystemHealthCheckCommand.php
```

Isi lengkap:

```php
<?php

namespace App\Console\Commands;

use App\Services\System\SystemHealthService;
use Illuminate\Console\Command;

class SystemHealthCheckCommand extends Command
{
    protected $signature = 'app:system-health-check';

    protected $description = 'Run production readiness health checks.';

    public function handle(SystemHealthService $systemHealthService): int
    {
        $checks = $systemHealthService->check();

        foreach ($checks as $name => $result) {
            $status = ($result['ok'] ?? false) ? 'OK' : 'FAILED';

            $this->line(strtoupper($name) . ': ' . $status);

            foreach ($result as $key => $value) {
                if ($key === 'ok') {
                    continue;
                }

                $this->line('  - ' . $key . ': ' . $this->stringify($value));
            }
        }

        return $systemHealthService->isHealthy()
            ? self::SUCCESS
            : self::FAILURE;
    }

    private function stringify(mixed $value): string
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($value === null) {
            return 'null';
        }

        return (string) $value;
    }
}
```

---

# 14. Controller `SystemStatusController`

Buka:

```text
app/Http/Controllers/Admin/SystemStatusController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\System\DatabaseBackupService;
use App\Services\System\SystemHealthService;
use Illuminate\View\View;

class SystemStatusController extends Controller
{
    public function __invoke(
        SystemHealthService $systemHealthService,
        DatabaseBackupService $backupService
    ): View {
        return view('admin.system.status', [
            'checks' => $systemHealthService->check(),
            'isHealthy' => $systemHealthService->isHealthy(),
            'backups' => $backupService->listBackups(),
        ]);
    }
}
```

---

# 15. View `admin/system/status.blade.php`

Buka:

```text
resources/views/admin/system/status.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">System Status</h2>
        <p class="text-sm text-slate-500">
            Ringkasan kesiapan production HafizPlus School Platform.
        </p>
    </div>

    <div class="mb-6 rounded-2xl p-5 shadow-sm {{ $isHealthy ? 'bg-green-50' : 'bg-red-50' }}">
        <div class="text-sm font-semibold {{ $isHealthy ? 'text-green-700' : 'text-red-700' }}">
            Status Sistem
        </div>
        <div class="mt-2 text-2xl font-bold {{ $isHealthy ? 'text-green-900' : 'text-red-900' }}">
            {{ $isHealthy ? 'HEALTHY' : 'NEEDS ATTENTION' }}
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        @foreach ($checks as $name => $result)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold">{{ strtoupper($name) }}</h3>

                    @if ($result['ok'] ?? false)
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">
                            OK
                        </span>
                    @else
                        <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                            FAILED
                        </span>
                    @endif
                </div>

                <dl class="space-y-2 text-sm">
                    @foreach ($result as $key => $value)
                        @continue($key === 'ok')

                        <div>
                            <dt class="font-semibold text-slate-600">{{ str_replace('_', ' ', $key) }}</dt>
                            <dd class="text-slate-800">
                                @if (is_array($value))
                                    {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                @elseif (is_bool($value))
                                    {{ $value ? 'true' : 'false' }}
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">Database Backups</h3>

        <div class="overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">File</th>
                        <th class="px-4 py-3">Size</th>
                        <th class="px-4 py-3">Last Modified</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($backups as $backup)
                        <tr class="border-t">
                            <td class="px-4 py-3 font-semibold">{{ $backup['name'] }}</td>
                            <td class="px-4 py-3">{{ number_format($backup['size_bytes']) }} bytes</td>
                            <td class="px-4 py-3">{{ $backup['last_modified'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-slate-500">
                                Belum ada backup database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-sm text-slate-500">
            Backup manual dijalankan lewat command: php artisan app:backup-database
        </p>
    </div>
@endsection
```

---

# 16. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Admin\SystemStatusController;
```

Tambahkan route di dalam group `auth`:

```php
Route::middleware('role:super_admin,admin')
    ->prefix('admin/system')
    ->name('admin.system.')
    ->group(function (): void {
        Route::get('status', SystemStatusController::class)
            ->name('status');
    });
```

---

# 17. Update Navigasi

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan link hanya untuk `super_admin` dan `admin`:

```blade
@if (auth()->user()->hasRole(['super_admin', 'admin']))
    <a href="{{ route('admin.system.status') }}"
       class="font-semibold text-slate-700 hover:text-slate-950">
        System Status
    </a>
@endif
```

---

# 18. Update Scheduler

Buka:

```text
routes/console.php
```

Tambahkan:

```php
<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:backup-database')
    ->dailyAt('23:30')
    ->withoutOverlapping()
    ->onFailure(function (): void {
        logger()->error('Scheduled database backup failed.');
    });
```

Jika file `routes/console.php` sudah berisi kode lain, jangan hapus. Tambahkan bagian `Schedule::command(...)` saja.

---

# 19. Server Cron untuk Production

Tambahkan ke dokumentasi deployment.

Cron production Linux:

```cron
* * * * * cd /var/www/hafizplus-school-platform && php artisan schedule:run >> /dev/null 2>&1
```

Catatan:

1. Cron ini hanya dibutuhkan di server production.
2. Jangan dijalankan di Windows local kecuali memang ingin simulasi.
3. Command backup tetap bisa diuji manual di local.

---

# 20. Queue Production Readiness

Phase 10 belum wajib menjalankan queue production penuh.

Namun siapkan konfigurasi `.env.production.example`:

```env
QUEUE_CONNECTION=database
```

Jika nanti memakai queue worker production, gunakan:

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Untuk server Linux production, queue worker harus dijaga oleh process monitor seperti Supervisor.

---

# 21. Production Build Commands

Dokumentasikan command deployment standar:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan migrate --force
php artisan optimize
```

Jika perlu rollback cache:

```bash
php artisan optimize:clear
```

Catatan:

1. Jangan jalankan `php artisan migrate:fresh` di production.
2. Jangan jalankan seeder demo di production.
3. Jangan jalankan `composer update` langsung di production.
4. Gunakan `composer install` berdasarkan `composer.lock`.

---

# 22. Security Hardening Checklist

Buat file:

```text
docs/security-hardening.md
```

Isi:

````md
# Security Hardening — HafizPlus School Platform

## Environment

Production wajib:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-production
SESSION_SECURE_COOKIE=true
LOG_LEVEL=warning
````

## File Protection

Server harus mengarah ke folder:

```text
public/
```

Jangan arahkan web root ke root project.

File berikut tidak boleh bisa diakses publik:

```text
.env
composer.json
composer.lock
package.json
storage/
database/
routes/
config/
```

## Secrets

Rahasia berikut tidak boleh masuk Git:

1. APP_KEY production.
2. DB password.
3. Mail password.
4. API key.
5. Backup file.
6. Production `.env`.

## User Security

1. Semua password default harus diganti.
2. Akun demo harus dihapus atau dinonaktifkan.
3. Role super admin dibatasi.
4. Parent hanya boleh lihat anak sendiri.
5. Student hanya boleh lihat data sendiri.
6. Admin tidak boleh menghapus audit/log penting sembarangan.

## Session

Production disarankan:

```env
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

## Error Handling

Production wajib:

```env
APP_DEBUG=false
```

Jangan tampilkan stack trace ke user.

## Backup

1. Backup database minimal harian.
2. Simpan minimal 14 hari.
3. Jangan simpan backup di public directory.
4. Test restore minimal sebelum rilis.

````

---

# 23. Backup Restore Policy

Buat file:

```text
docs/backup-restore-policy.md
````

Isi:

````md
# Backup and Restore Policy — HafizPlus School Platform

## Tujuan

Menjamin data tahfizh, user, orang tua, santri, laporan, dan notifikasi bisa dipulihkan jika terjadi kerusakan data.

## Jenis Backup

Phase 10 membuat backup database MySQL format:

```text
.sql
````

Lokasi local:

```text
storage/app/backups
```

## Frekuensi

Production:

```text
Setiap hari pukul 23:30
```

## Retensi

Default:

```text
14 hari
```

Konfigurasi:

```env
BACKUP_KEEP_DAYS=14
```

## Command Manual

```bash
php artisan app:backup-database
```

## Restore Manual

Contoh restore MySQL:

```bash
mysql -u hafizplus_user -p hafizplus_school_platform < backup-file.sql
```

## Aturan Penting

1. Jangan restore ke production tanpa backup baru.
2. Jangan restore file tidak jelas sumbernya.
3. Jangan simpan backup di folder public.
4. Jangan commit backup ke Git.
5. Simpan salinan backup di media eksternal/server lain jika masuk production sungguhan.

## Test Restore

Minimal lakukan test restore ke database staging:

```bash
CREATE DATABASE hafizplus_restore_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
mysql -u root -p hafizplus_restore_test < backup-file.sql
```

Lalu cek:

```bash
php artisan migrate:status
```

````

---

# 24. Production Checklist

Buat file:

```text
docs/production-checklist.md
````

Isi:

```md
# Production Checklist — HafizPlus School Platform

## Environment

- [ ] Domain production tersedia.
- [ ] Server production tersedia.
- [ ] PHP minimal 8.2 tersedia.
- [ ] MySQL/MariaDB tersedia.
- [ ] Composer tersedia.
- [ ] Node.js dan NPM tersedia untuk build.
- [ ] Web server mengarah ke folder `public`.
- [ ] HTTPS aktif.
- [ ] `.env` production dibuat.
- [ ] `APP_ENV=production`.
- [ ] `APP_DEBUG=false`.
- [ ] `APP_KEY` production dibuat dan disimpan aman.

## Database

- [ ] Database production dibuat.
- [ ] User database production dibuat.
- [ ] Password database kuat.
- [ ] Migration production berhasil.
- [ ] Tidak memakai database local/test.
- [ ] Seeder demo tidak dijalankan sembarangan.

## Storage

- [ ] Folder `storage` writable.
- [ ] Folder `bootstrap/cache` writable.
- [ ] Folder backup tersedia.
- [ ] Symlink storage dibuat jika diperlukan.

## Security

- [ ] Password default diganti.
- [ ] Akun demo dinonaktifkan atau dihapus.
- [ ] Super admin dibatasi.
- [ ] Parent hanya melihat anak sendiri.
- [ ] Student hanya melihat data sendiri.
- [ ] Export hanya untuk role internal.
- [ ] Notifikasi hanya milik user terkait.
- [ ] APP_DEBUG false.
- [ ] Backup tidak berada di public folder.

## Performance

- [ ] `composer install --no-dev --optimize-autoloader`.
- [ ] `npm run build`.
- [ ] `php artisan optimize`.
- [ ] Route cache aman.
- [ ] View cache aman.
- [ ] Config cache aman.

## Scheduler

- [ ] Cron scheduler production dibuat.
- [ ] `php artisan schedule:list` dicek.
- [ ] Backup harian masuk scheduler.

## Queue

- [ ] `QUEUE_CONNECTION` ditentukan.
- [ ] Jika queue dipakai, worker dijalankan dengan Supervisor.
- [ ] Failed jobs dipantau.

## Backup

- [ ] Backup manual berhasil.
- [ ] Backup scheduled berhasil.
- [ ] Restore test berhasil di staging.
- [ ] Retensi backup dipastikan.

## Final Verification

- [ ] Login semua role berhasil.
- [ ] Dashboard internal berhasil.
- [ ] Input setoran berhasil.
- [ ] Hitung hutang berhasil.
- [ ] Report bulanan berhasil.
- [ ] Report triwulan berhasil.
- [ ] Portal parent berhasil.
- [ ] Portal student berhasil.
- [ ] Notification center berhasil.
- [ ] Export PDF berhasil.
- [ ] Export Excel berhasil.
- [ ] System status healthy.
```

---

# 25. Deployment Checklist

Buat file:

```text
docs/deployment-checklist.md
```

Isi:

````md
# Deployment Checklist — HafizPlus School Platform

## Pre-Deploy

```bash
git status
composer validate
composer install
npm install
npm run build
php artisan test
php artisan route:list
php artisan migrate:status
php artisan app:system-health-check
````

## Deploy Commands

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan optimize
php artisan app:system-health-check
```

## Jika Menggunakan Maintenance Mode

Sebelum deploy:

```bash
php artisan down
```

Setelah deploy:

```bash
php artisan up
```

## Post-Deploy

```bash
php artisan route:list
php artisan migrate:status
php artisan schedule:list
php artisan app:system-health-check
php artisan app:backup-database
```

## Browser Check

Cek:

```text
/login
/dashboard
/reports/tahfizh/dashboard
/portal/parent/dashboard
/portal/student/dashboard
/notifications
/exports/tahfizh
/admin/system/status
/up
```

## Hal yang Tidak Boleh

1. Jangan `migrate:fresh` di production.
2. Jangan `db:wipe` di production.
3. Jangan `composer update` di production.
4. Jangan ubah `APP_KEY` sembarangan.
5. Jangan commit `.env`.
6. Jangan upload backup ke public.

````

---

# 26. Rollback Plan

Buat file:

```text
docs/rollback-plan.md
````

Isi:

````md
# Rollback Plan — HafizPlus School Platform

## Tujuan

Menyiapkan langkah pemulihan jika deploy gagal.

## Skenario Rollback

1. Aplikasi error setelah deploy.
2. Migration gagal.
3. Export error.
4. Login error.
5. Dashboard/report error.
6. Data tidak tampil.
7. Queue/scheduler error.

## Langkah Rollback Kode

```bash
php artisan down
git log --oneline
git checkout PREVIOUS_COMMIT_HASH
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan optimize:clear
php artisan optimize
php artisan up
````

## Rollback Database

Rollback database hanya dilakukan jika benar-benar perlu.

Sebelum rollback database:

```bash
php artisan app:backup-database
```

Restore:

```bash
mysql -u hafizplus_user -p hafizplus_school_platform < backup-before-deploy.sql
```

## Setelah Rollback

```bash
php artisan app:system-health-check
php artisan route:list
php artisan migrate:status
```

Cek browser:

```text
/login
/dashboard
/reports/tahfizh/dashboard
/admin/system/status
```

## Larangan

1. Jangan rollback database tanpa backup baru.
2. Jangan hapus migration sembarangan.
3. Jangan ubah APP_KEY.
4. Jangan hapus storage production.
5. Jangan restore backup dari sumber tidak jelas.

````

---

# 27. Dokumentasi Phase 10

Buat file:

```text
docs/phase-10-production-hardening.md
````

Isi:

````md
# Phase 10 — Production Hardening

## Status

Phase 10 membangun fondasi production hardening untuk HafizPlus School Platform.

## Output

1. `.env.production.example`.
2. Security hardening checklist.
3. Deployment checklist.
4. Rollback plan.
5. Backup and restore policy.
6. Database backup command.
7. System health check command.
8. System status page.
9. Scheduler backup harian.
10. Production verification checklist.

## Command Baru

```text
php artisan app:backup-database
php artisan app:system-health-check
````

## Service Baru

```text
App\Services\System\DatabaseBackupService
App\Services\System\SystemHealthService
```

## Controller Baru

```text
App\Http\Controllers\Admin\SystemStatusController
```

## Route Baru

```text
admin.system.status
```

## View Baru

```text
resources/views/admin/system/status.blade.php
```

## Scheduler

Backup database harian:

```text
23:30
```

## Role Access

| Role           | System Status |
| -------------- | ------------: |
| Super Admin    |            Ya |
| Admin Sekolah  |            Ya |
| Kepala Sekolah |         Tidak |
| Guru Tahfidz   |         Tidak |
| Orang Tua      |         Tidak |
| Santri         |         Tidak |

## Belum Dibuat

Phase 10 belum membuat:

1. Multi-server deployment.
2. Object storage external.
3. Real-time monitoring.
4. Error tracking external.
5. CI/CD pipeline penuh.
6. Blue-green deployment.
7. Disaster recovery otomatis.
8. Multi-tenant production.
9. White-label deployment.
10. Payment security.

## Definition of Done

Phase 10 selesai jika:

1. `.env.production.example` dibuat.
2. `.gitignore` aman.
3. Backup command berhasil.
4. System health check command berhasil.
5. System status page bisa dibuka admin.
6. Scheduler backup tercatat.
7. Production checklist dibuat.
8. Deployment checklist dibuat.
9. Rollback plan dibuat.
10. Backup restore policy dibuat.
11. Security hardening document dibuat.
12. `php artisan optimize` berhasil.
13. `npm run build` berhasil.
14. Parent/student tidak bisa akses system status.
15. Dokumentasi Phase 10 selesai.

````

---

# 28. Update Project Progress

Buka:

```text
docs/project-progress.md
````

Update menjadi:

```md
# Project Progress — HafizPlus School Platform

| Phase | Nama | Status |
|---:|---|---|
| 0 | Product Foundation | Done |
| 1 | Auth, Role, and Initial Database Foundation | Done |
| 2 | Master Data Foundation | Done |
| 3 | Tahfizh Core Database Foundation | Done |
| 4 | Tahfizh Input Foundation | Done |
| 5 | Target and Debt Calculation | Done |
| 6 | Dashboard and Reports | Done |
| 7 | Parent and Student Progress Portal | Done |
| 8 | Notification Center | Done |
| 9 | Export PDF and Excel | Done |
| 10 | Production Hardening | Done |
| 11 | Mutabaah Yaumiyah Tracker | Pending |
| 12 | QR Attendance System | Pending |
| 13 | Tahsin Management App | Pending |
| 14 | Student Finance Ledger | Pending |
| 15 | SchoolOS Mini | Pending |
```

---

# 29. Validasi Command

Jalankan:

```powershell
php artisan app:system-health-check
php artisan app:backup-database
php artisan route:list
php artisan schedule:list
npm run build
php artisan optimize
```

Jika ingin clear ulang cache local:

```powershell
php artisan optimize:clear
```

---

# 30. Test Manual Browser

Jalankan server:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/admin/system/status
```

Login sebagai:

```text
admin@hafizplus.test
password
```

Target:

1. Halaman system status tampil.
2. App status OK.
3. Database status OK.
4. Storage status OK.
5. Cache status OK.
6. Backup status OK.
7. Daftar backup tampil jika sudah menjalankan `app:backup-database`.

---

# 31. Test Akses Role

## 31.1 Super Admin

Buka:

```text
/admin/system/status
```

Target:

```text
Bisa akses
```

## 31.2 Admin

Buka:

```text
/admin/system/status
```

Target:

```text
Bisa akses
```

## 31.3 Principal

Buka:

```text
/admin/system/status
```

Target:

```text
403 Forbidden
```

## 31.4 Teacher

Buka:

```text
/admin/system/status
```

Target:

```text
403 Forbidden
```

## 31.5 Parent

Buka:

```text
/admin/system/status
```

Target:

```text
403 Forbidden
```

## 31.6 Student

Buka:

```text
/admin/system/status
```

Target:

```text
403 Forbidden
```

---

# 32. Troubleshooting

## 32.1 `mysqldump` Tidak Ditemukan di Windows

Cari file:

```text
C:\xampp\mysql\bin\mysqldump.exe
```

Tambahkan ke `.env` local:

```env
BACKUP_MYSQLDUMP_PATH="C:\xampp\mysql\bin\mysqldump.exe"
```

Jika dotenv error karena backslash, gunakan slash:

```env
BACKUP_MYSQLDUMP_PATH="C:/xampp/mysql/bin/mysqldump.exe"
```

Atau tambahkan `C:\xampp\mysql\bin` ke PATH Windows.

---

## 32.2 Backup Gagal karena Password MySQL

Pastikan `.env`:

```env
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL memakai password, isi password sebenarnya.

---

## 32.3 Storage Tidak Writable

Linux production:

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

Local Windows:

1. Pastikan folder project tidak read-only.
2. Jalankan terminal sebagai user biasa yang punya akses folder.
3. Jangan taruh project di folder protected.

---

## 32.4 `php artisan optimize` Error

Jalankan:

```bash
php artisan optimize:clear
composer dump-autoload
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

Jika error berasal dari route closure yang tidak bisa dicache, ubah closure route menjadi controller.

---

## 32.5 `APP_DEBUG` Masih True

Production wajib:

```env
APP_DEBUG=false
```

Lalu jalankan:

```bash
php artisan config:clear
php artisan config:cache
```

---

# 33. Commit Phase 10

Jalankan:

```powershell
git status
git add .
git commit -m "chore: add production hardening foundation"
```

Jika remote tersedia:

```powershell
git push origin phase-10-production-hardening
```

---

# 34. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 10 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur hardening dibuat:
- .env.production.example
- Database backup command
- System health check command
- System status page
- Scheduler backup harian
- Production checklist
- Deployment checklist
- Rollback plan
- Backup restore policy
- Security hardening document

Command baru:
- php artisan app:backup-database
- php artisan app:system-health-check

Route baru:
- admin.system.status

Role access:
- Super Admin: bisa akses system status
- Admin: bisa akses system status
- Principal/Guru/Parent/Student: tidak bisa akses system status

Validasi:
- php artisan app:system-health-check
- php artisan app:backup-database
- php artisan schedule:list
- php artisan route:list
- npm run build
- php artisan optimize

Belum dibuat:
- CI/CD penuh
- External monitoring
- Object storage backup
- Multi-server deployment
- Queue Supervisor config final
- Multi-tenant production
- White-label deployment

Status:
- Siap masuk uji production terbatas / staging sekolah.
```

---

# 35. Larangan Setelah Phase 10

Agent harus berhenti setelah Phase 10 selesai.

Jangan lanjut membuat:

1. Mutabaah.
2. Attendance.
3. Tahsin.
4. Finance.
5. Boarding.
6. SchoolOS Mini.
7. Multi-tenant.
8. White-label.
9. Cashless.
10. Native mobile.

Semua itu masuk fase berikutnya.
