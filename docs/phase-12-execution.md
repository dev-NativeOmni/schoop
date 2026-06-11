# Phase 12 Execution Guide — QR Attendance System

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
Phase 12 — QR Attendance System
```

---

# 1. Keputusan Sebelum Phase 12

## 1.1 UAT Phase 11 Wajib

Sebelum menjalankan Phase 12, agent wajib memastikan Phase 11 sudah aman.

Jangan menambah QR Attendance jika Mutabaah masih rusak.

Phase 12 hanya boleh dieksekusi jika:

1. Template Mutabaah berjalan.
2. Input Mutabaah harian berjalan.
3. Dashboard Mutabaah tampil.
4. Parent hanya bisa melihat Mutabaah anak sendiri.
5. Student hanya bisa melihat Mutabaah pribadi.
6. Internal route Mutabaah tidak bisa diakses parent/student.
7. `npm run build` berhasil.
8. `php artisan app:system-health-check` berhasil.
9. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0 atau P1, hentikan Phase 12 dan fix dulu.

---

## 1.2 Klasifikasi Bug Sebelum Phase 12

| Prioritas | Contoh Bug                                                           | Keputusan                |
| --------- | -------------------------------------------------------------------- | ------------------------ |
| P0        | Login gagal, data bocor, parent bisa lihat anak lain, QR token bocor | Wajib fix sebelum lanjut |
| P1        | Scan QR gagal, presensi dobel, report salah hitung                   | Wajib fix sebelum lanjut |
| P2        | Tampilan kurang rapi, wording kurang jelas                           | Boleh dicatat            |
| P3        | Enhancement kosmetik                                                 | Boleh ditunda            |

---

# 2. Tujuan Phase 12

Phase 12 bertujuan membuat **QR Attendance System** untuk presensi siswa/santri.

Fokus Phase 12:

1. QR unik per santri.
2. Generate QR token internal.
3. Rotasi QR token jika QR bocor.
4. Halaman kartu QR santri.
5. Halaman scanner QR berbasis web.
6. Presensi masuk.
7. Presensi pulang opsional.
8. Deteksi terlambat otomatis.
9. Input manual izin/sakit.
10. Rekap presensi harian.
11. Rekap presensi bulanan sederhana.
12. Parent attendance portal read-only.
13. Student attendance portal read-only.
14. Role-based access.
15. Ownership-based access.
16. Dokumentasi Phase 12.

---

# 3. Batasan Phase 12

AI agent tidak boleh membuat fitur berikut pada Phase 12:

1. Tahsin Management.
2. Finance Ledger.
3. Cashless Kantin.
4. Payment Gateway.
5. Wallet.
6. White-label.
7. Multi-tenant kompleks.
8. Native Android.
9. Native iOS.
10. Mobile app scanner native.
11. WhatsApp gateway.
12. Push notification.
13. Firebase.
14. Websocket.
15. Face recognition.
16. RFID.
17. NFC.
18. Fingerprint.
19. Integrasi mesin absensi.
20. Import Excel.
21. Payroll.
22. Billing siswa.
23. Boarding permission system.
24. Pinjam barang.
25. Perizinan keluar kompleks.

Phase 12 hanya membuat QR Attendance System berbasis web.

---

# 4. Prinsip Teknis Phase 12

## 4.1 Jangan Pakai Layanan QR Eksternal

QR tidak boleh dibuat dengan layanan eksternal seperti:

```text
api.qrserver.com
chart.googleapis.com
external QR generator website
```

Alasan:

1. QR token santri bisa bocor ke pihak ketiga.
2. Attendance token adalah data sensitif.
3. Sistem sekolah harus mandiri.
4. Lebih aman memakai QR SVG internal.

Phase 12 memakai package backend:

```powershell
composer require bacon/bacon-qr-code
```

Package ini hanya untuk membuat QR SVG lokal.

---

## 4.2 Scanner Harus Login

Scanner tidak boleh public.

Yang boleh scan:

1. Super Admin.
2. Admin.
3. Kepala Sekolah jika diizinkan read/operator.
4. Guru.
5. Operator jika role sudah ada.

Parent dan student tidak boleh scan attendance.

---

## 4.3 QR Token Bukan Student ID

QR tidak boleh berisi:

```text
student_id
nisn
nama_lengkap
email
nomor hp
```

QR hanya berisi token random:

```text
HFP-ATT:{random_token}
```

Server yang akan resolve token menjadi santri.

---

## 4.4 Token Bisa Dirotasi

Jika QR bocor, admin harus bisa regenerate QR token santri.

Token lama otomatis nonaktif.

---

## 4.5 Tidak Ada Mesin Absensi Dulu

Phase 12 hanya:

```text
Web scanner + QR card + database attendance.
```

Jangan membuat:

1. Device scanner khusus.
2. RFID.
3. NFC.
4. Fingerprint.
5. Face recognition.

---

# 5. Konsep Attendance

## 5.1 Attendance Session

Attendance session adalah sesi presensi per hari.

Contoh:

```text
Presensi Harian — 2026-06-11
```

Session bisa punya:

1. Tanggal.
2. Jam mulai scan masuk.
3. Jam batas terlambat.
4. Jam akhir scan masuk.
5. Jam mulai scan pulang.
6. Jam akhir scan pulang.
7. Status aktif/closed.

---

## 5.2 Attendance Record

Attendance record adalah data kehadiran per santri per session.

Status:

| Status       | Makna             |
| ------------ | ----------------- |
| `present`    | Hadir tepat waktu |
| `late`       | Hadir terlambat   |
| `sick`       | Sakit             |
| `permission` | Izin              |
| `absent`     | Tidak hadir       |

Source:

| Source   | Makna                       |
| -------- | --------------------------- |
| `qr`     | Scan QR                     |
| `manual` | Diinput admin/guru          |
| `system` | Dibuat otomatis oleh sistem |

---

## 5.3 Check In dan Check Out

Phase 12 wajib membuat:

1. Check in.
2. Check out opsional.

Jika santri scan pertama kali pada session aktif:

```text
check_in_at diisi
```

Jika santri scan lagi dan check_in_at sudah ada:

```text
check_out_at diisi
```

Jika check_out_at sudah ada, scan berikutnya tidak mengubah data kecuali admin edit manual.

---

# 6. Target Output Phase 12

Setelah Phase 12 selesai, aplikasi harus punya:

1. Menu **Attendance**.
2. Menu **QR Santri**.
3. Menu **Scanner QR**.
4. Menu **Session Presensi**.
5. Menu **Laporan Presensi**.
6. Tabel:

   * `attendance_qr_tokens`
   * `attendance_sessions`
   * `attendance_records`
7. Model:

   * `AttendanceQrToken`
   * `AttendanceSession`
   * `AttendanceRecord`
8. Controller:

   * `AttendanceQrCardController`
   * `AttendanceSessionController`
   * `AttendanceScannerController`
   * `AttendanceManualRecordController`
   * `AttendanceReportController`
   * `ParentAttendancePortalController`
   * `StudentAttendancePortalController`
9. Request:

   * `StoreAttendanceSessionRequest`
   * `UpdateAttendanceSessionRequest`
   * `ScanAttendanceQrRequest`
   * `StoreManualAttendanceRecordRequest`
   * `AttendanceReportFilterRequest`
10. Service:

* `AttendanceAccessService`
* `AttendanceQrTokenService`
* `QrCodeService`
* `AttendanceScanService`
* `AttendanceReportService`

11. Command:

* `php artisan app:generate-attendance-qr-tokens`

12. View:

* QR card index
* QR card print
* scanner page
* session index/create/edit/show
* report dashboard
* manual record form
* parent attendance portal
* student attendance portal

13. Dokumentasi Phase 12.
14. Update `docs/project-progress.md`.

---

# 7. Role Access Phase 12

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

| Role           | QR Card   | Scanner  | Manual Record | Report         | Portal |
| -------------- | --------- | -------- | ------------- | -------------- | ------ |
| Super Admin    | Ya        | Ya       | Ya            | Semua          | Tidak  |
| Admin          | Ya        | Ya       | Ya            | Semua          | Tidak  |
| Kepala Sekolah | Read-only | Opsional | Tidak         | Semua          | Tidak  |
| Teacher/Guru   | Read-only | Ya       | Ya terbatas   | Santri terkait | Tidak  |
| Parent         | Tidak     | Tidak    | Tidak         | Anak sendiri   | Ya     |
| Student        | Tidak     | Tidak    | Tidak         | Data sendiri   | Ya     |

Aturan keras:

1. Parent tidak boleh melihat attendance anak lain.
2. Student tidak boleh melihat attendance santri lain.
3. Parent/student tidak boleh scan QR.
4. Parent/student tidak boleh regenerate QR.
5. Teacher tidak boleh regenerate QR.
6. Teacher hanya boleh input manual santri yang menjadi scope-nya.
7. Admin boleh regenerate QR.
8. QR scan wajib melewati auth.

---

# 8. Validasi Awal Sebelum Eksekusi

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
13. Phase 11 selesai dan UAT aman.
14. Tabel berikut sudah ada:

* `users`
* `roles`
* `schools`
* `class_rooms`
* `students`
* `parent_profiles`
* `parent_student`
* `teacher_profiles`

15. Working tree bersih atau semua perubahan diketahui.

Jika Phase 11 belum aman, hentikan eksekusi.

---

# 9. Buat Branch Git Phase 12

Jalankan:

```powershell
git checkout -b phase-12-qr-attendance-system
```

Jika branch sudah ada:

```powershell
git checkout phase-12-qr-attendance-system
```

---

# 10. Install Package QR Lokal

Jalankan:

```powershell
composer require bacon/bacon-qr-code
```

Jangan install package QR yang memanggil layanan eksternal.

---

# 11. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Console/
│   └── Commands/
│       └── GenerateAttendanceQrTokensCommand.php
├── Http/
│   ├── Controllers/
│   │   ├── Attendance/
│   │   │   ├── AttendanceQrCardController.php
│   │   │   ├── AttendanceSessionController.php
│   │   │   ├── AttendanceScannerController.php
│   │   │   ├── AttendanceManualRecordController.php
│   │   │   └── AttendanceReportController.php
│   │   └── Portal/
│   │       ├── ParentAttendancePortalController.php
│   │       └── StudentAttendancePortalController.php
│   └── Requests/
│       └── Attendance/
│           ├── StoreAttendanceSessionRequest.php
│           ├── UpdateAttendanceSessionRequest.php
│           ├── ScanAttendanceQrRequest.php
│           ├── StoreManualAttendanceRecordRequest.php
│           └── AttendanceReportFilterRequest.php
├── Models/
│   ├── AttendanceQrToken.php
│   ├── AttendanceSession.php
│   └── AttendanceRecord.php
└── Services/
    └── Attendance/
        ├── AttendanceAccessService.php
        ├── AttendanceQrTokenService.php
        ├── QrCodeService.php
        ├── AttendanceScanService.php
        └── AttendanceReportService.php

database/
└── migrations/
    ├── xxxx_xx_xx_xxxxxx_create_attendance_qr_tokens_table.php
    ├── xxxx_xx_xx_xxxxxx_create_attendance_sessions_table.php
    └── xxxx_xx_xx_xxxxxx_create_attendance_records_table.php

resources/
└── views/
    ├── attendance/
    │   ├── qr-cards/
    │   │   ├── index.blade.php
    │   │   └── print.blade.php
    │   ├── sessions/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── scanner/
    │   │   └── index.blade.php
    │   ├── manual/
    │   │   └── create.blade.php
    │   └── reports/
    │       └── dashboard.blade.php
    └── portal/
        ├── parent/
        │   └── attendance.blade.php
        └── student/
            └── attendance.blade.php

routes/
└── web.php

docs/
├── phase-12-execution.md
└── phase-12-qr-attendance-system.md
```

