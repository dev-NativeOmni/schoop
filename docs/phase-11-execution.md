# Phase 11 Execution Guide — Mutabaah Yaumiyah Tracker

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
Phase 11 — Mutabaah Yaumiyah Tracker
```

---

# 1. Keputusan Sebelum Phase 11

## 1.1 UAT dan Bug Fix Sprint Wajib

Sebelum menjalankan Phase 11, agent wajib memastikan Phase 0 sampai Phase 10 sudah lolos validasi minimal.

Jangan langsung menambah modul Mutabaah jika masih ada bug inti.

Phase 11 hanya boleh dieksekusi jika:

1. Login semua role aman.
2. Role access tidak bocor.
3. Parent hanya bisa melihat anak sendiri.
4. Student hanya bisa melihat data sendiri.
5. Guru bisa input setoran tahfizh.
6. Sequential guard tahfizh berjalan.
7. Target dan hutang hafalan benar.
8. Dashboard dan report tidak error.
9. Notification center berjalan.
10. Export PDF dan Excel berjalan.
11. Backup database berhasil.
12. System health check berhasil.
13. `npm run build` berhasil.
14. Tidak ada error P0/P1 yang terbuka.

Jika masih ada error P0 atau P1, hentikan Phase 11 dan buat bug fix sprint dulu.

---

## 1.2 Klasifikasi Bug Sebelum Phase 11

Gunakan prioritas berikut:

| Prioritas | Contoh Bug                                                            | Keputusan                               |
| --------- | --------------------------------------------------------------------- | --------------------------------------- |
| P0        | Login gagal, data bocor, parent bisa lihat anak lain, backup gagal    | Wajib fix sebelum Phase 11              |
| P1        | Report salah hitung, export error, input setoran lambat, hutang salah | Wajib fix sebelum Phase 11              |
| P2        | Tampilan kurang rapi, teks kurang jelas, spacing UI kurang bagus      | Boleh dicatat, Phase 11 boleh disiapkan |
| P3        | Enhancement kecil, kosmetik minor                                     | Boleh ditunda                           |

---

## 1.3 UAT Minimal Sebelum Phase 11

Jalankan command berikut:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform

php artisan app:system-health-check
php artisan app:backup-database
php artisan route:list
php artisan migrate:status
php artisan schedule:list
npm run build
php artisan optimize
```

Tes manual URL berikut:

```text
/login
/dashboard
/tahfizh/hafalan-records
/tahfizh/targets
/tahfizh/debts
/reports/tahfizh/dashboard
/reports/tahfizh/monthly
/reports/tahfizh/quarterly
/portal/parent/dashboard
/portal/student/dashboard
/notifications
/exports/tahfizh
/admin/system/status
/up
```

Tes akun minimal:

| Role                   | Jumlah Akun Test |
| ---------------------- | ---------------: |
| Super Admin            |                1 |
| Admin                  |                1 |
| Kepala Sekolah         |                1 |
| Guru Tahfidz / Teacher |              1–2 |
| Orang Tua              |              1–3 |
| Santri                 |              2–5 |

Jika semua aman, lanjut Phase 11.

---

# 2. Tujuan Phase 11

Phase 11 bertujuan membuat modul **Mutabaah Yaumiyah Tracker**.

Modul ini digunakan untuk mencatat aktivitas ibadah dan karakter harian santri.

Fokus Phase 11:

1. Template aktivitas mutabaah.
2. Kategori aktivitas mutabaah.
3. Input mutabaah harian.
4. Rekap harian.
5. Rekap mingguan.
6. Rekap bulanan sederhana.
7. Dashboard mutabaah untuk admin, kepala sekolah, dan guru.
8. Portal read-only untuk orang tua.
9. Portal read-only untuk santri.
10. Role-based access.
11. Ownership-based access.
12. Dokumentasi Phase 11.

---

# 3. Batasan Phase 11

AI agent tidak boleh membuat fitur berikut pada Phase 11:

1. Attendance.
2. QR attendance.
3. Tahsin management.
4. Finance ledger.
5. Cashless kantin.
6. Payment gateway.
7. Wallet.
8. White-label.
9. Multi-tenant kompleks.
10. Native Android.
11. Native iOS.
12. Mobile app.
13. WhatsApp gateway.
14. Push notification.
15. Firebase.
16. Websocket.
17. LMS.
18. Boarding school module.
19. AI voice correction.
20. Face recognition.
21. RFID/NFC.
22. Import Excel.
23. Payment report.
24. Student billing.

Phase 11 hanya membuat Mutabaah Yaumiyah Tracker.

---

# 4. Konsep Mutabaah Yaumiyah

## 4.1 Apa Itu Mutabaah di Sistem Ini

Mutabaah Yaumiyah adalah pencatatan aktivitas ibadah dan karakter harian santri.

Contoh aktivitas:

1. Shalat Subuh.
2. Shalat Dzuhur.
3. Shalat Ashar.
4. Shalat Maghrib.
5. Shalat Isya.
6. Shalat Dhuha.
7. Tilawah harian.
8. Dzikir pagi.
9. Dzikir petang.
10. Puasa sunnah.
11. Adab kepada guru.
12. Adab kepada orang tua.
13. Membaca doa harian.
14. Kedisiplinan pribadi.

---

## 4.2 Jenis Input

Phase 11 mendukung beberapa tipe input:

| Tipe        | Keterangan                         |
| ----------- | ---------------------------------- |
| `checklist` | Selesai / tidak selesai            |
| `score`     | Skor numerik 0–100                 |
| `count`     | Jumlah, misalnya halaman atau kali |
| `text`      | Catatan singkat                    |

---

## 4.3 Status Record

Setiap aktivitas harian memakai status:

| Status     | Makna                                       |
| ---------- | ------------------------------------------- |
| `done`     | Aktivitas dilakukan                         |
| `not_done` | Aktivitas tidak dilakukan                   |
| `excused`  | Tidak dilakukan dengan alasan yang diterima |

---

## 4.4 Sumber Input

Phase 11 menyimpan sumber input:

| Source    | Keterangan                                        |
| --------- | ------------------------------------------------- |
| `admin`   | Diinput oleh Super Admin/Admin                    |
| `teacher` | Diinput oleh guru                                 |
| `parent`  | Diinput oleh orang tua jika aktivitas mengizinkan |
| `student` | Diinput oleh santri jika aktivitas mengizinkan    |

Default Phase 11:

1. Admin bisa mengelola template.
2. Guru bisa input mutabaah santri.
3. Kepala sekolah hanya melihat dashboard/report.
4. Orang tua melihat mutabaah anak sendiri.
5. Santri melihat mutabaah pribadi.
6. Parent/student input dibuat aman melalui flag `allow_parent_input` dan `allow_student_input`, tetapi default template boleh dibuat `false`.

---

# 5. Target Output Phase 11

Setelah Phase 11 selesai, aplikasi harus punya:

1. Menu **Mutabaah Yaumiyah**.
2. Menu **Template Mutabaah**.
3. Menu **Input Mutabaah Harian**.
4. Menu **Laporan Mutabaah**.
5. Tabel:

   * `mutabaah_categories`
   * `mutabaah_activities`
   * `mutabaah_records`
6. Model:

   * `MutabaahCategory`
   * `MutabaahActivity`
   * `MutabaahRecord`
7. Controller:

   * `MutabaahActivityController`
   * `MutabaahDailyInputController`
   * `MutabaahReportController`
   * `ParentMutabaahPortalController`
   * `StudentMutabaahPortalController`
8. Request:

   * `StoreMutabaahActivityRequest`
   * `UpdateMutabaahActivityRequest`
   * `StoreMutabaahDailyInputRequest`
   * `MutabaahReportFilterRequest`
9. Service:

   * `MutabaahAccessService`
   * `MutabaahRecordService`
   * `MutabaahReportService`
10. Seeder:

* `MutabaahCategorySeeder`
* `MutabaahActivitySeeder`

11. View:

* template index/create/edit/show
* daily input index
* report dashboard
* parent read-only mutabaah
* student read-only mutabaah

12. Dokumentasi Phase 11.
13. Update `docs/project-progress.md`.

---

# 6. Role Access Phase 11

Gunakan role yang sudah ada di project.

Sebelum coding, agent wajib cek nama role sebenarnya di database:

```powershell
php artisan tinker
```

Lalu jalankan:

```php
App\Models\Role::query()->pluck('name')->all();
```

Jika project memakai slug berbeda, sesuaikan middleware route dan service access.

Role default yang diasumsikan dokumen ini:

```text
super_admin
admin
kepala_sekolah
teacher
parent
student
```

