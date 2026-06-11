# Phase 13 Execution Guide — Tahsin Management App

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
Phase 13 — Tahsin Management App
```

---

# 1. Keputusan Sebelum Phase 13

## 1.1 UAT Phase 12 Wajib

Sebelum menjalankan Phase 13, agent wajib memastikan Phase 12 QR Attendance sudah aman.

Jangan menambah Tahsin jika QR Attendance masih rusak.

Phase 13 hanya boleh dieksekusi jika:

1. QR token santri berhasil dibuat.
2. QR card bisa dicetak.
3. Scanner QR berjalan.
4. Scan pertama mengisi check in.
5. Scan kedua mengisi check out.
6. Scan ketiga ditolak.
7. QR rotate berjalan.
8. QR lama ditolak setelah rotate.
9. Attendance report tampil.
10. Parent hanya melihat presensi anak sendiri.
11. Student hanya melihat presensi pribadi.
12. Parent/student tidak bisa akses scanner.
13. `npm run build` berhasil.
14. `php artisan app:system-health-check` berhasil.
15. Tidak ada bug P0/P1 terbuka.

Jika masih ada error P0/P1, hentikan Phase 13 dan fix dulu.

---

## 1.2 Klasifikasi Bug Sebelum Phase 13

| Prioritas | Contoh Bug                                                                                  | Keputusan                  |
| --------- | ------------------------------------------------------------------------------------------- | -------------------------- |
| P0        | Login gagal, data bocor, parent bisa melihat anak lain, scanner bisa diakses parent/student | Wajib fix sebelum Phase 13 |
| P1        | QR scan gagal, report attendance salah, presensi dobel, QR rotate rusak                     | Wajib fix sebelum Phase 13 |
| P2        | UI kurang rapi, wording kurang jelas                                                        | Boleh dicatat              |
| P3        | Enhancement kosmetik                                                                        | Boleh ditunda              |

---

# 2. Tujuan Phase 13

Phase 13 bertujuan membuat modul **Tahsin Management App**.

Modul ini digunakan untuk:

1. Mengelola level tahsin.
2. Mengelola kompetensi / skill tahsin.
3. Menghubungkan santri dengan profil tahsin.
4. Mencatat asesmen tahsin.
5. Menilai skill bacaan santri secara manual.
6. Melihat progress tahsin per santri.
7. Membuat laporan tahsin untuk guru, admin, dan kepala sekolah.
8. Menampilkan progress tahsin ke orang tua.
9. Menampilkan progress tahsin ke santri.
10. Menjaga role-based access.
11. Menjaga ownership-based access.
12. Dokumentasi Phase 13.

---

# 3. Batasan Phase 13

AI agent tidak boleh membuat fitur berikut pada Phase 13:

1. AI voice correction.
2. Voice recognition.
3. Speech-to-text.
4. Audio upload.
5. Video learning.
6. LMS penuh.
7. Native Android.
8. Native iOS.
9. Mobile app.
10. Finance ledger.
11. Cashless kantin.
12. Payment gateway.
13. Wallet.
14. White-label.
15. Multi-tenant kompleks.
16. WhatsApp gateway.
17. Push notification.
18. Firebase.
19. Websocket.
20. Boarding school system.
21. Marketplace konten.
22. Sertifikat otomatis.
23. Ujian online kompleks.
24. Machine learning tajwid.

Phase 13 hanya membuat **Tahsin Management App berbasis input manual guru**.

---

# 4. Konsep Tahsin Management

## 4.1 Apa Itu Tahsin di Sistem Ini

Tahsin adalah modul pembinaan kualitas bacaan Al-Qur’an santri.

Fokus penilaian awal:

1. Makharijul huruf.
2. Sifat huruf.
3. Mad.
4. Ghunnah.
5. Hukum nun mati dan tanwin.
6. Hukum mim mati.
7. Qalqalah.
8. Waqaf dan ibtida.
9. Kelancaran bacaan.
10. Adab membaca Al-Qur’an.

---

## 4.2 Level Tahsin

Contoh level:

| Level           | Keterangan                                    |
| --------------- | --------------------------------------------- |
| Tahsin Dasar    | Perbaikan huruf, makhraj, dan kelancaran awal |
| Tahsin Menengah | Tajwid dasar, mad, ghunnah, hukum nun/mim     |
| Tahsin Lanjutan | Waqaf ibtida, kelancaran, tartil, konsistensi |
| Siap Tahfizh    | Bacaan sudah cukup stabil untuk fokus hafalan |

---

## 4.3 Tipe Asesmen

Phase 13 mendukung tipe asesmen:

| Tipe        | Keterangan                |
| ----------- | ------------------------- |
| `placement` | Tes awal penempatan level |
| `daily`     | Penilaian harian          |
| `weekly`    | Penilaian mingguan        |
| `monthly`   | Penilaian bulanan         |
| `final`     | Ujian akhir level         |

---

## 4.4 Status Profil Tahsin

| Status            | Makna                  |
| ----------------- | ---------------------- |
| `not_started`     | Belum mulai            |
| `in_progress`     | Sedang berjalan        |
| `passed`          | Lulus level            |
| `needs_attention` | Butuh perhatian khusus |

---

## 4.5 Status Skill

| Status       | Makna       |
| ------------ | ----------- |
| `mastered`   | Sudah baik  |
| `progress`   | Berkembang  |
| `weak`       | Lemah       |
| `not_tested` | Belum dites |

---

# 5. Target Output Phase 13

Setelah Phase 13 selesai, aplikasi harus punya:

1. Menu **Tahsin**.
2. Menu **Level Tahsin**.
3. Menu **Skill Tahsin**.
4. Menu **Profil Tahsin Santri**.
5. Menu **Asesmen Tahsin**.
6. Menu **Laporan Tahsin**.
7. Tabel:

   * `tahsin_levels`
   * `tahsin_skills`
   * `tahsin_student_profiles`
   * `tahsin_assessments`
   * `tahsin_assessment_items`
8. Model:

   * `TahsinLevel`
   * `TahsinSkill`
   * `TahsinStudentProfile`
   * `TahsinAssessment`
   * `TahsinAssessmentItem`
9. Controller:

   * `TahsinLevelController`
   * `TahsinSkillController`
   * `TahsinStudentProfileController`
   * `TahsinAssessmentController`
   * `TahsinReportController`
   * `ParentTahsinPortalController`
   * `StudentTahsinPortalController`
10. Request:

* `StoreTahsinLevelRequest`
* `UpdateTahsinLevelRequest`
* `StoreTahsinSkillRequest`
* `UpdateTahsinSkillRequest`
* `StoreTahsinStudentProfileRequest`
* `UpdateTahsinStudentProfileRequest`
* `StoreTahsinAssessmentRequest`
* `TahsinReportFilterRequest`

11. Service:

* `TahsinAccessService`
* `TahsinAssessmentService`
* `TahsinReportService`

12. Seeder:

* `TahsinLevelSeeder`
* `TahsinSkillSeeder`

13. View:

* level index/create/edit/show
* skill index/create/edit/show
* profile index/edit/show
* assessment index/create/show
* report dashboard
* parent tahsin portal
* student tahsin portal

14. Dokumentasi Phase 13.
15. Update `docs/project-progress.md`.

---

# 6. Role Access Phase 13

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

| Role           | Level/Skill | Profil Santri | Asesmen                            | Report         | Portal |
| -------------- | ----------- | ------------- | ---------------------------------- | -------------- | ------ |
| Super Admin    | CRUD        | CRUD          | CRUD                               | Semua          | Tidak  |
| Admin          | CRUD        | CRUD          | CRUD                               | Semua          | Tidak  |
| Kepala Sekolah | Read-only   | Read-only     | Read-only                          | Semua          | Tidak  |
| Teacher/Guru   | Read-only   | Read-only     | Create/Update asesmen santri scope | Santri terkait | Tidak  |
| Parent         | Tidak       | Tidak         | Tidak                              | Anak sendiri   | Ya     |
| Student        | Tidak       | Tidak         | Tidak                              | Data sendiri   | Ya     |

Aturan keras:

1. Parent tidak boleh melihat tahsin anak lain.
2. Student tidak boleh melihat data tahsin santri lain.
3. Parent/student tidak boleh input asesmen.
4. Parent/student tidak boleh CRUD level/skill.
5. Teacher tidak boleh CRUD level/skill.
6. Teacher hanya boleh input asesmen santri dalam scope-nya.
7. Kepala sekolah hanya monitoring, bukan input utama.
8. Finance, cashless, white-label, LMS, dan mobile app tidak boleh disentuh.

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
14. Phase 12 selesai dan UAT aman.
15. Tabel berikut sudah ada:

* `users`
* `roles`
* `schools`
* `class_rooms`
* `students`
* `parent_profiles`
* `parent_student`
* `teacher_profiles`

16. Working tree bersih atau semua perubahan diketahui.

Jika Phase 12 belum aman, hentikan Phase 13.

---

# 8. Buat Branch Git Phase 13

Jalankan:

```powershell
git checkout -b phase-13-tahsin-management-app
```

Jika branch sudah ada:

```powershell
git checkout phase-13-tahsin-management-app
```

---

# 9. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Tahsin/
│   │   │   ├── TahsinLevelController.php
│   │   │   ├── TahsinSkillController.php
│   │   │   ├── TahsinStudentProfileController.php
│   │   │   ├── TahsinAssessmentController.php
│   │   │   └── TahsinReportController.php
│   │   └── Portal/
│   │       ├── ParentTahsinPortalController.php
│   │       └── StudentTahsinPortalController.php
│   └── Requests/
│       └── Tahsin/
│           ├── StoreTahsinLevelRequest.php
│           ├── UpdateTahsinLevelRequest.php
│           ├── StoreTahsinSkillRequest.php
│           ├── UpdateTahsinSkillRequest.php
│           ├── StoreTahsinStudentProfileRequest.php
│           ├── UpdateTahsinStudentProfileRequest.php
│           ├── StoreTahsinAssessmentRequest.php
│           └── TahsinReportFilterRequest.php
├── Models/
│   ├── TahsinLevel.php
│   ├── TahsinSkill.php
│   ├── TahsinStudentProfile.php
│   ├── TahsinAssessment.php
│   └── TahsinAssessmentItem.php
└── Services/
    └── Tahsin/
        ├── TahsinAccessService.php
        ├── TahsinAssessmentService.php
        └── TahsinReportService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_tahsin_levels_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_tahsin_skills_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_tahsin_student_profiles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_tahsin_assessments_table.php
│   └── xxxx_xx_xx_xxxxxx_create_tahsin_assessment_items_table.php
└── seeders/
    ├── TahsinLevelSeeder.php
    └── TahsinSkillSeeder.php

resources/
└── views/
    ├── tahsin/
    │   ├── levels/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── skills/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── profiles/
    │   │   ├── index.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── assessments/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   └── show.blade.php
    │   └── reports/
    │       └── dashboard.blade.php
    └── portal/
        ├── parent/
        │   └── tahsin.blade.php
        └── student/
            └── tahsin.blade.php

routes/
└── web.php

docs/
├── phase-13-execution.md
└── phase-13-tahsin-management-app.md
```