---

# 12. Buat Model, Migration, Controller, Request, Command

Jalankan:

```powershell
php artisan make:model AttendanceQrToken -m
php artisan make:model AttendanceSession -m
php artisan make:model AttendanceRecord -m

php artisan make:controller Attendance/AttendanceQrCardController
php artisan make:controller Attendance/AttendanceSessionController --resource
php artisan make:controller Attendance/AttendanceScannerController
php artisan make:controller Attendance/AttendanceManualRecordController
php artisan make:controller Attendance/AttendanceReportController
php artisan make:controller Portal/ParentAttendancePortalController
php artisan make:controller Portal/StudentAttendancePortalController

php artisan make:request Attendance/StoreAttendanceSessionRequest
php artisan make:request Attendance/UpdateAttendanceSessionRequest
php artisan make:request Attendance/ScanAttendanceQrRequest
php artisan make:request Attendance/StoreManualAttendanceRecordRequest
php artisan make:request Attendance/AttendanceReportFilterRequest

php artisan make:command GenerateAttendanceQrTokensCommand
```

Buat folder service:

```powershell
mkdir app\Services\Attendance
```

Buat file service:

```powershell
New-Item app\Services\Attendance\AttendanceAccessService.php
New-Item app\Services\Attendance\AttendanceQrTokenService.php
New-Item app\Services\Attendance\QrCodeService.php
New-Item app\Services\Attendance\AttendanceScanService.php
New-Item app\Services\Attendance\AttendanceReportService.php
```

Buat folder view:

```powershell
mkdir resources\views\attendance
mkdir resources\views\attendance\qr-cards
mkdir resources\views\attendance\sessions
mkdir resources\views\attendance\scanner
mkdir resources\views\attendance\manual
mkdir resources\views\attendance\reports
```

Jika folder portal sudah ada, jangan hapus.

---

