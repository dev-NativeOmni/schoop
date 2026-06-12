# Phase 15 Execution Guide — SchoolOS Mini

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

Phase saat ini:

```text
Phase 15 — SchoolOS Mini
```

---

# 1. Keputusan Sebelum Phase 15

## 1.1 UAT Phase 14 Wajib

Sebelum menjalankan Phase 15, agent wajib memastikan **Student Finance Ledger** sudah aman.

Jangan menambah SchoolOS Mini jika Finance masih bermasalah.

Phase 15 hanya boleh dieksekusi jika:

1. Admin bisa membuat kategori biaya.
2. Admin bisa membuat item biaya.
3. Admin bisa membuat tagihan santri.
4. Tagihan membuat ledger debit.
5. Admin bisa mencatat pembayaran.
6. Pembayaran membuat ledger credit.
7. Pembayaran partial mengubah status tagihan menjadi `partial`.
8. Pembayaran penuh mengubah status tagihan menjadi `paid`.
9. Void tagihan berjalan dan meninggalkan ledger pembalik.
10. Void pembayaran berjalan dan meninggalkan ledger pembalik.
11. Kepala sekolah hanya read-only.
12. Parent hanya melihat finance anak sendiri.
13. Student hanya melihat finance pribadi.
14. Parent/student tidak bisa akses internal finance.
15. `npm run build` berhasil.
16. `php artisan app:system-health-check` berhasil.
17. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0/P1, hentikan Phase 15 dan buat bug fix sprint dulu.

---

## 1.2 Kenapa Phase 15 Bukan Cashless / White Label

Phase 15 bukan:

1. Cashless kantin.
2. Merchant POS.
3. Payment gateway.
4. Wallet.
5. White-label app builder.
6. Multi-tenant SaaS penuh.
7. Native mobile app.
8. LMS penuh.

Phase 15 adalah **SchoolOS Mini**, yaitu layer penyatuan modul-modul yang sudah ada agar aplikasi terasa seperti satu platform sekolah, bukan kumpulan menu terpisah.

---

# 2. Tujuan Phase 15

Phase 15 bertujuan membuat **SchoolOS Mini**.

SchoolOS Mini adalah lapisan integrasi yang menyatukan modul yang sudah dibangun:

1. Tahfizh.
2. Parent Portal.
3. Student Portal.
4. Notification Center.
5. Export.
6. Mutabaah.
7. Attendance.
8. Tahsin.
9. Finance.

Fokus Phase 15:

1. Dashboard utama sekolah.
2. Module registry.
3. Role-based home dashboard.
4. Student 360 profile.
5. School context setting.
6. Academic year dan semester aktif.
7. Unified navigation.
8. Unified activity timeline.
9. Module health cards.
10. Quick action center.
11. Internal search sederhana.
12. Dokumentasi SchoolOS Mini.

---

# 3. Batasan Phase 15

AI agent tidak boleh membuat fitur berikut pada Phase 15:

1. Payment gateway.
2. QRIS otomatis.
3. Virtual account.
4. Cashless kantin.
5. Merchant POS.
6. Wallet.
7. Refund otomatis.
8. Rekonsiliasi bank otomatis.
9. Native Android.
10. Native iOS.
11. White-label builder.
12. Multi-tenant kompleks.
13. Domain/subdomain per sekolah.
14. Billing SaaS.
15. LMS penuh.
16. Video learning.
17. Chat parent-guru.
18. WhatsApp gateway.
19. Push notification.
20. Firebase.
21. Websocket.
22. Face recognition.
23. RFID/NFC.
24. Payroll.
25. Accounting penuh.
26. Marketplace konten.
27. AI voice correction.

Phase 15 hanya membuat **integrasi internal platform**.

---

# 4. Konsep SchoolOS Mini

## 4.1 Apa Itu SchoolOS Mini

SchoolOS Mini adalah dashboard pusat dan kerangka operasional mini untuk semua modul yang sudah dibuat.

Tujuan utamanya:

1. Admin tidak bingung pindah antar modul.
2. Kepala sekolah bisa melihat status sekolah dalam satu halaman.
3. Guru punya halaman kerja yang ringkas.
4. Parent/student tetap punya portal yang aman.
5. Data santri bisa dilihat sebagai profil 360.
6. Setiap modul bisa dilihat statusnya: aktif, belum dikonfigurasi, butuh perhatian, error.
7. Sistem mulai terasa sebagai platform sekolah, bukan aplikasi tahfizh saja.

---

## 4.2 Modul yang Disatukan

| Modul               | Status    |
| ------------------- | --------- |
| Tahfizh             | Sudah ada |
| Reports             | Sudah ada |
| Parent Portal       | Sudah ada |
| Student Portal      | Sudah ada |
| Notification Center | Sudah ada |
| Export PDF/Excel    | Sudah ada |
| Mutabaah            | Sudah ada |
| QR Attendance       | Sudah ada |
| Tahsin              | Sudah ada |
| Finance Ledger      | Sudah ada |

---

## 4.3 Student 360 Profile

Student 360 adalah halaman ringkasan satu santri yang menggabungkan:

1. Data profil santri.
2. Kelas.
3. Orang tua.
4. Tahfizh summary.
5. Mutabaah summary.
6. Attendance summary.
7. Tahsin summary.
8. Finance summary.
9. Notification/activity summary.

Halaman ini hanya boleh dibuka oleh:

1. Super Admin.
2. Admin.
3. Kepala sekolah.
4. Guru sesuai scope.
5. Parent untuk anak sendiri.
6. Student untuk diri sendiri.

---

## 4.4 Module Registry

Module registry adalah daftar modul yang tersedia di sistem.

Contoh modul:

| Module Key      | Label         |
| --------------- | ------------- |
| `tahfizh`       | Tahfizh       |
| `reports`       | Reports       |
| `notifications` | Notifications |
| `mutabaah`      | Mutabaah      |
| `attendance`    | Attendance    |
| `tahsin`        | Tahsin        |
| `finance`       | Finance       |
| `exports`       | Export        |

Module registry membantu:

1. Menampilkan menu berdasarkan modul aktif.
2. Menampilkan status modul.
3. Menampilkan quick action.
4. Menyiapkan fondasi multi-tenant nanti tanpa membuat multi-tenant sekarang.

---

# 5. Target Output Phase 15

Setelah Phase 15 selesai, aplikasi harus punya:

1. Menu **SchoolOS**.
2. Dashboard SchoolOS.
3. Role Home Dashboard.
4. Student 360 Profile.
5. Module Registry.
6. School Settings sederhana.
7. Academic Year dan Semester aktif.
8. Quick Action Center.
9. Activity Timeline sederhana.
10. Module Health Cards.
11. Internal Search sederhana.
12. Tabel:

    * `academic_years`
    * `school_terms`
    * `school_settings`
    * `system_modules`
13. Model:

    * `AcademicYear`
    * `SchoolTerm`
    * `SchoolSetting`
    * `SystemModule`
14. Controller:

    * `SchoolOsDashboardController`
    * `Student360Controller`
    * `AcademicYearController`
    * `SchoolSettingController`
    * `SystemModuleController`
    * `SchoolOsSearchController`