---

# 10. Buat Model, Migration, Seeder, Controller, Request

Jalankan:

```powershell
php artisan make:model TahsinLevel -m
php artisan make:model TahsinSkill -m
php artisan make:model TahsinStudentProfile -m
php artisan make:model TahsinAssessment -m
php artisan make:model TahsinAssessmentItem -m

php artisan make:seeder TahsinLevelSeeder
php artisan make:seeder TahsinSkillSeeder

php artisan make:controller Tahsin/TahsinLevelController --resource
php artisan make:controller Tahsin/TahsinSkillController --resource
php artisan make:controller Tahsin/TahsinStudentProfileController
php artisan make:controller Tahsin/TahsinAssessmentController
php artisan make:controller Tahsin/TahsinReportController
php artisan make:controller Portal/ParentTahsinPortalController
php artisan make:controller Portal/StudentTahsinPortalController

php artisan make:request Tahsin/StoreTahsinLevelRequest
php artisan make:request Tahsin/UpdateTahsinLevelRequest
php artisan make:request Tahsin/StoreTahsinSkillRequest
php artisan make:request Tahsin/UpdateTahsinSkillRequest
php artisan make:request Tahsin/StoreTahsinStudentProfileRequest
php artisan make:request Tahsin/UpdateTahsinStudentProfileRequest
php artisan make:request Tahsin/StoreTahsinAssessmentRequest
php artisan make:request Tahsin/TahsinReportFilterRequest
```

Buat folder service:

```powershell
mkdir app\Services\Tahsin
```

Buat file service:

```powershell
New-Item app\Services\Tahsin\TahsinAccessService.php
New-Item app\Services\Tahsin\TahsinAssessmentService.php
New-Item app\Services\Tahsin\TahsinReportService.php
```

Buat folder view:

```powershell
mkdir resources\views\tahsin
mkdir resources\views\tahsin\levels
mkdir resources\views\tahsin\skills
mkdir resources\views\tahsin\profiles
mkdir resources\views\tahsin\assessments
mkdir resources\views\tahsin\reports
```

Jika folder portal sudah ada, jangan hapus.

---