# 13. Migration `create_attendance_qr_tokens_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_attendance_qr_tokens_table.php
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
        Schema::create('attendance_qr_tokens', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->string('token', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('rotated_at')->nullable();
            $table->timestamp('last_used_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['student_id', 'is_active'], 'attendance_qr_active_student_unique');
            $table->index(['school_id', 'is_active']);
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_qr_tokens');
    }
};
```

---

# 14. Migration `create_attendance_sessions_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_attendance_sessions_table.php
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
        Schema::create('attendance_sessions', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('class_room_id')
                ->nullable()
                ->constrained('class_rooms')
                ->nullOnDelete();

            $table->string('name');
            $table->date('attendance_date');

            $table->time('check_in_starts_at')->nullable();
            $table->time('late_after_at')->nullable();
            $table->time('check_in_ends_at')->nullable();

            $table->time('check_out_starts_at')->nullable();
            $table->time('check_out_ends_at')->nullable();

            $table->enum('status', [
                'draft',
                'active',
                'closed',
            ])->default('active');

            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'attendance_date']);
            $table->index(['class_room_id', 'attendance_date']);
            $table->index(['status', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
```

---

# 15. Migration `create_attendance_records_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_attendance_records_table.php
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
        Schema::create('attendance_records', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            $table->foreignId('attendance_session_id')
                ->constrained('attendance_sessions')
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->enum('status', [
                'present',
                'late',
                'sick',
                'permission',
                'absent',
            ])->default('present');

            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();

            $table->enum('source', [
                'qr',
                'manual',
                'system',
            ])->default('qr');

            $table->text('note')->nullable();

            $table->foreignId('scanned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['attendance_session_id', 'student_id'],
                'attendance_unique_session_student'
            );

            $table->index(['school_id', 'attendance_date']);
            $table->index(['student_id', 'attendance_date']);
            $table->index(['attendance_session_id', 'status']);
            $table->index(['source', 'attendance_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
```

---

# 16. Model `AttendanceQrToken`

Buka:

```text
app/Models/AttendanceQrToken.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceQrToken extends Model
{
    protected $fillable = [
        'school_id',
        'student_id',
        'token',
        'is_active',
        'rotated_at',
        'last_used_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rotated_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

---

# 17. Model `AttendanceSession`

Buka:

```text
app/Models/AttendanceSession.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'class_room_id',
        'name',
        'attendance_date',
        'check_in_starts_at',
        'late_after_at',
        'check_in_ends_at',
        'check_out_starts_at',
        'check_out_ends_at',
        'status',
        'note',
        'created_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

---

# 18. Model `AttendanceRecord`

Buka:

```text
app/Models/AttendanceRecord.php
```

Isi lengkap:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'school_id',
        'attendance_session_id',
        'student_id',
        'attendance_date',
        'status',
        'check_in_at',
        'check_out_at',
        'source',
        'note',
        'scanned_by',
        'updated_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
```

---

# 19. Update Model `Student`

Buka:

```text
app/Models/Student.php
```

Tambahkan relasi berikut tanpa menghapus relasi lama:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

public function attendanceQrToken(): HasOne
{
    return $this->hasOne(AttendanceQrToken::class)
        ->where('is_active', true);
}

public function attendanceRecords(): HasMany
{
    return $this->hasMany(AttendanceRecord::class);
}
```

Jika sudah ada relasi dengan nama sama, jangan duplikasi.

---

# 20. Service `AttendanceAccessService`

Buka:

```text
app/Services/Attendance/AttendanceAccessService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Attendance;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AttendanceAccessService
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

    public function canManageQr(?User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function canScan(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isTeacher($user);
    }

    public function canInputManual(?User $user): bool
    {
        return $this->isAdmin($user)
            || $this->isTeacher($user);
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

        if ($this->isTeacher($user)) {
            $query = Student::query()->whereKey($student->id);

            return $this->applyStudentScope($query, $user)->exists();
        }

        if ($this->isParent($user)) {
            $query = Student::query()->whereKey($student->id);

            return $this->applyStudentScope($query, $user)->exists();
        }

        if ($this->isStudent($user)) {
            return (int) $student->user_id === (int) $user->id;
        }

        return false;
    }
}
```

---

# 21. Service `AttendanceQrTokenService`

Buka:

```text
app/Services/Attendance/AttendanceQrTokenService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Attendance;

use App\Models\AttendanceQrToken;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;

class AttendanceQrTokenService
{
    public function ensureActiveToken(Student $student, ?User $createdBy = null): AttendanceQrToken
    {
        $existing = AttendanceQrToken::query()
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->first();

        if ($existing) {
            return $existing;
        }

        return $this->createToken($student, $createdBy);
    }

    public function rotateToken(Student $student, ?User $createdBy = null): AttendanceQrToken
    {
        AttendanceQrToken::query()
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'rotated_at' => now(),
            ]);

        return $this->createToken($student, $createdBy);
    }

    public function qrPayload(AttendanceQrToken $qrToken): string
    {
        return 'HFP-ATT:' . $qrToken->token;
    }

    public function parsePayload(string $payload): string
    {
        $payload = trim($payload);

        if (str_starts_with($payload, 'HFP-ATT:')) {
            return Str::after($payload, 'HFP-ATT:');
        }

        return $payload;
    }

    private function createToken(Student $student, ?User $createdBy = null): AttendanceQrToken
    {
        do {
            $token = Str::random(64);
        } while (AttendanceQrToken::query()->where('token', $token)->exists());

        return AttendanceQrToken::query()->create([
            'school_id' => $student->school_id ?? null,
            'student_id' => $student->id,
            'token' => $token,
            'is_active' => true,
            'created_by' => $createdBy?->id,
        ]);
    }
}
```

---

# 22. Service `QrCodeService`

Buka:

```text
app/Services/Attendance/QrCodeService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Attendance;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    public function svg(string $payload, int $size = 240): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($payload);
    }
}
```

---

# 23. Service `AttendanceScanService`

Buka:

```text
app/Services/Attendance/AttendanceScanService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Attendance;

use App\Models\AttendanceQrToken;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AttendanceScanService
{
    public function __construct(
        private readonly AttendanceQrTokenService $tokenService
    ) {
    }

    public function scan(string $payload, AttendanceSession $session, User $scanner): AttendanceRecord
    {
        if ($session->status !== 'active') {
            throw ValidationException::withMessages([
                'qr_payload' => 'Session presensi tidak aktif.',
            ]);
        }

        $token = $this->tokenService->parsePayload($payload);

        $qrToken = AttendanceQrToken::query()
            ->with('student')
            ->where('token', $token)
            ->where('is_active', true)
            ->first();

        if (! $qrToken || ! $qrToken->student) {
            throw ValidationException::withMessages([
                'qr_payload' => 'QR tidak valid atau sudah tidak aktif.',
            ]);
        }

        $student = $qrToken->student;

        if ($session->class_room_id && (int) $student->class_room_id !== (int) $session->class_room_id) {
            throw ValidationException::withMessages([
                'qr_payload' => 'Santri tidak termasuk kelas pada session presensi ini.',
            ]);
        }

        $now = now();
        $status = $this->resolveStatus($session, $now);

        $record = AttendanceRecord::query()
            ->where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if (! $record) {
            $record = AttendanceRecord::query()->create([
                'school_id' => $student->school_id ?? $session->school_id,
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
                'attendance_date' => $session->attendance_date,
                'status' => $status,
                'check_in_at' => $now,
                'source' => 'qr',
                'scanned_by' => $scanner->id,
            ]);

            $qrToken->update(['last_used_at' => $now]);

            return $record;
        }

        if ($record->check_in_at && ! $record->check_out_at) {
            $record->update([
                'check_out_at' => $now,
                'source' => 'qr',
                'scanned_by' => $scanner->id,
            ]);

            $qrToken->update(['last_used_at' => $now]);

            return $record->fresh();
        }

        throw ValidationException::withMessages([
            'qr_payload' => 'Presensi santri ini sudah lengkap untuk session ini.',
        ]);
    }

    private function resolveStatus(AttendanceSession $session, Carbon $now): string
    {
        if (! $session->late_after_at) {
            return 'present';
        }

        $lateAfter = Carbon::parse($session->attendance_date->toDateString() . ' ' . $session->late_after_at);

        return $now->greaterThan($lateAfter) ? 'late' : 'present';
    }
}
```

---

# 24. Service `AttendanceReportService`

Buka:

```text
app/Services/Attendance/AttendanceReportService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Attendance;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AttendanceReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $recordQuery = AttendanceRecord::query()
            ->with(['student', 'session'])
            ->whereBetween('attendance_date', [$startDate, $endDate]);

        if (! empty($filters['class_room_id'])) {
            $recordQuery->whereHas('student', function (Builder $query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $recordQuery->where('student_id', $filters['student_id']);
        }

        $records = $recordQuery->get();

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_records' => $records->count(),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'sick' => $records->where('status', 'sick')->count(),
                'permission' => $records->where('status', 'permission')->count(),
                'absent' => $records->where('status', 'absent')->count(),
            ],
            'by_student' => $this->groupByStudent($records),
            'records' => $records->sortByDesc('attendance_date')->values(),
        ];
    }

    public function studentSnapshot(Student $student, array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $records = AttendanceRecord::query()
            ->with('session')
            ->where('student_id', $student->id)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->orderByDesc('attendance_date')
            ->get();

        return [
            'student' => $student,
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_records' => $records->count(),
                'present' => $records->where('status', 'present')->count(),
                'late' => $records->where('status', 'late')->count(),
                'sick' => $records->where('status', 'sick')->count(),
                'permission' => $records->where('status', 'permission')->count(),
                'absent' => $records->where('status', 'absent')->count(),
            ],
            'records' => $records,
        ];
    }

    private function groupByStudent(Collection $records): Collection
    {
        return $records
            ->groupBy('student_id')
            ->map(function (Collection $studentRecords): array {
                $first = $studentRecords->first();

                return [
                    'student' => $first?->student,
                    'total' => $studentRecords->count(),
                    'present' => $studentRecords->where('status', 'present')->count(),
                    'late' => $studentRecords->where('status', 'late')->count(),
                    'sick' => $studentRecords->where('status', 'sick')->count(),
                    'permission' => $studentRecords->where('status', 'permission')->count(),
                    'absent' => $studentRecords->where('status', 'absent')->count(),
                ];
            })
            ->values();
    }
}
```

---

# 25. Request `StoreAttendanceSessionRequest`

Buka:

```text
app/Http/Requests/Attendance/StoreAttendanceSessionRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $roleValue = $this->user()?->role ?? null;
        $role = is_object($roleValue) ? ($roleValue->name ?? $roleValue->slug ?? null) : $roleValue;

        return in_array($role, [
            'super_admin',
            'admin',
            'admin_sekolah',
        ], true);
    }

    public function rules(): array
    {
        return [
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'attendance_date' => ['required', 'date'],
            'check_in_starts_at' => ['nullable', 'date_format:H:i'],
            'late_after_at' => ['nullable', 'date_format:H:i'],
            'check_in_ends_at' => ['nullable', 'date_format:H:i'],
            'check_out_starts_at' => ['nullable', 'date_format:H:i'],
            'check_out_ends_at' => ['nullable', 'date_format:H:i'],
            'status' => ['required', Rule::in(['draft', 'active', 'closed'])],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
```

---

# 26. Request `UpdateAttendanceSessionRequest`

Buka:

```text
app/Http/Requests/Attendance/UpdateAttendanceSessionRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Attendance;

class UpdateAttendanceSessionRequest extends StoreAttendanceSessionRequest
{
    //
}
```

---

# 27. Request `ScanAttendanceQrRequest`

Buka:

```text
app/Http/Requests/Attendance/ScanAttendanceQrRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class ScanAttendanceQrRequest extends FormRequest
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
            'attendance_session_id' => ['required', 'exists:attendance_sessions,id'],
            'qr_payload' => ['required', 'string', 'max:255'],
        ];
    }
}
```

---

# 28. Request `StoreManualAttendanceRecordRequest`

Buka:

```text
app/Http/Requests/Attendance/StoreManualAttendanceRecordRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreManualAttendanceRecordRequest extends FormRequest
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
            'attendance_session_id' => ['required', 'exists:attendance_sessions,id'],
            'student_id' => ['required', 'exists:students,id'],
            'status' => ['required', Rule::in(['present', 'late', 'sick', 'permission', 'absent'])],
            'check_in_at' => ['nullable', 'date'],
            'check_out_at' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