15. Request:

    * `StoreAcademicYearRequest`
    * `UpdateAcademicYearRequest`
    * `StoreSchoolTermRequest`
    * `UpdateSchoolTermRequest`
    * `UpdateSchoolSettingRequest`
    * `UpdateSystemModuleRequest`
    * `SchoolOsSearchRequest`
16. Service:

    * `SchoolOsAccessService`
    * `SchoolContextService`
    * `ModuleRegistryService`
    * `SchoolOsDashboardService`
    * `Student360SnapshotService`
    * `SchoolOsSearchService`
17. Seeder:

    * `SystemModuleSeeder`
18. View:

    * SchoolOS dashboard
    * module registry
    * academic year index/create/edit
    * school term index/create/edit
    * school settings
    * student 360 show
    * search result
19. Dokumentasi Phase 15.
20. Update `docs/project-progress.md`.

---

# 6. Role Access Phase 15

Sebelum coding, agent wajib cek role di database:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\Role::query()->pluck('name')->all();
```

Role default yang diasumsikan:

```text
super_admin
admin
admin_sekolah
kepala_sekolah
teacher
guru
guru_tahfidz
parent
student
```

| Role           | SchoolOS Dashboard    | Settings | Module Registry | Student 360  | Search       |
| -------------- | --------------------- | -------- | --------------- | ------------ | ------------ |
| Super Admin    | Semua                 | Ya       | Ya              | Semua        | Semua        |
| Admin          | Semua sekolah terkait | Ya       | Ya              | Semua        | Semua        |
| Kepala Sekolah | Read-only             | Tidak    | Read-only       | Semua        | Semua        |
| Teacher/Guru   | Guru dashboard        | Tidak    | Read-only       | Santri scope | Santri scope |
| Parent         | Parent dashboard      | Tidak    | Tidak           | Anak sendiri | Anak sendiri |
| Student        | Student dashboard     | Tidak    | Tidak           | Diri sendiri | Diri sendiri |

Aturan keras:

1. Parent tidak boleh melihat student 360 anak lain.
2. Student tidak boleh melihat student 360 santri lain.
3. Teacher hanya boleh melihat santri yang menjadi scope-nya.
4. Parent/student tidak boleh mengubah settings.
5. Parent/student tidak boleh mengubah module registry.
6. Kepala sekolah read-only.
7. Admin boleh konfigurasi module dan school setting.
8. Jangan membuat multi-tenant kompleks di Phase 15.

---

# 7. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform

php artisan --version
php -v
composer -V
npm -v
php artisan migrate:status
php artisan route:list
php artisan app:system-health-check
npm run build
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
12. Phase 10 selesai.
13. Phase 11 selesai.
14. Phase 12 selesai.
15. Phase 13 selesai.
16. Phase 14 selesai dan UAT aman.
17. Tabel berikut sudah ada:

    * `users`
    * `roles`
    * `schools`
    * `class_rooms`
    * `students`
    * `parent_profiles`
    * `parent_student`
    * `teacher_profiles`
    * `hafalan_records`
    * `mutabaah_records`
    * `attendance_records`
    * `tahsin_assessments`
    * `student_bills`
    * `student_payments`
18. Working tree bersih atau semua perubahan diketahui.

Jika Phase 14 belum aman, hentikan Phase 15.

---

# 8. Buat Branch Git Phase 15

Jalankan:

```powershell
git checkout -b phase-15-schoolos-mini
```

Jika branch sudah ada:

```powershell
git checkout phase-15-schoolos-mini
```

---

# 9. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── SchoolOs/
│   │       ├── SchoolOsDashboardController.php
│   │       ├── Student360Controller.php
│   │       ├── AcademicYearController.php
│   │       ├── SchoolSettingController.php
│   │       ├── SystemModuleController.php
│   │       └── SchoolOsSearchController.php
│   └── Requests/
│       └── SchoolOs/
│           ├── StoreAcademicYearRequest.php
│           ├── UpdateAcademicYearRequest.php
│           ├── StoreSchoolTermRequest.php
│           ├── UpdateSchoolTermRequest.php
│           ├── UpdateSchoolSettingRequest.php
│           ├── UpdateSystemModuleRequest.php
│           └── SchoolOsSearchRequest.php
├── Models/
│   ├── AcademicYear.php
│   ├── SchoolTerm.php
│   ├── SchoolSetting.php
│   └── SystemModule.php
└── Services/
    └── SchoolOs/
        ├── SchoolOsAccessService.php
        ├── SchoolContextService.php
        ├── ModuleRegistryService.php
        ├── SchoolOsDashboardService.php
        ├── Student360SnapshotService.php
        └── SchoolOsSearchService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_academic_years_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_school_terms_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_school_settings_table.php
│   └── xxxx_xx_xx_xxxxxx_create_system_modules_table.php
└── seeders/
    └── SystemModuleSeeder.php

resources/
└── views/
    └── schoolos/
        ├── dashboard.blade.php
        ├── search-results.blade.php
        ├── students/
        │   └── show-360.blade.php
        ├── academic-years/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        ├── settings/
        │   └── index.blade.php
        └── modules/
            └── index.blade.php

routes/
└── web.php

docs/
├── phase-15-execution.md
└── phase-15-schoolos-mini.md
```

---

# 10. Buat Model, Migration, Seeder, Controller, Request

Jalankan:

```powershell
php artisan make:model AcademicYear -m
php artisan make:model SchoolTerm -m
php artisan make:model SchoolSetting -m
php artisan make:model SystemModule -m

php artisan make:seeder SystemModuleSeeder

php artisan make:controller SchoolOs/SchoolOsDashboardController
php artisan make:controller SchoolOs/Student360Controller
php artisan make:controller SchoolOs/AcademicYearController
php artisan make:controller SchoolOs/SchoolSettingController
php artisan make:controller SchoolOs/SystemModuleController
php artisan make:controller SchoolOs/SchoolOsSearchController

php artisan make:request SchoolOs/StoreAcademicYearRequest
php artisan make:request SchoolOs/UpdateAcademicYearRequest
php artisan make:request SchoolOs/StoreSchoolTermRequest
php artisan make:request SchoolOs/UpdateSchoolTermRequest
php artisan make:request SchoolOs/UpdateSchoolSettingRequest
php artisan make:request SchoolOs/UpdateSystemModuleRequest
php artisan make:request SchoolOs/SchoolOsSearchRequest
```

Buat folder service:

```powershell
mkdir app\Services\SchoolOs
```

Buat file service:

```powershell
New-Item app\Services\SchoolOs\SchoolOsAccessService.php
New-Item app\Services\SchoolOs\SchoolContextService.php
New-Item app\Services\SchoolOs\ModuleRegistryService.php
New-Item app\Services\SchoolOs\SchoolOsDashboardService.php
New-Item app\Services\SchoolOs\Student360SnapshotService.php
New-Item app\Services\SchoolOs\SchoolOsSearchService.php
```

Buat folder view:

```powershell
mkdir resources\views\schoolos
mkdir resources\views\schoolos\students
mkdir resources\views\schoolos\academic-years
mkdir resources\views\schoolos\settings
mkdir resources\views\schoolos\modules
```

---

# 11. Migration `create_academic_years_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_academic_years_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
```

---

# 12. Migration `create_school_terms_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_school_terms_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_terms', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();

            $table->string('name');
            $table->unsignedTinyInteger('term_number')->default(1);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index(['academic_year_id', 'term_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_terms');
    }
};
```

---

# 13. Migration `create_school_settings_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_school_settings_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['school_id', 'key']);
            $table->index('key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
```

---

# 14. Migration `create_system_modules_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_system_modules_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_modules', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('module_key');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_core')->default(false);

            $table->timestamps();

            $table->unique(['school_id', 'module_key']);
            $table->index(['school_id', 'is_enabled']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_modules');
    }
};
```

---

# 15. Model `AcademicYear`

Buka:

```text
app/Models/AcademicYear.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function terms(): HasMany
    {
        return $this->hasMany(SchoolTerm::class);
    }
}
```

---

# 16. Model `SchoolTerm`

Buka:

```text
app/Models/SchoolTerm.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolTerm extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'name',
        'term_number',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'term_number' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
```

---

# 17. Model `SchoolSetting`

Buka:

```text
app/Models/SchoolSetting.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_id',
        'key',
        'value',
        'type',
        'description',
    ];
}
```

---

# 18. Model `SystemModule`

Buka:

```text
app/Models/SystemModule.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemModule extends Model
{
    protected $fillable = [
        'school_id',
        'module_key',
        'name',
        'description',
        'route_name',
        'icon',
        'sort_order',
        'is_enabled',
        'is_core',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_enabled' => 'boolean',
        'is_core' => 'boolean',
    ];
}
```

---

# 19. Seeder `SystemModuleSeeder`

Buka:

```text
database/seeders/SystemModuleSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\SystemModule;
use Illuminate\Database\Seeder;

class SystemModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'module_key' => 'schoolos',
                'name' => 'SchoolOS',
                'description' => 'Dashboard pusat dan integrasi modul sekolah.',
                'route_name' => 'schoolos.dashboard',
                'sort_order' => 1,
                'is_core' => true,
            ],
            [
                'module_key' => 'tahfizh',
                'name' => 'Tahfizh',
                'description' => 'Monitoring setoran, target, hutang, dan laporan tahfizh.',
                'route_name' => 'reports.tahfizh.dashboard',
                'sort_order' => 10,
                'is_core' => true,
            ],
            [
                'module_key' => 'mutabaah',
                'name' => 'Mutabaah',
                'description' => 'Tracker ibadah dan karakter harian santri.',
                'route_name' => 'mutabaah.reports.dashboard',
                'sort_order' => 20,
                'is_core' => false,
            ],
            [
                'module_key' => 'attendance',
                'name' => 'Attendance',
                'description' => 'QR attendance dan laporan presensi.',
                'route_name' => 'attendance.reports.dashboard',
                'sort_order' => 30,
                'is_core' => false,
            ],
            [
                'module_key' => 'tahsin',
                'name' => 'Tahsin',
                'description' => 'Level, skill, asesmen, dan progress tahsin.',
                'route_name' => 'tahsin.reports.dashboard',
                'sort_order' => 40,
                'is_core' => false,
            ],
            [
                'module_key' => 'finance',
                'name' => 'Finance',
                'description' => 'Tagihan, pembayaran, ledger, dan laporan finance.',
                'route_name' => 'finance.reports.dashboard',
                'sort_order' => 50,
                'is_core' => false,
            ],
            [
                'module_key' => 'notifications',
                'name' => 'Notifications',
                'description' => 'Notification center berbasis database.',
                'route_name' => 'notifications.index',
                'sort_order' => 60,
                'is_core' => false,
            ],
            [
                'module_key' => 'exports',
                'name' => 'Exports',
                'description' => 'Export PDF dan Excel.',
                'route_name' => 'exports.tahfizh.index',
                'sort_order' => 70,
                'is_core' => false,
            ],
        ];

        foreach ($modules as $module) {
            SystemModule::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'module_key' => $module['module_key'],
                ],
                [
                    'name' => $module['name'],
                    'description' => $module['description'],
                    'route_name' => $module['route_name'],
                    'sort_order' => $module['sort_order'],
                    'is_enabled' => true,
                    'is_core' => $module['is_core'],
                ]
            );
        }
    }
}
```

Update:

```text
database/seeders/DatabaseSeeder.php
```

Tambahkan:

```php
$this->call([
    SystemModuleSeeder::class,
]);
```

Jangan hapus seeder lama.

---

# 20. Service `SchoolOsAccessService`

Buka:

```text
app/Services/SchoolOs/SchoolOsAccessService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\SchoolOs;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class SchoolOsAccessService
{
    public function roleName(?User $user): ?string
    {
        if (! $user) {
            return null;
        }

        $roleValue = $user->role ?? null;

        if (is_object($roleValue)) {
            return $roleValue->name ?? $roleValue->slug ?? null;
        }

        return is_string($roleValue) ? $roleValue : null;
    }

    public function isAdmin(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'super_admin',
            'admin',
            'admin_sekolah',
        ], true);
    }

    public function isPrincipal(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'kepala_sekolah',
            'principal',
        ], true);
    }

    public function isTeacher(?User $user): bool
    {
        return in_array($this->roleName($user), [
            'teacher',
            'guru',
            'guru_tahfidz',
        ], true);
    }

    public function isParent(?User $user): bool
    {
        return $this->roleName($user) === 'parent';
    }

    public function isStudent(?User $user): bool
    {
        return $this->roleName($user) === 'student';
    }

    public function canManageSettings(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canViewSchoolOs(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isPrincipal($user)
            || $this->isTeacher($user)
            || $this->isParent($user)
            || $this->isStudent($user);
    }

    public function applyStudentScope(Builder $query, User $user): Builder
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return $query;
        }

        if ($this->isTeacher($user)) {
            $teacherProfile = $user->teacherProfile ?? null;

            if (! $teacherProfile) {
                return $query->whereRaw('1 = 0');
            }

            if (! empty($teacherProfile->class_room_id)) {
                return $query->where('class_room_id', $teacherProfile->class_room_id);
            }

            return $query;
        }

        if ($this->isParent($user)) {
            $parentProfile = $user->parentProfile ?? null;

            if (! $parentProfile) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereHas('parents', function (Builder $parentQuery) use ($parentProfile): void {
                $parentQuery->where('parent_profiles.id', $parentProfile->id);
            });
        }

        if ($this->isStudent($user)) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function canViewStudent(User $user, Student $student): bool
    {
        return $this->applyStudentScope(
            Student::query()->whereKey($student->id),
            $user
        )->exists();
    }
}
```

---

# 21. Service `SchoolContextService`

Buka:

```text
app/Services/SchoolOs/SchoolContextService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\SchoolOs;

use App\Models\AcademicYear;
use App\Models\SchoolSetting;
use App\Models\SchoolTerm;