Jika project memakai `guru_tahfidz` alih-alih `teacher`, sesuaikan.

| Role           | Template  | Input Harian   | Report         | Portal      |
| -------------- | --------- | -------------- | -------------- | ----------- |
| Super Admin    | CRUD      | Ya             | Semua          | Tidak perlu |
| Admin          | CRUD      | Ya             | Semua          | Tidak perlu |
| Kepala Sekolah | Read-only | Tidak          | Semua          | Tidak perlu |
| Teacher/Guru   | Read-only | Ya             | Santri terkait | Tidak perlu |
| Parent         | Tidak     | Jika diizinkan | Anak sendiri   | Ya          |
| Student        | Tidak     | Jika diizinkan | Data sendiri   | Ya          |

Aturan keras:

1. Parent tidak boleh melihat anak lain.
2. Student tidak boleh melihat data santri lain.
3. Parent/student tidak boleh mengubah template.
4. Parent/student tidak boleh melihat dashboard internal.
5. Kepala sekolah tidak boleh mengubah record.
6. Teacher tidak boleh mengelola template kecuali nanti diberi izin khusus.
7. Delete record tidak dibuat di Phase 11 kecuali admin butuh revisi data. Untuk MVP, gunakan update/upsert, bukan delete.

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
13. Tabel berikut sudah ada:

* `users`
* `roles`
* `schools`
* `class_rooms`
* `students`
* `parent_profiles`
* `parent_student`
* `teacher_profiles`

14. System health check berhasil.
15. Backup database berhasil.
16. Working tree bersih atau semua perubahan diketahui.

Jika Phase 10 belum aman, hentikan eksekusi.

---

# 8. Buat Branch Git Phase 11

Jalankan:

```powershell
git checkout -b phase-11-mutabaah-yaumiyah-tracker
```

Jika branch sudah ada:

```powershell
git checkout phase-11-mutabaah-yaumiyah-tracker
```

---

# 9. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Mutabaah/
│   │   │   ├── MutabaahActivityController.php
│   │   │   ├── MutabaahDailyInputController.php
│   │   │   └── MutabaahReportController.php
│   │   └── Portal/
│   │       ├── ParentMutabaahPortalController.php
│   │       └── StudentMutabaahPortalController.php
│   └── Requests/
│       └── Mutabaah/
│           ├── StoreMutabaahActivityRequest.php
│           ├── UpdateMutabaahActivityRequest.php
│           ├── StoreMutabaahDailyInputRequest.php
│           └── MutabaahReportFilterRequest.php
├── Models/
│   ├── MutabaahCategory.php
│   ├── MutabaahActivity.php
│   └── MutabaahRecord.php
└── Services/
    └── Mutabaah/
        ├── MutabaahAccessService.php
        ├── MutabaahRecordService.php
        └── MutabaahReportService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_mutabaah_categories_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_mutabaah_activities_table.php
│   └── xxxx_xx_xx_xxxxxx_create_mutabaah_records_table.php
└── seeders/
    ├── MutabaahCategorySeeder.php
    └── MutabaahActivitySeeder.php

resources/
└── views/
    ├── mutabaah/
    │   ├── activities/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── daily/
    │   │   └── index.blade.php
    │   └── reports/
    │       └── dashboard.blade.php
    └── portal/
        ├── parent/
        │   └── mutabaah.blade.php
        └── student/
            └── mutabaah.blade.php

routes/
└── web.php

docs/
├── phase-11-execution.md
└── phase-11-mutabaah-yaumiyah-tracker.md
```

---

# 10. Buat Model, Migration, Seeder, Controller, Request

Jalankan:

```powershell
php artisan make:model MutabaahCategory -m
php artisan make:model MutabaahActivity -m
php artisan make:model MutabaahRecord -m

php artisan make:seeder MutabaahCategorySeeder
php artisan make:seeder MutabaahActivitySeeder

php artisan make:controller Mutabaah/MutabaahActivityController --resource
php artisan make:controller Mutabaah/MutabaahDailyInputController
php artisan make:controller Mutabaah/MutabaahReportController
php artisan make:controller Portal/ParentMutabaahPortalController
php artisan make:controller Portal/StudentMutabaahPortalController

php artisan make:request Mutabaah/StoreMutabaahActivityRequest
php artisan make:request Mutabaah/UpdateMutabaahActivityRequest
php artisan make:request Mutabaah/StoreMutabaahDailyInputRequest
php artisan make:request Mutabaah/MutabaahReportFilterRequest
```

Buat folder service:

```powershell
mkdir app\Services\Mutabaah
```

Buat file service:

```powershell
New-Item app\Services\Mutabaah\MutabaahAccessService.php
New-Item app\Services\Mutabaah\MutabaahRecordService.php
New-Item app\Services\Mutabaah\MutabaahReportService.php
```

Buat folder view:

```powershell
mkdir resources\views\mutabaah
mkdir resources\views\mutabaah\activities
mkdir resources\views\mutabaah\daily
mkdir resources\views\mutabaah\reports
```

Jika folder portal sudah ada dari Phase 7, jangan hapus.

```powershell
mkdir resources\views\portal\parent
mkdir resources\views\portal\student
```

Jika folder sudah ada, lanjut saja.

---

# 11. Migration `create_mutabaah_categories_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_mutabaah_categories_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutabaah_categories', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutabaah_categories');
    }
};
```

---

# 12. Migration `create_mutabaah_activities_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_mutabaah_activities_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutabaah_activities', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('mutabaah_category_id')
                ->nullable()
                ->constrained('mutabaah_categories')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();

            $table->enum('input_type', [
                'checklist',
                'score',
                'count',
                'text',
            ])->default('checklist');

            $table->unsignedSmallInteger('target_score')->nullable();
            $table->unsignedSmallInteger('target_count')->nullable();
            $table->string('target_unit')->nullable();

            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_teacher_input')->default(true);
            $table->boolean('allow_parent_input')->default(false);
            $table->boolean('allow_student_input')->default(false);

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index(['mutabaah_category_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutabaah_activities');
    }
};
```

---

# 13. Migration `create_mutabaah_records_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_mutabaah_records_table.php
```

Isi lengkap:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutabaah_records', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('mutabaah_activity_id')
                ->constrained('mutabaah_activities')
                ->restrictOnDelete();

            $table->date('record_date');

            $table->enum('status', [
                'done',
                'not_done',
                'excused',
            ])->default('not_done');

            $table->unsignedSmallInteger('score')->nullable();
            $table->unsignedSmallInteger('count_value')->nullable();
            $table->text('text_value')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('submitted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('source', [
                'admin',
                'teacher',
                'parent',
                'student',
            ])->default('teacher');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['student_id', 'mutabaah_activity_id', 'record_date'],
                'mutabaah_unique_student_activity_date'
            );

            $table->index(['school_id', 'record_date']);
            $table->index(['student_id', 'record_date']);
            $table->index(['mutabaah_activity_id', 'record_date']);
            $table->index(['source', 'record_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutabaah_records');
    }
};
```

---

# 14. Model `MutabaahCategory`

Buka:

```text
app/Models/MutabaahCategory.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MutabaahCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'slug',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function activities(): HasMany
    {
        return $this->hasMany(MutabaahActivity::class, 'mutabaah_category_id');
    }
}
```

---

# 15. Model `MutabaahActivity`

Buka:

```text
app/Models/MutabaahActivity.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MutabaahActivity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'mutabaah_category_id',
        'name',
        'slug',
        'description',
        'input_type',
        'target_score',
        'target_count',
        'target_unit',
        'is_required',
        'is_active',
        'allow_teacher_input',
        'allow_parent_input',
        'allow_student_input',
        'sort_order',
    ];

    protected $casts = [
        'target_score' => 'integer',
        'target_count' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'allow_teacher_input' => 'boolean',
        'allow_parent_input' => 'boolean',
        'allow_student_input' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MutabaahCategory::class, 'mutabaah_category_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(MutabaahRecord::class, 'mutabaah_activity_id');
    }
}
```

---

# 16. Model `MutabaahRecord`

Buka:

```text
app/Models/MutabaahRecord.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutabaahRecord extends Model
{
    protected $fillable = [
        'school_id',
        'student_id',
        'mutabaah_activity_id',
        'record_date',
        'status',
        'score',
        'count_value',
        'text_value',
        'note',
        'submitted_by',
        'source',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'record_date' => 'date',
        'score' => 'integer',
        'count_value' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(MutabaahActivity::class, 'mutabaah_activity_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
```

---

# 17. Seeder `MutabaahCategorySeeder`

Buka:

```text
database/seeders/MutabaahCategorySeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\MutabaahCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MutabaahCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ibadah Wajib',
                'description' => 'Aktivitas ibadah wajib harian.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Ibadah Sunnah',
                'description' => 'Aktivitas ibadah sunnah harian atau pekanan.',
                'sort_order' => 20,
            ],
            [
                'name' => 'Qur’an dan Dzikir',
                'description' => 'Tilawah, dzikir, dan aktivitas Qur’an harian.',
                'sort_order' => 30,
            ],
            [
                'name' => 'Adab dan Karakter',
                'description' => 'Pembiasaan adab dan karakter santri.',
                'sort_order' => 40,
            ],
        ];

        foreach ($categories as $category) {
            MutabaahCategory::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'slug' => Str::slug($category['name']),
                ],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
```

---

# 18. Seeder `MutabaahActivitySeeder`

Buka:

```text
database/seeders/MutabaahActivitySeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\MutabaahActivity;
use App\Models\MutabaahCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MutabaahActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryMap = MutabaahCategory::query()
            ->whereNull('school_id')
            ->pluck('id', 'name');

        $activities = [
            [
                'category' => 'Ibadah Wajib',
                'name' => 'Shalat Subuh',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 10,
            ],
            [
                'category' => 'Ibadah Wajib',
                'name' => 'Shalat Dzuhur',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 20,
            ],
            [
                'category' => 'Ibadah Wajib',
                'name' => 'Shalat Ashar',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 30,
            ],
            [
                'category' => 'Ibadah Wajib',
                'name' => 'Shalat Maghrib',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 40,
            ],
            [
                'category' => 'Ibadah Wajib',
                'name' => 'Shalat Isya',
                'input_type' => 'checklist',
                'is_required' => true,
                'sort_order' => 50,
            ],
            [
                'category' => 'Ibadah Sunnah',
                'name' => 'Shalat Dhuha',
                'input_type' => 'checklist',
                'is_required' => false,
                'sort_order' => 60,
            ],
            [
                'category' => 'Qur’an dan Dzikir',
                'name' => 'Tilawah Harian',
                'input_type' => 'count',
                'target_count' => 1,
                'target_unit' => 'halaman',
                'is_required' => false,
                'sort_order' => 70,
            ],
            [
                'category' => 'Qur’an dan Dzikir',
                'name' => 'Dzikir Pagi',
                'input_type' => 'checklist',
                'is_required' => false,
                'sort_order' => 80,
            ],
            [
                'category' => 'Qur’an dan Dzikir',
                'name' => 'Dzikir Petang',
                'input_type' => 'checklist',
                'is_required' => false,
                'sort_order' => 90,
            ],
            [
                'category' => 'Adab dan Karakter',
                'name' => 'Adab kepada Guru',
                'input_type' => 'score',
                'target_score' => 80,
                'is_required' => false,
                'sort_order' => 100,
            ],
            [
                'category' => 'Adab dan Karakter',
                'name' => 'Kedisiplinan Pribadi',
                'input_type' => 'score',
                'target_score' => 80,
                'is_required' => false,
                'sort_order' => 110,
            ],
        ];

        foreach ($activities as $activity) {
            $categoryId = $categoryMap[$activity['category']] ?? null;

            MutabaahActivity::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'slug' => Str::slug($activity['name']),
                ],
                [
                    'mutabaah_category_id' => $categoryId,
                    'name' => $activity['name'],
                    'description' => null,
                    'input_type' => $activity['input_type'],
                    'target_score' => $activity['target_score'] ?? null,
                    'target_count' => $activity['target_count'] ?? null,
                    'target_unit' => $activity['target_unit'] ?? null,
                    'is_required' => $activity['is_required'],
                    'is_active' => true,
                    'allow_teacher_input' => true,
                    'allow_parent_input' => false,
                    'allow_student_input' => false,
                    'sort_order' => $activity['sort_order'],
                ]
            );
        }
    }
}
```

---

# 19. Update `DatabaseSeeder`

Buka:

```text
database/seeders/DatabaseSeeder.php
```

Tambahkan pemanggilan seeder berikut tanpa menghapus seeder lama:

```php
$this->call([
    MutabaahCategorySeeder::class,
    MutabaahActivitySeeder::class,
]);
```

Contoh aman:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
         * Jangan hapus seeder lama.
         * Pastikan seeder Phase 0–10 tetap dipanggil sesuai struktur project.
         */

        $this->call([
            MutabaahCategorySeeder::class,
            MutabaahActivitySeeder::class,
        ]);
    }
}
```

Jika `DatabaseSeeder` sudah berisi banyak seeder, tambahkan dua seeder Mutabaah di bagian paling bawah.

---

# 20. Service `MutabaahAccessService`

Buka:

```text
app/Services/Mutabaah/MutabaahAccessService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Mutabaah;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class MutabaahAccessService
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

    public function canManageTemplate(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canViewInternalReport(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isPrincipal($user)
            || $this->isTeacher($user);
    }

    public function canInputDailyRecord(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isTeacher($user);
    }

    public function applyStudentScope(Builder $query, User $user): Builder
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return $query;
        }

        if ($this->isTeacher($user)) {
            return $this->applyTeacherStudentScope($query, $user);
        }

        if ($this->isParent($user)) {
            return $this->applyParentStudentScope($query, $user);
        }

        if ($this->isStudent($user)) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }

    public function applyTeacherStudentScope(Builder $query, User $user): Builder
    {
        /*
         * Catatan:
         * Struktur relasi teacher-class bisa berbeda antar phase.
         * Jika project sudah punya relasi teacher_profiles.class_room_id,
         * scope ini akan membatasi santri berdasarkan kelas guru.
         * Jika belum ada, admin wajib menyesuaikan sesuai struktur project.
         */
        $teacherProfile = $user->teacherProfile ?? null;

        if ($teacherProfile && isset($teacherProfile->class_room_id)) {
            return $query->where('class_room_id', $teacherProfile->class_room_id);
        }

        return $query;
    }

    public function applyParentStudentScope(Builder $query, User $user): Builder
    {
        $parentProfile = $user->parentProfile ?? null;

        if (! $parentProfile) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('parents', function (Builder $parentQuery) use ($parentProfile): void {
            $parentQuery->where('parent_profiles.id', $parentProfile->id);
        });
    }

    public function canViewStudent(User $user, Student $student): bool
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return true;
        }

        if ($this->isTeacher($user)) {
            $query = Student::query()->whereKey($student->id);

            return $this->applyTeacherStudentScope($query, $user)->exists();
        }

        if ($this->isParent($user)) {
            $query = Student::query()->whereKey($student->id);

            return $this->applyParentStudentScope($query, $user)->exists();
        }

        if ($this->isStudent($user)) {
            return (int) $student->user_id === (int) $user->id;
        }

        return false;
    }
}
```

Catatan penting:

Jika model `User` belum punya relasi `teacherProfile` atau `parentProfile`, tambahkan relasi di model `User`.

---

# 21. Update Model `User`

Buka:

```text
app/Models/User.php
```

Pastikan ada relasi berikut jika belum ada:

```php
use Illuminate\Database\Eloquent\Relations\HasOne;

public function teacherProfile(): HasOne
{
    return $this->hasOne(TeacherProfile::class);
}

public function parentProfile(): HasOne
{
    return $this->hasOne(ParentProfile::class);
}

public function studentProfile(): HasOne
{
    return $this->hasOne(Student::class);
}
```

Jika nama foreign key di project berbeda, sesuaikan dengan migration Phase 1–2.

Jangan hapus isi model lama.

---

# 22. Update Model `Student`

Buka:

```text
app/Models/Student.php
```

Pastikan ada relasi berikut jika belum ada:

```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

public function parents(): BelongsToMany
{
    return $this->belongsToMany(
        ParentProfile::class,
        'parent_student',
        'student_id',
        'parent_profile_id'
    );
}

public function mutabaahRecords(): HasMany
{
    return $this->hasMany(MutabaahRecord::class);
}
```

Jangan hapus relasi lama.

---

# 23. Service `MutabaahRecordService`

Buka:

```text
app/Services/Mutabaah/MutabaahRecordService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Mutabaah;