```

---

# 29. Request `AttendanceReportFilterRequest`

Buka:

```text
app/Http/Requests/Attendance/AttendanceReportFilterRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceReportFilterRequest extends FormRequest
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
        ];
    }
}
```

---

# 30. Command `GenerateAttendanceQrTokensCommand`

Buka:

```text
app/Console/Commands/GenerateAttendanceQrTokensCommand.php
```

Isi lengkap:

```php
<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Services\Attendance\AttendanceQrTokenService;
use Illuminate\Console\Command;

class GenerateAttendanceQrTokensCommand extends Command
{
    protected $signature = 'app:generate-attendance-qr-tokens {--rotate : Rotate existing active tokens}';

    protected $description = 'Generate attendance QR tokens for students.';

    public function handle(AttendanceQrTokenService $tokenService): int
    {
        $rotate = (bool) $this->option('rotate');

        $students = Student::query()->get();

        $bar = $this->output->createProgressBar($students->count());
        $bar->start();

        foreach ($students as $student) {
            if ($rotate) {
                $tokenService->rotateToken($student);
            } else {
                $tokenService->ensureActiveToken($student);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Attendance QR tokens generated successfully.');

        return self::SUCCESS;
    }
}
```

---

# 31. Controller `AttendanceQrCardController`

Buka:

```text
app/Http/Controllers/Attendance/AttendanceQrCardController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceQrTokenService;
use App\Services\Attendance\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceQrCardController extends Controller
{
    public function index(
        Request $request,
        AttendanceAccessService $accessService,
        AttendanceQrTokenService $tokenService
    ): View {
        abort_unless($accessService->canManageQr($request->user()), 403);

        $students = Student::query()
            ->with('attendanceQrToken')
            ->orderBy('nama_lengkap')
            ->paginate(30);

        foreach ($students as $student) {
            $tokenService->ensureActiveToken($student, $request->user());
        }

        return view('attendance.qr-cards.index', compact('students'));
    }

    public function print(
        Request $request,
        AttendanceAccessService $accessService,
        AttendanceQrTokenService $tokenService,
        QrCodeService $qrCodeService
    ): View {
        abort_unless($accessService->canManageQr($request->user()), 403);

        $students = Student::query()
            ->with('attendanceQrToken')
            ->orderBy('nama_lengkap')
            ->get();

        $qrCards = $students->map(function (Student $student) use ($tokenService, $qrCodeService, $request): array {
            $token = $tokenService->ensureActiveToken($student, $request->user());
            $payload = $tokenService->qrPayload($token);

            return [
                'student' => $student,
                'svg' => $qrCodeService->svg($payload, 220),
            ];
        });

        return view('attendance.qr-cards.print', compact('qrCards'));
    }

    public function rotate(
        Student $student,
        Request $request,
        AttendanceAccessService $accessService,
        AttendanceQrTokenService $tokenService
    ): RedirectResponse {
        abort_unless($accessService->canManageQr($request->user()), 403);

        $tokenService->rotateToken($student, $request->user());

        return back()->with('success', 'QR token santri berhasil diganti.');
    }
}
```

Catatan:

Jika tabel `students` tidak punya kolom `nama_lengkap`, ubah `orderBy('nama_lengkap')` sesuai kolom asli.

---

# 32. Controller `AttendanceSessionController`

Buka:

```text
app/Http/Controllers/Attendance/AttendanceSessionController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceSessionRequest;
use App\Http\Requests\Attendance\UpdateAttendanceSessionRequest;
use App\Models\AttendanceSession;
use App\Models\ClassRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceSessionController extends Controller
{
    public function index(): View
    {
        $sessions = AttendanceSession::query()
            ->with('classRoom')
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('attendance.sessions.index', compact('sessions'));
    }

    public function create(): View
    {
        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        return view('attendance.sessions.create', compact('classRooms'));
    }

    public function store(StoreAttendanceSessionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['school_id'] = $request->user()->school_id ?? null;

        AttendanceSession::query()->create($data);

        return redirect()
            ->route('attendance.sessions.index')
            ->with('success', 'Session presensi berhasil dibuat.');
    }

    public function show(AttendanceSession $session): View
    {
        $session->load([
            'classRoom',
            'records.student',
            'records.scanner',
        ]);

        return view('attendance.sessions.show', compact('session'));
    }

    public function edit(AttendanceSession $session): View
    {
        $classRooms = ClassRoom::query()
            ->orderBy('name')
            ->get();

        return view('attendance.sessions.edit', compact('session', 'classRooms'));
    }

    public function update(UpdateAttendanceSessionRequest $request, AttendanceSession $session): RedirectResponse
    {
        $session->update($request->validated());

        return redirect()
            ->route('attendance.sessions.index')
            ->with('success', 'Session presensi berhasil diperbarui.');
    }

    public function destroy(AttendanceSession $session): RedirectResponse
    {
        $session->delete();

        return redirect()
            ->route('attendance.sessions.index')
            ->with('success', 'Session presensi berhasil dihapus.');
    }
}
```

Catatan:

Jika `ClassRoom` tidak punya kolom `name`, ubah sesuai kolom asli.

---

# 33. Controller `AttendanceScannerController`

Buka:

```text
app/Http/Controllers/Attendance/AttendanceScannerController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\ScanAttendanceQrRequest;
use App\Models\AttendanceSession;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceScanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceScannerController extends Controller
{
    public function index(Request $request, AttendanceAccessService $accessService): View
    {
        abort_unless($accessService->canScan($request->user()), 403);

        $sessions = AttendanceSession::query()
            ->where('status', 'active')
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->get();

        return view('attendance.scanner.index', compact('sessions'));
    }

    public function scan(
        ScanAttendanceQrRequest $request,
        AttendanceScanService $scanService
    ): JsonResponse {
        $session = AttendanceSession::query()
            ->whereKey($request->validated('attendance_session_id'))
            ->firstOrFail();

        $record = $scanService->scan(
            payload: $request->validated('qr_payload'),
            session: $session,
            scanner: $request->user()
        );

        $record->load('student');

        return response()->json([
            'message' => 'Presensi berhasil disimpan.',
            'record' => [
                'student_name' => $record->student?->nama_lengkap ?? $record->student?->name ?? 'Santri',
                'status' => $record->status,
                'check_in_at' => $record->check_in_at?->format('H:i:s'),
                'check_out_at' => $record->check_out_at?->format('H:i:s'),
            ],
        ]);
    }
}
```

---

# 34. Controller `AttendanceManualRecordController`

Buka:

```text
app/Http/Controllers/Attendance/AttendanceManualRecordController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreManualAttendanceRecordRequest;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceManualRecordController extends Controller
{
    public function create(Request $request, AttendanceAccessService $accessService): View
    {
        abort_unless($accessService->canInputManual($request->user()), 403);

        $sessions = AttendanceSession::query()
            ->whereIn('status', ['active', 'closed'])
            ->orderByDesc('attendance_date')
            ->limit(50)
            ->get();

        $students = $accessService
            ->applyStudentScope(Student::query()->orderBy('nama_lengkap'), $request->user())
            ->limit(300)
            ->get();

        return view('attendance.manual.create', compact('sessions', 'students'));
    }

    public function store(
        StoreManualAttendanceRecordRequest $request,
        AttendanceAccessService $accessService
    ): RedirectResponse {
        $session = AttendanceSession::query()->findOrFail($request->validated('attendance_session_id'));
        $student = Student::query()->findOrFail($request->validated('student_id'));

        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        AttendanceRecord::query()->updateOrCreate(
            [
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ],
            [
                'school_id' => $student->school_id ?? $session->school_id,
                'attendance_date' => $session->attendance_date,
                'status' => $request->validated('status'),
                'check_in_at' => $request->validated('check_in_at'),
                'check_out_at' => $request->validated('check_out_at'),
                'source' => 'manual',
                'note' => $request->validated('note'),
                'updated_by' => $request->user()->id,
            ]
        );

        return back()->with('success', 'Presensi manual berhasil disimpan.');
    }
}
```

---

# 35. Controller `AttendanceReportController`

Buka:

```text
app/Http/Controllers/Attendance/AttendanceReportController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceReportFilterRequest;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceReportService;
use Illuminate\View\View;

class AttendanceReportController extends Controller
{
    public function dashboard(
        AttendanceReportFilterRequest $request,
        AttendanceAccessService $accessService,
        AttendanceReportService $reportService
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

        return view('attendance.reports.dashboard', compact(
            'report',
            'filters',
            'classRooms',
            'students'
        ));
    }
}
```

---

# 36. Controller `ParentAttendancePortalController`

Buka:

```text
app/Http/Controllers/Portal/ParentAttendancePortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceReportFilterRequest;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceReportService;
use Illuminate\View\View;

class ParentAttendancePortalController extends Controller
{
    public function index(
        AttendanceReportFilterRequest $request,
        AttendanceAccessService $accessService,
        AttendanceReportService $reportService
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

        return view('portal.parent.attendance', compact(
            'students',
            'selectedStudent',
            'snapshot'
        ));
    }
}
```

---

# 37. Controller `StudentAttendancePortalController`

Buka:

```text
app/Http/Controllers/Portal/StudentAttendancePortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\AttendanceReportFilterRequest;
use App\Models\Student;
use App\Services\Attendance\AttendanceAccessService;
use App\Services\Attendance\AttendanceReportService;
use Illuminate\View\View;

class StudentAttendancePortalController extends Controller
{
    public function index(
        AttendanceReportFilterRequest $request,
        AttendanceAccessService $accessService,
        AttendanceReportService $reportService
    ): View {
        abort_unless($accessService->isStudent($request->user()), 403);

        $student = Student::query()
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $student) {
            return view('portal.student.attendance', [
                'student' => null,
                'snapshot' => null,
            ]);
        }

        $snapshot = $reportService->studentSnapshot($student, $request->validated());

        return view('portal.student.attendance', compact('student', 'snapshot'));
    }
}
```

---

# 38. View `attendance/qr-cards/index.blade.php`

Buat:

```text
resources/views/attendance/qr-cards/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">QR Santri</h1>
            <p class="text-sm text-gray-600">Kelola QR presensi santri.</p>
        </div>