class SchoolContextService
{
    public function activeAcademicYear(?int $schoolId = null): ?AcademicYear
    {
        return AcademicYear::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
    }

    public function activeTerm(?int $schoolId = null): ?SchoolTerm
    {
        return SchoolTerm::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->first();
    }

    public function setting(string $key, mixed $default = null, ?int $schoolId = null): mixed
    {
        $setting = SchoolSetting::query()
            ->where('school_id', $schoolId)
            ->where('key', $key)
            ->first();

        return $setting?->value ?? $default;
    }
}
```

---

# 22. Service `ModuleRegistryService`

Buka:

```text
app/Services/SchoolOs/ModuleRegistryService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\SchoolOs;

use App\Models\SystemModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class ModuleRegistryService
{
    public function enabledModules(?int $schoolId = null): Collection
    {
        return SystemModule::query()
            ->where('school_id', $schoolId)
            ->where('is_enabled', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function (SystemModule $module): SystemModule {
                $module->route_exists = $module->route_name
                    ? Route::has($module->route_name)
                    : false;

                return $module;
            });
    }

    public function allModules(?int $schoolId = null): Collection
    {
        return SystemModule::query()
            ->where('school_id', $schoolId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(function (SystemModule $module): SystemModule {
                $module->route_exists = $module->route_name
                    ? Route::has($module->route_name)
                    : false;

                return $module;
            });
    }
}
```

---

# 23. Service `SchoolOsDashboardService`

Buka:

```text
app/Services/SchoolOs/SchoolOsDashboardService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\SchoolOs;

use App\Models\AttendanceRecord;
use App\Models\FinanceLedgerEntry;
use App\Models\HafalanRecord;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\TahsinAssessment;
use Illuminate\Support\Facades\Schema;

class SchoolOsDashboardService
{
    public function summary(): array
    {
        return [
            'students_count' => Schema::hasTable('students')
                ? Student::query()->count()
                : 0,

            'hafalan_records_today' => Schema::hasTable('hafalan_records')
                ? HafalanRecord::query()->whereDate('created_at', today())->count()
                : 0,

            'mutabaah_records_today' => Schema::hasTable('mutabaah_records')
                ? MutabaahRecord::query()->whereDate('created_at', today())->count()
                : 0,

            'attendance_records_today' => Schema::hasTable('attendance_records')
                ? AttendanceRecord::query()->whereDate('attendance_date', today())->count()
                : 0,

            'tahsin_assessments_this_month' => Schema::hasTable('tahsin_assessments')
                ? TahsinAssessment::query()->whereMonth('assessment_date', now()->month)->whereYear('assessment_date', now()->year)->count()
                : 0,

            'open_finance_bills' => Schema::hasTable('student_bills')
                ? StudentBill::query()->whereIn('status', ['posted', 'partial', 'overdue'])->count()
                : 0,

            'finance_balance_total' => Schema::hasTable('finance_ledger_entries')
                ? $this->financeBalanceTotal()
                : 0,
        ];
    }

    private function financeBalanceTotal(): int
    {
        $debit = (int) FinanceLedgerEntry::query()
            ->where('direction', 'debit')
            ->sum('amount');

        $credit = (int) FinanceLedgerEntry::query()
            ->where('direction', 'credit')
            ->sum('amount');

        return $debit - $credit;
    }
}
```

Catatan:

Jika model `AttendanceRecord`, `MutabaahRecord`, `TahsinAssessment`, atau `FinanceLedgerEntry` belum ada karena phase sebelumnya belum dieksekusi, jangan lanjut Phase 15.

---

# 24. Service `Student360SnapshotService`

Buka:

```text
app/Services/SchoolOs/Student360SnapshotService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\SchoolOs;

use App\Models\AttendanceRecord;
use App\Models\FinanceLedgerEntry;
use App\Models\HafalanRecord;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\TahsinAssessment;
use Illuminate\Support\Facades\Schema;

class Student360SnapshotService
{
    public function snapshot(Student $student): array
    {
        return [
            'student' => $student->loadMissing(['classRoom', 'parents']),
            'tahfizh' => $this->tahfizh($student),
            'mutabaah' => $this->mutabaah($student),
            'attendance' => $this->attendance($student),
            'tahsin' => $this->tahsin($student),
            'finance' => $this->finance($student),
        ];
    }

    private function tahfizh(Student $student): array
    {
        if (! Schema::hasTable('hafalan_records')) {
            return ['records_count' => 0, 'total_lines' => 0, 'latest_record' => null];
        }

        $query = HafalanRecord::query()->where('student_id', $student->id);

        return [
            'records_count' => (clone $query)->count(),
            'total_lines' => (int) (clone $query)->sum('total_lines'),
            'latest_record' => (clone $query)->latest('record_date')->first(),
        ];
    }

    private function mutabaah(Student $student): array
    {
        if (! Schema::hasTable('mutabaah_records')) {
            return ['records_today' => 0, 'done_today' => 0];
        }

        $query = MutabaahRecord::query()
            ->where('student_id', $student->id)
            ->whereDate('record_date', today());

        return [
            'records_today' => (clone $query)->count(),
            'done_today' => (clone $query)->where('status', 'done')->count(),
        ];
    }

    private function attendance(Student $student): array
    {
        if (! Schema::hasTable('attendance_records')) {
            return ['records_this_month' => 0, 'late_this_month' => 0];
        }

        $query = AttendanceRecord::query()
            ->where('student_id', $student->id)
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year);

        return [
            'records_this_month' => (clone $query)->count(),
            'late_this_month' => (clone $query)->where('status', 'late')->count(),
        ];
    }

    private function tahsin(Student $student): array
    {
        if (! Schema::hasTable('tahsin_assessments')) {
            return ['assessments_count' => 0, 'latest_assessment' => null];
        }

        $query = TahsinAssessment::query()->where('student_id', $student->id);

        return [
            'assessments_count' => (clone $query)->count(),
            'latest_assessment' => (clone $query)->latest('assessment_date')->first(),
        ];
    }

    private function finance(Student $student): array
    {
        if (! Schema::hasTable('finance_ledger_entries')) {
            return ['debit' => 0, 'credit' => 0, 'balance' => 0];
        }

        $debit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', 'debit')
            ->sum('amount');

        $credit = (int) FinanceLedgerEntry::query()
            ->where('student_id', $student->id)
            ->where('direction', 'credit')
            ->sum('amount');

        return [
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $debit - $credit,
        ];
    }
}
```

---

# 25. Service `SchoolOsSearchService`

Buka:

```text
app/Services/SchoolOs/SchoolOsSearchService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\SchoolOs;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;

class SchoolOsSearchService
{
    public function search(string $keyword, User $user, SchoolOsAccessService $accessService): array
    {
        $students = $accessService
            ->applyStudentScope(
                Student::query()
                    ->with('classRoom')
                    ->where(function ($query) use ($keyword): void {
                        $query->where('nama_lengkap', 'like', '%' . $keyword . '%')
                            ->orWhere('name', 'like', '%' . $keyword . '%')
                            ->orWhere('nisn', 'like', '%' . $keyword . '%')
                            ->orWhere('nis', 'like', '%' . $keyword . '%');
                    }),
                $user
            )
            ->limit(20)
            ->get();

        return [
            'students' => $students,
        ];
    }
}
```

Catatan:

Jika `students` tidak punya kolom `name`, `nisn`, atau `nis`, sesuaikan query agar tidak muncul error `Unknown column`.

---

# 26. Request `StoreAcademicYearRequest`

Buka:

```text
app/Http/Requests/SchoolOs/StoreAcademicYearRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\SchoolOs;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