use App\Models\MutabaahActivity;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class MutabaahRecordService
{
    public function saveDailyRecords(
        Student $student,
        string $recordDate,
        array $records,
        User $submittedBy,
        string $source
    ): Collection {
        return collect($records)->map(function (array $item) use ($student, $recordDate, $submittedBy, $source): MutabaahRecord {
            $activity = MutabaahActivity::query()
                ->whereKey($item['mutabaah_activity_id'] ?? null)
                ->where('is_active', true)
                ->first();

            if (! $activity) {
                throw ValidationException::withMessages([
                    'records' => 'Aktivitas mutabaah tidak ditemukan atau tidak aktif.',
                ]);
            }

            $this->validateInputByType($activity, $item);

            return MutabaahRecord::query()->updateOrCreate(
                [
                    'student_id' => $student->id,
                    'mutabaah_activity_id' => $activity->id,
                    'record_date' => $recordDate,
                ],
                [
                    'school_id' => $student->school_id ?? null,
                    'status' => $item['status'] ?? 'not_done',
                    'score' => $item['score'] ?? null,
                    'count_value' => $item['count_value'] ?? null,
                    'text_value' => $item['text_value'] ?? null,
                    'note' => $item['note'] ?? null,
                    'submitted_by' => $submittedBy->id,
                    'source' => $source,
                ]
            );
        });
    }

    private function validateInputByType(MutabaahActivity $activity, array $item): void
    {
        $status = $item['status'] ?? 'not_done';

        if (! in_array($status, ['done', 'not_done', 'excused'], true)) {
            throw ValidationException::withMessages([
                'records' => 'Status mutabaah tidak valid.',
            ]);
        }

        if ($activity->input_type === 'score') {
            $score = $item['score'] ?? null;

            if ($score !== null && ((int) $score < 0 || (int) $score > 100)) {
                throw ValidationException::withMessages([
                    'records' => 'Skor mutabaah harus berada di antara 0 sampai 100.',
                ]);
            }
        }

        if ($activity->input_type === 'count') {
            $countValue = $item['count_value'] ?? null;

            if ($countValue !== null && (int) $countValue < 0) {
                throw ValidationException::withMessages([
                    'records' => 'Jumlah mutabaah tidak boleh negatif.',
                ]);
            }
        }
    }
}
```

---

# 24. Service `MutabaahReportService`

Buka:

```text
app/Services/Mutabaah/MutabaahReportService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Mutabaah;

use App\Models\MutabaahActivity;
use App\Models\MutabaahRecord;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MutabaahReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfWeek()->toDateString())->startOfDay();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->endOfWeek()->toDateString())->endOfDay();

        $recordQuery = MutabaahRecord::query()
            ->with(['student', 'activity.category'])
            ->whereBetween('record_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ]);

        if (! empty($filters['class_room_id'])) {
            $recordQuery->whereHas('student', function (Builder $query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $recordQuery->where('student_id', $filters['student_id']);
        }

        $records = $recordQuery->get();

        $totalRecords = $records->count();
        $doneRecords = $records->where('status', 'done')->count();
        $notDoneRecords = $records->where('status', 'not_done')->count();
        $excusedRecords = $records->where('status', 'excused')->count();

        $completionRate = $totalRecords > 0
            ? round(($doneRecords / $totalRecords) * 100, 2)
            : 0;

        return [
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'summary' => [
                'total_records' => $totalRecords,
                'done_records' => $doneRecords,
                'not_done_records' => $notDoneRecords,
                'excused_records' => $excusedRecords,
                'completion_rate' => $completionRate,
            ],
            'by_student' => $this->groupByStudent($records),
            'by_activity' => $this->groupByActivity($records),
        ];
    }

    public function studentSnapshot(Student $student, array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfWeek()->toDateString())->startOfDay();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->endOfWeek()->toDateString())->endOfDay();

        $activities = MutabaahActivity::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $records = MutabaahRecord::query()
            ->with(['activity.category', 'submittedBy'])
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $startDate->toDateString(),
                $endDate->toDateString(),
            ])
            ->orderByDesc('record_date')
            ->get();

        $totalRecords = $records->count();
        $doneRecords = $records->where('status', 'done')->count();

        return [
            'student' => $student,
            'period' => [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ],
            'activities' => $activities,
            'records' => $records,
            'summary' => [
                'total_records' => $totalRecords,
                'done_records' => $doneRecords,
                'completion_rate' => $totalRecords > 0
                    ? round(($doneRecords / $totalRecords) * 100, 2)
                    : 0,
            ],
        ];
    }

    private function groupByStudent(Collection $records): Collection
    {
        return $records
            ->groupBy('student_id')
            ->map(function (Collection $studentRecords): array {
                $first = $studentRecords->first();
                $total = $studentRecords->count();
                $done = $studentRecords->where('status', 'done')->count();

                return [
                    'student' => $first?->student,
                    'total' => $total,
                    'done' => $done,
                    'not_done' => $studentRecords->where('status', 'not_done')->count(),
                    'excused' => $studentRecords->where('status', 'excused')->count(),
                    'completion_rate' => $total > 0 ? round(($done / $total) * 100, 2) : 0,
                ];
            })
            ->sortByDesc('completion_rate')
            ->values();
    }

    private function groupByActivity(Collection $records): Collection
    {
        return $records
            ->groupBy('mutabaah_activity_id')
            ->map(function (Collection $activityRecords): array {
                $first = $activityRecords->first();
                $total = $activityRecords->count();
                $done = $activityRecords->where('status', 'done')->count();

                return [
                    'activity' => $first?->activity,
                    'total' => $total,
                    'done' => $done,
                    'not_done' => $activityRecords->where('status', 'not_done')->count(),
                    'excused' => $activityRecords->where('status', 'excused')->count(),
                    'completion_rate' => $total > 0 ? round(($done / $total) * 100, 2) : 0,
                ];
            })
            ->sortByDesc('completion_rate')
            ->values();
    }
}
```

---

# 25. Request `StoreMutabaahActivityRequest`

Buka:

```text
app/Http/Requests/Mutabaah/StoreMutabaahActivityRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Mutabaah;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMutabaahActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $roleValue = $user?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, ['super_admin', 'admin', 'admin_sekolah'], true);
    }

    public function rules(): array
    {
        return [
            'mutabaah_category_id' => ['nullable', 'exists:mutabaah_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'input_type' => ['required', Rule::in(['checklist', 'score', 'count', 'text'])],
            'target_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'target_count' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'target_unit' => ['nullable', 'string', 'max:50'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'allow_teacher_input' => ['nullable', 'boolean'],
            'allow_parent_input' => ['nullable', 'boolean'],
            'allow_student_input' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
    }
}
```

---

# 26. Request `UpdateMutabaahActivityRequest`

Buka:

```text
app/Http/Requests/Mutabaah/UpdateMutabaahActivityRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Mutabaah;

class UpdateMutabaahActivityRequest extends StoreMutabaahActivityRequest
{
    //
}
```

---

# 27. Request `StoreMutabaahDailyInputRequest`

Buka:

```text
app/Http/Requests/Mutabaah/StoreMutabaahDailyInputRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Mutabaah;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMutabaahDailyInputRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $roleValue = $user?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
            'teacher',
            'guru',
            'guru_tahfidz',
            'parent',
            'student',
        ], true);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'record_date' => ['required', 'date'],
            'records' => ['required', 'array', 'min:1'],
            'records.*.mutabaah_activity_id' => ['required', 'exists:mutabaah_activities,id'],
            'records.*.status' => ['required', Rule::in(['done', 'not_done', 'excused'])],
            'records.*.score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'records.*.count_value' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'records.*.text_value' => ['nullable', 'string', 'max:2000'],
            'records.*.note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
```

---

# 28. Request `MutabaahReportFilterRequest`

Buka:

```text
app/Http/Requests/Mutabaah/MutabaahReportFilterRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Mutabaah;

use Illuminate\Foundation\Http\FormRequest;

class MutabaahReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $roleValue = $user?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
            'kepala_sekolah',
            'principal',
            'teacher',
            'guru',
            'guru_tahfidz',
            'parent',
            'student',
        ], true);
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
        ];
    }
}
```

---

# 29. Controller `MutabaahActivityController`

Buka:

```text
app/Http/Controllers/Mutabaah/MutabaahActivityController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Mutabaah;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\StoreMutabaahActivityRequest;
use App\Http\Requests\Mutabaah\UpdateMutabaahActivityRequest;
use App\Models\MutabaahActivity;
use App\Models\MutabaahCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MutabaahActivityController extends Controller
{
    public function index(): View
    {
        $activities = MutabaahActivity::query()
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('mutabaah.activities.index', compact('activities'));
    }

    public function create(): View
    {
        $categories = MutabaahCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('mutabaah.activities.create', compact('categories'));
    }

    public function store(StoreMutabaahActivityRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);
        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['allow_teacher_input'] = $request->boolean('allow_teacher_input', true);
        $data['allow_parent_input'] = $request->boolean('allow_parent_input');
        $data['allow_student_input'] = $request->boolean('allow_student_input');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        MutabaahActivity::query()->create($data);

        return redirect()
            ->route('mutabaah.activities.index')
            ->with('success', 'Template mutabaah berhasil dibuat.');
    }

    public function show(MutabaahActivity $activity): View
    {
        $activity->load('category');

        return view('mutabaah.activities.show', compact('activity'));
    }

    public function edit(MutabaahActivity $activity): View
    {
        $categories = MutabaahCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('mutabaah.activities.edit', compact('activity', 'categories'));
    }

    public function update(UpdateMutabaahActivityRequest $request, MutabaahActivity $activity): RedirectResponse
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']);
        $data['is_required'] = $request->boolean('is_required');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['allow_teacher_input'] = $request->boolean('allow_teacher_input', true);
        $data['allow_parent_input'] = $request->boolean('allow_parent_input');
        $data['allow_student_input'] = $request->boolean('allow_student_input');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $activity->update($data);

        return redirect()
            ->route('mutabaah.activities.index')
            ->with('success', 'Template mutabaah berhasil diperbarui.');
    }

    public function destroy(MutabaahActivity $activity): RedirectResponse
    {
        $activity->delete();

        return redirect()
            ->route('mutabaah.activities.index')
            ->with('success', 'Template mutabaah berhasil dinonaktifkan.');
    }
}
```

---

# 30. Controller `MutabaahDailyInputController`

Buka:

```text
app/Http/Controllers/Mutabaah/MutabaahDailyInputController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Mutabaah;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\StoreMutabaahDailyInputRequest;
use App\Models\ClassRoom;
use App\Models\MutabaahActivity;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahRecordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MutabaahDailyInputController extends Controller
{
    public function index(Request $request, MutabaahAccessService $accessService): View
    {
        abort_unless($accessService->canInputDailyRecord($request->user()), 403);

        $recordDate = $request->string('record_date')->toString() ?: now()->toDateString();
        $classRoomId = $request->integer('class_room_id') ?: null;
        $studentId = $request->integer('student_id') ?: null;

        $studentsQuery = Student::query()
            ->orderBy('name')
            ->orderBy('nama_lengkap');

        $studentsQuery = $accessService->applyStudentScope($studentsQuery, $request->user());

        if ($classRoomId) {
            $studentsQuery->where('class_room_id', $classRoomId);
        }

        if ($studentId) {
            $studentsQuery->whereKey($studentId);
        }

        $students = $studentsQuery->limit(100)->get();

        $activities = MutabaahActivity::query()
            ->with('category')
            ->where('is_active', true)
            ->where('allow_teacher_input', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $existingRecords = MutabaahRecord::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->whereIn('mutabaah_activity_id', $activities->pluck('id'))
            ->whereDate('record_date', $recordDate)
            ->get()
            ->keyBy(fn (MutabaahRecord $record): string => $record->student_id . '-' . $record->mutabaah_activity_id);

        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        return view('mutabaah.daily.index', compact(
            'recordDate',
            'classRoomId',
            'studentId',
            'students',
            'activities',
            'existingRecords',
            'classRooms'
        ));
    }

    public function store(
        StoreMutabaahDailyInputRequest $request,
        MutabaahAccessService $accessService,
        MutabaahRecordService $recordService
    ): RedirectResponse {
        $student = Student::query()->findOrFail($request->validated('student_id'));

        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $source = $accessService->isAdmin($request->user()) ? 'admin' : 'teacher';

        $recordService->saveDailyRecords(
            student: $student,
            recordDate: $request->validated('record_date'),
            records: $request->validated('records'),
            submittedBy: $request->user(),
            source: $source
        );

        return back()->with('success', 'Mutabaah harian berhasil disimpan.');
    }
}
```

Catatan penting:

Jika model `Student` memakai kolom `nama_lengkap`, tapi tidak punya `name`, hapus `orderBy('name')`.

Jika model `ClassRoom` memakai kolom selain `name`, sesuaikan.

---

# 31. Controller `MutabaahReportController`

Buka:

```text
app/Http/Controllers/Mutabaah/MutabaahReportController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Mutabaah;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\MutabaahReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahReportService;
use Illuminate\View\View;

class MutabaahReportController extends Controller
{
    public function dashboard(
        MutabaahReportFilterRequest $request,
        MutabaahAccessService $accessService,
        MutabaahReportService $reportService
    ): View {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $filters = $request->validated();

        $report = $reportService->dashboard($filters);

        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        $studentsQuery = Student::query()
            ->orderBy('name')
            ->orderBy('nama_lengkap');

        $students = $accessService
            ->applyStudentScope($studentsQuery, $request->user())
            ->limit(200)
            ->get();

        return view('mutabaah.reports.dashboard', compact(
            'report',
            'filters',
            'classRooms',
            'students'
        ));
    }
}
```

Catatan:

Jika `Student` tidak punya kolom `name`, hapus `orderBy('name')`.

---

# 32. Controller `ParentMutabaahPortalController`

Buka:

```text
app/Http/Controllers/Portal/ParentMutabaahPortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\MutabaahReportFilterRequest;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahReportService;
use Illuminate\View\View;

class ParentMutabaahPortalController extends Controller
{
    public function index(
        MutabaahReportFilterRequest $request,
        MutabaahAccessService $accessService,
        MutabaahReportService $reportService
    ): View {
        abort_unless($accessService->isParent($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query(), $request->user())
            ->orderBy('name')
            ->orderBy('nama_lengkap')
            ->get();

        $selectedStudent = null;
        $snapshot = null;

        if ($students->isNotEmpty()) {
            $selectedStudent = $students->firstWhere('id', (int) $request->input('student_id'))
                ?? $students->first();

            $snapshot = $reportService->studentSnapshot($selectedStudent, $request->validated());
        }

        return view('portal.parent.mutabaah', compact(
            'students',
            'selectedStudent',
            'snapshot'
        ));
    }
}
```

Catatan:

Jika model `Student` tidak punya kolom `name`, hapus `orderBy('name')`.

---

# 33. Controller `StudentMutabaahPortalController`

Buka:

```text
app/Http/Controllers/Portal/StudentMutabaahPortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\MutabaahReportFilterRequest;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahReportService;
use Illuminate\View\View;

class StudentMutabaahPortalController extends Controller
{
    public function index(
        MutabaahReportFilterRequest $request,
        MutabaahAccessService $accessService,
        MutabaahReportService $reportService
    ): View {
        abort_unless($accessService->isStudent($request->user()), 403);

        $student = Student::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $student) {
            return view('portal.student.mutabaah', [
                'student' => null,
                'snapshot' => null,
            ]);
        }

        $snapshot = $reportService->studentSnapshot($student, $request->validated());

        return view('portal.student.mutabaah', compact('student', 'snapshot'));
    }
}
```

---

# 34. View `mutabaah/activities/index.blade.php`

Buat:

```text
resources/views/mutabaah/activities/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Template Mutabaah</h1>
            <p class="text-sm text-gray-600">Kelola aktivitas mutabaah yaumiyah.</p>
        </div>

        <a href="{{ route('mutabaah.activities.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Tambah Template
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Tipe</th>
                    <th class="px-4 py-3 text-left">Wajib</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($activities as $activity)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $activity->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $activity->category?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ ucfirst($activity->input_type) }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $activity->is_required ? 'Ya' : 'Tidak' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $activity->is_active ? 'Aktif' : 'Nonaktif' }}
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('mutabaah.activities.show', $activity) }}"
                               class="text-blue-600 hover:underline">Detail</a>
                            <a href="{{ route('mutabaah.activities.edit', $activity) }}"
                               class="text-amber-600 hover:underline">Edit</a>
                            <form action="{{ route('mutabaah.activities.destroy', $activity) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Nonaktifkan template ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" type="submit">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Belum ada template mutabaah.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection
```

---

# 35. View `mutabaah/activities/create.blade.php`

Buat:

```text
resources/views/mutabaah/activities/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Template Mutabaah</h1>
        <p class="text-sm text-gray-600">Buat aktivitas mutabaah baru.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mutabaah.activities.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        @include('mutabaah.activities._form', [
            'activity' => null,
            'categories' => $categories,
        ])

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('mutabaah.activities.index') }}"
               class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