        <a href="{{ route('attendance.qr-cards.print') }}"
           target="_blank"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Cetak QR
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
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">QR Aktif</th>
                    <th class="px-4 py-3 text-left">Terakhir Dipakai</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $student)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">
                            {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $student->attendanceQrToken ? 'Ya' : 'Belum' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $student->attendanceQrToken?->last_used_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('attendance.qr-cards.rotate', $student) }}"
                                  method="POST"
                                  onsubmit="return confirm('Ganti QR santri ini? QR lama akan tidak aktif.')">
                                @csrf
                                <button class="text-red-600 hover:underline">
                                    Rotate QR
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            Belum ada data santri.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
@endsection
```

---

# 39. View `attendance/qr-cards/print.blade.php`

Buat:

```text
resources/views/attendance/qr-cards/print.blade.php
```

Isi lengkap:

```blade
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak QR Presensi Santri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }

        .card {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            page-break-inside: avoid;
        }

        .name {
            font-weight: 700;
            font-size: 14px;
            margin-top: 8px;
        }

        .meta {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 16px;">
        <button onclick="window.print()">Cetak</button>
    </div>

    <div class="grid">
        @foreach($qrCards as $card)
            <div class="card">
                <div>{!! $card['svg'] !!}</div>
                <div class="name">
                    {{ $card['student']->nama_lengkap ?? $card['student']->name ?? 'Santri #' . $card['student']->id }}
                </div>
                <div class="meta">
                    HafizPlus School Platform — QR Attendance
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
```

---

# 40. View `attendance/scanner/index.blade.php`

Buat:

```text
resources/views/attendance/scanner/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Scanner QR Attendance</h1>
        <p class="text-sm text-gray-600">Scan QR santri untuk check in/check out.</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700">Session Presensi</label>
            <select id="attendance_session_id" class="mt-1 w-full rounded-lg border-gray-300">
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">
                        {{ $session->name }} — {{ $session->attendance_date?->format('d M Y') }}
                    </option>
                @endforeach
            </select>
        </div>

        @if($sessions->isEmpty())
            <div class="rounded-lg bg-yellow-50 px-4 py-3 text-yellow-700">
                Belum ada session presensi aktif. Buat session terlebih dahulu.
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <video id="preview" class="w-full rounded-lg bg-black" autoplay muted playsinline></video>

                    <div class="mt-3 flex gap-2">
                        <button id="startScanner" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                            Mulai Scanner
                        </button>
                        <button id="stopScanner" class="px-4 py-2 bg-gray-600 text-white rounded-lg">
                            Stop
                        </button>
                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        Jika kamera/browser tidak mendukung scanner otomatis, gunakan input manual token di samping.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Manual QR Payload</label>
                    <textarea id="qr_payload"
                              rows="5"
                              class="mt-1 w-full rounded-lg border-gray-300"
                              placeholder="Tempel payload QR di sini"></textarea>

                    <button id="submitManual"
                            class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg">
                        Submit Presensi
                    </button>

                    <div id="scanResult" class="mt-4 rounded-lg px-4 py-3 hidden"></div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    const csrfToken = @json(csrf_token());
    const scanUrl = @json(route('attendance.scanner.scan'));

    let stream = null;
    let detector = null;
    let scanning = false;
    let lastPayload = null;
    let lastScanAt = 0;

    const video = document.getElementById('preview');
    const resultBox = document.getElementById('scanResult');

    function showResult(message, success = true) {
        resultBox.classList.remove('hidden');
        resultBox.classList.remove('bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700');

        if (success) {
            resultBox.classList.add('bg-green-50', 'text-green-700');
        } else {
            resultBox.classList.add('bg-red-50', 'text-red-700');
        }

        resultBox.textContent = message;
    }

    async function submitPayload(payload) {
        const sessionId = document.getElementById('attendance_session_id').value;

        if (!sessionId) {
            showResult('Pilih session presensi terlebih dahulu.', false);
            return;
        }

        if (!payload) {
            showResult('QR payload kosong.', false);
            return;
        }

        const now = Date.now();

        if (payload === lastPayload && now - lastScanAt < 3000) {
            return;
        }

        lastPayload = payload;
        lastScanAt = now;

        try {
            const response = await fetch(scanUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    attendance_session_id: sessionId,
                    qr_payload: payload,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                const message = data.message || Object.values(data.errors || {}).flat().join(' ') || 'Scan gagal.';
                showResult(message, false);
                return;
            }

            const record = data.record;
            showResult(`${record.student_name} — ${record.status} — IN: ${record.check_in_at || '-'} OUT: ${record.check_out_at || '-'}`, true);
        } catch (error) {
            showResult('Gagal mengirim data presensi.', false);
        }
    }

    async function startScanner() {
        if (!('BarcodeDetector' in window)) {
            showResult('Browser tidak mendukung BarcodeDetector. Gunakan input manual token.', false);
            return;
        }

        detector = new BarcodeDetector({ formats: ['qr_code'] });

        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' },
            audio: false,
        });

        video.srcObject = stream;
        scanning = true;

        requestAnimationFrame(scanLoop);
    }

    async function scanLoop() {
        if (!scanning || !detector || !video) {
            return;
        }

        try {
            const barcodes = await detector.detect(video);

            if (barcodes.length > 0) {
                await submitPayload(barcodes[0].rawValue);
            }
        } catch (error) {
            // Abaikan frame error.
        }

        requestAnimationFrame(scanLoop);
    }

    function stopScanner() {
        scanning = false;

        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    }

    document.getElementById('startScanner')?.addEventListener('click', startScanner);
    document.getElementById('stopScanner')?.addEventListener('click', stopScanner);

    document.getElementById('submitManual')?.addEventListener('click', function () {
        const payload = document.getElementById('qr_payload').value.trim();
        submitPayload(payload);
    });
</script>
@endsection
```

---

# 41. View `attendance/sessions/index.blade.php`

Buat:

```text
resources/views/attendance/sessions/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Session Presensi</h1>
            <p class="text-sm text-gray-600">Kelola session presensi harian.</p>
        </div>

        <a href="{{ route('attendance.sessions.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Buat Session
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
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Kelas</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sessions as $session)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $session->name }}</td>
                        <td class="px-4 py-3">{{ $session->attendance_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $session->classRoom?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ ucfirst($session->status) }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('attendance.sessions.show', $session) }}" class="text-blue-600 hover:underline">Detail</a>
                            <a href="{{ route('attendance.sessions.edit', $session) }}" class="text-amber-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            Belum ada session presensi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