# 11. Migration `create_tahsin_levels_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tahsin_levels_table.php
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
        Schema::create('tahsin_levels', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('minimum_score')->default(70);
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
        Schema::dropIfExists('tahsin_levels');
    }
};
```

---

# 12. Migration `create_tahsin_skills_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tahsin_skills_table.php
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
        Schema::create('tahsin_skills', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('tahsin_level_id')
                ->nullable()
                ->constrained('tahsin_levels')
                ->nullOnDelete();

            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();

            $table->unsignedSmallInteger('maximum_score')->default(100);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'is_active']);
            $table->index(['tahsin_level_id', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_skills');
    }
};
```

---

# 13. Migration `create_tahsin_student_profiles_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tahsin_student_profiles_table.php
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
        Schema::create('tahsin_student_profiles', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('current_tahsin_level_id')
                ->nullable()
                ->constrained('tahsin_levels')
                ->nullOnDelete();

            $table->foreignId('assigned_teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('status', [
                'not_started',
                'in_progress',
                'passed',
                'needs_attention',
            ])->default('not_started');

            $table->decimal('placement_score', 5, 2)->nullable();
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique('student_id');
            $table->index(['school_id', 'status']);
            $table->index('current_tahsin_level_id');
            $table->index('assigned_teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_student_profiles');
    }
};
```

---

# 14. Migration `create_tahsin_assessments_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tahsin_assessments_table.php
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
        Schema::create('tahsin_assessments', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('tahsin_level_id')
                ->nullable()
                ->constrained('tahsin_levels')
                ->nullOnDelete();

            $table->date('assessment_date');

            $table->enum('assessment_type', [
                'placement',
                'daily',
                'weekly',
                'monthly',
                'final',
            ])->default('daily');

            $table->decimal('overall_score', 5, 2)->default(0);

            $table->enum('grade', [
                'excellent',
                'good',
                'fair',
                'needs_improvement',
            ])->default('needs_improvement');

            $table->enum('status', [
                'draft',
                'submitted',
                'reviewed',
            ])->default('submitted');

            $table->text('note')->nullable();
            $table->text('recommendation')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'assessment_date']);
            $table->index(['student_id', 'assessment_date']);
            $table->index(['teacher_id', 'assessment_date']);
            $table->index(['tahsin_level_id', 'assessment_date']);
            $table->index('assessment_type');
            $table->index('grade');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_assessments');
    }
};
```

---

# 15. Migration `create_tahsin_assessment_items_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tahsin_assessment_items_table.php
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
        Schema::create('tahsin_assessment_items', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('tahsin_assessment_id')
                ->constrained('tahsin_assessments')
                ->cascadeOnDelete();

            $table->foreignId('tahsin_skill_id')
                ->constrained('tahsin_skills')
                ->restrictOnDelete();

            $table->decimal('score', 5, 2)->default(0);

            $table->enum('status', [
                'mastered',
                'progress',
                'weak',
                'not_tested',
            ])->default('not_tested');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique(
                ['tahsin_assessment_id', 'tahsin_skill_id'],
                'tahsin_assessment_skill_unique'
            );

            $table->index('tahsin_skill_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahsin_assessment_items');
    }
};
```

---

# 16. Model `TahsinLevel`

Buka:

```text
app/Models/TahsinLevel.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahsinLevel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'slug',
        'description',
        'minimum_score',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'minimum_score' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function skills(): HasMany
    {
        return $this->hasMany(TahsinSkill::class);
    }

    public function studentProfiles(): HasMany
    {
        return $this->hasMany(TahsinStudentProfile::class, 'current_tahsin_level_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(TahsinAssessment::class);
    }
}
```

---

# 17. Model `TahsinSkill`

Buka:

```text
app/Models/TahsinSkill.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahsinSkill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'tahsin_level_id',
        'name',
        'code',
        'description',
        'maximum_score',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'maximum_score' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function level(): BelongsTo
    {
        return $this->belongsTo(TahsinLevel::class, 'tahsin_level_id');
    }

    public function assessmentItems(): HasMany
    {
        return $this->hasMany(TahsinAssessmentItem::class);
    }
}
```

---

# 18. Model `TahsinStudentProfile`

Buka:

```text
app/Models/TahsinStudentProfile.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahsinStudentProfile extends Model
{
    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_PASSED = 'passed';
    public const STATUS_NEEDS_ATTENTION = 'needs_attention';

    protected $fillable = [
        'school_id',
        'student_id',
        'current_tahsin_level_id',
        'assigned_teacher_id',
        'status',
        'placement_score',
        'started_at',
        'completed_at',
        'note',
    ];

    protected $casts = [
        'placement_score' => 'decimal:2',
        'started_at' => 'date',
        'completed_at' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function currentLevel(): BelongsTo
    {
        return $this->belongsTo(TahsinLevel::class, 'current_tahsin_level_id');
    }

    public function assignedTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }
}
```

---

# 19. Model `TahsinAssessment`

Buka:

```text
app/Models/TahsinAssessment.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahsinAssessment extends Model
{
    use SoftDeletes;

    public const TYPE_PLACEMENT = 'placement';
    public const TYPE_DAILY = 'daily';
    public const TYPE_WEEKLY = 'weekly';
    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_FINAL = 'final';

    public const GRADE_EXCELLENT = 'excellent';
    public const GRADE_GOOD = 'good';
    public const GRADE_FAIR = 'fair';
    public const GRADE_NEEDS_IMPROVEMENT = 'needs_improvement';

    protected $fillable = [
        'school_id',
        'student_id',
        'teacher_id',
        'tahsin_level_id',
        'assessment_date',
        'assessment_type',
        'overall_score',
        'grade',
        'status',
        'note',
        'recommendation',
        'created_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'overall_score' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(TahsinLevel::class, 'tahsin_level_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TahsinAssessmentItem::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

---

# 20. Model `TahsinAssessmentItem`

Buka:

```text
app/Models/TahsinAssessmentItem.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahsinAssessmentItem extends Model
{
    public const STATUS_MASTERED = 'mastered';
    public const STATUS_PROGRESS = 'progress';
    public const STATUS_WEAK = 'weak';
    public const STATUS_NOT_TESTED = 'not_tested';

    protected $fillable = [
        'tahsin_assessment_id',
        'tahsin_skill_id',
        'score',
        'status',
        'note',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(TahsinAssessment::class, 'tahsin_assessment_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(TahsinSkill::class, 'tahsin_skill_id');
    }
}
```

---

# 21. Update Model `Student`

Buka:

```text
app/Models/Student.php
```

Tambahkan relasi berikut tanpa menghapus relasi lama:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

public function tahsinProfile(): HasOne
{
    return $this->hasOne(TahsinStudentProfile::class);
}

public function tahsinAssessments(): HasMany
{
    return $this->hasMany(TahsinAssessment::class);
}
```

Jika relasi sudah ada, jangan duplikasi.

---

# 22. Seeder `TahsinLevelSeeder`

Buka:

```text
database/seeders/TahsinLevelSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\TahsinLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TahsinLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Tahsin Dasar',
                'description' => 'Perbaikan makhraj, huruf, dan kelancaran awal.',
                'minimum_score' => 70,
                'sort_order' => 10,
            ],
            [
                'name' => 'Tahsin Menengah',
                'description' => 'Penguatan tajwid dasar, mad, ghunnah, nun mati, dan mim mati.',
                'minimum_score' => 75,
                'sort_order' => 20,
            ],
            [
                'name' => 'Tahsin Lanjutan',
                'description' => 'Penguatan tartil, waqaf ibtida, dan konsistensi bacaan.',
                'minimum_score' => 80,
                'sort_order' => 30,
            ],
            [
                'name' => 'Siap Tahfizh',
                'description' => 'Bacaan sudah cukup stabil untuk fokus hafalan.',
                'minimum_score' => 85,
                'sort_order' => 40,
            ],
        ];

        foreach ($levels as $level) {
            TahsinLevel::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'slug' => Str::slug($level['name']),
                ],
                [
                    'name' => $level['name'],
                    'description' => $level['description'],
                    'minimum_score' => $level['minimum_score'],
                    'sort_order' => $level['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
```

---

# 23. Seeder `TahsinSkillSeeder`

Buka:

```text
database/seeders/TahsinSkillSeeder.php
```

Isi lengkap:

```php
<?php

namespace Database\Seeders;

use App\Models\TahsinLevel;
use App\Models\TahsinSkill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TahsinSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelMap = TahsinLevel::query()
            ->whereNull('school_id')
            ->pluck('id', 'name');

        $skills = [
            [
                'level' => 'Tahsin Dasar',
                'name' => 'Makharijul Huruf',
                'code' => 'MAKHRAJ',
                'description' => 'Ketepatan tempat keluarnya huruf.',
                'sort_order' => 10,
            ],
            [
                'level' => 'Tahsin Dasar',
                'name' => 'Sifat Huruf',
                'code' => 'SIFAT',
                'description' => 'Ketepatan sifat huruf ketika dibaca.',
                'sort_order' => 20,
            ],
            [
                'level' => 'Tahsin Dasar',
                'name' => 'Kelancaran Bacaan',
                'code' => 'FLUENCY',
                'description' => 'Kelancaran membaca tanpa banyak berhenti salah.',
                'sort_order' => 30,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Mad',
                'code' => 'MAD',
                'description' => 'Ketepatan panjang pendek bacaan.',
                'sort_order' => 40,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Ghunnah',
                'code' => 'GHUNNAH',
                'description' => 'Ketepatan dengung pada bacaan.',
                'sort_order' => 50,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Hukum Nun Mati dan Tanwin',
                'code' => 'NUN_TANWIN',
                'description' => 'Ketepatan idzhar, idgham, iqlab, dan ikhfa.',
                'sort_order' => 60,
            ],
            [
                'level' => 'Tahsin Menengah',
                'name' => 'Hukum Mim Mati',
                'code' => 'MIM_MATI',
                'description' => 'Ketepatan hukum mim mati.',
                'sort_order' => 70,
            ],
            [
                'level' => 'Tahsin Lanjutan',
                'name' => 'Qalqalah',
                'code' => 'QALQALAH',
                'description' => 'Ketepatan pantulan qalqalah.',
                'sort_order' => 80,
            ],
            [
                'level' => 'Tahsin Lanjutan',
                'name' => 'Waqaf dan Ibtida',
                'code' => 'WAQAF_IBTIDA',
                'description' => 'Ketepatan berhenti dan memulai bacaan.',
                'sort_order' => 90,
            ],
            [
                'level' => 'Siap Tahfizh',
                'name' => 'Tartil dan Konsistensi',
                'code' => 'TARTIL',
                'description' => 'Konsistensi bacaan tartil untuk tahfizh.',
                'sort_order' => 100,
            ],
        ];

        foreach ($skills as $skill) {
            TahsinSkill::query()->updateOrCreate(
                [
                    'school_id' => null,
                    'code' => $skill['code'],
                ],
                [
                    'tahsin_level_id' => $levelMap[$skill['level']] ?? null,
                    'name' => $skill['name'],
                    'description' => $skill['description'],
                    'maximum_score' => 100,
                    'sort_order' => $skill['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
```

---

# 24. Update `DatabaseSeeder`

Buka:

```text
database/seeders/DatabaseSeeder.php
```

Tambahkan tanpa menghapus seeder lama:

```php
$this->call([
    TahsinLevelSeeder::class,
    TahsinSkillSeeder::class,
]);
```

Jika `DatabaseSeeder` sudah punya banyak seeder, tambahkan dua seeder Tahsin di bagian bawah.

---

# 25. Service `TahsinAccessService`

Buka:

```text
app/Services/Tahsin/TahsinAccessService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Tahsin;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class TahsinAccessService
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

    public function canManageMaster(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canManageProfile(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canCreateAssessment(?User $user): bool
    {
        return $this->isAdmin($user) || $this->isTeacher($user);
    }

    public function canViewInternalReport(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isPrincipal($user)
            || $this->isTeacher($user);
    }

    public function applyStudentScope(Builder $query, User $user): Builder
    {
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return $query;
        }

        if ($this->isTeacher($user)) {
            $teacherProfile = $user->teacherProfile ?? null;

            if ($teacherProfile && isset($teacherProfile->class_room_id)) {
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
        if ($this->isAdmin($user) || $this->isPrincipal($user)) {
            return true;
        }

        $query = Student::query()->whereKey($student->id);

        return $this->applyStudentScope($query, $user)->exists();
    }
}
```

---

# 26. Service `TahsinAssessmentService`

Buka:

```text
app/Services/Tahsin/TahsinAssessmentService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Tahsin;

use App\Models\Student;
use App\Models\TahsinAssessment;
use App\Models\TahsinAssessmentItem;
use App\Models\TahsinSkill;
use App\Models\TahsinStudentProfile;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TahsinAssessmentService
{
    public function createAssessment(array $data, User $user): TahsinAssessment
    {
        return DB::transaction(function () use ($data, $user): TahsinAssessment {
            $student = Student::query()->findOrFail($data['student_id']);

            $items = collect($data['items'] ?? []);

            if ($items->isEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'Minimal satu skill tahsin harus dinilai.',
                ]);
            }

            $overallScore = $this->calculateOverallScore($items);
            $grade = $this->resolveGrade($overallScore);

            $assessment = TahsinAssessment::query()->create([
                'school_id' => $student->school_id ?? null,
                'student_id' => $student->id,
                'teacher_id' => $data['teacher_id'] ?? $user->id,
                'tahsin_level_id' => $data['tahsin_level_id'] ?? null,
                'assessment_date' => $data['assessment_date'],
                'assessment_type' => $data['assessment_type'],
                'overall_score' => $overallScore,
                'grade' => $grade,
                'status' => $data['status'] ?? 'submitted',
                'note' => $data['note'] ?? null,
                'recommendation' => $data['recommendation'] ?? null,
                'created_by' => $user->id,
            ]);

            foreach ($items as $item) {
                $skill = TahsinSkill::query()
                    ->whereKey($item['tahsin_skill_id'] ?? null)
                    ->where('is_active', true)
                    ->first();

                if (! $skill) {
                    throw ValidationException::withMessages([
                        'items' => 'Skill tahsin tidak ditemukan atau tidak aktif.',
                    ]);
                }

                TahsinAssessmentItem::query()->create([
                    'tahsin_assessment_id' => $assessment->id,
                    'tahsin_skill_id' => $skill->id,
                    'score' => $item['score'] ?? 0,
                    'status' => $item['status'] ?? 'not_tested',
                    'note' => $item['note'] ?? null,
                ]);
            }

            $this->syncStudentProfile($student, $assessment);

            return $assessment->fresh(['student', 'teacher', 'level', 'items.skill']);
        });
    }

    private function calculateOverallScore(Collection $items): float
    {
        $scores = $items
            ->pluck('score')
            ->filter(fn ($score): bool => $score !== null)
            ->map(fn ($score): float => (float) $score);

        if ($scores->isEmpty()) {
            return 0;
        }

        return round($scores->avg(), 2);
    }

    private function resolveGrade(float $overallScore): string
    {
        if ($overallScore >= 90) {
            return TahsinAssessment::GRADE_EXCELLENT;
        }

        if ($overallScore >= 80) {
            return TahsinAssessment::GRADE_GOOD;
        }

        if ($overallScore >= 70) {
            return TahsinAssessment::GRADE_FAIR;
        }

        return TahsinAssessment::GRADE_NEEDS_IMPROVEMENT;
    }

    private function syncStudentProfile(Student $student, TahsinAssessment $assessment): void
    {
        $status = $assessment->overall_score >= 70
            ? TahsinStudentProfile::STATUS_IN_PROGRESS
            : TahsinStudentProfile::STATUS_NEEDS_ATTENTION;

        $profile = TahsinStudentProfile::query()->firstOrNew([
            'student_id' => $student->id,
        ]);

        $profile->fill([
            'school_id' => $student->school_id ?? null,
            'current_tahsin_level_id' => $assessment->tahsin_level_id,
            'assigned_teacher_id' => $assessment->teacher_id,
            'status' => $status,
            'placement_score' => $assessment->assessment_type === TahsinAssessment::TYPE_PLACEMENT
                ? $assessment->overall_score
                : $profile->placement_score,
            'started_at' => $profile->started_at ?? now()->toDateString(),
        ]);

        $profile->save();
    }
}
```

---

# 27. Service `TahsinReportService`

Buka:

```text
app/Services/Tahsin/TahsinReportService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Tahsin;

use App\Models\Student;
use App\Models\TahsinAssessment;
use App\Models\TahsinStudentProfile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TahsinReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $assessmentQuery = TahsinAssessment::query()
            ->with(['student.classRoom', 'teacher', 'level'])
            ->whereBetween('assessment_date', [$startDate, $endDate]);

        if (! empty($filters['class_room_id'])) {
            $assessmentQuery->whereHas('student', function (Builder $query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $assessmentQuery->where('student_id', $filters['student_id']);
        }

        if (! empty($filters['teacher_id'])) {
            $assessmentQuery->where('teacher_id', $filters['teacher_id']);
        }

        if (! empty($filters['tahsin_level_id'])) {
            $assessmentQuery->where('tahsin_level_id', $filters['tahsin_level_id']);
        }

        $assessments = $assessmentQuery->get();

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_assessments' => $assessments->count(),
                'average_score' => $assessments->count() > 0
                    ? round($assessments->avg('overall_score'), 2)
                    : 0,
                'excellent' => $assessments->where('grade', 'excellent')->count(),
                'good' => $assessments->where('grade', 'good')->count(),
                'fair' => $assessments->where('grade', 'fair')->count(),
                'needs_improvement' => $assessments->where('grade', 'needs_improvement')->count(),
            ],
            'by_student' => $this->groupByStudent($assessments),
            'assessments' => $assessments->sortByDesc('assessment_date')->values(),
        ];
    }

    public function studentSnapshot(Student $student, array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $profile = TahsinStudentProfile::query()
            ->with(['currentLevel', 'assignedTeacher'])
            ->where('student_id', $student->id)
            ->first();

        $assessments = TahsinAssessment::query()
            ->with(['level', 'teacher', 'items.skill'])
            ->where('student_id', $student->id)
            ->whereBetween('assessment_date', [$startDate, $endDate])
            ->orderByDesc('assessment_date')
            ->get();

        return [
            'student' => $student,
            'profile' => $profile,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_assessments' => $assessments->count(),
                'average_score' => $assessments->count() > 0
                    ? round($assessments->avg('overall_score'), 2)
                    : 0,
                'latest_score' => $assessments->first()?->overall_score,
                'latest_grade' => $assessments->first()?->grade,
            ],
            'assessments' => $assessments,
        ];
    }

    private function groupByStudent(Collection $assessments): Collection
    {
        return $assessments
            ->groupBy('student_id')
            ->map(function (Collection $studentAssessments): array {
                $first = $studentAssessments->first();

                return [
                    'student' => $first?->student,
                    'total' => $studentAssessments->count(),
                    'average_score' => $studentAssessments->count() > 0
                        ? round($studentAssessments->avg('overall_score'), 2)
                        : 0,
                    'latest_assessment' => $studentAssessments->sortByDesc('assessment_date')->first(),
                ];
            })
            ->sortByDesc('average_score')
            ->values();
    }
}
```

---

# 28. Request `StoreTahsinLevelRequest`

Buka:

```text
app/Http/Requests/Tahsin/StoreTahsinLevelRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTahsinLevelRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'minimum_score' => ['required', 'integer', 'min:0', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

# 29. Request `UpdateTahsinLevelRequest`

Buka:

```text
app/Http/Requests/Tahsin/UpdateTahsinLevelRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

class UpdateTahsinLevelRequest extends StoreTahsinLevelRequest
{
    //
}
```

---

# 30. Request `StoreTahsinSkillRequest`

Buka:

```text
app/Http/Requests/Tahsin/StoreTahsinSkillRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTahsinSkillRequest extends FormRequest
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
            'tahsin_level_id' => ['nullable', 'exists:tahsin_levels,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'maximum_score' => ['required', 'integer', 'min:1', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
```

---

# 31. Request `UpdateTahsinSkillRequest`

Buka:

```text
app/Http/Requests/Tahsin/UpdateTahsinSkillRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

class UpdateTahsinSkillRequest extends StoreTahsinSkillRequest
{
    //
}
```

---

# 32. Request `StoreTahsinStudentProfileRequest`

Buka:

```text
app/Http/Requests/Tahsin/StoreTahsinStudentProfileRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTahsinStudentProfileRequest extends FormRequest
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
            'student_id' => ['required', 'exists:students,id'],
            'current_tahsin_level_id' => ['nullable', 'exists:tahsin_levels,id'],
            'assigned_teacher_id' => ['nullable', 'exists:users,id'],
            'status' => [
                'required',
                Rule::in(['not_started', 'in_progress', 'passed', 'needs_attention']),
            ],
            'placement_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
```

---

# 33. Request `UpdateTahsinStudentProfileRequest`

Buka:

```text
app/Http/Requests/Tahsin/UpdateTahsinStudentProfileRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

class UpdateTahsinStudentProfileRequest extends StoreTahsinStudentProfileRequest
{
    //
}
```

---

# 34. Request `StoreTahsinAssessmentRequest`

Buka:

```text
app/Http/Requests/Tahsin/StoreTahsinAssessmentRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTahsinAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
            'teacher',
            'guru',
            'guru_tahfidz',
        ], true);
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'tahsin_level_id' => ['nullable', 'exists:tahsin_levels,id'],
            'assessment_date' => ['required', 'date'],
            'assessment_type' => [
                'required',
                Rule::in(['placement', 'daily', 'weekly', 'monthly', 'final']),
            ],
            'status' => [
                'nullable',
                Rule::in(['draft', 'submitted', 'reviewed']),
            ],
            'note' => ['nullable', 'string', 'max:3000'],
            'recommendation' => ['nullable', 'string', 'max:3000'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.tahsin_skill_id' => ['required', 'exists:tahsin_skills,id'],
            'items.*.score' => ['required', 'numeric', 'min:0', 'max:100'],
            'items.*.status' => [
                'required',
                Rule::in(['mastered', 'progress', 'weak', 'not_tested']),
            ],
            'items.*.note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
```

---

# 35. Request `TahsinReportFilterRequest`

Buka:

```text
app/Http/Requests/Tahsin/TahsinReportFilterRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahsin;

use Illuminate\Foundation\Http\FormRequest;

class TahsinReportFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
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
            'teacher_id' => ['nullable', 'exists:users,id'],
            'tahsin_level_id' => ['nullable', 'exists:tahsin_levels,id'],
        ];
    }
}
```

---

# 36. Controller `TahsinLevelController`

Buka:

```text
app/Http/Controllers/Tahsin/TahsinLevelController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\StoreTahsinLevelRequest;
use App\Http\Requests\Tahsin\UpdateTahsinLevelRequest;
use App\Models\TahsinLevel;
use App\Services\Tahsin\TahsinAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TahsinLevelController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $levels = TahsinLevel::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view('tahsin.levels.index', compact('levels'));
    }

    public function create(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        return view('tahsin.levels.create');
    }

    public function store(StoreTahsinLevelRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        TahsinLevel::query()->create($data);

        return redirect()
            ->route('tahsin.levels.index')
            ->with('success', 'Level tahsin berhasil dibuat.');
    }

    public function show(Request $request, TahsinLevel $level, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $level->load('skills');

        return view('tahsin.levels.show', compact('level'));
    }

    public function edit(Request $request, TahsinLevel $level, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        return view('tahsin.levels.edit', compact('level'));
    }

    public function update(UpdateTahsinLevelRequest $request, TahsinLevel $level): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $level->update($data);

        return redirect()
            ->route('tahsin.levels.index')
            ->with('success', 'Level tahsin berhasil diperbarui.');
    }

    public function destroy(Request $request, TahsinLevel $level, TahsinAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $level->delete();

        return redirect()
            ->route('tahsin.levels.index')
            ->with('success', 'Level tahsin berhasil dihapus.');
    }
}
```

---

# 37. Controller `TahsinSkillController`

Buka:

```text
app/Http/Controllers/Tahsin/TahsinSkillController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\StoreTahsinSkillRequest;
use App\Http\Requests\Tahsin\UpdateTahsinSkillRequest;
use App\Models\TahsinLevel;
use App\Models\TahsinSkill;
use App\Services\Tahsin\TahsinAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahsinSkillController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $skills = TahsinSkill::query()
            ->with('level')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(30);

        return view('tahsin.skills.index', compact('skills'));
    }

    public function create(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tahsin.skills.create', compact('levels'));
    }

    public function store(StoreTahsinSkillRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        TahsinSkill::query()->create($data);

        return redirect()
            ->route('tahsin.skills.index')
            ->with('success', 'Skill tahsin berhasil dibuat.');
    }

    public function show(Request $request, TahsinSkill $skill, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $skill->load('level');

        return view('tahsin.skills.show', compact('skill'));
    }

    public function edit(Request $request, TahsinSkill $skill, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tahsin.skills.edit', compact('skill', 'levels'));
    }

    public function update(UpdateTahsinSkillRequest $request, TahsinSkill $skill): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active', true);

        $skill->update($data);

        return redirect()
            ->route('tahsin.skills.index')
            ->with('success', 'Skill tahsin berhasil diperbarui.');
    }

    public function destroy(Request $request, TahsinSkill $skill, TahsinAccessService $accessService): RedirectResponse
    {
        abort_unless($accessService->canManageMaster($request->user()), 403);

        $skill->delete();

        return redirect()
            ->route('tahsin.skills.index')
            ->with('success', 'Skill tahsin berhasil dihapus.');
    }
}
```

---

# 38. Controller `TahsinStudentProfileController`

Buka:

```text
app/Http/Controllers/Tahsin/TahsinStudentProfileController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\UpdateTahsinStudentProfileRequest;
use App\Models\Student;
use App\Models\TahsinLevel;
use App\Models\TahsinStudentProfile;
use App\Models\User;
use App\Services\Tahsin\TahsinAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahsinStudentProfileController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(
                Student::query()->with(['classRoom', 'tahsinProfile.currentLevel', 'tahsinProfile.assignedTeacher']),
                $request->user()
            )
            ->orderBy('nama_lengkap')
            ->paginate(30);

        return view('tahsin.profiles.index', compact('students'));
    }

    public function show(Request $request, Student $student, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $student->load([
            'classRoom',
            'tahsinProfile.currentLevel',
            'tahsinProfile.assignedTeacher',
            'tahsinAssessments.level',
            'tahsinAssessments.teacher',
            'tahsinAssessments.items.skill',
        ]);

        return view('tahsin.profiles.show', compact('student'));
    }

    public function edit(Request $request, Student $student, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageProfile($request->user()), 403);

        $profile = TahsinStudentProfile::query()->firstOrNew([
            'student_id' => $student->id,
        ]);

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $teachers = User::query()
            ->orderBy('name')
            ->get();

        return view('tahsin.profiles.edit', compact('student', 'profile', 'levels', 'teachers'));
    }

    public function update(
        UpdateTahsinStudentProfileRequest $request,
        Student $student
    ): RedirectResponse {
        $data = $request->validated();

        TahsinStudentProfile::query()->updateOrCreate(
            ['student_id' => $student->id],
            [
                'school_id' => $student->school_id ?? null,
                'current_tahsin_level_id' => $data['current_tahsin_level_id'] ?? null,
                'assigned_teacher_id' => $data['assigned_teacher_id'] ?? null,
                'status' => $data['status'],
                'placement_score' => $data['placement_score'] ?? null,
                'started_at' => $data['started_at'] ?? null,
                'completed_at' => $data['completed_at'] ?? null,
                'note' => $data['note'] ?? null,
            ]
        );

        return redirect()
            ->route('tahsin.profiles.show', $student)
            ->with('success', 'Profil tahsin santri berhasil diperbarui.');
    }
}
```

Catatan:

Jika `students` tidak punya kolom `nama_lengkap`, ubah `orderBy('nama_lengkap')` ke kolom asli.

---

# 39. Controller `TahsinAssessmentController`

Buka:

```text
app/Http/Controllers/Tahsin/TahsinAssessmentController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\StoreTahsinAssessmentRequest;
use App\Models\Student;
use App\Models\TahsinAssessment;
use App\Models\TahsinLevel;
use App\Models\TahsinSkill;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinAssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahsinAssessmentController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $assessments = TahsinAssessment::query()
            ->with(['student.classRoom', 'teacher', 'level'])
            ->orderByDesc('assessment_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('tahsin.assessments.index', compact('assessments'));
    }

    public function create(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canCreateAssessment($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query()->with('classRoom'), $request->user())
            ->orderBy('nama_lengkap')
            ->limit(300)
            ->get();

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $skills = TahsinSkill::query()
            ->with('level')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tahsin.assessments.create', compact('students', 'levels', 'skills'));
    }

    public function store(
        StoreTahsinAssessmentRequest $request,
        TahsinAccessService $accessService,
        TahsinAssessmentService $assessmentService
    ): RedirectResponse {
        $student = Student::query()->findOrFail($request->validated('student_id'));

        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $assessment = $assessmentService->createAssessment(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('tahsin.assessments.show', $assessment)
            ->with('success', 'Asesmen tahsin berhasil disimpan.');
    }

    public function show(
        Request $request,
        TahsinAssessment $assessment,
        TahsinAccessService $accessService
    ): View {
        $assessment->load(['student', 'teacher', 'level', 'items.skill']);

        abort_unless($accessService->canViewStudent($request->user(), $assessment->student), 403);

        return view('tahsin.assessments.show', compact('assessment'));
    }
}
```

---

# 40. Controller `TahsinReportController`

Buka:

```text
app/Http/Controllers/Tahsin/TahsinReportController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\TahsinReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\TahsinLevel;
use App\Models\User;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinReportService;
use Illuminate\View\View;

class TahsinReportController extends Controller
{
    public function dashboard(
        TahsinReportFilterRequest $request,
        TahsinAccessService $accessService,
        TahsinReportService $reportService
    ): View {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $filters = $request->validated();
        $report = $reportService->dashboard($filters);

        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('nama_lengkap'), $request->user())
            ->limit(300)
            ->get();

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $teachers = User::query()
            ->orderBy('name')
            ->get();

        return view('tahsin.reports.dashboard', compact(
            'report',
            'filters',
            'classRooms',
            'students',
            'levels',
            'teachers'
        ));
    }
}
```

Catatan:

Jika `class_rooms` tidak punya kolom `name`, ubah sesuai kolom asli.

---

# 41. Controller `ParentTahsinPortalController`

Buka:

```text
app/Http/Controllers/Portal/ParentTahsinPortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\TahsinReportFilterRequest;
use App\Models\Student;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinReportService;
use Illuminate\View\View;

class ParentTahsinPortalController extends Controller
{
    public function index(
        TahsinReportFilterRequest $request,
        TahsinAccessService $accessService,
        TahsinReportService $reportService
    ): View {
        abort_unless($accessService->isParent($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('nama_lengkap'), $request->user())
            ->get();

        $selectedStudent = null;
        $snapshot = null;

        if ($students->isNotEmpty()) {
            $selectedStudent = $students->firstWhere('id', (int) $request->input('student_id'))
                ?? $students->first();

            $snapshot = $reportService->studentSnapshot($selectedStudent, $request->validated());
        }

        return view('portal.parent.tahsin', compact(
            'students',
            'selectedStudent',
            'snapshot'
        ));
    }
}
```

---

# 42. Controller `StudentTahsinPortalController`

Buka:

```text
app/Http/Controllers/Portal/StudentTahsinPortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\TahsinReportFilterRequest;
use App\Models\Student;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinReportService;
use Illuminate\View\View;

class StudentTahsinPortalController extends Controller
{
    public function index(
        TahsinReportFilterRequest $request,
        TahsinAccessService $accessService,
        TahsinReportService $reportService
    ): View {
        abort_unless($accessService->isStudent($request->user()), 403);

        $student = Student::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $student) {
            return view('portal.student.tahsin', [
                'student' => null,
                'snapshot' => null,
            ]);
        }

        $snapshot = $reportService->studentSnapshot($student, $request->validated());

        return view('portal.student.tahsin', compact('student', 'snapshot'));
    }
}
```

---

# 43. View `tahsin/levels/index.blade.php`

Buat:

```text
resources/views/tahsin/levels/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Level Tahsin</h1>
            <p class="text-sm text-gray-600">Kelola level pembinaan tahsin.</p>
        </div>

        <a href="{{ route('tahsin.levels.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Level
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
                    <th class="px-4 py-3 text-left">Minimum Score</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($levels as $level)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $level->name }}</td>
                        <td class="px-4 py-3">{{ $level->minimum_score }}</td>
                        <td class="px-4 py-3">{{ $level->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('tahsin.levels.show', $level) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('tahsin.levels.edit', $level) }}" class="text-amber-600">Edit</a>
                            <form action="{{ route('tahsin.levels.destroy', $level) }}" method="POST" class="inline" onsubmit="return confirm('Hapus level ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            Belum ada level tahsin.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $levels->links() }}
    </div>
</div>
@endsection
```

---

# 44. View Partial `tahsin/levels/_form.blade.php`

Buat:

```text
resources/views/tahsin/levels/_form.blade.php
```

Isi lengkap:

```blade
@php
    $level = $level ?? null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Level</label>
    <input type="text"
           name="name"
           value="{{ old('name', $level?->name) }}"
           class="mt-1 w-full rounded-lg border-gray-300"
           required>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description"
              rows="3"
              class="mt-1 w-full rounded-lg border-gray-300">{{ old('description', $level?->description) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Minimum Score</label>
        <input type="number"
               name="minimum_score"
               value="{{ old('minimum_score', $level?->minimum_score ?? 70) }}"
               min="0"
               max="100"
               class="mt-1 w-full rounded-lg border-gray-300"
               required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Urutan</label>
        <input type="number"
               name="sort_order"
               value="{{ old('sort_order', $level?->sort_order ?? 0) }}"
               min="0"
               class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<label class="flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $level?->is_active ?? true))>
    <span class="text-sm text-gray-700">Aktif</span>
</label>
```

---

# 45. View `tahsin/levels/create.blade.php`

Buat:

```text
resources/views/tahsin/levels/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Level Tahsin</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.levels.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        @include('tahsin.levels._form', ['level' => null])

        <div class="flex justify-end gap-3">
            <a href="{{ route('tahsin.levels.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
```

---

# 46. View `tahsin/levels/edit.blade.php`

Buat:

```text
resources/views/tahsin/levels/edit.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Level Tahsin</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.levels.update', $level) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('tahsin.levels._form', ['level' => $level])

        <div class="flex justify-end gap-3">
            <a href="{{ route('tahsin.levels.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
```

---

# 47. View `tahsin/levels/show.blade.php`

Buat:

```text
resources/views/tahsin/levels/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $level->name }}</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $level->description ?? '-' }}</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <div class="text-sm text-gray-500">Minimum Score</div>
                <div class="font-semibold">{{ $level->minimum_score }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Status</div>
                <div class="font-semibold">{{ $level->is_active ? 'Aktif' : 'Nonaktif' }}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Jumlah Skill</div>
                <div class="font-semibold">{{ $level->skills->count() }}</div>
            </div>
        </div>
    </div>

    <div class="mt-6 bg-white rounded-xl shadow overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 font-semibold">Skill pada level ini</div>
        <table class="min-w-full text-sm">
            <tbody class="divide-y">
                @forelse($level->skills as $skill)
                    <tr>
                        <td class="px-4 py-3">{{ $skill->name }}</td>
                        <td class="px-4 py-3">{{ $skill->code }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-500">Belum ada skill.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

# 48. View `tahsin/skills/index.blade.php`

Buat:

```text
resources/views/tahsin/skills/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Skill Tahsin</h1>
            <p class="text-sm text-gray-600">Kelola kompetensi penilaian tahsin.</p>
        </div>

        <a href="{{ route('tahsin.skills.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Skill
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
                    <th class="px-4 py-3 text-left">Skill</th>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Aktif</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($skills as $skill)
                    <tr>
                        <td class="px-4 py-3">{{ $skill->name }}</td>
                        <td class="px-4 py-3">{{ $skill->code ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $skill->level?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $skill->is_active ? 'Ya' : 'Tidak' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('tahsin.skills.show', $skill) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('tahsin.skills.edit', $skill) }}" class="text-amber-600">Edit</a>
                            <form action="{{ route('tahsin.skills.destroy', $skill) }}" method="POST" class="inline" onsubmit="return confirm('Hapus skill ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada skill tahsin.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $skills->links() }}</div>
</div>
@endsection
```

---

# 49. View Partial `tahsin/skills/_form.blade.php`

Buat:

```text
resources/views/tahsin/skills/_form.blade.php
```

Isi lengkap:

```blade
@php
    $skill = $skill ?? null;
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700">Level</label>
    <select name="tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
        <option value="">Tanpa level khusus</option>
        @foreach($levels as $level)
            <option value="{{ $level->id }}" @selected(old('tahsin_level_id', $skill?->tahsin_level_id) == $level->id)>
                {{ $level->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Nama Skill</label>
    <input type="text" name="name" value="{{ old('name', $skill?->name) }}" class="mt-1 w-full rounded-lg border-gray-300" required>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Kode</label>
    <input type="text" name="code" value="{{ old('code', $skill?->code) }}" class="mt-1 w-full rounded-lg border-gray-300">
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
    <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-gray-300">{{ old('description', $skill?->description) }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Maximum Score</label>
        <input type="number" name="maximum_score" value="{{ old('maximum_score', $skill?->maximum_score ?? 100) }}" min="1" max="100" class="mt-1 w-full rounded-lg border-gray-300" required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $skill?->sort_order ?? 0) }}" min="0" class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<label class="flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $skill?->is_active ?? true))>
    <span class="text-sm text-gray-700">Aktif</span>
</label>
```

---

# 50. View `tahsin/skills/create.blade.php`

Buat:

```text
resources/views/tahsin/skills/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Skill Tahsin</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.skills.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        @include('tahsin.skills._form', [
            'skill' => null,
            'levels' => $levels,
        ])

        <div class="flex justify-end gap-3">
            <a href="{{ route('tahsin.skills.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
```

---

# 51. View `tahsin/skills/edit.blade.php`

Buat:

```text
resources/views/tahsin/skills/edit.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Skill Tahsin</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.skills.update', $skill) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('tahsin.skills._form', [
            'skill' => $skill,
            'levels' => $levels,
        ])

        <div class="flex justify-end gap-3">
            <a href="{{ route('tahsin.skills.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