```

---

# 36. View Partial `mutabaah/activities/_form.blade.php`

Buat:

```text
resources/views/mutabaah/activities/_form.blade.php
```

Isi lengkap:

```blade
@php
    $activity = $activity ?? null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Kategori</label>
    <select name="mutabaah_category_id" class="mt-1 w-full rounded-lg border-gray-300">
        <option value="">Tanpa kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('mutabaah_category_id', $activity?->mutabaah_category_id) == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Aktivitas</label>
    <input type="text"
           name="name"
           value="{{ old('name', $activity?->name) }}"
           class="mt-1 w-full rounded-lg border-gray-300"
           required>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description"
              rows="3"
              class="mt-1 w-full rounded-lg border-gray-300">{{ old('description', $activity?->description) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Tipe Input</label>
        <select name="input_type" class="mt-1 w-full rounded-lg border-gray-300" required>
            @foreach(['checklist' => 'Checklist', 'score' => 'Skor', 'count' => 'Jumlah', 'text' => 'Teks'] as $value => $label)
                <option value="{{ $value }}" @selected(old('input_type', $activity?->input_type ?? 'checklist') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Target Skor</label>
        <input type="number"
               name="target_score"
               value="{{ old('target_score', $activity?->target_score) }}"
               min="0"
               max="100"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Target Jumlah</label>
        <input type="number"
               name="target_count"
               value="{{ old('target_count', $activity?->target_count) }}"
               min="0"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Satuan Target</label>
        <input type="text"
               name="target_unit"
               value="{{ old('target_unit', $activity?->target_unit) }}"
               placeholder="halaman, kali, menit"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Urutan</label>
        <input type="number"
               name="sort_order"
               value="{{ old('sort_order', $activity?->sort_order ?? 0) }}"
               min="0"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_required" value="1" @checked(old('is_required', $activity?->is_required))>
        <span class="text-sm text-gray-700">Aktivitas wajib</span>
    </label>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $activity?->is_active ?? true))>
        <span class="text-sm text-gray-700">Aktif</span>
    </label>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="allow_teacher_input" value="1" @checked(old('allow_teacher_input', $activity?->allow_teacher_input ?? true))>
        <span class="text-sm text-gray-700">Guru boleh input</span>
    </label>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="allow_parent_input" value="1" @checked(old('allow_parent_input', $activity?->allow_parent_input ?? false))>
        <span class="text-sm text-gray-700">Orang tua boleh input</span>
    </label>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="allow_student_input" value="1" @checked(old('allow_student_input', $activity?->allow_student_input ?? false))>
        <span class="text-sm text-gray-700">Santri boleh input</span>
    </label>
</div>
```

---

# 37. View `mutabaah/activities/edit.blade.php`

Buat:

```text
resources/views/mutabaah/activities/edit.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Template Mutabaah</h1>
        <p class="text-sm text-gray-600">{{ $activity->name }}</p>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mutabaah.activities.update', $activity) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('mutabaah.activities._form', [
            'activity' => $activity,
            'categories' => $categories,
        ])

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('mutabaah.activities.index') }}"
               class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