```

---

# 42. View Partial `attendance/sessions/_form.blade.php`

Buat:

```text
resources/views/attendance/sessions/_form.blade.php
```

Isi lengkap:

```blade
@php
    $session = $session ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Session</label>
        <input type="text"
               name="name"
               value="{{ old('name', $session?->name ?? 'Presensi Harian') }}"
               class="mt-1 w-full rounded-lg border-gray-300"
               required>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal</label>
        <input type="date"
               name="attendance_date"
               value="{{ old('attendance_date', $session?->attendance_date?->toDateString() ?? now()->toDateString()) }}"
               class="mt-1 w-full rounded-lg border-gray-300"
               required>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Kelas</label>
    <select name="class_room_id" class="mt-1 w-full rounded-lg border-gray-300">
        <option value="">Semua kelas</option>
        @foreach($classRooms as $classRoom)
            <option value="{{ $classRoom->id }}" @selected(old('class_room_id', $session?->class_room_id) == $classRoom->id)>
                {{ $classRoom->name ?? $classRoom->nama ?? 'Kelas #' . $classRoom->id }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-1 md:grid-cols-5 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Mulai Masuk</label>
        <input type="time" name="check_in_starts_at" value="{{ old('check_in_starts_at', $session?->check_in_starts_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Batas Terlambat</label>
        <input type="time" name="late_after_at" value="{{ old('late_after_at', $session?->late_after_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Akhir Masuk</label>
        <input type="time" name="check_in_ends_at" value="{{ old('check_in_ends_at', $session?->check_in_ends_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Mulai Pulang</label>
        <input type="time" name="check_out_starts_at" value="{{ old('check_out_starts_at', $session?->check_out_starts_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Akhir Pulang</label>
        <input type="time" name="check_out_ends_at" value="{{ old('check_out_ends_at', $session?->check_out_ends_at) }}" class="mt-1 w-full rounded-lg border-gray-300">
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" class="mt-1 w-full rounded-lg border-gray-300">
        @foreach(['draft' => 'Draft', 'active' => 'Active', 'closed' => 'Closed'] as $value => $label)
            <option value="{{ $value }}" @selected(old('status', $session?->status ?? 'active') === $value)>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-medium text-gray-700">Catatan</label>
    <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300">{{ old('note', $session?->note) }}</textarea>
</div>
```

---

# 43. View `attendance/sessions/create.blade.php`

Buat:

```text
resources/views/attendance/sessions/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Buat Session Presensi</h1>
        <p class="text-sm text-gray-600">Buat session presensi harian.</p>
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

    <form action="{{ route('attendance.sessions.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        @include('attendance.sessions._form', [
            'session' => null,
            'classRooms' => $classRooms,
        ])

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('attendance.sessions.index') }}" class="px-4 py-2 border rounded-lg">
                Batal
            </a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
```

---

# 44. View `attendance/sessions/edit.blade.php`

Buat:

```text
resources/views/attendance/sessions/edit.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Session Presensi</h1>
        <p class="text-sm text-gray-600">{{ $session->name }}</p>
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

    <form action="{{ route('attendance.sessions.update', $session) }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        @include('attendance.sessions._form', [
            'session' => $session,
            'classRooms' => $classRooms,
        ])

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('attendance.sessions.index') }}" class="px-4 py-2 border rounded-lg">
                Batal
            </a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