```

---

# 52. View `tahsin/skills/show.blade.php`

Buat:

```text
resources/views/tahsin/skills/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $skill->name }}</h1>
        <p class="text-sm text-gray-600 mt-1">{{ $skill->description ?? '-' }}</p>

        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 text-sm">
            <div>
                <dt class="text-gray-500">Kode</dt>
                <dd class="font-semibold">{{ $skill->code ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Level</dt>
                <dd class="font-semibold">{{ $skill->level?->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Maximum Score</dt>
                <dd class="font-semibold">{{ $skill->maximum_score }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
```

---

# 53. View `tahsin/profiles/index.blade.php`

Buat:

```text
resources/views/tahsin/profiles/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profil Tahsin Santri</h1>
        <p class="text-sm text-gray-600">Monitoring level dan status tahsin santri.</p>
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
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Kelas</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Guru</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($students as $student)
                    <tr>
                        <td class="px-4 py-3">{{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}</td>
                        <td class="px-4 py-3">{{ $student->classRoom?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $student->tahsinProfile?->currentLevel?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $student->tahsinProfile?->status ?? 'not_started' }}</td>
                        <td class="px-4 py-3">{{ $student->tahsinProfile?->assignedTeacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('tahsin.profiles.show', $student) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('tahsin.profiles.edit', $student) }}" class="text-amber-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada santri.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $students->links() }}</div>
</div>
@endsection
```

---

# 54. View `tahsin/profiles/edit.blade.php`

Buat:

```text
resources/views/tahsin/profiles/edit.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-1">Edit Profil Tahsin</h1>
    <p class="text-sm text-gray-600 mb-6">{{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}</p>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.profiles.update', $student) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        <input type="hidden" name="student_id" value="{{ $student->id }}">

        <div>
            <label class="block text-sm font-medium text-gray-700">Level Saat Ini</label>
            <select name="current_tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Belum ada level</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" @selected(old('current_tahsin_level_id', $profile?->current_tahsin_level_id) == $level->id)>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Guru Pembimbing</label>
            <select name="assigned_teacher_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Belum ditentukan</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(old('assigned_teacher_id', $profile?->assigned_teacher_id) == $teacher->id)>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border-gray-300" required>
                @foreach(['not_started' => 'Belum mulai', 'in_progress' => 'Berjalan', 'passed' => 'Lulus', 'needs_attention' => 'Butuh perhatian'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $profile?->status ?? 'not_started') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Placement Score</label>
                <input type="number" name="placement_score" value="{{ old('placement_score', $profile?->placement_score) }}" min="0" max="100" step="0.01" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mulai</label>
                <input type="date" name="started_at" value="{{ old('started_at', $profile?->started_at?->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Selesai</label>
                <input type="date" name="completed_at" value="{{ old('completed_at', $profile?->completed_at?->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300">{{ old('note', $profile?->note) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('tahsin.profiles.index') }}" class="px-4 py-2 border rounded-lg">Batal</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
```

---

# 55. View `tahsin/profiles/show.blade.php`

Buat:

```text
resources/views/tahsin/profiles/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
        </h1>
        <p class="text-sm text-gray-600">Detail profil dan riwayat asesmen tahsin.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Kelas</div>
            <div class="font-bold">{{ $student->classRoom?->name ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Level</div>
            <div class="font-bold">{{ $student->tahsinProfile?->currentLevel?->name ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Status</div>
            <div class="font-bold">{{ $student->tahsinProfile?->status ?? 'not_started' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Guru</div>
            <div class="font-bold">{{ $student->tahsinProfile?->assignedTeacher?->name ?? '-' }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Tipe</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Score</th>
                    <th class="px-4 py-3 text-left">Grade</th>
                    <th class="px-4 py-3 text-left">Guru</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($student->tahsinAssessments as $assessment)
                    <tr>
                        <td class="px-4 py-3">{{ $assessment->assessment_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $assessment->assessment_type }}</td>
                        <td class="px-4 py-3">{{ $assessment->level?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->overall_score }}</td>
                        <td class="px-4 py-3">{{ $assessment->grade }}</td>
                        <td class="px-4 py-3">{{ $assessment->teacher?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Belum ada asesmen tahsin.
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

# 56. View `tahsin/assessments/index.blade.php`

Buat:

```text
resources/views/tahsin/assessments/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Asesmen Tahsin</h1>
            <p class="text-sm text-gray-600">Riwayat penilaian bacaan santri.</p>
        </div>

        <a href="{{ route('tahsin.assessments.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Asesmen
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
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Guru</th>
                    <th class="px-4 py-3 text-left">Level</th>
                    <th class="px-4 py-3 text-left">Score</th>
                    <th class="px-4 py-3 text-left">Grade</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($assessments as $assessment)
                    <tr>
                        <td class="px-4 py-3">{{ $assessment->assessment_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $assessment->student?->nama_lengkap ?? $assessment->student?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->teacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->level?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $assessment->overall_score }}</td>
                        <td class="px-4 py-3">{{ $assessment->grade }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('tahsin.assessments.show', $assessment) }}" class="text-blue-600">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada asesmen.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $assessments->links() }}</div>
</div>
@endsection
```

---

# 57. View `tahsin/assessments/create.blade.php`

Buat:

```text
resources/views/tahsin/assessments/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Asesmen Tahsin</h1>

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-red-700">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahsin.assessments.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Santri</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">
                            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Level</label>
                <select name="tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
                    <option value="">Tidak spesifik</option>
                    @foreach($levels as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" name="assessment_date" value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe</label>
                <select name="assessment_type" class="mt-1 w-full rounded-lg border-gray-300" required>
                    <option value="placement">Placement</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="final">Final</option>
                </select>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4">
            <h2 class="font-semibold text-gray-900 mb-3">Penilaian Skill</h2>

            <div class="space-y-3">
                @foreach($skills as $index => $skill)
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3 bg-white rounded-lg p-3 border">
                        <input type="hidden" name="items[{{ $index }}][tahsin_skill_id]" value="{{ $skill->id }}">

                        <div class="md:col-span-2">
                            <div class="font-medium text-gray-900">{{ $skill->name }}</div>
                            <div class="text-xs text-gray-500">{{ $skill->level?->name ?? '-' }}</div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500">Score</label>
                            <input type="number" name="items[{{ $index }}][score]" value="0" min="0" max="100" step="0.01" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500">Status</label>
                            <select name="items[{{ $index }}][status]" class="mt-1 w-full rounded-lg border-gray-300">
                                <option value="mastered">Mastered</option>
                                <option value="progress">Progress</option>
                                <option value="weak">Weak</option>
                                <option value="not_tested" selected>Not Tested</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500">Catatan</label>
                            <input type="text" name="items[{{ $index }}][note]" class="mt-1 w-full rounded-lg border-gray-300">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan Umum</label>
            <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Rekomendasi</label>
            <textarea name="recommendation" rows="3" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan Asesmen</button>
        </div>
    </form>
</div>
@endsection
```

---

# 58. View `tahsin/assessments/show.blade.php`

Buat:

```text
resources/views/tahsin/assessments/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Detail Asesmen Tahsin</h1>
        <p class="text-sm text-gray-600">
            {{ $assessment->student?->nama_lengkap ?? $assessment->student?->name ?? '-' }} ·
            {{ $assessment->assessment_date?->format('d M Y') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Score</div>
            <div class="text-2xl font-bold">{{ $assessment->overall_score }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Grade</div>
            <div class="text-2xl font-bold">{{ $assessment->grade }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Level</div>
            <div class="font-bold">{{ $assessment->level?->name ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Guru</div>
            <div class="font-bold">{{ $assessment->teacher?->name ?? '-' }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Skill</th>
                    <th class="px-4 py-3 text-left">Score</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($assessment->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->skill?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $item->score }}</td>
                        <td class="px-4 py-3">{{ $item->status }}</td>
                        <td class="px-4 py-3">{{ $item->note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="font-semibold mb-2">Catatan</div>
            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $assessment->note ?? '-' }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="font-semibold mb-2">Rekomendasi</div>
            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $assessment->recommendation ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection
```

---

# 59. View `tahsin/reports/dashboard.blade.php`

Buat:

```text
resources/views/tahsin/reports/dashboard.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Tahsin</h1>
        <p class="text-sm text-gray-600">Ringkasan progress dan asesmen tahsin santri.</p>
    </div>

    <form method="GET" action="{{ route('tahsin.reports.dashboard') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Mulai</label>
            <input type="date" name="start_date" value="{{ $filters['start_date'] ?? $report['period']['start_date'] }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Selesai</label>
            <input type="date" name="end_date" value="{{ $filters['end_date'] ?? $report['period']['end_date'] }}" class="mt-1 w-full rounded-lg border-gray-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Kelas</label>
            <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua</option>
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
                <option value="">Semua</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" @selected(($filters['student_id'] ?? null) == $student->id)>
                        {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Level</label>
            <select name="tahsin_level_id" class="mt-1 w-full rounded-lg border-gray-300">
                <option value="">Semua</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" @selected(($filters['tahsin_level_id'] ?? null) == $level->id)>
                        {{ $level->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total Asesmen</div>
            <div class="text-2xl font-bold">{{ $report['summary']['total_assessments'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Rata-rata</div>
            <div class="text-2xl font-bold">{{ $report['summary']['average_score'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Excellent</div>
            <div class="text-2xl font-bold text-green-700">{{ $report['summary']['excellent'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Good/Fair</div>
            <div class="text-2xl font-bold text-blue-700">{{ $report['summary']['good'] + $report['summary']['fair'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Need Improvement</div>
            <div class="text-2xl font-bold text-red-700">{{ $report['summary']['needs_improvement'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Total Asesmen</th>
                    <th class="px-4 py-3 text-left">Rata-rata</th>
                    <th class="px-4 py-3 text-left">Asesmen Terakhir</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($report['by_student'] as $row)
                    <tr>
                        <td class="px-4 py-3">{{ $row['student']?->nama_lengkap ?? $row['student']?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $row['total'] }}</td>
                        <td class="px-4 py-3">{{ $row['average_score'] }}</td>
                        <td class="px-4 py-3">
                            {{ $row['latest_assessment']?->assessment_date?->format('d M Y') ?? '-' }}
                            —
                            {{ $row['latest_assessment']?->overall_score ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
```

---

# 60. View `portal/parent/tahsin.blade.php`

Buat:

```text
resources/views/portal/parent/tahsin.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tahsin Anak</h1>
        <p class="text-sm text-gray-600">Pantau progress tahsin anak.</p>
    </div>

    @if($students->isEmpty())
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Belum ada data anak yang terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.parent.tahsin') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Anak</label>
                <select name="student_id" class="mt-1 w-full rounded-lg border-gray-300">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Mulai</label>
                <input type="date" name="start_date" value="{{ $snapshot['period']['start_date'] ?? now()->startOfMonth()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Selesai</label>
                <input type="date" name="end_date" value="{{ $snapshot['period']['end_date'] ?? now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            </div>
        </form>

        @if($snapshot)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Level</div>
                    <div class="font-bold">{{ $snapshot['profile']?->currentLevel?->name ?? '-' }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="font-bold">{{ $snapshot['profile']?->status ?? 'not_started' }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Asesmen</div>
                    <div class="font-bold">{{ $snapshot['summary']['total_assessments'] }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Rata-rata</div>
                    <div class="font-bold">{{ $snapshot['summary']['average_score'] }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Tipe</th>
                            <th class="px-4 py-3 text-left">Level</th>
                            <th class="px-4 py-3 text-left">Score</th>
                            <th class="px-4 py-3 text-left">Grade</th>
                            <th class="px-4 py-3 text-left">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($snapshot['assessments'] as $assessment)
                            <tr>
                                <td class="px-4 py-3">{{ $assessment->assessment_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $assessment->assessment_type }}</td>
                                <td class="px-4 py-3">{{ $assessment->level?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $assessment->overall_score }}</td>
                                <td class="px-4 py-3">{{ $assessment->grade }}</td>
                                <td class="px-4 py-3">{{ $assessment->recommendation ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada asesmen tahsin.
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

# 61. View `portal/student/tahsin.blade.php`

Buat:

```text
resources/views/portal/student/tahsin.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tahsin Saya</h1>
        <p class="text-sm text-gray-600">Lihat progress tahsin pribadi.</p>
    </div>

    @if(! $student)
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Profil santri belum terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.student.tahsin') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Mulai</label>
                <input type="date" name="start_date" value="{{ $snapshot['period']['start_date'] ?? now()->startOfMonth()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Selesai</label>
                <input type="date" name="end_date" value="{{ $snapshot['period']['end_date'] ?? now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">Filter</button>
            </div>
        </form>

        @if($snapshot)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Level</div>
                    <div class="font-bold">{{ $snapshot['profile']?->currentLevel?->name ?? '-' }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Status</div>
                    <div class="font-bold">{{ $snapshot['profile']?->status ?? 'not_started' }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Total Asesmen</div>
                    <div class="font-bold">{{ $snapshot['summary']['total_assessments'] }}</div>
                </div>

                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Rata-rata</div>
                    <div class="font-bold">{{ $snapshot['summary']['average_score'] }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Tipe</th>
                            <th class="px-4 py-3 text-left">Level</th>
                            <th class="px-4 py-3 text-left">Score</th>
                            <th class="px-4 py-3 text-left">Grade</th>
                            <th class="px-4 py-3 text-left">Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($snapshot['assessments'] as $assessment)
                            <tr>
                                <td class="px-4 py-3">{{ $assessment->assessment_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $assessment->assessment_type }}</td>
                                <td class="px-4 py-3">{{ $assessment->level?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $assessment->overall_score }}</td>
                                <td class="px-4 py-3">{{ $assessment->grade }}</td>
                                <td class="px-4 py-3">{{ $assessment->recommendation ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada asesmen tahsin.
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

# 62. Update Routes `routes/web.php`

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Tahsin\TahsinLevelController;
use App\Http\Controllers\Tahsin\TahsinSkillController;
use App\Http\Controllers\Tahsin\TahsinStudentProfileController;
use App\Http\Controllers\Tahsin\TahsinAssessmentController;
use App\Http\Controllers\Tahsin\TahsinReportController;
use App\Http\Controllers\Portal\ParentTahsinPortalController;
use App\Http\Controllers\Portal\StudentTahsinPortalController;
```

Tambahkan route di dalam middleware `auth`:

```php
Route::middleware(['auth'])->group(function (): void {
    Route::prefix('tahsin')
        ->name('tahsin.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal,teacher,guru,guru_tahfidz'])
        ->group(function (): void {
            Route::get('/reports/dashboard', [TahsinReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::get('/profiles', [TahsinStudentProfileController::class, 'index'])
                ->name('profiles.index');

            Route::get('/profiles/{student}', [TahsinStudentProfileController::class, 'show'])
                ->name('profiles.show');

            Route::get('/profiles/{student}/edit', [TahsinStudentProfileController::class, 'edit'])
                ->name('profiles.edit');

            Route::put('/profiles/{student}', [TahsinStudentProfileController::class, 'update'])
                ->name('profiles.update');

            Route::get('/assessments', [TahsinAssessmentController::class, 'index'])
                ->name('assessments.index');

            Route::get('/assessments/create', [TahsinAssessmentController::class, 'create'])
                ->name('assessments.create');

            Route::post('/assessments', [TahsinAssessmentController::class, 'store'])
                ->name('assessments.store');

            Route::get('/assessments/{assessment}', [TahsinAssessmentController::class, 'show'])
                ->name('assessments.show');

            Route::resource('levels', TahsinLevelController::class);
            Route::resource('skills', TahsinSkillController::class);
        });

    Route::get('/portal/parent/tahsin', [ParentTahsinPortalController::class, 'index'])
        ->middleware(['role:parent'])
        ->name('portal.parent.tahsin');

    Route::get('/portal/student/tahsin', [StudentTahsinPortalController::class, 'index'])
        ->middleware(['role:student'])
        ->name('portal.student.tahsin');
});
```

Jika middleware role project hanya menerima slug role tertentu, sesuaikan dengan data role asli.

Jangan hapus route lama.

---

# 63. Update Navigation

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

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'kepala_sekolah', 'principal', 'teacher', 'guru', 'guru_tahfidz'], true))
    <a href="{{ route('tahsin.reports.dashboard') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Tahsin
    </a>

    <a href="{{ route('tahsin.assessments.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Asesmen Tahsin
    </a>

    <a href="{{ route('tahsin.profiles.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Profil Tahsin
    </a>
@endif

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true))
    <a href="{{ route('tahsin.levels.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Level Tahsin
    </a>

    <a href="{{ route('tahsin.skills.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Skill Tahsin
    </a>
@endif

@if($roleName === 'parent')
    <a href="{{ route('portal.parent.tahsin') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Tahsin Anak
    </a>
@endif

@if($roleName === 'student')
    <a href="{{ route('portal.student.tahsin') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Tahsin Saya
    </a>
@endif
```

Sesuaikan class dengan style layout project.

---

# 64. Jalankan Migration dan Seeder

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=TahsinLevelSeeder
php artisan db:seed --class=TahsinSkillSeeder
```

Cek via tinker:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\TahsinLevel::count();
App\Models\TahsinSkill::count();
App\Models\TahsinStudentProfile::count();
App\Models\TahsinAssessment::count();
```

Target:

```text
TahsinLevel > 0
TahsinSkill > 0
TahsinStudentProfile = 0 boleh
TahsinAssessment = 0 boleh
```

---

# 65. Validasi Route

Jalankan:

```powershell
php artisan route:list --name=tahsin
php artisan route:list --name=portal.parent.tahsin
php artisan route:list --name=portal.student.tahsin
```

Target route tersedia:

```text
tahsin.reports.dashboard
tahsin.profiles.index
tahsin.profiles.show
tahsin.profiles.edit
tahsin.profiles.update
tahsin.assessments.index
tahsin.assessments.create
tahsin.assessments.store
tahsin.assessments.show
tahsin.levels.index
tahsin.levels.create
tahsin.levels.store
tahsin.levels.show
tahsin.levels.edit
tahsin.levels.update
tahsin.levels.destroy
tahsin.skills.index
tahsin.skills.create
tahsin.skills.store
tahsin.skills.show
tahsin.skills.edit
tahsin.skills.update
tahsin.skills.destroy
portal.parent.tahsin
portal.student.tahsin
```

---

# 66. UAT Phase 13

## 66.1 Test Admin

Login sebagai admin.

Tes:

1. Buka `/tahsin/levels`.
2. Tambah level tahsin.
3. Edit level tahsin.
4. Buka `/tahsin/skills`.
5. Tambah skill tahsin.
6. Edit skill tahsin.
7. Buka `/tahsin/profiles`.
8. Edit profil tahsin santri.
9. Buka `/tahsin/assessments/create`.
10. Buat asesmen tahsin.
11. Buka detail asesmen.
12. Buka `/tahsin/reports/dashboard`.

Expected:

1. Tidak ada error 500.
2. Level dan skill tersimpan.
3. Profil tahsin santri tersimpan.
4. Asesmen tersimpan.
5. Overall score dihitung otomatis dari item.
6. Grade otomatis muncul.
7. Report tampil.

---

## 66.2 Test Guru

Login sebagai guru.

Tes:

1. Buka `/tahsin/assessments`.
2. Buat asesmen tahsin.
3. Buka report tahsin.
4. Coba buka `/tahsin/levels/create`.
5. Coba buka `/tahsin/skills/create`.

Expected:

1. Guru bisa input asesmen.
2. Guru bisa melihat report santri scope-nya.
3. Guru tidak bisa CRUD level.
4. Guru tidak bisa CRUD skill.

---

## 66.3 Test Kepala Sekolah

Login sebagai kepala sekolah.

Tes:

1. Buka `/tahsin/reports/dashboard`.
2. Buka detail asesmen.
3. Coba buka create asesmen.
4. Coba edit level/skill.

Expected:

1. Kepala sekolah bisa melihat report.
2. Kepala sekolah bisa monitoring.
3. Kepala sekolah tidak bisa input asesmen.
4. Kepala sekolah tidak bisa CRUD master tahsin.

---

## 66.4 Test Parent

Login sebagai parent.

Tes:

1. Buka `/portal/parent/tahsin`.
2. Pastikan hanya anak sendiri tampil.
3. Ubah query `student_id` ke ID anak lain.
4. Pastikan data anak lain tidak tampil.
5. Coba buka `/tahsin/reports/dashboard`.
6. Coba buka `/tahsin/assessments/create`.

Expected:

1. Parent hanya melihat data anak sendiri.
2. Parent tidak bisa melihat data anak lain.
3. Parent tidak bisa akses internal tahsin report.
4. Parent tidak bisa input asesmen.

---

## 66.5 Test Student

Login sebagai student.

Tes:

1. Buka `/portal/student/tahsin`.
2. Pastikan hanya data pribadi tampil.
3. Coba buka `/tahsin/reports/dashboard`.
4. Coba buka `/tahsin/assessments/create`.

Expected:

1. Student hanya melihat data pribadi.
2. Student tidak bisa akses dashboard internal.
3. Student tidak bisa input asesmen.

---

# 67. Build Frontend

Jalankan:

```powershell
npm run build
```

Build wajib berhasil.

---

# 68. Validasi Akhir

Jalankan:

```powershell
php artisan migrate:status
php artisan route:list --name=tahsin
php artisan route:list --name=portal.parent.tahsin
php artisan route:list --name=portal.student.tahsin
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
http://127.0.0.1:8000/tahsin/levels
http://127.0.0.1:8000/tahsin/skills
http://127.0.0.1:8000/tahsin/profiles
http://127.0.0.1:8000/tahsin/assessments
http://127.0.0.1:8000/tahsin/assessments/create
http://127.0.0.1:8000/tahsin/reports/dashboard
http://127.0.0.1:8000/portal/parent/tahsin
http://127.0.0.1:8000/portal/student/tahsin
```

---

# 69. Dokumentasi Phase 13

Buat file:

```text
docs/phase-13-tahsin-management-app.md
```

Isi lengkap:

```md
# Phase 13 — Tahsin Management App

## Status

Phase 13 menambahkan modul Tahsin Management App untuk HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. Level tahsin.
2. Skill tahsin.
3. Profil tahsin santri.
4. Asesmen tahsin manual.
5. Penilaian skill tahsin.
6. Kalkulasi overall score.
7. Grade otomatis.
8. Report tahsin.
9. Parent tahsin portal.
10. Student tahsin portal.
11. Role-based access.
12. Ownership-based access.

## Tabel Baru

1. `tahsin_levels`
2. `tahsin_skills`
3. `tahsin_student_profiles`
4. `tahsin_assessments`
5. `tahsin_assessment_items`

## Model Baru

1. `TahsinLevel`
2. `TahsinSkill`
3. `TahsinStudentProfile`
4. `TahsinAssessment`
5. `TahsinAssessmentItem`

## Controller Baru

1. `TahsinLevelController`
2. `TahsinSkillController`
3. `TahsinStudentProfileController`
4. `TahsinAssessmentController`
5. `TahsinReportController`
6. `ParentTahsinPortalController`
7. `StudentTahsinPortalController`

## Service Baru

1. `TahsinAccessService`
2. `TahsinAssessmentService`
3. `TahsinReportService`

## Seeder Baru

1. `TahsinLevelSeeder`
2. `TahsinSkillSeeder`

## Route Baru

1. `tahsin.reports.dashboard`
2. `tahsin.profiles.index`
3. `tahsin.profiles.show`
4. `tahsin.profiles.edit`
5. `tahsin.profiles.update`
6. `tahsin.assessments.index`
7. `tahsin.assessments.create`
8. `tahsin.assessments.store`
9. `tahsin.assessments.show`
10. `tahsin.levels.index`
11. `tahsin.levels.create`
12. `tahsin.levels.store`
13. `tahsin.levels.show`
14. `tahsin.levels.edit`
15. `tahsin.levels.update`
16. `tahsin.levels.destroy`
17. `tahsin.skills.index`
18. `tahsin.skills.create`
19. `tahsin.skills.store`
20. `tahsin.skills.show`
21. `tahsin.skills.edit`
22. `tahsin.skills.update`
23. `tahsin.skills.destroy`
24. `portal.parent.tahsin`
25. `portal.student.tahsin`

## Role Access

| Role | Akses |
|---|---|
| Super Admin | CRUD master, profil, asesmen, report |
| Admin | CRUD master, profil, asesmen, report |
| Kepala Sekolah | Report dan monitoring read-only |
| Guru | Input asesmen dan report terbatas |
| Parent | Portal tahsin anak sendiri |
| Student | Portal tahsin pribadi |

## Batasan

Phase 13 tidak membuat:

1. AI voice correction.
2. Voice recognition.
3. Audio upload.
4. LMS penuh.
5. Finance.
6. Cashless.
7. White-label.
8. Native mobile app.
9. WhatsApp gateway.
10. Push notification.
11. Multi-tenant kompleks.

## Definition of Done

Phase 13 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. Level tahsin tampil.
4. Skill tahsin tampil.
5. Admin bisa CRUD level dan skill.
6. Admin bisa edit profil tahsin santri.
7. Guru bisa input asesmen.
8. Overall score otomatis dihitung.
9. Grade otomatis muncul.
10. Report tahsin tampil.
11. Parent hanya melihat tahsin anak sendiri.
12. Student hanya melihat tahsin pribadi.
13. Parent/student tidak bisa akses internal dashboard.
14. Parent/student tidak bisa input asesmen.
15. `npm run build` berhasil.
16. `php artisan app:system-health-check` berhasil.
17. Dokumentasi dibuat.
```

---

# 70. Update `docs/project-progress.md`

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
| 14 | Student Finance Ledger | Pending |
| 15 | SchoolOS Mini | Pending |
```

---

# 71. Commit Phase 13

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add tahsin management app"
```

Jika remote tersedia:

```powershell
git push origin phase-13-tahsin-management-app
```

---

# 72. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 13 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Level Tahsin
- Skill Tahsin
- Profil Tahsin Santri
- Asesmen Tahsin
- Penilaian Skill Tahsin
- Overall Score otomatis
- Grade otomatis
- Dashboard Report Tahsin
- Parent Tahsin Portal
- Student Tahsin Portal
- Role-based access
- Ownership-based access

Tabel dibuat:
- tahsin_levels
- tahsin_skills
- tahsin_student_profiles
- tahsin_assessments
- tahsin_assessment_items

Route dibuat:
- tahsin.reports.dashboard
- tahsin.profiles.index
- tahsin.profiles.show
- tahsin.profiles.edit
- tahsin.profiles.update
- tahsin.assessments.index
- tahsin.assessments.create
- tahsin.assessments.store
- tahsin.assessments.show
- tahsin.levels.index
- tahsin.levels.create
- tahsin.levels.store
- tahsin.levels.show
- tahsin.levels.edit
- tahsin.levels.update
- tahsin.levels.destroy
- tahsin.skills.index
- tahsin.skills.create
- tahsin.skills.store
- tahsin.skills.show
- tahsin.skills.edit
- tahsin.skills.update
- tahsin.skills.destroy
- portal.parent.tahsin
- portal.student.tahsin

Belum dibuat:
- AI voice correction
- Voice recognition
- Audio upload
- LMS penuh
- Finance
- Cashless
- White-label
- Mobile app
- WhatsApp gateway
- Push notification
- Multi-tenant kompleks

Status:
- Siap lanjut Phase 14 hanya setelah UAT Phase 13 aman.
```

---

# 73. Larangan Setelah Phase 13

Agent harus berhenti setelah Phase 13 selesai.

Jangan lanjut membuat:

1. Student Finance Ledger.
2. Payment Gateway.
3. Cashless POS.
4. White-label.
5. Mobile App.
6. Multi-tenant architecture.
7. WhatsApp gateway.
8. Push notification.
9. LMS.
10. Boarding system.
11. AI voice correction.

Semua itu masuk phase berikutnya.

---

# 74. Keputusan Akhir

Phase 13 hanya valid jika Tahsin Management App stabil, aman, dan tidak merusak core Tahfizh, Mutabaah, Attendance, Parent Portal, dan Notification Center.

Prioritas setelah Phase 13:

1. UAT Tahsin.
2. Fix bug Tahsin.
3. Cek akses parent/student.
4. Cek asesmen guru.
5. Cek report tahsin.
6. Cek performa query assessment.
7. Baru pertimbangkan Phase 14 — Student Finance Ledger.

Jangan masuk finance sebelum Tahsin aman. Data uang lebih sensitif daripada data progress, dan error saldo/tagihan akan langsung merusak trust sekolah.