```

---

# 38. View `mutabaah/activities/show.blade.php`

Buat:

```text
resources/views/mutabaah/activities/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $activity->name }}</h1>
            <p class="text-sm text-gray-600">Detail template mutabaah.</p>
        </div>

        <a href="{{ route('mutabaah.activities.edit', $activity) }}"
           class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">
            Edit
        </a>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="font-medium text-gray-500">Kategori</dt>
                <dd class="text-gray-900">{{ $activity->category?->name ?? '-' }}</dd>
            </div>

            <div>
                <dt class="font-medium text-gray-500">Tipe Input</dt>
                <dd class="text-gray-900">{{ ucfirst($activity->input_type) }}</dd>
            </div>

            <div>
                <dt class="font-medium text-gray-500">Target Skor</dt>
                <dd class="text-gray-900">{{ $activity->target_score ?? '-' }}</dd>
            </div>

            <div>
                <dt class="font-medium text-gray-500">Target Jumlah</dt>
                <dd class="text-gray-900">
                    {{ $activity->target_count ?? '-' }}
                    {{ $activity->target_unit }}
                </dd>
            </div>

            <div>
                <dt class="font-medium text-gray-500">Wajib</dt>
                <dd class="text-gray-900">{{ $activity->is_required ? 'Ya' : 'Tidak' }}</dd>
            </div>

            <div>
                <dt class="font-medium text-gray-500">Aktif</dt>
                <dd class="text-gray-900">{{ $activity->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
            </div>
        </dl>

        <div class="mt-6">
            <dt class="font-medium text-gray-500">Deskripsi</dt>
            <dd class="text-gray-900 whitespace-pre-line">{{ $activity->description ?? '-' }}</dd>
        </div>
    </div>
</div>
@endsection
```

---

# 39. View `mutabaah/daily/index.blade.php`

Buat:

```text
resources/views/mutabaah/daily/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Input Mutabaah Harian</h1>
        <p class="text-sm text-gray-600">Input aktivitas ibadah dan karakter harian santri.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="GET" action="{{ route('mutabaah.daily.index') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input type="date"
                   name="record_date"
                   value="{{ $recordDate }}"
                   class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kelas</label>
            <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua kelas</option>
                @foreach($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" @selected((int) $classRoomId === (int) $classRoom->id)>
                        {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-2 flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Filter
            </button>
        </div>
    </form>

    @forelse($students as $student)
        <form method="POST" action="{{ route('mutabaah.daily.store') }}" class="mb-6 bg-white rounded-xl shadow overflow-hidden">
            @csrf

            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="record_date" value="{{ $recordDate }}">

            <div class="px-4 py-3 bg-gray-50 border-b">
                <h2 class="font-semibold text-gray-900">
                    {{ $student->name ?? $student->nama_lengkap ?? 'Santri #' . $student->id }}
                </h2>
                <p class="text-xs text-gray-500">Tanggal: {{ $recordDate }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Aktivitas</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Skor</th>
                            <th class="px-4 py-3 text-left">Jumlah</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($activities as $index => $activity)
                            @php
                                $recordKey = $student->id . '-' . $activity->id;
                                $record = $existingRecords->get($recordKey);
                            @endphp

                            <tr>
                                <td class="px-4 py-3">
                                    <input type="hidden"
                                           name="records[{{ $index }}][mutabaah_activity_id]"
                                           value="{{ $activity->id }}">

                                    <div class="font-medium text-gray-900">{{ $activity->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $activity->category?->name ?? '-' }} · {{ $activity->input_type }}
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <select name="records[{{ $index }}][status]"
                                            class="rounded-lg border-gray-300">
                                        <option value="done" @selected(($record?->status ?? 'not_done') === 'done')>Selesai</option>
                                        <option value="not_done" @selected(($record?->status ?? 'not_done') === 'not_done')>Belum</option>
                                        <option value="excused" @selected(($record?->status ?? 'not_done') === 'excused')>Izin/Alasan</option>
                                    </select>
                                </td>

                                <td class="px-4 py-3">
                                    <input type="number"
                                           name="records[{{ $index }}][score]"
                                           value="{{ $record?->score }}"
                                           min="0"
                                           max="100"
                                           class="w-24 rounded-lg border-gray-300"
                                           @disabled($activity->input_type !== 'score')>
                                </td>

                                <td class="px-4 py-3">
                                    <input type="number"
                                           name="records[{{ $index }}][count_value]"
                                           value="{{ $record?->count_value }}"
                                           min="0"
                                           class="w-24 rounded-lg border-gray-300"
                                           @disabled($activity->input_type !== 'count')>
                                </td>

                                <td class="px-4 py-3">
                                    <input type="text"
                                           name="records[{{ $index }}][note]"
                                           value="{{ $record?->note }}"
                                           class="w-full rounded-lg border-gray-300"
                                           placeholder="Catatan">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 bg-gray-50 border-t text-right">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Simpan Mutabaah
                </button>
            </div>
        </form>
    @empty
        <div class="bg-white rounded-xl shadow p-6 text-center text-gray-500">
            Tidak ada santri pada filter ini.
        </div>
    @endforelse
</div>
@endsection
```

---

# 40. View `mutabaah/reports/dashboard.blade.php`

Buat:

```text
resources/views/mutabaah/reports/dashboard.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Mutabaah</h1>
        <p class="text-sm text-gray-600">Ringkasan mutabaah santri berdasarkan periode.</p>
    </div>

    <form method="GET" action="{{ route('mutabaah.reports.dashboard') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date"
                   name="start_date"
                   value="{{ $filters['start_date'] ?? $report['period']['start_date'] }}"
                   class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date"
                   name="end_date"
                   value="{{ $filters['end_date'] ?? $report['period']['end_date'] }}"
                   class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kelas</label>
            <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua kelas</option>
                @foreach($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" @selected(($filters['class_room_id'] ?? null) == $classRoom->id)>
                        {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Santri</label>
            <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua santri</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>
                        {{ $student->name ?? $student->nama_lengkap ?? 'Santri #' . $student->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Filter
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Record</div>
            <div class="text-2xl font-bold text-gray-900">{{ $report['summary']['total_records'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Selesai</div>
            <div class="text-2xl font-bold text-green-700">{{ $report['summary']['done_records'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Belum</div>
            <div class="text-2xl font-bold text-red-700">{{ $report['summary']['not_done_records'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Completion Rate</div>
            <div class="text-2xl font-bold text-blue-700">{{ $report['summary']['completion_rate'] }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b">
                <h2 class="font-semibold text-gray-900">Rekap per Santri</h2>
            </div>

            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Santri</th>
                        <th class="px-4 py-3 text-right">Selesai</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($report['by_student'] as $row)
                        <tr>
                            <td class="px-4 py-3">
                                {{ $row['student']?->name ?? $row['student']?->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">{{ $row['done'] }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['total'] }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['completion_rate'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b">
                <h2 class="font-semibold text-gray-900">Rekap per Aktivitas</h2>
            </div>

            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Aktivitas</th>
                        <th class="px-4 py-3 text-right">Selesai</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($report['by_activity'] as $row)
                        <tr>
                            <td class="px-4 py-3">
                                {{ $row['activity']?->name ?? '-' }}
                            </td>
                            <td class="px-4 py-3 text-right">{{ $row['done'] }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['total'] }}</td>
                            <td class="px-4 py-3 text-right">{{ $row['completion_rate'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

---

# 41. View `portal/parent/mutabaah.blade.php`

Buat:

```text
resources/views/portal/parent/mutabaah.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mutabaah Anak</h1>
        <p class="text-sm text-gray-600">Pantau aktivitas ibadah dan karakter anak.</p>
    </div>

    @if($students->isEmpty())
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Belum ada data anak yang terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.parent.mutabaah') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Anak</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                            {{ $student->name ?? $student->nama_lengkap ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date"
                       name="start_date"
                       value="{{ $snapshot['period']['start_date'] ?? now()->startOfWeek()->toDateString() }}"
                       class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date"
                       name="end_date"
                       value="{{ $snapshot['period']['end_date'] ?? now()->endOfWeek()->toDateString() }}"
                       class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>

        @if($snapshot)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Record</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $snapshot['summary']['total_records'] }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Selesai</div>
                    <div class="text-2xl font-bold text-green-700">{{ $snapshot['summary']['done_records'] }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Completion Rate</div>
                    <div class="text-2xl font-bold text-blue-700">{{ $snapshot['summary']['completion_rate'] }}%</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Aktivitas</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Nilai/Jumlah</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($snapshot['records'] as $record)
                            <tr>
                                <td class="px-4 py-3">{{ $record->record_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $record->activity?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($record->status === 'done')
                                        <span class="text-green-700">Selesai</span>
                                    @elseif($record->status === 'excused')
                                        <span class="text-amber-700">Izin/Alasan</span>
                                    @else
                                        <span class="text-red-700">Belum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $record->score ?? $record->count_value ?? '-' }}
                                </td>
                                <td class="px-4 py-3">{{ $record->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada mutabaah pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
@endsection
```

---

# 42. View `portal/student/mutabaah.blade.php`

Buat:

```text
resources/views/portal/student/mutabaah.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Mutabaah Saya</h1>
        <p class="text-sm text-gray-600">Lihat aktivitas ibadah dan karakter harian Anda.</p>
    </div>

    @if(! $student)
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Profil santri belum terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.student.mutabaah') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date"
                       name="start_date"
                       value="{{ $snapshot['period']['start_date'] ?? now()->startOfWeek()->toDateString() }}"
                       class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date"
                       name="end_date"
                       value="{{ $snapshot['period']['end_date'] ?? now()->endOfWeek()->toDateString() }}"
                       class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>

        @if($snapshot)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Record</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $snapshot['summary']['total_records'] }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Selesai</div>
                    <div class="text-2xl font-bold text-green-700">{{ $snapshot['summary']['done_records'] }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Completion Rate</div>
                    <div class="text-2xl font-bold text-blue-700">{{ $snapshot['summary']['completion_rate'] }}%</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Aktivitas</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Nilai/Jumlah</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($snapshot['records'] as $record)
                            <tr>
                                <td class="px-4 py-3">{{ $record->record_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $record->activity?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    @if($record->status === 'done')
                                        <span class="text-green-700">Selesai</span>
                                    @elseif($record->status === 'excused')
                                        <span class="text-amber-700">Izin/Alasan</span>
                                    @else
                                        <span class="text-red-700">Belum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $record->score ?? $record->count_value ?? '-' }}
                                </td>
                                <td class="px-4 py-3">{{ $record->note ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada mutabaah pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    @endif
</div>
@endsection
```

---

# 43. Update Routes `routes/web.php`

Buka:

```text
routes/web.php
```

Tambahkan import controller:

```php
use App\Http\Controllers\Mutabaah\MutabaahActivityController;
use App\Http\Controllers\Mutabaah\MutabaahDailyInputController;
use App\Http\Controllers\Mutabaah\MutabaahReportController;
use App\Http\Controllers\Portal\ParentMutabaahPortalController;
use App\Http\Controllers\Portal\StudentMutabaahPortalController;
```

Tambahkan route berikut di dalam group `auth` yang sudah ada.

Jika project sudah memakai middleware `role`, gunakan:

```php
Route::middleware(['auth'])->group(function (): void {
    Route::prefix('mutabaah')
        ->name('mutabaah.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal,teacher,guru,guru_tahfidz'])
        ->group(function (): void {
            Route::get('/reports/dashboard', [MutabaahReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::get('/daily', [MutabaahDailyInputController::class, 'index'])
                ->name('daily.index');

            Route::post('/daily', [MutabaahDailyInputController::class, 'store'])
                ->name('daily.store');

            Route::resource('activities', MutabaahActivityController::class);
        });

    Route::get('/portal/parent/mutabaah', [ParentMutabaahPortalController::class, 'index'])
        ->middleware(['role:parent'])
        ->name('portal.parent.mutabaah');

    Route::get('/portal/student/mutabaah', [StudentMutabaahPortalController::class, 'index'])
        ->middleware(['role:student'])
        ->name('portal.student.mutabaah');
});
```

Jika role middleware tidak menerima banyak variasi nama role, pakai role slug yang benar dari database.

Jangan hapus route lama.

---

# 44. Update Navigation

Buka layout navigation yang dipakai project.

Kemungkinan file:

```text
resources/views/layouts/navigation.blade.php
```

atau:

```text
resources/views/layouts/app.blade.php
```

Tambahkan menu internal:

```blade
@php
    $roleValue = auth()->user()?->role ?? null;
    $roleName = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;
@endphp

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal', 'teacher', 'guru', 'guru_tahfidz'], true))
    <a href="{{ route('mutabaah.reports.dashboard') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Mutabaah
    </a>
@endif

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true))
    <a href="{{ route('mutabaah.activities.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Template Mutabaah
    </a>
@endif

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'teacher', 'guru', 'guru_tahfidz'], true))
    <a href="{{ route('mutabaah.daily.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Input Mutabaah
    </a>
@endif

@if($roleName === 'parent')
    <a href="{{ route('portal.parent.mutabaah') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Mutabaah Anak
    </a>
@endif

@if($roleName === 'student')
    <a href="{{ route('portal.student.mutabaah') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Mutabaah Saya
    </a>
@endif
```

Sesuaikan class Tailwind dengan style navigation yang sudah ada.

Jangan merusak menu lama.

---

# 45. Jalankan Migration dan Seeder

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=MutabaahCategorySeeder
php artisan db:seed --class=MutabaahActivitySeeder
```

Cek via tinker:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\MutabaahCategory::count();
App\Models\MutabaahActivity::count();
App\Models\MutabaahRecord::count();
```

Target:

```text
MutabaahCategory > 0
MutabaahActivity > 0
MutabaahRecord = 0 boleh
```

---

# 46. Validasi Route

Jalankan:

```powershell
php artisan route:list --name=mutabaah
php artisan route:list --name=portal.parent.mutabaah
php artisan route:list --name=portal.student.mutabaah
```

Target route tersedia:

```text
mutabaah.activities.index
mutabaah.activities.create
mutabaah.activities.store
mutabaah.activities.show
mutabaah.activities.edit
mutabaah.activities.update
mutabaah.activities.destroy
mutabaah.daily.index
mutabaah.daily.store
mutabaah.reports.dashboard
portal.parent.mutabaah
portal.student.mutabaah
```

---

# 47. UAT Phase 11

## 47.1 Test Admin

Login sebagai admin.

Tes:

1. Buka `/mutabaah/activities`.
2. Tambah template baru.
3. Edit template.
4. Nonaktifkan template.
5. Buka `/mutabaah/daily`.
6. Filter tanggal.
7. Filter kelas.
8. Input mutabaah santri.
9. Simpan.
10. Reload halaman.
11. Pastikan data tetap tampil.
12. Buka `/mutabaah/reports/dashboard`.
13. Filter periode.
14. Pastikan summary berubah sesuai data.

Expected:

1. Tidak error 500.
2. Validasi tampil jika input salah.
3. Data tidak duplicate untuk santri + aktivitas + tanggal yang sama.
4. Record lama di-update, bukan membuat duplikasi.

---

## 47.2 Test Kepala Sekolah

Login sebagai kepala sekolah.

Tes:

1. Buka laporan mutabaah.
2. Pastikan bisa melihat report.
3. Coba buka template create.
4. Coba input daily.

Expected:

1. Bisa melihat report.
2. Tidak boleh membuat template.
3. Tidak boleh input record.

---

## 47.3 Test Guru

Login sebagai guru.

Tes:

1. Buka input mutabaah.
2. Input mutabaah santri.
3. Buka laporan mutabaah.
4. Pastikan guru tidak bisa menghapus template.
5. Pastikan guru tidak bisa mengelola template.

Expected:

1. Guru bisa input daily.
2. Guru bisa lihat laporan.
3. Guru tidak bisa CRUD template.

---

## 47.4 Test Parent

Login sebagai parent.

Tes:

1. Buka `/portal/parent/mutabaah`.
2. Pastikan hanya anak sendiri yang tampil.
3. Ubah query `student_id` ke ID anak lain.
4. Pastikan tetap tidak melihat data anak lain.
5. Pastikan parent tidak bisa membuka `/mutabaah/reports/dashboard`.

Expected:

1. Parent hanya melihat anak sendiri.
2. Tidak ada data bocor.
3. Akses internal dashboard ditolak.

---

## 47.5 Test Student

Login sebagai student.

Tes:

1. Buka `/portal/student/mutabaah`.
2. Pastikan hanya data pribadi tampil.
3. Pastikan tidak bisa buka `/mutabaah/reports/dashboard`.
4. Pastikan tidak bisa buka `/mutabaah/daily`.

Expected:

1. Student hanya melihat data sendiri.
2. Internal dashboard ditolak.
3. Daily input internal ditolak.

---

# 48. Build Frontend

Jalankan:

```powershell
npm run build
```

Jika error Tailwind/class tidak masalah secara logic, tetap fix jika build gagal.

Build wajib berhasil.

---

# 49. Validasi Akhir

Jalankan:

```powershell
php artisan migrate:status
php artisan route:list --name=mutabaah
php artisan route:list --name=portal.parent.mutabaah
php artisan route:list --name=portal.student.mutabaah
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
http://127.0.0.1:8000/mutabaah/activities
http://127.0.0.1:8000/mutabaah/daily
http://127.0.0.1:8000/mutabaah/reports/dashboard
http://127.0.0.1:8000/portal/parent/mutabaah
http://127.0.0.1:8000/portal/student/mutabaah
```

---

# 50. Dokumentasi Phase 11

Buat file:

```text
docs/phase-11-mutabaah-yaumiyah-tracker.md
```

Isi lengkap:

```md
# Phase 11 — Mutabaah Yaumiyah Tracker

## Status

Phase 11 menambahkan modul Mutabaah Yaumiyah Tracker untuk HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. Template aktivitas mutabaah.
2. Kategori mutabaah.
3. Input mutabaah harian.
4. Rekap mutabaah.
5. Dashboard mutabaah.
6. Portal mutabaah orang tua.
7. Portal mutabaah santri.

## Tabel Baru

1. `mutabaah_categories`
2. `mutabaah_activities`
3. `mutabaah_records`

## Model Baru

1. `MutabaahCategory`
2. `MutabaahActivity`
3. `MutabaahRecord`

## Controller Baru

1. `MutabaahActivityController`
2. `MutabaahDailyInputController`
3. `MutabaahReportController`
4. `ParentMutabaahPortalController`
5. `StudentMutabaahPortalController`

## Service Baru

1. `MutabaahAccessService`
2. `MutabaahRecordService`
3. `MutabaahReportService`

## Route Baru

1. `mutabaah.activities.index`
2. `mutabaah.activities.create`
3. `mutabaah.activities.store`
4. `mutabaah.activities.show`
5. `mutabaah.activities.edit`
6. `mutabaah.activities.update`
7. `mutabaah.activities.destroy`
8. `mutabaah.daily.index`
9. `mutabaah.daily.store`
10. `mutabaah.reports.dashboard`
11. `portal.parent.mutabaah`
12. `portal.student.mutabaah`

## Role Access

| Role | Akses |
|---|---|
| Super Admin | CRUD template, input, report |
| Admin | CRUD template, input, report |
| Kepala Sekolah | Report read-only |
| Guru | Input dan report terbatas |
| Parent | Portal mutabaah anak sendiri |
| Student | Portal mutabaah pribadi |

## Batasan

Phase 11 tidak membuat:

1. Attendance.
2. Tahsin.
3. Finance.
4. Cashless.
5. White-label.
6. Mobile app.
7. WhatsApp gateway.
8. Push notification.
9. Multi-tenant kompleks.

## Definition of Done

Phase 11 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. Template mutabaah tampil.
4. Admin bisa CRUD template.
5. Guru bisa input mutabaah harian.
6. Record tidak duplicate untuk santri + aktivitas + tanggal.
7. Dashboard mutabaah tampil.
8. Parent hanya bisa melihat anak sendiri.
9. Student hanya bisa melihat data sendiri.
10. Internal route tidak bisa diakses parent/student.
11. `npm run build` berhasil.
12. `php artisan app:system-health-check` berhasil.
13. Dokumentasi dibuat.
```

---

# 51. Update `docs/project-progress.md`

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
| 1 | Auth, Role, Initial Database | Done |
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
| 12 | QR Attendance System | Pending |
| 13 | Tahsin Management App | Pending |
| 14 | Student Finance Ledger | Pending |
| 15 | SchoolOS Mini | Pending |
```

---

# 52. Commit Phase 11

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add mutabaah yaumiyah tracker"
```

Jika remote tersedia:

```powershell
git push origin phase-11-mutabaah-yaumiyah-tracker
```

---

# 53. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 11 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Template Mutabaah
- Kategori Mutabaah
- Input Mutabaah Harian
- Dashboard Mutabaah
- Portal Mutabaah Orang Tua
- Portal Mutabaah Santri
- Role-based access
- Ownership-based access

Tabel dibuat:
- mutabaah_categories
- mutabaah_activities
- mutabaah_records

Route dibuat:
- mutabaah.activities.index
- mutabaah.activities.create
- mutabaah.activities.store
- mutabaah.activities.show
- mutabaah.activities.edit
- mutabaah.activities.update
- mutabaah.activities.destroy
- mutabaah.daily.index
- mutabaah.daily.store
- mutabaah.reports.dashboard
- portal.parent.mutabaah
- portal.student.mutabaah

Belum dibuat:
- Attendance
- QR attendance
- Tahsin
- Finance
- Cashless
- White-label
- Mobile app
- WhatsApp gateway
- Push notification
- Multi-tenant kompleks

Status:
- Siap lanjut Phase 12 hanya setelah UAT Phase 11 aman.
```

---

# 54. Larangan Setelah Phase 11

Agent harus berhenti setelah Phase 11 selesai.

Jangan lanjut membuat:

1. QR Attendance.
2. Tahsin Management.
3. Finance Ledger.
4. Cashless POS.
5. White-label.
6. Mobile App.
7. Multi-tenant architecture.
8. WhatsApp gateway.
9. Push notification.
10. LMS.
11. Boarding system.

Semua itu masuk phase berikutnya.

---

# 55. Keputusan Akhir

Phase 11 hanya valid jika Mutabaah Yaumiyah Tracker stabil, aman, dan tidak merusak core Tahfizh Monitoring App.

Prioritas setelah Phase 11 bukan langsung fitur baru.

Prioritas setelah Phase 11:

1. UAT Mutabaah.
2. Fix bug Mutabaah.
3. Cek akses parent/student.
4. Cek performa input harian.
5. Cek dashboard.
6. Baru pertimbangkan Phase 12 — QR Attendance System.