# 27. Request `UpdateAcademicYearRequest`

Buka:

```text
app/Http/Requests/SchoolOs/UpdateAcademicYearRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\SchoolOs;

class UpdateAcademicYearRequest extends StoreAcademicYearRequest
{
    //
}
```

---

# 28. Request `StoreSchoolTermRequest`

Buka:

```text
app/Http/Requests/SchoolOs/StoreSchoolTermRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\SchoolOs;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolTermRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:100'],
            'term_number' => ['required', 'integer', 'min:1', 'max:4'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

# 29. Request `UpdateSchoolTermRequest`

Buka:

```text
app/Http/Requests/SchoolOs/UpdateSchoolTermRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\SchoolOs;

class UpdateSchoolTermRequest extends StoreSchoolTermRequest
{
    //
}
```

---

# 30. Request `UpdateSchoolSettingRequest`

Buka:

```text
app/Http/Requests/SchoolOs/UpdateSchoolSettingRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\SchoolOs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
```

---

# 31. Request `UpdateSystemModuleRequest`

Buka:

```text
app/Http/Requests/SchoolOs/UpdateSystemModuleRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\SchoolOs;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'is_enabled' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }
}
```

---

# 32. Request `SchoolOsSearchRequest`

Buka:

```text
app/Http/Requests/SchoolOs/SchoolOsSearchRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\SchoolOs;

use Illuminate\Foundation\Http\FormRequest;

class SchoolOsSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ];
    }
}
```

---

# 33. Controller `SchoolOsDashboardController`

Buka:

```text
app/Http/Controllers/SchoolOs/SchoolOsDashboardController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Services\SchoolOs\ModuleRegistryService;
use App\Services\SchoolOs\SchoolContextService;
use App\Services\SchoolOs\SchoolOsAccessService;
use App\Services\SchoolOs\SchoolOsDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolOsDashboardController extends Controller
{
    public function index(
        Request $request,
        SchoolOsAccessService $accessService,
        SchoolContextService $contextService,
        ModuleRegistryService $moduleRegistry,
        SchoolOsDashboardService $dashboardService
    ): View {
        abort_unless($accessService->canViewSchoolOs($request->user()), 403);

        return view('schoolos.dashboard', [
            'roleName' => $accessService->roleName($request->user()),
            'activeAcademicYear' => $contextService->activeAcademicYear(),
            'activeTerm' => $contextService->activeTerm(),
            'modules' => $moduleRegistry->enabledModules(),
            'summary' => $dashboardService->summary(),
        ]);
    }
}
```

---

# 34. Controller `Student360Controller`

Buka:

```text
app/Http/Controllers/SchoolOs/Student360Controller.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\SchoolOs\SchoolOsAccessService;
use App\Services\SchoolOs\Student360SnapshotService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Student360Controller extends Controller
{
    public function show(
        Request $request,
        Student $student,
        SchoolOsAccessService $accessService,
        Student360SnapshotService $snapshotService
    ): View {
        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $snapshot = $snapshotService->snapshot($student);

        return view('schoolos.students.show-360', compact('student', 'snapshot'));
    }
}
```

---

# 35. Controller `AcademicYearController`

Buka:

```text
app/Http/Controllers/SchoolOs/AcademicYearController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\StoreAcademicYearRequest;
use App\Http\Requests\SchoolOs\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use App\Services\SchoolOs\SchoolOsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(Request $request, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        $academicYears = AcademicYear::query()
            ->with('terms')
            ->orderByDesc('start_date')
            ->paginate(20);

        return view('schoolos.academic-years.index', compact('academicYears'));
    }

    public function create(Request $request, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        return view('schoolos.academic-years.create');
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = $request->validated();

            if ($request->boolean('is_active')) {
                AcademicYear::query()->update(['is_active' => false]);
            }

            AcademicYear::query()->create([
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('schoolos.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dibuat.');
    }

    public function edit(Request $request, AcademicYear $academicYear, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        return view('schoolos.academic-years.edit', compact('academicYear'));
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        DB::transaction(function () use ($request, $academicYear): void {
            $data = $request->validated();

            if ($request->boolean('is_active')) {
                AcademicYear::query()
                    ->whereKeyNot($academicYear->id)
                    ->update(['is_active' => false]);
            }

            $academicYear->update([
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('schoolos.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }
}
```

---

# 36. Controller `SystemModuleController`

Buka:

```text
app/Http/Controllers/SchoolOs/SystemModuleController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\UpdateSystemModuleRequest;
use App\Models\SystemModule;
use App\Services\SchoolOs\ModuleRegistryService;
use App\Services\SchoolOs\SchoolOsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemModuleController extends Controller
{
    public function index(
        Request $request,
        SchoolOsAccessService $accessService,
        ModuleRegistryService $moduleRegistryService
    ): View {
        abort_unless(
            $accessService->canManageSettings($request->user()) || $accessService->isPrincipal($request->user()),
            403
        );

        $modules = $moduleRegistryService->allModules();

        return view('schoolos.modules.index', compact('modules'));
    }

    public function update(
        UpdateSystemModuleRequest $request,
        SystemModule $systemModule
    ): RedirectResponse {
        $systemModule->update([
            'is_enabled' => $request->boolean('is_enabled'),
            'sort_order' => $request->input('sort_order', $systemModule->sort_order),
        ]);

        return redirect()
            ->route('schoolos.modules.index')
            ->with('success', 'Status modul berhasil diperbarui.');
    }
}
```

---

# 37. Controller `SchoolSettingController`

Buka:

```text
app/Http/Controllers/SchoolOs/SchoolSettingController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\UpdateSchoolSettingRequest;
use App\Models\SchoolSetting;
use App\Services\SchoolOs\SchoolOsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolSettingController extends Controller
{
    public function index(Request $request, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        $settings = SchoolSetting::query()
            ->orderBy('key')
            ->get()
            ->keyBy('key');

        return view('schoolos.settings.index', compact('settings'));
    }

    public function update(UpdateSchoolSettingRequest $request): RedirectResponse
    {
        foreach ($request->validated('settings') as $key => $value) {
            SchoolSetting::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'key' => $key,
                ],
                [
                    'value' => $value,
                    'type' => 'string',
                ]
            );
        }

        return redirect()
            ->route('schoolos.settings.index')
            ->with('success', 'Pengaturan sekolah berhasil diperbarui.');
    }
}
```

---

# 38. Controller `SchoolOsSearchController`

Buka:

```text
app/Http/Controllers/SchoolOs/SchoolOsSearchController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\SchoolOsSearchRequest;
use App\Services\SchoolOs\SchoolOsAccessService;
use App\Services\SchoolOs\SchoolOsSearchService;
use Illuminate\View\View;

class SchoolOsSearchController extends Controller
{
    public function __invoke(
        SchoolOsSearchRequest $request,
        SchoolOsAccessService $accessService,
        SchoolOsSearchService $searchService
    ): View {
        $results = $searchService->search(
            $request->validated('q'),
            $request->user(),
            $accessService
        );

        return view('schoolos.search-results', [
            'keyword' => $request->validated('q'),
            'results' => $results,
        ]);
    }
}
```

---

# 39. View `schoolos/dashboard.blade.php`

Buat:

```text
resources/views/schoolos/dashboard.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">SchoolOS Mini</h1>
        <p class="text-sm text-gray-600">
            Dashboard pusat HafizPlus School Platform.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Tahun Ajaran Aktif</div>
            <div class="font-bold">{{ $activeAcademicYear?->name ?? 'Belum diatur' }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Semester Aktif</div>
            <div class="font-bold">{{ $activeTerm?->name ?? 'Belum diatur' }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Role Login</div>
            <div class="font-bold">{{ $roleName ?? '-' }}</div>
        </div>
    </div>

    <form method="GET" action="{{ route('schoolos.search') }}" class="mb-6 bg-white rounded-xl shadow p-4 flex gap-3">
        <input
            type="text"
            name="q"
            minlength="2"
            placeholder="Cari santri..."
            class="flex-1 rounded-lg border-gray-300"
            required
        >
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Cari
        </button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Santri</div>
            <div class="text-xl font-bold">{{ $summary['students_count'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Setoran Hari Ini</div>
            <div class="text-xl font-bold">{{ $summary['hafalan_records_today'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Presensi Hari Ini</div>
            <div class="text-xl font-bold">{{ $summary['attendance_records_today'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Tagihan Terbuka</div>
            <div class="text-xl font-bold">{{ $summary['open_finance_bills'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Modul Aktif</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($modules as $module)
                <div class="border rounded-xl p-4">
                    <div class="font-bold text-gray-900">{{ $module->name }}</div>
                    <p class="text-sm text-gray-600 mt-1">{{ $module->description }}</p>

                    @if($module->route_exists && $module->route_name)
                        <a href="{{ route($module->route_name) }}" class="inline-block mt-3 text-blue-600 text-sm">
                            Buka Modul
                        </a>
                    @else
                        <div class="mt-3 text-xs text-red-600">
                            Route belum tersedia / perlu disesuaikan.
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
```

---

# 40. View `schoolos/students/show-360.blade.php`

Buat:

```text
resources/views/schoolos/students/show-360.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Student 360</h1>
        <p class="text-sm text-gray-600">
            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
            —
            {{ $student->classRoom?->name ?? $student->classRoom?->nama ?? '-' }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Setoran</div>
            <div class="text-xl font-bold">{{ $snapshot['tahfizh']['records_count'] }}</div>
            <div class="text-xs text-gray-500">Total baris: {{ $snapshot['tahfizh']['total_lines'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Mutabaah Hari Ini</div>
            <div class="text-xl font-bold">{{ $snapshot['mutabaah']['done_today'] }} / {{ $snapshot['mutabaah']['records_today'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Presensi Bulan Ini</div>
            <div class="text-xl font-bold">{{ $snapshot['attendance']['records_this_month'] }}</div>
            <div class="text-xs text-gray-500">Terlambat: {{ $snapshot['attendance']['late_this_month'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Asesmen Tahsin</div>
            <div class="text-xl font-bold">{{ $snapshot['tahsin']['assessments_count'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Tagihan</div>
            <div class="text-xl font-bold">Rp {{ number_format($snapshot['finance']['debit'], 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Saldo Finance</div>
            <div class="text-xl font-bold">Rp {{ number_format($snapshot['finance']['balance'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-3">Informasi Santri</h2>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Nama</dt>
                <dd class="font-medium">{{ $student->nama_lengkap ?? $student->name ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-gray-500">Kelas</dt>
                <dd class="font-medium">{{ $student->classRoom?->name ?? $student->classRoom?->nama ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-gray-500">NIS/NISN</dt>
                <dd class="font-medium">{{ $student->nis ?? $student->nisn ?? '-' }}</dd>
            </div>

            <div>
                <dt class="text-gray-500">User ID</dt>
                <dd class="font-medium">{{ $student->user_id ?? '-' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
```

---

# 41. View `schoolos/search-results.blade.php`

Buat:

```text
resources/views/schoolos/search-results.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Hasil Pencarian</h1>
        <p class="text-sm text-gray-600">Keyword: {{ $keyword }}</p>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Kelas</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($results['students'] as $student)
                    <tr>
                        <td class="px-4 py-3">{{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}</td>
                        <td class="px-4 py-3">{{ $student->classRoom?->name ?? $student->classRoom?->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('schoolos.students.show', $student) }}" class="text-blue-600">
                                Student 360
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada hasil.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

# 42. View `schoolos/modules/index.blade.php`

Buat:

```text
resources/views/schoolos/modules/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Module Registry</h1>
        <p class="text-sm text-gray-600">Kelola status modul SchoolOS Mini.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Module Key</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Route</th>
                    <th class="px-4 py-3 text-left">Enabled</th>
                    <th class="px-4 py-3 text-left">Sort</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($modules as $module)
                    <tr>
                        <td class="px-4 py-3">{{ $module->module_key }}</td>
                        <td class="px-4 py-3">{{ $module->name }}</td>
                        <td class="px-4 py-3">
                            {{ $module->route_name ?? '-' }}
                            @if(! $module->route_exists)
                                <span class="text-xs text-red-600">(route belum ada)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $module->is_enabled ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3">{{ $module->sort_order }}</td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('schoolos.modules.update', $module) }}" method="POST" class="inline-flex items-center gap-2">
                                @csrf
                                @method('PATCH')

                                <input type="hidden" name="is_enabled" value="0">
                                <label class="inline-flex items-center gap-1">
                                    <input type="checkbox" name="is_enabled" value="1" @checked($module->is_enabled)>
                                    Aktif
                                </label>

                                <input type="number" name="sort_order" value="{{ $module->sort_order }}" class="w-20 rounded border-gray-300">

                                <button class="text-blue-600">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

# 43. View `schoolos/settings/index.blade.php`

Buat:

```text
resources/views/schoolos/settings/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">School Settings</h1>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('schoolos.settings.update') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Sekolah Display</label>
            <input
                type="text"
                name="settings[school_display_name]"
                value="{{ $settings['school_display_name']->value ?? '' }}"
                class="mt-1 w-full rounded-lg border-gray-300"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Timezone</label>
            <input
                type="text"
                name="settings[timezone]"
                value="{{ $settings['timezone']->value ?? 'Asia/Jakarta' }}"
                class="mt-1 w-full rounded-lg border-gray-300"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Alamat Sekolah</label>
            <textarea
                name="settings[school_address]"
                rows="3"
                class="mt-1 w-full rounded-lg border-gray-300"
            >{{ $settings['school_address']->value ?? '' }}</textarea>
        </div>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Settings
            </button>
        </div>
    </form>
</div>
@endsection
```

---

# 44. View Minimal Academic Year

Buat:

```text
resources/views/schoolos/academic-years/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tahun Ajaran</h1>
            <p class="text-sm text-gray-600">Kelola tahun ajaran aktif.</p>
        </div>

        <a href="{{ route('schoolos.academic-years.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Tahun Ajaran
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Mulai</th>
                    <th class="px-4 py-3 text-left">Selesai</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($academicYears as $academicYear)
                    <tr>
                        <td class="px-4 py-3">{{ $academicYear->name }}</td>
                        <td class="px-4 py-3">{{ $academicYear->start_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $academicYear->end_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $academicYear->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('schoolos.academic-years.edit', $academicYear) }}" class="text-blue-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada tahun ajaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $academicYears->links() }}</div>
</div>
@endsection
```

Buat:

```text
resources/views/schoolos/academic-years/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Tahun Ajaran</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('schoolos.academic-years.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Tahun Ajaran</label>
            <input type="text" name="name" class="mt-1 w-full rounded-lg border-gray-300" placeholder="2026/2027" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1">
            Jadikan aktif
        </label>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
```

Buat:

```text
resources/views/schoolos/academic-years/edit.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Tahun Ajaran</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('schoolos.academic-years.update', $academicYear) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium text-gray-700">Nama Tahun Ajaran</label>
            <input type="text" name="name" value="{{ old('name', $academicYear->name) }}" class="mt-1 w-full rounded-lg border-gray-300" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', $academicYear->start_date?->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $academicYear->end_date?->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked($academicYear->is_active)>
            Jadikan aktif
        </label>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
```

---

# 45. Update Routes `routes/web.php`

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\SchoolOs\AcademicYearController;
use App\Http\Controllers\SchoolOs\SchoolOsDashboardController;
use App\Http\Controllers\SchoolOs\SchoolOsSearchController;
use App\Http\Controllers\SchoolOs\SchoolSettingController;
use App\Http\Controllers\SchoolOs\Student360Controller;
use App\Http\Controllers\SchoolOs\SystemModuleController;
```

Tambahkan route di dalam middleware `auth`:

```php
Route::middleware(['auth'])->group(function (): void {
    Route::prefix('schoolos')
        ->name('schoolos.')
        ->group(function (): void {
            Route::get('/', [SchoolOsDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/search', SchoolOsSearchController::class)
                ->name('search');

            Route::get('/students/{student}/360', [Student360Controller::class, 'show'])
                ->name('students.show');

            Route::resource('academic-years', AcademicYearController::class)
                ->except(['show', 'destroy']);

            Route::get('/settings', [SchoolSettingController::class, 'index'])
                ->name('settings.index');

            Route::patch('/settings', [SchoolSettingController::class, 'update'])
                ->name('settings.update');

            Route::get('/modules', [SystemModuleController::class, 'index'])
                ->name('modules.index');

            Route::patch('/modules/{systemModule}', [SystemModuleController::class, 'update'])
                ->name('modules.update');
        });
});
```

Jangan hapus route lama.

---

# 46. Update Navigation

Buka salah satu file navigation:

```text
resources/views/layouts/navigation.blade.php
```

atau:

```text
resources/views/layouts/app.blade.php
```

Tambahkan menu:

```blade
@php
    $roleValue = auth()->user()?->role ?? null;
    $roleName = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;
@endphp

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal', 'teacher', 'guru', 'guru_tahfidz', 'parent', 'student'], true))
    <a href="{{ route('schoolos.dashboard') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        SchoolOS
    </a>
@endif

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true))
    <a href="{{ route('schoolos.academic-years.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Tahun Ajaran
    </a>

    <a href="{{ route('schoolos.modules.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Module Registry
    </a>

    <a href="{{ route('schoolos.settings.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        School Settings
    </a>
@endif
```

Sesuaikan class dengan style layout project.

---

# 47. Jalankan Migration dan Seeder

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=SystemModuleSeeder
```

Cek via tinker:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\SystemModule::count();
App\Models\AcademicYear::count();
App\Models\SchoolSetting::count();
```

Target minimal:

```text
SystemModule > 0
AcademicYear = 0 boleh
SchoolSetting = 0 boleh
```

---

# 48. Validasi Route

Jalankan:

```powershell
php artisan route:list --name=schoolos
```

Target route tersedia:

```text
schoolos.dashboard
schoolos.search
schoolos.students.show
schoolos.academic-years.index
schoolos.academic-years.create
schoolos.academic-years.store
schoolos.academic-years.edit
schoolos.academic-years.update
schoolos.settings.index
schoolos.settings.update
schoolos.modules.index
schoolos.modules.update
```

---

# 49. UAT Phase 15

## 49.1 Test Admin

Login sebagai admin.

Tes:

1. Buka `/schoolos`.
2. Pastikan summary card tampil.
3. Pastikan module cards tampil.
4. Pastikan link modul tidak error jika route tersedia.
5. Buka `/schoolos/modules`.
6. Aktif/nonaktifkan module.
7. Buka `/schoolos/settings`.
8. Simpan school settings.
9. Buka `/schoolos/academic-years`.
10. Buat tahun ajaran.
11. Jadikan tahun ajaran aktif.
12. Search santri dari `/schoolos`.
13. Buka Student 360.

Expected:

1. Tidak ada error 500.
2. Dashboard tampil.
3. Module registry tampil.
4. Academic year aktif tampil di dashboard.
5. Student 360 menampilkan ringkasan lintas modul.
6. Admin bisa mengubah settings.
7. Admin bisa mengubah module registry.

---

## 49.2 Test Kepala Sekolah

Login sebagai kepala sekolah.

Tes:

1. Buka `/schoolos`.
2. Buka Student 360 santri.
3. Buka `/schoolos/modules`.
4. Coba mengubah module registry.
5. Coba membuka settings.

Expected:

1. Kepala sekolah bisa melihat dashboard.
2. Kepala sekolah bisa melihat Student 360.
3. Kepala sekolah tidak bisa mengubah module registry.
4. Kepala sekolah tidak bisa mengubah settings.

---

## 49.3 Test Guru

Login sebagai guru.

Tes:

1. Buka `/schoolos`.
2. Search santri dalam scope.
3. Buka Student 360 santri dalam scope.
4. Coba buka Student 360 santri di luar scope.

Expected:

1. Guru bisa melihat dashboard.
2. Guru hanya melihat santri dalam scope.
3. Guru tidak bisa melihat santri di luar scope.
4. Guru tidak bisa mengubah settings/module registry.

---

## 49.4 Test Parent

Login sebagai parent.

Tes:

1. Buka `/schoolos`.
2. Search anak sendiri.
3. Buka Student 360 anak sendiri.
4. Ubah URL ke ID santri lain.
5. Coba buka `/schoolos/settings`.
6. Coba buka `/schoolos/modules`.

Expected:

1. Parent bisa membuka SchoolOS dashboard versi terbatas.
2. Parent hanya melihat anak sendiri.
3. Parent tidak bisa melihat santri lain.
4. Parent tidak bisa membuka settings.
5. Parent tidak bisa mengubah modules.

---

## 49.5 Test Student

Login sebagai student.

Tes:

1. Buka `/schoolos`.
2. Search nama sendiri.
3. Buka Student 360 diri sendiri.
4. Ubah URL ke ID santri lain.
5. Coba buka `/schoolos/settings`.
6. Coba buka `/schoolos/modules`.

Expected:

1. Student bisa membuka dashboard versi terbatas.
2. Student hanya melihat data pribadi.
3. Student tidak bisa melihat santri lain.
4. Student tidak bisa membuka settings.
5. Student tidak bisa mengubah modules.

---

# 50. Build Frontend

Jalankan:

```powershell
npm run build
```

Build wajib berhasil.

---

# 51. Validasi Akhir

Jalankan:

```powershell
php artisan migrate:status
php artisan route:list --name=schoolos
php artisan app:system-health-check
npm run build
git status
```

Jalankan server:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/schoolos
http://127.0.0.1:8000/schoolos/modules
http://127.0.0.1:8000/schoolos/settings
http://127.0.0.1:8000/schoolos/academic-years
```

---

# 52. Dokumentasi Phase 15

Buat file:

```text
docs/phase-15-schoolos-mini.md
```

Isi lengkap:

```md
# Phase 15 — SchoolOS Mini

## Status

Phase 15 menambahkan SchoolOS Mini sebagai layer integrasi HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. SchoolOS Dashboard.
2. Role-based home dashboard.
3. Module registry.
4. School settings.
5. Academic year.
6. School term.
7. Student 360 Profile.
8. Internal search sederhana.
9. Module health cards.
10. Quick access ke modul aktif.

## Tabel Baru

1. `academic_years`
2. `school_terms`
3. `school_settings`
4. `system_modules`

## Model Baru

1. `AcademicYear`
2. `SchoolTerm`
3. `SchoolSetting`
4. `SystemModule`

## Controller Baru

1. `SchoolOsDashboardController`
2. `Student360Controller`
3. `AcademicYearController`
4. `SchoolSettingController`
5. `SystemModuleController`
6. `SchoolOsSearchController`

## Service Baru

1. `SchoolOsAccessService`
2. `SchoolContextService`
3. `ModuleRegistryService`
4. `SchoolOsDashboardService`
5. `Student360SnapshotService`
6. `SchoolOsSearchService`

## Seeder Baru

1. `SystemModuleSeeder`

## Route Baru

1. `schoolos.dashboard`
2. `schoolos.search`
3. `schoolos.students.show`
4. `schoolos.academic-years.index`
5. `schoolos.academic-years.create`
6. `schoolos.academic-years.store`
7. `schoolos.academic-years.edit`
8. `schoolos.academic-years.update`
9. `schoolos.settings.index`
10. `schoolos.settings.update`
11. `schoolos.modules.index`
12. `schoolos.modules.update`

## Role Access

| Role | Akses |
|---|---|
| Super Admin | Semua |
| Admin | Dashboard, settings, module registry, Student 360 |
| Kepala Sekolah | Dashboard read-only, Student 360 |
| Guru | Dashboard guru, Student 360 santri scope |
| Parent | Dashboard terbatas, Student 360 anak sendiri |
| Student | Dashboard terbatas, Student 360 diri sendiri |

## Batasan

Phase 15 tidak membuat:

1. Multi-tenant kompleks.
2. White-label.
3. Cashless kantin.
4. Merchant POS.
5. Wallet.
6. Payment gateway.
7. Native mobile.
8. LMS penuh.
9. WhatsApp gateway.
10. Push notification.

## Definition of Done

Phase 15 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. SchoolOS dashboard tampil.
4. Module registry tampil.
5. Module cards tampil.
6. Academic year bisa dibuat.
7. Academic year aktif tampil di dashboard.
8. School settings bisa disimpan.
9. Student 360 tampil.
10. Internal search bisa menemukan santri.
11. Parent hanya melihat anak sendiri.
12. Student hanya melihat data pribadi.
13. Guru hanya melihat santri scope.
14. Kepala sekolah read-only.
15. Admin bisa mengelola settings dan module registry.
16. `npm run build` berhasil.
17. `php artisan app:system-health-check` berhasil.
18. Dokumentasi dibuat.
```

---

# 53. Update `docs/project-progress.md`

Buka:

```text
docs/project-progress.md
```

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
| 11 | Mutabaah Yaumiyah Tracker | Done |
| 12 | QR Attendance System | Done |
| 13 | Tahsin Management App | Done |
| 14 | Student Finance Ledger | Done |
| 15 | SchoolOS Mini | Done |
| 16 | Boarding School Management System | Pending |
| 17 | Multi-Tenant Foundation | Pending |
| 18 | White-Label School App Builder | Pending |
| 19 | Cashless Kantin / Merchant POS | Pending |
```

---

# 54. Commit Phase 15

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add schoolos mini"
```

Jika remote tersedia:

```powershell
git push origin phase-15-schoolos-mini
```

---

# 55. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 15 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- SchoolOS Dashboard
- Role-based dashboard
- Module Registry
- School Settings
- Academic Year
- School Term
- Student 360 Profile
- Internal Search
- Module Health Cards
- Quick Access Modules
- Role-based access
- Ownership-based access

Tabel dibuat:
- academic_years
- school_terms
- school_settings
- system_modules

Route dibuat:
- schoolos.dashboard
- schoolos.search
- schoolos.students.show
- schoolos.academic-years.*
- schoolos.settings.index
- schoolos.settings.update
- schoolos.modules.index
- schoolos.modules.update

Belum dibuat:
- Multi-tenant kompleks
- White-label
- Cashless kantin
- Merchant POS
- Wallet
- Payment gateway
- Native mobile
- LMS penuh

Status:
- Siap lanjut Phase 16 hanya setelah UAT Phase 15 aman.
```

---

# 56. Larangan Setelah Phase 15

Agent harus berhenti setelah Phase 15 selesai.

Jangan lanjut membuat:

1. Boarding Management.
2. Multi-tenant.
3. White-label.
4. Cashless POS.
5. Wallet.
6. Payment Gateway.
7. Mobile App.
8. WhatsApp Gateway.
9. Push Notification.
10. LMS penuh.

Semua itu masuk phase berikutnya.

---

# 57. Keputusan Akhir

Phase 15 hanya valid jika SchoolOS Mini berhasil menyatukan modul yang sudah ada tanpa merusak:

1. Tahfizh.
2. Parent Portal.
3. Notification.
4. Export.
5. Mutabaah.
6. Attendance.
7. Tahsin.
8. Finance.

Prioritas setelah Phase 15:

1. UAT SchoolOS dashboard.
2. UAT Student 360.
3. UAT ownership access.
4. UAT module registry.
5. UAT academic year.
6. UAT search.
7. Fix bug.
8. Backup database.
9. Baru pertimbangkan Phase 16 — Boarding School Management System.

Jangan masuk multi-tenant, white-label, atau cashless sebelum SchoolOS Mini benar-benar stabil.