```

---

# 45. View `attendance/sessions/show.blade.php`

Buat:

```text
resources/views/attendance/sessions/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $session->name }}</h1>
            <p class="text-sm text-gray-600">
                {{ $session->attendance_date?->format('d M Y') }} · {{ ucfirst($session->status) }}
            </p>
        </div>

        <a href="{{ route('attendance.scanner.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Buka Scanner
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Check In</th>
                    <th class="px-4 py-3 text-left">Check Out</th>
                    <th class="px-4 py-3 text-left">Source</th>
                    <th class="px-4 py-3 text-left">Scanner</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($session->records as $record)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $record->student?->nama_lengkap ?? $record->student?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3">{{ ucfirst($record->status) }}</td>
                        <td class="px-4 py-3">{{ $record->check_in_at?->format('H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $record->check_out_at?->format('H:i:s') ?? '-' }}</td>
                        <td class="px-4 py-3">{{ ucfirst($record->source) }}</td>
                        <td class="px-4 py-3">{{ $record->scanner?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Belum ada data presensi pada session ini.
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

# 46. View `attendance/manual/create.blade.php`

Buat:

```text
resources/views/attendance/manual/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Input Presensi Manual</h1>
        <p class="text-sm text-gray-600">Gunakan untuk izin, sakit, absen, atau koreksi data.</p>
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

    <form action="{{ route('attendance.manual.store') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">Session</label>
            <select name="attendance_session_id" class="mt-1 w-full rounded-lg border-gray-300" required>
                @foreach($sessions as $session)
                    <option value="{{ $session->id }}">
                        {{ $session->name }} — {{ $session->attendance_date?->format('d M Y') }}
                    </option>
                @endforeach
            </select>
        </div>

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
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border-gray-300" required>
                <option value="present">Hadir</option>
                <option value="late">Terlambat</option>
                <option value="sick">Sakit</option>
                <option value="permission">Izin</option>
                <option value="absent">Tidak Hadir</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Check In</label>
                <input type="datetime-local" name="check_in_at" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Check Out</label>
                <input type="datetime-local" name="check_out_at" class="mt-1 w-full rounded-lg border-gray-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catatan</label>
            <textarea name="note" rows="3" class="mt-1 w-full rounded-lg border-gray-300"></textarea>
        </div>

        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
```

---

# 47. View `attendance/reports/dashboard.blade.php`

Buat:

```text
resources/views/attendance/reports/dashboard.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Laporan Presensi</h1>
        <p class="text-sm text-gray-600">Ringkasan presensi santri.</p>
    </div>

    <form method="GET" action="{{ route('attendance.reports.dashboard') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-5 gap-4">
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
                        {{ $student->nama_lengkap ?? $student->name ?? 'Santri #' . $student->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Filter
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Total</div>
            <div class="text-2xl font-bold">{{ $report['summary']['total_records'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Hadir</div>
            <div class="text-2xl font-bold text-green-700">{{ $report['summary']['present'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Terlambat</div>
            <div class="text-2xl font-bold text-amber-700">{{ $report['summary']['late'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Sakit</div>
            <div class="text-2xl font-bold text-blue-700">{{ $report['summary']['sick'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Izin</div>
            <div class="text-2xl font-bold text-purple-700">{{ $report['summary']['permission'] }}</div>
        </div>

        <div class="bg-white rounded-xl shadow p-4">
            <div class="text-sm text-gray-500">Alpa</div>
            <div class="text-2xl font-bold text-red-700">{{ $report['summary']['absent'] }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-right">Hadir</th>
                    <th class="px-4 py-3 text-right">Telat</th>
                    <th class="px-4 py-3 text-right">Sakit</th>
                    <th class="px-4 py-3 text-right">Izin</th>
                    <th class="px-4 py-3 text-right">Alpa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($report['by_student'] as $row)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $row['student']?->nama_lengkap ?? $row['student']?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-right">{{ $row['total'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $row['present'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $row['late'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $row['sick'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $row['permission'] }}</td>
                        <td class="px-4 py-3 text-right">{{ $row['absent'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Belum ada data presensi.
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

# 48. View `portal/parent/attendance.blade.php`

Buat:

```text
resources/views/portal/parent/attendance.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Presensi Anak</h1>
        <p class="text-sm text-gray-600">Pantau kehadiran anak.</p>
    </div>

    @if($students->isEmpty())
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Belum ada data anak yang terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.parent.attendance') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $snapshot['period']['start_date'] ?? now()->startOfMonth()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $snapshot['period']['end_date'] ?? now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Filter
                </button>
            </div>
        </form>

        @if($snapshot)
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Hadir</div>
                    <div class="text-2xl font-bold text-green-700">{{ $snapshot['summary']['present'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Telat</div>
                    <div class="text-2xl font-bold text-amber-700">{{ $snapshot['summary']['late'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Sakit</div>
                    <div class="text-2xl font-bold text-blue-700">{{ $snapshot['summary']['sick'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Izin</div>
                    <div class="text-2xl font-bold text-purple-700">{{ $snapshot['summary']['permission'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Alpa</div>
                    <div class="text-2xl font-bold text-red-700">{{ $snapshot['summary']['absent'] }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Session</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Masuk</th>
                            <th class="px-4 py-3 text-left">Pulang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($snapshot['records'] as $record)
                            <tr>
                                <td class="px-4 py-3">{{ $record->attendance_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $record->session?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ ucfirst($record->status) }}</td>
                                <td class="px-4 py-3">{{ $record->check_in_at?->format('H:i') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $record->check_out_at?->format('H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data presensi.
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

# 49. View `portal/student/attendance.blade.php`

Buat:

```text
resources/views/portal/student/attendance.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Presensi Saya</h1>
        <p class="text-sm text-gray-600">Lihat riwayat kehadiran pribadi.</p>
    </div>

    @if(! $student)
        <div class="bg-white rounded-xl shadow p-6 text-gray-600">
            Profil santri belum terhubung. Hubungi admin sekolah.
        </div>
    @else
        <form method="GET" action="{{ route('portal.student.attendance') }}" class="mb-6 bg-white rounded-xl shadow p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $snapshot['period']['start_date'] ?? now()->startOfMonth()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $snapshot['period']['end_date'] ?? now()->toDateString() }}" class="mt-1 w-full rounded-lg border-gray-300">
            </div>

            <div class="flex items-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Filter
                </button>
            </div>
        </form>

        @if($snapshot)
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Hadir</div>
                    <div class="text-2xl font-bold text-green-700">{{ $snapshot['summary']['present'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Telat</div>
                    <div class="text-2xl font-bold text-amber-700">{{ $snapshot['summary']['late'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Sakit</div>
                    <div class="text-2xl font-bold text-blue-700">{{ $snapshot['summary']['sick'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Izin</div>
                    <div class="text-2xl font-bold text-purple-700">{{ $snapshot['summary']['permission'] }}</div>
                </div>
                <div class="bg-white rounded-xl shadow p-4">
                    <div class="text-sm text-gray-500">Alpa</div>
                    <div class="text-2xl font-bold text-red-700">{{ $snapshot['summary']['absent'] }}</div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Session</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Masuk</th>
                            <th class="px-4 py-3 text-left">Pulang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($snapshot['records'] as $record)
                            <tr>
                                <td class="px-4 py-3">{{ $record->attendance_date?->format('d M Y') }}</td>
                                <td class="px-4 py-3">{{ $record->session?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ ucfirst($record->status) }}</td>
                                <td class="px-4 py-3">{{ $record->check_in_at?->format('H:i') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $record->check_out_at?->format('H:i') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                    Belum ada data presensi.
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

# 50. Update Routes `routes/web.php`

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Attendance\AttendanceQrCardController;
use App\Http\Controllers\Attendance\AttendanceSessionController;
use App\Http\Controllers\Attendance\AttendanceScannerController;
use App\Http\Controllers\Attendance\AttendanceManualRecordController;
use App\Http\Controllers\Attendance\AttendanceReportController;
use App\Http\Controllers\Portal\ParentAttendancePortalController;
use App\Http\Controllers\Portal\StudentAttendancePortalController;
```

Tambahkan route di dalam middleware `auth`:

```php
Route::middleware(['auth'])->group(function (): void {
    Route::prefix('attendance')
        ->name('attendance.')
        ->middleware(['role:super_admin,admin,admin_sekolah,kepala_sekolah,principal,teacher,guru,guru_tahfidz'])
        ->group(function (): void {
            Route::get('/qr-cards', [AttendanceQrCardController::class, 'index'])
                ->name('qr-cards.index');

            Route::get('/qr-cards/print', [AttendanceQrCardController::class, 'print'])
                ->name('qr-cards.print');

            Route::post('/qr-cards/{student}/rotate', [AttendanceQrCardController::class, 'rotate'])
                ->name('qr-cards.rotate');

            Route::get('/scanner', [AttendanceScannerController::class, 'index'])
                ->name('scanner.index');

            Route::post('/scanner/scan', [AttendanceScannerController::class, 'scan'])
                ->name('scanner.scan');

            Route::get('/manual/create', [AttendanceManualRecordController::class, 'create'])
                ->name('manual.create');

            Route::post('/manual', [AttendanceManualRecordController::class, 'store'])
                ->name('manual.store');

            Route::get('/reports/dashboard', [AttendanceReportController::class, 'dashboard'])
                ->name('reports.dashboard');

            Route::resource('sessions', AttendanceSessionController::class);
        });

    Route::get('/portal/parent/attendance', [ParentAttendancePortalController::class, 'index'])
        ->middleware(['role:parent'])
        ->name('portal.parent.attendance');

    Route::get('/portal/student/attendance', [StudentAttendancePortalController::class, 'index'])
        ->middleware(['role:student'])
        ->name('portal.student.attendance');
});
```

Jika middleware role project hanya menerima role tertentu, sesuaikan dengan role database.

Jangan hapus route lama.

---

# 51. Update Navigation

Buka file navigation:

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
    <a href="{{ route('attendance.reports.dashboard') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Attendance
    </a>
@endif

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah'], true))
    <a href="{{ route('attendance.sessions.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Session Presensi
    </a>

    <a href="{{ route('attendance.qr-cards.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        QR Santri
    </a>
@endif

@if(in_array($roleName, ['super_admin', 'admin', 'admin_sekolah', 'teacher', 'guru', 'guru_tahfidz'], true))
    <a href="{{ route('attendance.scanner.index') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Scanner QR
    </a>

    <a href="{{ route('attendance.manual.create') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Presensi Manual
    </a>
@endif

@if($roleName === 'parent')
    <a href="{{ route('portal.parent.attendance') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Presensi Anak
    </a>
@endif

@if($roleName === 'student')
    <a href="{{ route('portal.student.attendance') }}"
       class="block px-3 py-2 rounded-md text-sm font-medium">
        Presensi Saya
    </a>
@endif
```

Sesuaikan class dengan style navigation project.

---

# 52. Jalankan Migration dan Generate QR Token

Jalankan:

```powershell
php artisan migrate
php artisan app:generate-attendance-qr-tokens
```

Cek via tinker:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\AttendanceQrToken::count();
App\Models\AttendanceSession::count();
App\Models\AttendanceRecord::count();
```

Target:

```text
AttendanceQrToken > 0 jika students sudah ada
AttendanceSession = 0 boleh
AttendanceRecord = 0 boleh
```

---

# 53. Validasi Route

Jalankan:

```powershell
php artisan route:list --name=attendance
php artisan route:list --name=portal.parent.attendance
php artisan route:list --name=portal.student.attendance
```

Target route tersedia:

```text
attendance.qr-cards.index
attendance.qr-cards.print
attendance.qr-cards.rotate
attendance.scanner.index
attendance.scanner.scan
attendance.manual.create
attendance.manual.store
attendance.reports.dashboard
attendance.sessions.index
attendance.sessions.create
attendance.sessions.store
attendance.sessions.show
attendance.sessions.edit
attendance.sessions.update
attendance.sessions.destroy
portal.parent.attendance
portal.student.attendance
```

---

# 54. UAT Phase 12

## 54.1 Test Admin

Login sebagai admin.

Tes:

1. Buka `/attendance/sessions`.
2. Buat session presensi aktif.
3. Buka `/attendance/qr-cards`.
4. Pastikan santri punya QR aktif.
5. Buka `/attendance/qr-cards/print`.
6. Cetak atau tampilkan QR.
7. Buka `/attendance/scanner`.
8. Pilih session.
9. Scan QR santri.
10. Buka detail session.
11. Pastikan record masuk.
12. Scan QR yang sama lagi.
13. Pastikan check out terisi.
14. Buka laporan presensi.
15. Filter tanggal.
16. Pastikan summary benar.

Expected:

1. Tidak ada error 500.
2. Record tidak duplicate.
3. Scan pertama mengisi check in.
4. Scan kedua mengisi check out.
5. Scan ketiga ditolak.
6. Status late otomatis jika melewati `late_after_at`.

---

## 54.2 Test QR Rotate

Login sebagai admin.

Tes:

1. Buka QR card santri.
2. Rotate QR.
3. Coba scan QR lama.
4. Coba scan QR baru.

Expected:

1. QR lama ditolak.
2. QR baru diterima.
3. Token lama tidak aktif.

---

## 54.3 Test Guru

Login sebagai guru.

Tes:

1. Buka scanner.
2. Scan QR santri dalam scope guru.
3. Buka input manual.
4. Input izin/sakit jika diperlukan.
5. Coba akses QR card management.

Expected:

1. Guru bisa scan.
2. Guru bisa input manual sesuai scope.
3. Guru tidak bisa regenerate QR.
4. Guru tidak bisa akses QR card management admin.

---

## 54.4 Test Kepala Sekolah

Login sebagai kepala sekolah.

Tes:

1. Buka laporan attendance.
2. Coba buka scanner.
3. Coba buka QR card management.
4. Coba input manual.

Expected:

1. Bisa lihat laporan.
2. Tidak bisa regenerate QR.
3. Tidak bisa input manual jika kebijakan read-only.
4. Tidak bisa scan jika tidak diberi role operator.

---

## 54.5 Test Parent

Login sebagai parent.

Tes:

1. Buka `/portal/parent/attendance`.
2. Pastikan hanya anak sendiri yang tampil.
3. Ubah query `student_id` ke ID anak lain.
4. Pastikan tidak bisa melihat data anak lain.
5. Coba buka `/attendance/scanner`.
6. Coba buka `/attendance/reports/dashboard`.

Expected:

1. Parent hanya melihat anak sendiri.
2. Tidak ada data bocor.
3. Scanner ditolak.
4. Internal report ditolak.

---

## 54.6 Test Student

Login sebagai student.

Tes:

1. Buka `/portal/student/attendance`.
2. Pastikan hanya data pribadi tampil.
3. Coba buka `/attendance/scanner`.
4. Coba buka `/attendance/manual/create`.

Expected:

1. Student hanya melihat data pribadi.
2. Scanner ditolak.
3. Manual input ditolak.

---

# 55. Build Frontend

Jalankan:

```powershell
npm run build
```

Build wajib berhasil.

---

# 56. Validasi Akhir

Jalankan:

```powershell
php artisan migrate:status
php artisan route:list --name=attendance
php artisan route:list --name=portal.parent.attendance
php artisan route:list --name=portal.student.attendance
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
http://127.0.0.1:8000/attendance/sessions
http://127.0.0.1:8000/attendance/qr-cards
http://127.0.0.1:8000/attendance/qr-cards/print
http://127.0.0.1:8000/attendance/scanner
http://127.0.0.1:8000/attendance/manual/create
http://127.0.0.1:8000/attendance/reports/dashboard
http://127.0.0.1:8000/portal/parent/attendance
http://127.0.0.1:8000/portal/student/attendance
```

---

# 57. Dokumentasi Phase 12

Buat file:

```text
docs/phase-12-qr-attendance-system.md
```

Isi lengkap:

````md
# Phase 12 — QR Attendance System

## Status

Phase 12 menambahkan modul QR Attendance System untuk HafizPlus School Platform.

## Scope

Modul ini mencakup:

1. QR unik per santri.
2. Generate QR token internal.
3. Rotate QR token.
4. Cetak QR card.
5. Web QR scanner.
6. Attendance session.
7. Check in.
8. Check out opsional.
9. Late detection.
10. Manual attendance record.
11. Attendance report.
12. Parent attendance portal.
13. Student attendance portal.
14. Role-based access.
15. Ownership-based access.

## Tabel Baru

1. `attendance_qr_tokens`
2. `attendance_sessions`
3. `attendance_records`

## Model Baru

1. `AttendanceQrToken`
2. `AttendanceSession`
3. `AttendanceRecord`

## Controller Baru

1. `AttendanceQrCardController`
2. `AttendanceSessionController`
3. `AttendanceScannerController`
4. `AttendanceManualRecordController`
5. `AttendanceReportController`
6. `ParentAttendancePortalController`
7. `StudentAttendancePortalController`

## Service Baru

1. `AttendanceAccessService`
2. `AttendanceQrTokenService`
3. `QrCodeService`
4. `AttendanceScanService`
5. `AttendanceReportService`

## Command Baru

```powershell
php artisan app:generate-attendance-qr-tokens
````

## Package Baru

```powershell
composer require bacon/bacon-qr-code
```

## Route Baru

1. `attendance.qr-cards.index`
2. `attendance.qr-cards.print`
3. `attendance.qr-cards.rotate`
4. `attendance.scanner.index`
5. `attendance.scanner.scan`
6. `attendance.manual.create`
7. `attendance.manual.store`
8. `attendance.reports.dashboard`
9. `attendance.sessions.index`
10. `attendance.sessions.create`
11. `attendance.sessions.store`
12. `attendance.sessions.show`
13. `attendance.sessions.edit`
14. `attendance.sessions.update`
15. `attendance.sessions.destroy`
16. `portal.parent.attendance`
17. `portal.student.attendance`

## Role Access

| Role           | Akses                                  |
| -------------- | -------------------------------------- |
| Super Admin    | QR card, scanner, manual input, report |
| Admin          | QR card, scanner, manual input, report |
| Kepala Sekolah | Report read-only                       |
| Guru           | Scanner dan manual input terbatas      |
| Parent         | Portal presensi anak sendiri           |
| Student        | Portal presensi pribadi                |

## Batasan

Phase 12 tidak membuat:

1. Tahsin.
2. Finance.
3. Cashless.
4. White-label.
5. Native mobile app.
6. WhatsApp gateway.
7. Push notification.
8. Face recognition.
9. RFID.
10. NFC.
11. Fingerprint.
12. Integrasi mesin absensi.
13. Multi-tenant kompleks.

## Definition of Done

Phase 12 selesai jika:

1. Migration berhasil.
2. QR token berhasil dibuat untuk santri.
3. QR card bisa dicetak.
4. Admin bisa rotate QR.
5. QR lama tidak bisa dipakai setelah rotate.
6. Scanner bisa scan QR.
7. Scan pertama membuat check in.
8. Scan kedua membuat check out.
9. Scan ketiga ditolak.
10. Late detection berjalan.
11. Manual input izin/sakit/absen berjalan.
12. Report attendance tampil.
13. Parent hanya melihat presensi anak sendiri.
14. Student hanya melihat presensi pribadi.
15. Parent/student tidak bisa mengakses scanner.
16. Parent/student tidak bisa mengakses internal report.
17. `npm run build` berhasil.
18. `php artisan app:system-health-check` berhasil.
19. Dokumentasi dibuat.

````

---

# 58. Update `docs/project-progress.md`

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
| 12 | QR Attendance System | Done |
| 13 | Tahsin Management App | Pending |
| 14 | Student Finance Ledger | Pending |
| 15 | SchoolOS Mini | Pending |
```

---

# 59. Commit Phase 12

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add qr attendance system"
```

Jika remote tersedia:

```powershell
git push origin phase-12-qr-attendance-system
```

---

# 60. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 12 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- QR unik per santri
- QR token rotation
- Cetak QR card
- Web QR scanner
- Attendance session
- Check in
- Check out opsional
- Late detection
- Manual attendance input
- Attendance report
- Parent attendance portal
- Student attendance portal
- Role-based access
- Ownership-based access

Tabel dibuat:
- attendance_qr_tokens
- attendance_sessions
- attendance_records

Route dibuat:
- attendance.qr-cards.index
- attendance.qr-cards.print
- attendance.qr-cards.rotate
- attendance.scanner.index
- attendance.scanner.scan
- attendance.manual.create
- attendance.manual.store
- attendance.reports.dashboard
- attendance.sessions.index
- attendance.sessions.create
- attendance.sessions.store
- attendance.sessions.show
- attendance.sessions.edit
- attendance.sessions.update
- attendance.sessions.destroy
- portal.parent.attendance
- portal.student.attendance

Belum dibuat:
- Tahsin
- Finance
- Cashless
- White-label
- Mobile app
- WhatsApp gateway
- Push notification
- Face recognition
- RFID/NFC
- Fingerprint
- Multi-tenant kompleks

Status:
- Siap lanjut Phase 13 hanya setelah UAT Phase 12 aman.
```

---

# 61. Larangan Setelah Phase 12

Agent harus berhenti setelah Phase 12 selesai.

Jangan lanjut membuat:

1. Tahsin Management.
2. Finance Ledger.
3. Cashless POS.
4. White-label.
5. Mobile App.
6. Multi-tenant architecture.
7. WhatsApp gateway.
8. Push notification.
9. LMS.
10. Boarding system.

Semua itu masuk phase berikutnya.

---

# 62. Keputusan Akhir

Phase 12 hanya valid jika QR Attendance stabil, aman, dan tidak merusak core Tahfizh, Mutabaah, Parent Portal, dan Notification Center.

Prioritas setelah Phase 12:

1. UAT QR Attendance.
2. Fix bug scanner.
3. Cek QR token rotation.
4. Cek akses parent/student.
5. Cek report attendance.
6. Cek performa scanner di laptop dan HP.
7. Baru pertimbangkan Phase 13 — Tahsin Management App.
