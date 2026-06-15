# Phase 17 Execution Guide — Multi-Tenant Foundation

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
Phase 17 — Multi-Tenant Foundation
```

---

# 1. Keputusan Sebelum Phase 17

## 1.1 UAT Phase 16 Wajib

Sebelum menjalankan Phase 17, agent wajib memastikan **Boarding School Management System** sudah aman.

Jangan menambah multi-tenant jika single-school data ownership belum stabil.

Phase 17 hanya boleh dieksekusi jika:

1. Boarding dashboard tampil.
2. Asrama bisa dibuat.
3. Kamar bisa dibuat.
4. Bed bisa dibuat.
5. Assignment santri ke bed berjalan.
6. Santri tidak bisa punya dua assignment aktif.
7. Bed tidak bisa ditempati dua santri aktif.
8. Leave request berjalan.
9. Health log berjalan.
10. Discipline log berjalan.
11. Roll call berjalan.
12. Parent hanya melihat data boarding anak sendiri.
13. Student hanya melihat data boarding pribadi.
14. Boarding supervisor hanya melihat scope asrama/kamar yang diizinkan.
15. Kepala sekolah read-only.
16. Module registry sudah memuat module `boarding`.
17. `npm run build` berhasil.
18. `php artisan app:system-health-check` berhasil.
19. Backup database berhasil.
20. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0/P1, hentikan Phase 17 dan buat bug fix sprint dulu.

---

## 1.2 Klasifikasi Bug Sebelum Phase 17

| Prioritas | Contoh Bug | Keputusan |
|---|---|---|
| P0 | Login gagal, parent bisa melihat anak lain, student bisa melihat data santri lain, finance bocor, boarding bocor | Wajib fix sebelum Phase 17 |
| P1 | Report salah scope, Student 360 salah scope, assignment boarding rusak, ledger salah, attendance salah tenant | Wajib fix sebelum Phase 17 |
| P2 | UI kurang rapi, wording kurang jelas, spacing kurang bagus | Boleh dicatat |
| P3 | Enhancement kosmetik | Boleh ditunda |

---

## 1.3 Kenapa Phase 17 Bukan White-Label Dulu

Phase 17 bukan:

1. White-label school app.
2. Domain/subdomain per sekolah.
3. Custom branding penuh.
4. Billing SaaS.
5. Trial/subscription management.
6. Native mobile app.
7. Cashless kantin.
8. Merchant POS.
9. Payment gateway.

Phase 17 hanya membuat **pondasi multi-tenant data isolation**.

Keputusan keras:

> Jangan membuat white-label sebelum data antar sekolah benar-benar terisolasi.

Jika tenant isolation lemah, risiko terbesar bukan bug tampilan. Risiko terbesarnya adalah **data sekolah A terlihat oleh sekolah B**. Itu fatal.

---

# 2. Tujuan Phase 17

Phase 17 bertujuan membuat **Multi-Tenant Foundation** untuk HafizPlus School Platform.

Tenant pada Phase 17 adalah:

```text
Sekolah
```

Strategi teknis Phase 17:

```text
Single database, shared schema, tenant isolation menggunakan school_id.
```

Fokus Phase 17:

1. Menjadikan `schools` sebagai tenant utama.
2. Menambahkan tenant metadata ke tabel `schools`.
3. Membuat user-school membership.
4. Membuat active school context.
5. Membuat middleware tenant context.
6. Membuat tenant-aware query scope.
7. Membuat tenant access service.
8. Membuat tenant switcher untuk user multi-school.
9. Membuat tenant module settings.
10. Membuat tenant settings sederhana.
11. Membuat tenant audit log minimal.
12. Menambahkan `school_id` ke tabel penting yang belum terscope.
13. Menjaga semua module lama tetap berjalan.
14. Menjaga parent/student ownership access.
15. Menyiapkan fondasi untuk Phase 18 White-Label School App Builder.

---

# 3. Batasan Phase 17

AI agent tidak boleh membuat fitur berikut pada Phase 17:

1. White-label branding UI penuh.
2. Upload logo per sekolah untuk public app.
3. Custom color per sekolah.
4. Custom domain.
5. Subdomain per sekolah.
6. Tenant billing.
7. Paket subscription SaaS.
8. Invoice SaaS ke sekolah.
9. Payment gateway.
10. Cashless kantin.
11. Merchant POS.
12. Wallet.
13. Native Android.
14. Native iOS.
15. Mobile app release.
16. Multi-database tenancy.
17. Database per tenant.
18. Kubernetes/deployment multi-server.
19. Object storage per tenant.
20. Queue isolation production.
21. White-label app builder.
22. LMS penuh.
23. Marketplace konten.
24. WhatsApp gateway.
25. Push notification.

Phase 17 hanya membuat:

```text
Multi-Tenant Foundation berbasis school_id, middleware, service, scope, dan tenant settings.
```

---

# 4. Prinsip Arsitektur Multi-Tenant

## 4.1 Single Database Dulu

Phase 17 memakai strategi:

```text
Single database + shared schema + school_id tenant key
```

Jangan memakai:

```text
Database per sekolah
Schema per sekolah
Server per sekolah
Subdomain per sekolah
```

Alasan:

1. Lebih realistis untuk tahap sekarang.
2. Lebih mudah di-maintain.
3. Lebih cepat diuji lokal.
4. Lebih ringan untuk deployment awal.
5. Lebih cocok sebelum product-market validation banyak sekolah.
6. Cukup aman jika tenant scope benar-benar disiplin.

Konsekuensi:

1. Semua tabel tenant-owned wajib punya `school_id`.
2. Semua query tenant-owned wajib terscope active school.
3. Semua controller wajib memakai tenant access check.
4. Semua report wajib filter tenant.
5. Parent/student tetap wajib ownership check, bukan hanya tenant check.

---

## 4.2 Tenant = School

Pada Phase 17, tenant adalah record di tabel:

```text
schools
```

Jangan membuat entitas tenant terpisah jika tidak perlu.

Tambahkan metadata tenant ke `schools`:

1. `tenant_code`
2. `slug`
3. `tenant_status`
4. `is_tenant_enabled`
5. `tenant_activated_at`
6. `tenant_suspended_at`

Tenant status:

| Status | Makna |
|---|---|
| `draft` | Sekolah dibuat tapi belum aktif sebagai tenant |
| `active` | Tenant aktif |
| `suspended` | Tenant sementara dibekukan |
| `archived` | Tenant tidak aktif permanen |

---

## 4.3 User Bisa Punya Akses ke Banyak Sekolah

User internal seperti super admin atau konsultan bisa punya akses ke beberapa sekolah.

Gunakan tabel:

```text
user_school_memberships
```

Tujuan:

1. Menentukan user boleh masuk sekolah mana.
2. Menentukan role user di sekolah tersebut.
3. Menentukan active/inactive membership.
4. Menyiapkan tenant switcher.

Catatan:

1. Jangan menghapus kolom `school_id` lama di `users` pada Phase 17.
2. Kolom lama boleh tetap dipakai sebagai default school.
3. Membership adalah fondasi multi-school access.
4. Refactor penuh auth bisa dilakukan nanti jika dibutuhkan.

---

## 4.4 Active School Context

Setiap request authenticated harus punya konteks sekolah aktif.

Sumber active school:

1. Session `active_school_id`.
2. Jika tidak ada, ambil dari membership pertama yang aktif.
3. Jika user hanya punya `users.school_id`, pakai itu sebagai fallback.
4. Super admin boleh memilih tenant.
5. Parent/student tetap dibatasi ke school anak/profilnya.

Session key:

```text
active_school_id
```

Service utama:

```text
App\Services\Tenancy\TenantContextService
```

---

## 4.5 Tenant Scope Tidak Menggantikan Ownership Scope

Tenant scope hanya memastikan data sekolah tidak bocor antar sekolah.

Ownership scope tetap wajib untuk parent/student.

Contoh:

1. Parent di Sekolah A hanya boleh melihat anak sendiri di Sekolah A.
2. Parent tidak boleh melihat semua santri Sekolah A.
3. Student hanya boleh melihat data pribadi.
4. Teacher hanya boleh melihat santri scope guru jika aturan scope sudah tersedia.
5. Kepala sekolah boleh melihat seluruh data sekolah aktif secara read-only.

---

# 5. Target Output Phase 17

Setelah Phase 17 selesai, aplikasi harus punya:

1. Tenant metadata di tabel `schools`.
2. Tabel `user_school_memberships`.
3. Tabel `tenant_settings`.
4. Tabel `tenant_modules`.
5. Tabel `tenant_audit_logs`.
6. Migration penambahan `school_id` ke tabel penting yang belum terscope.
7. Model:
   - `UserSchoolMembership`
   - `TenantSetting`
   - `TenantModule`
   - `TenantAuditLog`
8. Controller:
   - `TenantDashboardController`
   - `TenantSwitcherController`
   - `TenantMembershipController`
   - `TenantSettingController`
   - `TenantModuleController`
   - `TenantAuditLogController`
9. Request:
   - `SwitchTenantRequest`
   - `StoreTenantMembershipRequest`
   - `UpdateTenantMembershipRequest`
   - `UpdateTenantSettingRequest`
   - `UpdateTenantModuleRequest`
   - `TenantAuditLogFilterRequest`
10. Middleware:
   - `ResolveTenantContext`
   - `EnsureTenantAccess`
11. Trait:
   - `BelongsToTenant`
12. Service:
   - `TenantContextService`
   - `TenantAccessService`
   - `TenantMembershipService`
   - `TenantModuleService`
   - `TenantSettingsService`
   - `TenantAuditLogger`
   - `TenantDataBackfillService`
13. Command:
   - `php artisan app:backfill-tenant-school-id`
   - `php artisan app:tenant-health-check`
14. Seeder:
   - `TenantModuleSeeder`
15. View:
   - tenant dashboard
   - tenant switcher
   - membership index/create/edit
   - tenant settings
   - tenant modules
   - tenant audit logs
16. Route tenant management.
17. Update navigation.
18. Update `docs/project-progress.md`.
19. Dokumentasi Phase 17.

---

# 6. Role Access Phase 17

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
boarding_supervisor
parent
student
```

| Role | Switch Tenant | Membership | Tenant Settings | Tenant Modules | Audit Log | Data Tenant |
|---|---|---|---|---|---|---|
| Super Admin | Semua tenant | CRUD semua | CRUD semua | CRUD semua | Semua | Semua |
| Admin Sekolah | Tenant miliknya | Read-only / terbatas | Update tenant sendiri | Update tenant sendiri terbatas | Tenant sendiri | Tenant sendiri |
| Kepala Sekolah | Tenant miliknya | Read-only | Read-only | Read-only | Tenant sendiri read-only | Tenant sendiri |
| Teacher/Guru | Tenant miliknya | Tidak | Tidak | Tidak | Tidak | Scope guru |
| Boarding Supervisor | Tenant miliknya | Tidak | Tidak | Tidak | Tidak | Scope boarding |
| Parent | Tidak bebas | Tidak | Tidak | Tidak | Tidak | Anak sendiri |
| Student | Tidak bebas | Tidak | Tidak | Tidak | Tidak | Diri sendiri |

Aturan keras:

1. Super admin boleh switch tenant.
2. Admin sekolah hanya boleh mengelola tenant sekolahnya.
3. Kepala sekolah read-only.
4. Teacher/guru tidak boleh mengelola tenant.
5. Parent/student tidak boleh melihat tenant switcher.
6. Parent/student tidak boleh melihat daftar sekolah.
7. Parent/student tidak boleh akses tenant settings.
8. Parent/student tetap ownership-based.
9. User tanpa membership aktif tidak boleh masuk tenant.
10. Tenant suspended tidak boleh diakses kecuali super admin.

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
php artisan app:backup-database
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
16. Phase 14 selesai.
17. Phase 15 selesai.
18. Phase 16 selesai dan UAT aman.
19. Tabel berikut sudah ada:
    - `users`
    - `roles`
    - `schools`
    - `class_rooms`
    - `students`
    - `parent_profiles`
    - `parent_student`
    - `teacher_profiles`
    - `system_modules`
    - `academic_years`
    - `school_terms`
    - `school_settings`
    - `boarding_dormitories`
    - `boarding_student_assignments`
20. Working tree bersih atau semua perubahan diketahui.

Jika Phase 16 belum aman, hentikan Phase 17.

---

# 8. Buat Branch Git Phase 17

Jalankan:

```powershell
git checkout -b phase-17-multi-tenant-foundation
```

Jika branch sudah ada:

```powershell
git checkout phase-17-multi-tenant-foundation
```

---

# 9. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Console/
│   └── Commands/
│       ├── BackfillTenantSchoolIdCommand.php
│       └── TenantHealthCheckCommand.php
├── Http/
│   ├── Controllers/
│   │   └── Tenancy/
│   │       ├── TenantDashboardController.php
│   │       ├── TenantSwitcherController.php
│   │       ├── TenantMembershipController.php
│   │       ├── TenantSettingController.php
│   │       ├── TenantModuleController.php
│   │       └── TenantAuditLogController.php
│   ├── Middleware/
│   │   ├── ResolveTenantContext.php
│   │   └── EnsureTenantAccess.php
│   └── Requests/
│       └── Tenancy/
│           ├── SwitchTenantRequest.php
│           ├── StoreTenantMembershipRequest.php
│           ├── UpdateTenantMembershipRequest.php
│           ├── UpdateTenantSettingRequest.php
│           ├── UpdateTenantModuleRequest.php
│           └── TenantAuditLogFilterRequest.php
├── Models/
│   ├── UserSchoolMembership.php
│   ├── TenantSetting.php
│   ├── TenantModule.php
│   └── TenantAuditLog.php
├── Models/
│   └── Concerns/
│       └── BelongsToTenant.php
└── Services/
    └── Tenancy/
        ├── TenantContextService.php
        ├── TenantAccessService.php
        ├── TenantMembershipService.php
        ├── TenantModuleService.php
        ├── TenantSettingsService.php
        ├── TenantAuditLogger.php
        └── TenantDataBackfillService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_add_tenant_fields_to_schools_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_user_school_memberships_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_tenant_settings_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_tenant_modules_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_tenant_audit_logs_table.php
│   └── xxxx_xx_xx_xxxxxx_add_school_id_to_existing_module_tables.php
└── seeders/
    └── TenantModuleSeeder.php

resources/
└── views/
    └── tenancy/
        ├── dashboard.blade.php
        ├── switcher.blade.php
        ├── memberships/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        ├── settings/
        │   └── index.blade.php
        ├── modules/
        │   └── index.blade.php
        └── audit-logs/
            └── index.blade.php

routes/
└── web.php

bootstrap/
└── app.php

docs/
├── phase-17-execution.md
└── phase-17-multi-tenant-foundation.md
```

---

# 10. Buat Model, Migration, Seeder, Controller, Request, Middleware, Command

Jalankan:

```powershell
php artisan make:model UserSchoolMembership -m
php artisan make:model TenantSetting -m
php artisan make:model TenantModule -m
php artisan make:model TenantAuditLog -m

php artisan make:migration add_tenant_fields_to_schools_table --table=schools
php artisan make:migration add_school_id_to_existing_module_tables

php artisan make:seeder TenantModuleSeeder

php artisan make:controller Tenancy/TenantDashboardController
php artisan make:controller Tenancy/TenantSwitcherController
php artisan make:controller Tenancy/TenantMembershipController
php artisan make:controller Tenancy/TenantSettingController
php artisan make:controller Tenancy/TenantModuleController
php artisan make:controller Tenancy/TenantAuditLogController

php artisan make:request Tenancy/SwitchTenantRequest
php artisan make:request Tenancy/StoreTenantMembershipRequest
php artisan make:request Tenancy/UpdateTenantMembershipRequest
php artisan make:request Tenancy/UpdateTenantSettingRequest
php artisan make:request Tenancy/UpdateTenantModuleRequest
php artisan make:request Tenancy/TenantAuditLogFilterRequest

php artisan make:middleware ResolveTenantContext
php artisan make:middleware EnsureTenantAccess

php artisan make:command BackfillTenantSchoolIdCommand
php artisan make:command TenantHealthCheckCommand
```

Buat folder service dan trait:

```powershell
mkdir app\Services\Tenancy
mkdir app\Models\Concerns

New-Item app\Services\Tenancy\TenantContextService.php
New-Item app\Services\Tenancy\TenantAccessService.php
New-Item app\Services\Tenancy\TenantMembershipService.php
New-Item app\Services\Tenancy\TenantModuleService.php
New-Item app\Services\Tenancy\TenantSettingsService.php
New-Item app\Services\Tenancy\TenantAuditLogger.php
New-Item app\Services\Tenancy\TenantDataBackfillService.php
New-Item app\Models\Concerns\BelongsToTenant.php
```

Buat folder view:

```powershell
mkdir resources\views\tenancy
mkdir resources\views\tenancy\memberships
mkdir resources\views\tenancy\settings
mkdir resources\views\tenancy\modules
mkdir resources\views\tenancy\audit-logs
```

---

# 11. Migration `add_tenant_fields_to_schools_table`

Buka file migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_add_tenant_fields_to_schools_table.php
```

Isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table): void {
            if (! Schema::hasColumn('schools', 'tenant_code')) {
                $table->string('tenant_code')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('schools', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('tenant_code');
            }

            if (! Schema::hasColumn('schools', 'tenant_status')) {
                $table->string('tenant_status')->default('active')->after('slug');
            }

            if (! Schema::hasColumn('schools', 'is_tenant_enabled')) {
                $table->boolean('is_tenant_enabled')->default(true)->after('tenant_status');
            }

            if (! Schema::hasColumn('schools', 'tenant_activated_at')) {
                $table->timestamp('tenant_activated_at')->nullable()->after('is_tenant_enabled');
            }

            if (! Schema::hasColumn('schools', 'tenant_suspended_at')) {
                $table->timestamp('tenant_suspended_at')->nullable()->after('tenant_activated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table): void {
            $columns = [
                'tenant_code',
                'slug',
                'tenant_status',
                'is_tenant_enabled',
                'tenant_activated_at',
                'tenant_suspended_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('schools', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
```

---

# 12. Migration `create_user_school_memberships_table`

Buka migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_user_school_memberships_table.php
```

Isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_school_memberships', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->string('membership_status')->default('active');
            $table->boolean('is_default')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'school_id'], 'user_school_membership_unique');
            $table->index(['school_id', 'membership_status']);
            $table->index(['user_id', 'membership_status']);
            $table->index('is_default');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_school_memberships');
    }
};
```

---

# 13. Migration `create_tenant_settings_table`

Buka migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tenant_settings_table.php
```

Isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('setting_key');
            $table->text('setting_value')->nullable();
            $table->string('value_type')->default('string');
            $table->boolean('is_public')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['school_id', 'setting_key'], 'tenant_setting_unique_key');
            $table->index(['school_id', 'is_public']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
```

---

# 14. Migration `create_tenant_modules_table`

Buka migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tenant_modules_table.php
```

Isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('module_key');
            $table->string('module_name');
            $table->boolean('is_enabled')->default(true);
            $table->json('configuration')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['school_id', 'module_key'], 'tenant_module_unique_key');
            $table->index(['school_id', 'is_enabled']);
            $table->index('module_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_modules');
    }
};
```

---

# 15. Migration `create_tenant_audit_logs_table`

Buka migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_create_tenant_audit_logs_table.php
```

Isi:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('auditable_type')->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['school_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_audit_logs');
    }
};
```

---

# 16. Migration `add_school_id_to_existing_module_tables`

Buka migration:

```text
database/migrations/xxxx_xx_xx_xxxxxx_add_school_id_to_existing_module_tables.php
```

Isi dengan pendekatan defensif:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tenantTables = [
        'quran_juzs' => false,
        'quran_surahs' => false,
        'mushaf_pages' => false,
        'tahfizh_targets' => true,
        'hafalan_records' => true,
        'tahfizh_debts' => true,
        'mutabaah_categories' => true,
        'mutabaah_activities' => true,
        'mutabaah_records' => true,
        'attendance_qr_tokens' => true,
        'attendance_sessions' => true,
        'attendance_records' => true,
        'tahsin_levels' => true,
        'tahsin_skills' => true,
        'tahsin_student_profiles' => true,
        'tahsin_assessments' => true,
        'finance_fee_categories' => true,
        'finance_fee_items' => true,
        'student_bills' => true,
        'student_payments' => true,
        'finance_ledger_entries' => true,
        'boarding_dormitories' => true,
        'boarding_rooms' => true,
        'boarding_student_assignments' => true,
        'boarding_leave_requests' => true,
        'boarding_health_logs' => true,
        'boarding_discipline_logs' => true,
        'boarding_roll_call_sessions' => true,
        'boarding_roll_call_records' => true,
    ];

    public function up(): void
    {
        foreach ($this->tenantTables as $tableName => $shouldHaveSchoolId) {
            if (! $shouldHaveSchoolId) {
                continue;
            }

            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (Schema::hasColumn($tableName, 'school_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->foreignId('school_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('schools')
                    ->nullOnDelete();

                $table->index('school_id');
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tenantTables as $tableName => $shouldHaveSchoolId) {
            if (! $shouldHaveSchoolId) {
                continue;
            }

            if (! Schema::hasTable($tableName)) {
                continue;
            }

            if (! Schema::hasColumn($tableName, 'school_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropConstrainedForeignId('school_id');
            });
        }
    }
};
```

Catatan:

1. Tabel data Qur'an global seperti `quran_juzs`, `quran_surahs`, dan `mushaf_pages` tidak perlu `school_id`.
2. Tabel tenant-owned wajib punya `school_id`.
3. Jika tabel belum ada karena implementasi sebelumnya berbeda, migration jangan crash.
4. Setelah migration, command backfill wajib dijalankan.

---

# 17. Trait `BelongsToTenant`

Buka:

```text
app/Models/Concerns/BelongsToTenant.php
```

Isi:

```php
<?php

namespace App\Models\Concerns;

use App\Services\Tenancy\TenantContextService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::creating(function (Model $model): void {
            if (! $model->getAttribute('school_id')) {
                $schoolId = app(TenantContextService::class)->activeSchoolId();

                if ($schoolId) {
                    $model->setAttribute('school_id', $schoolId);
                }
            }
        });
    }

    public function scopeForActiveTenant(Builder $query): Builder
    {
        $schoolId = app(TenantContextService::class)->activeSchoolId();

        if (! $schoolId) {
            return $query;
        }

        return $query->where($query->getModel()->getTable() . '.school_id', $schoolId);
    }

    public function scopeForTenant(Builder $query, int $schoolId): Builder
    {
        return $query->where($query->getModel()->getTable() . '.school_id', $schoolId);
    }
}
```

Aturan:

1. Trait ini belum boleh dipasang sembarangan ke tabel global.
2. Pasang hanya ke model tenant-owned.
3. Jangan pasang ke `QuranJuz`, `QuranSurah`, atau `MushafPage`.

---

# 18. Service `TenantContextService`

Buka:

```text
app/Services/Tenancy/TenantContextService.php
```

Isi:

```php
<?php

namespace App\Services\Tenancy;

use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;
use Illuminate\Support\Facades\Session;

class TenantContextService
{
    public const SESSION_KEY = 'active_school_id';

    public function activeSchoolId(): ?int
    {
        $schoolId = Session::get(self::SESSION_KEY);

        return $schoolId ? (int) $schoolId : null;
    }

    public function activeSchool(): ?School
    {
        $schoolId = $this->activeSchoolId();

        if (! $schoolId) {
            return null;
        }

        return School::query()->find($schoolId);
    }

    public function setActiveSchool(User $user, int $schoolId): void
    {
        if (! app(TenantAccessService::class)->userCanAccessSchool($user, $schoolId)) {
            abort(403, 'Anda tidak memiliki akses ke sekolah ini.');
        }

        Session::put(self::SESSION_KEY, $schoolId);

        UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->update(['last_accessed_at' => now()]);
    }

    public function resolveForUser(User $user): ?int
    {
        $current = $this->activeSchoolId();

        if ($current && app(TenantAccessService::class)->userCanAccessSchool($user, $current)) {
            return $current;
        }

        $membership = UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('membership_status', 'active')
            ->orderByDesc('is_default')
            ->orderByDesc('last_accessed_at')
            ->first();

        if ($membership) {
            Session::put(self::SESSION_KEY, $membership->school_id);

            return (int) $membership->school_id;
        }

        if ($user->school_id ?? null) {
            Session::put(self::SESSION_KEY, $user->school_id);

            return (int) $user->school_id;
        }

        return null;
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }
}
```

---

# 19. Service `TenantAccessService`

Buka:

```text
app/Services/Tenancy/TenantAccessService.php
```

Isi:

```php
<?php

namespace App\Services\Tenancy;

use App\Models\School;
use App\Models\User;
use App\Models\UserSchoolMembership;

class TenantAccessService
{
    public function userCanAccessSchool(User $user, int $schoolId): bool
    {
        if ($this->isSuperAdmin($user)) {
            return School::query()
                ->where('id', $schoolId)
                ->exists();
        }

        $hasMembership = UserSchoolMembership::query()
            ->where('user_id', $user->id)
            ->where('school_id', $schoolId)
            ->where('membership_status', 'active')
            ->exists();

        if ($hasMembership) {
            return true;
        }

        return (int) ($user->school_id ?? 0) === $schoolId;
    }

    public function ensureUserCanAccessSchool(User $user, int $schoolId): void
    {
        if (! $this->userCanAccessSchool($user, $schoolId)) {
            abort(403, 'Akses tenant ditolak.');
        }

        $school = School::query()->findOrFail($schoolId);

        if (! $this->isSuperAdmin($user) && ($school->is_tenant_enabled === false || $school->tenant_status === 'suspended')) {
            abort(403, 'Tenant sekolah sedang tidak aktif.');
        }
    }

    public function isSuperAdmin(User $user): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole(['super_admin']);
    }

    public function isTenantAdmin(User $user): bool
    {
        return method_exists($user, 'hasRole') && $user->hasRole(['super_admin', 'admin', 'admin_sekolah']);
    }
}
```

---

# 20. Middleware `ResolveTenantContext`

Buka:

```text
app/Http/Middleware/ResolveTenantContext.php
```

Isi:

```php
<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            app(TenantContextService::class)->resolveForUser($request->user());
        }

        return $next($request);
    }
}
```

---

# 21. Middleware `EnsureTenantAccess`

Buka:

```text
app/Http/Middleware/EnsureTenantAccess.php
```

Isi:

```php
<?php

namespace App\Http\Middleware;

use App\Services\Tenancy\TenantAccessService;
use App\Services\Tenancy\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $schoolId = app(TenantContextService::class)->activeSchoolId();

        if (! $schoolId) {
            abort(403, 'Tenant sekolah belum dipilih.');
        }

        app(TenantAccessService::class)->ensureUserCanAccessSchool($user, $schoolId);

        return $next($request);
    }
}
```

---

# 22. Register Middleware Laravel 12

Buka:

```text
bootstrap/app.php
```

Tambahkan alias middleware jika belum ada:

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'tenant.resolve' => \App\Http\Middleware\ResolveTenantContext::class,
        'tenant.access' => \App\Http\Middleware\EnsureTenantAccess::class,
    ]);
})
```

Jika `withMiddleware` sudah ada, gabungkan alias tanpa menghapus middleware lama seperti `role`.

---

# 23. Model Minimal

## 23.1 `UserSchoolMembership`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserSchoolMembership extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'school_id',
        'role_id',
        'membership_status',
        'is_default',
        'joined_at',
        'last_accessed_at',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'joined_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
```

## 23.2 `TenantSetting`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'setting_key',
        'setting_value',
        'value_type',
        'is_public',
        'description',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
```

## 23.3 `TenantModule`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'module_key',
        'module_name',
        'is_enabled',
        'configuration',
        'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'configuration' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
```

## 23.4 `TenantAuditLog`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

# 24. Update Model `School`

Buka:

```text
app/Models/School.php
```

Tambahkan fillable/casts dan relasi berikut jika belum ada:

```php
protected $casts = [
    'is_tenant_enabled' => 'boolean',
    'tenant_activated_at' => 'datetime',
    'tenant_suspended_at' => 'datetime',
];

public function memberships()
{
    return $this->hasMany(UserSchoolMembership::class);
}

public function tenantSettings()
{
    return $this->hasMany(TenantSetting::class);
}

public function tenantModules()
{
    return $this->hasMany(TenantModule::class);
}
```

Pastikan fillable mencakup:

```php
' tenant_code',
'slug',
'tenant_status',
'is_tenant_enabled',
'tenant_activated_at',
'tenant_suspended_at',
```

Catatan: hapus spasi typo jika agent memasukkan `' tenant_code'`. Yang benar:

```php
'tenant_code',
```

---

# 25. Update Model `User`

Buka:

```text
app/Models/User.php
```

Tambahkan relasi:

```php
public function schoolMemberships()
{
    return $this->hasMany(UserSchoolMembership::class);
}

public function accessibleSchools()
{
    return $this->belongsToMany(School::class, 'user_school_memberships')
        ->withPivot(['role_id', 'membership_status', 'is_default', 'last_accessed_at'])
        ->withTimestamps();
}
```

Jangan hapus relasi lama.

---

# 26. Tenant Module Seeder

Buka:

```text
database/seeders/TenantModuleSeeder.php
```

Isi:

```php
<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\TenantModule;
use Illuminate\Database\Seeder;

class TenantModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['module_key' => 'tahfizh', 'module_name' => 'Tahfizh'],
            ['module_key' => 'reports', 'module_name' => 'Reports'],
            ['module_key' => 'notifications', 'module_name' => 'Notifications'],
            ['module_key' => 'exports', 'module_name' => 'Exports'],
            ['module_key' => 'mutabaah', 'module_name' => 'Mutabaah'],
            ['module_key' => 'attendance', 'module_name' => 'Attendance'],
            ['module_key' => 'tahsin', 'module_name' => 'Tahsin'],
            ['module_key' => 'finance', 'module_name' => 'Finance'],
            ['module_key' => 'schoolos', 'module_name' => 'SchoolOS'],
            ['module_key' => 'boarding', 'module_name' => 'Boarding'],
        ];

        School::query()->each(function (School $school) use ($modules): void {
            foreach ($modules as $module) {
                TenantModule::query()->updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'module_key' => $module['module_key'],
                    ],
                    [
                        'module_name' => $module['module_name'],
                        'is_enabled' => true,
                    ]
                );
            }
        });
    }
}
```

Tambahkan ke `DatabaseSeeder` jika pola project memakai central seeder:

```php
$this->call(TenantModuleSeeder::class);
```

---

# 27. Command `BackfillTenantSchoolIdCommand`

Buka:

```text
app/Console/Commands/BackfillTenantSchoolIdCommand.php
```

Isi logic minimal:

```php
<?php

namespace App\Console\Commands;

use App\Services\Tenancy\TenantDataBackfillService;
use Illuminate\Console\Command;

class BackfillTenantSchoolIdCommand extends Command
{
    protected $signature = 'app:backfill-tenant-school-id {--dry-run}';

    protected $description = 'Backfill school_id for tenant-owned records.';

    public function handle(TenantDataBackfillService $service): int
    {
        $result = $service->backfill((bool) $this->option('dry-run'));

        foreach ($result as $table => $count) {
            $this->line("{$table}: {$count} rows processed");
        }

        return self::SUCCESS;
    }
}
```

---

# 28. Service `TenantDataBackfillService`

Buka:

```text
app/Services/Tenancy/TenantDataBackfillService.php
```

Isi minimal:

```php
<?php

namespace App\Services\Tenancy;

use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantDataBackfillService
{
    public function backfill(bool $dryRun = false): array
    {
        $defaultSchoolId = School::query()->orderBy('id')->value('id');

        if (! $defaultSchoolId) {
            return ['schools' => 0];
        }

        $tables = [
            'tahfizh_targets',
            'hafalan_records',
            'tahfizh_debts',
            'mutabaah_categories',
            'mutabaah_activities',
            'mutabaah_records',
            'attendance_qr_tokens',
            'attendance_sessions',
            'attendance_records',
            'tahsin_levels',
            'tahsin_skills',
            'tahsin_student_profiles',
            'tahsin_assessments',
            'finance_fee_categories',
            'finance_fee_items',
            'student_bills',
            'student_payments',
            'finance_ledger_entries',
            'boarding_dormitories',
            'boarding_rooms',
            'boarding_student_assignments',
            'boarding_leave_requests',
            'boarding_health_logs',
            'boarding_discipline_logs',
            'boarding_roll_call_sessions',
            'boarding_roll_call_records',
        ];

        $result = [];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'school_id')) {
                $result[$table] = 0;
                continue;
            }

            $count = DB::table($table)->whereNull('school_id')->count();
            $result[$table] = $count;

            if (! $dryRun && $count > 0) {
                DB::table($table)->whereNull('school_id')->update([
                    'school_id' => $defaultSchoolId,
                ]);
            }
        }

        return $result;
    }
}
```

Catatan:

1. Ini backfill aman untuk database single-school lama.
2. Jika nanti sudah ada multi-school real, jangan asal set semua null ke school pertama.
3. Untuk production, backfill harus dicek manual dulu dengan `--dry-run`.

---

# 29. Command `TenantHealthCheckCommand`

Buka:

```text
app/Console/Commands/TenantHealthCheckCommand.php
```

Isi:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TenantHealthCheckCommand extends Command
{
    protected $signature = 'app:tenant-health-check';

    protected $description = 'Check tenant data isolation readiness.';

    public function handle(): int
    {
        $tables = [
            'users',
            'schools',
            'user_school_memberships',
            'tenant_settings',
            'tenant_modules',
            'tenant_audit_logs',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                $this->error("Missing table: {$table}");
                return self::FAILURE;
            }

            $this->info("OK table: {$table}");
        }

        $tenantTables = [
            'students',
            'class_rooms',
            'hafalan_records',
            'mutabaah_records',
            'attendance_records',
            'tahsin_assessments',
            'student_bills',
            'boarding_student_assignments',
        ];

        foreach ($tenantTables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            if (! Schema::hasColumn($table, 'school_id')) {
                $this->warn("Tenant column missing: {$table}.school_id");
                continue;
            }

            $nullCount = DB::table($table)->whereNull('school_id')->count();

            if ($nullCount > 0) {
                $this->warn("{$table}: {$nullCount} rows with NULL school_id");
            } else {
                $this->info("OK tenant scope: {$table}");
            }
        }

        return self::SUCCESS;
    }
}
```

---

# 30. Controller `TenantSwitcherController`

Buka:

```text
app/Http/Controllers/Tenancy/TenantSwitcherController.php
```

Isi minimal:

```php
<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenancy\SwitchTenantRequest;
use App\Models\School;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantSwitcherController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $schools = $user->hasRole(['super_admin'])
            ? School::query()->orderBy('name')->get()
            : $user->accessibleSchools()
                ->wherePivot('membership_status', 'active')
                ->orderBy('name')
                ->get();

        return view('tenancy.switcher', compact('schools'));
    }

    public function switch(SwitchTenantRequest $request, TenantContextService $tenantContext): RedirectResponse
    {
        $tenantContext->setActiveSchool($request->user(), (int) $request->validated('school_id'));

        return redirect()->route('schoolos.dashboard')
            ->with('success', 'Sekolah aktif berhasil diganti.');
    }
}
```

---

# 31. Request `SwitchTenantRequest`

Buka:

```text
app/Http/Requests/Tenancy/SwitchTenantRequest.php
```

Isi:

```php
<?php

namespace App\Http\Requests\Tenancy;

use App\Services\Tenancy\TenantAccessService;
use Illuminate\Foundation\Http\FormRequest;

class SwitchTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        $schoolId = (int) $this->input('school_id');

        return $schoolId > 0
            && app(TenantAccessService::class)->userCanAccessSchool($this->user(), $schoolId);
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'integer', 'exists:schools,id'],
        ];
    }
}
```

---

# 32. Route Phase 17

Tambahkan ke:

```text
routes/web.php
```

Di dalam middleware `auth`:

```php
use App\Http\Controllers\Tenancy\TenantAuditLogController;
use App\Http\Controllers\Tenancy\TenantDashboardController;
use App\Http\Controllers\Tenancy\TenantMembershipController;
use App\Http\Controllers\Tenancy\TenantModuleController;
use App\Http\Controllers\Tenancy\TenantSettingController;
use App\Http\Controllers\Tenancy\TenantSwitcherController;

Route::middleware(['auth', 'tenant.resolve'])->group(function (): void {
    Route::get('/tenancy', [TenantDashboardController::class, 'index'])->name('tenancy.dashboard');

    Route::get('/tenancy/switch', [TenantSwitcherController::class, 'index'])->name('tenancy.switcher');
    Route::post('/tenancy/switch', [TenantSwitcherController::class, 'switch'])->name('tenancy.switch');

    Route::middleware(['tenant.access'])->group(function (): void {
        Route::resource('/tenancy/memberships', TenantMembershipController::class)
            ->names('tenancy.memberships');

        Route::get('/tenancy/settings', [TenantSettingController::class, 'index'])->name('tenancy.settings.index');
        Route::put('/tenancy/settings', [TenantSettingController::class, 'update'])->name('tenancy.settings.update');

        Route::get('/tenancy/modules', [TenantModuleController::class, 'index'])->name('tenancy.modules.index');
        Route::put('/tenancy/modules/{tenantModule}', [TenantModuleController::class, 'update'])->name('tenancy.modules.update');

        Route::get('/tenancy/audit-logs', [TenantAuditLogController::class, 'index'])->name('tenancy.audit-logs.index');
    });
});
```

Pastikan parent/student tidak diberi menu route tenancy.

---

# 33. Update Existing Queries

Agent wajib meninjau query pada module berikut:

1. Master data.
2. Tahfizh.
3. Reports.
4. Parent Portal.
5. Student Portal.
6. Notification Center.
7. Export.
8. Mutabaah.
9. Attendance.
10. Tahsin.
11. Finance.
12. SchoolOS.
13. Boarding.

Aturan update:

1. Query admin harus filter active school.
2. Query kepala sekolah harus filter active school.
3. Query guru harus filter active school dan scope guru jika ada.
4. Query parent harus filter ownership anak sendiri.
5. Query student harus filter profile sendiri.
6. Export tidak boleh mengambil data semua school.
7. Dashboard tidak boleh menghitung lintas school kecuali super admin explicitly memilih mode global.
8. Notification harus tetap milik notifiable user, bukan global.
9. Student 360 harus tenant-aware dan ownership-aware.

Contoh query:

```php
$schoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();

$students = Student::query()
    ->where('school_id', $schoolId)
    ->latest()
    ->paginate(20);
```

Jangan menulis query tenant-owned tanpa filter `school_id`.

---

# 34. Update Navigation

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan area tenant context untuk role internal:

1. Tampilkan nama sekolah aktif.
2. Tampilkan tombol switch tenant untuk super admin/admin multi-school.
3. Jangan tampilkan switcher ke parent/student.
4. Jangan tampilkan tenant management ke teacher biasa.

Menu internal:

```text
Tenancy
- Tenant Dashboard
- Switch School
- User Memberships
- Tenant Settings
- Tenant Modules
- Tenant Audit Logs
```

Tampilkan menu Tenancy hanya untuk:

1. Super Admin.
2. Admin Sekolah.
3. Kepala Sekolah read-only untuk dashboard/settings jika diperlukan.

---

# 35. Update Login Flow

Setelah login berhasil:

1. Resolve active school.
2. Jika user punya satu sekolah, set otomatis.
3. Jika user punya banyak sekolah, arahkan ke tenant switcher atau dashboard dengan sekolah default.
4. Jika user tidak punya school/membership dan bukan super admin, tampilkan error:

```text
Akun Anda belum terhubung ke sekolah. Hubungi admin.
```

Jangan biarkan user masuk dashboard tanpa active tenant.

---

# 36. Tenant Audit Logger

Buka:

```text
app/Services/Tenancy/TenantAuditLogger.php
```

Isi minimal:

```php
<?php

namespace App\Services\Tenancy;

use App\Models\TenantAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TenantAuditLogger
{
    public function log(string $action, ?Model $model = null, array $oldValues = [], array $newValues = []): void
    {
        /** @var Request $request */
        $request = request();

        TenantAuditLog::query()->create([
            'school_id' => app(TenantContextService::class)->activeSchoolId(),
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => $model ? $model::class : null,
            'auditable_id' => $model?->getKey(),
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);
    }
}
```

Gunakan untuk action penting:

1. Switch tenant.
2. Create membership.
3. Update membership.
4. Update tenant settings.
5. Enable/disable tenant module.
6. Suspend tenant.
7. Activate tenant.

---

# 37. Tenant Health Rules

Phase 17 wajib punya pengecekan:

1. Semua user internal punya membership atau `school_id`.
2. Semua school punya `tenant_code` dan `slug`.
3. Semua tenant-owned table punya `school_id` jika tabel tersedia.
4. Tidak ada row penting dengan `school_id` null setelah backfill.
5. Parent/student ownership masih berjalan.
6. Export tidak bocor lintas tenant.
7. Student 360 tidak bocor lintas tenant.
8. Finance tidak bocor lintas tenant.
9. Boarding tidak bocor lintas tenant.

---

# 38. Update `docs/project-progress.md`

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
| 16 | Boarding School Management System | Done |
| 17 | Multi-Tenant Foundation | Done |
| 18 | White-Label School App Builder | Pending |
| 19 | Cashless Kantin / Merchant POS | Pending |
```

---

# 39. Dokumentasi Phase 17

Buat file:

```text
docs/phase-17-multi-tenant-foundation.md
```

Isi:

```md
# Phase 17 — Multi-Tenant Foundation

## Status

Phase 17 menambahkan fondasi multi-tenant untuk HafizPlus School Platform.

## Strategi

Multi-tenant memakai strategi:

- Single database.
- Shared schema.
- Tenant key menggunakan `school_id`.
- Tenant sama dengan sekolah.
- Active tenant disimpan di session `active_school_id`.

## Scope

Phase ini mencakup:

1. Tenant metadata di schools.
2. User-school memberships.
3. Tenant settings.
4. Tenant modules.
5. Tenant audit logs.
6. Tenant context middleware.
7. Tenant access middleware.
8. Tenant switcher.
9. Tenant-aware query foundation.
10. Backfill `school_id`.
11. Tenant health check.

## Tabel Baru

1. `user_school_memberships`
2. `tenant_settings`
3. `tenant_modules`
4. `tenant_audit_logs`

## Tabel Diubah

1. `schools`
2. Tabel tenant-owned yang belum punya `school_id`

## Service Baru

1. `TenantContextService`
2. `TenantAccessService`
3. `TenantMembershipService`
4. `TenantModuleService`
5. `TenantSettingsService`
6. `TenantAuditLogger`
7. `TenantDataBackfillService`

## Middleware Baru

1. `ResolveTenantContext`
2. `EnsureTenantAccess`

## Command Baru

1. `php artisan app:backfill-tenant-school-id`
2. `php artisan app:tenant-health-check`

## Route Baru

1. `tenancy.dashboard`
2. `tenancy.switcher`
3. `tenancy.switch`
4. `tenancy.memberships.*`
5. `tenancy.settings.index`
6. `tenancy.settings.update`
7. `tenancy.modules.index`
8. `tenancy.modules.update`
9. `tenancy.audit-logs.index`

## Batasan

Phase 17 tidak membuat:

1. White-label branding.
2. Custom domain.
3. Subdomain per sekolah.
4. Billing SaaS.
5. Payment gateway.
6. Cashless POS.
7. Mobile app.
8. Multi-database tenancy.
9. Tenant-specific deployment.

## Definition of Done

Phase 17 selesai jika:

1. Migration berhasil.
2. Backfill berhasil.
3. Tenant health check berhasil.
4. User-school membership berjalan.
5. Active tenant tersimpan di session.
6. Tenant switcher berjalan untuk user yang berhak.
7. Admin hanya melihat data tenant aktif.
8. Kepala sekolah read-only untuk tenant aktif.
9. Parent hanya melihat anak sendiri.
10. Student hanya melihat data sendiri.
11. Export tenant-aware.
12. Student 360 tenant-aware.
13. Finance tenant-aware.
14. Boarding tenant-aware.
15. Tenant modules bisa dikonfigurasi.
16. Tenant settings bisa dikonfigurasi.
17. Tenant audit log mencatat action penting.
18. `npm run build` berhasil.
19. `php artisan app:system-health-check` berhasil.
20. Dokumentasi dibuat.
```

---

# 40. Validasi Akhir

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=TenantModuleSeeder
php artisan app:backfill-tenant-school-id --dry-run
php artisan app:backfill-tenant-school-id
php artisan app:tenant-health-check
php artisan app:system-health-check
php artisan route:list
npm run build
```

Jalankan server:

```powershell
php artisan serve
```

Buka URL berikut:

```text
http://127.0.0.1:8000/tenancy
http://127.0.0.1:8000/tenancy/switch
http://127.0.0.1:8000/tenancy/memberships
http://127.0.0.1:8000/tenancy/settings
http://127.0.0.1:8000/tenancy/modules
http://127.0.0.1:8000/tenancy/audit-logs
```

---

# 41. Test Manual Wajib

## 41.1 Test Tenant Metadata

1. Jalankan migration.
2. Cek tabel `schools`.
3. Pastikan ada `tenant_code`, `slug`, `tenant_status`, `is_tenant_enabled`.
4. Pastikan sekolah lama punya value tenant.

Target:

1. Tidak ada error migration.
2. Tenant metadata tersimpan.
3. Tenant status default aktif.

---

## 41.2 Test Membership

1. Buat dua sekolah test.
2. Buat user admin sekolah A.
3. Buat user admin sekolah B.
4. Buat membership masing-masing.
5. Login sebagai admin sekolah A.
6. Pastikan hanya bisa akses sekolah A.
7. Login sebagai admin sekolah B.
8. Pastikan hanya bisa akses sekolah B.

Target:

1. Membership berjalan.
2. Tenant switcher tidak menampilkan sekolah yang bukan akses user.
3. Data tidak bocor lintas sekolah.

---

## 41.3 Test Super Admin Switch Tenant

1. Login sebagai super admin.
2. Buka tenant switcher.
3. Pilih sekolah A.
4. Cek dashboard SchoolOS.
5. Pilih sekolah B.
6. Cek dashboard SchoolOS.

Target:

1. Super admin bisa switch tenant.
2. Data dashboard berubah sesuai tenant.
3. Session `active_school_id` berubah.

---

## 41.4 Test Parent Ownership

1. Parent sekolah A login.
2. Buka portal parent.
3. Pastikan hanya anak sendiri tampil.
4. Coba akses URL anak lain dalam sekolah yang sama.
5. Coba akses anak dari sekolah lain.

Target:

1. Anak sendiri tampil.
2. Anak lain 403.
3. Anak sekolah lain 403.

---

## 41.5 Test Student Ownership

1. Student sekolah A login.
2. Buka portal student.
3. Pastikan hanya data pribadi tampil.
4. Coba akses Student 360 santri lain.

Target:

1. Data pribadi tampil.
2. Data santri lain 403.

---

## 41.6 Test Report dan Export

1. Buat data tahfizh sekolah A.
2. Buat data tahfizh sekolah B.
3. Login admin sekolah A.
4. Export report.
5. Pastikan data sekolah B tidak masuk.
6. Login admin sekolah B.
7. Export report.
8. Pastikan data sekolah A tidak masuk.

Target:

1. Export tenant-aware.
2. Report tenant-aware.
3. Tidak ada data silang.

---

## 41.7 Test Finance Tenant Scope

1. Buat tagihan sekolah A.
2. Buat tagihan sekolah B.
3. Login admin sekolah A.
4. Pastikan hanya tagihan sekolah A tampil.
5. Login parent sekolah A.
6. Pastikan hanya tagihan anak sendiri tampil.

Target:

1. Finance tidak bocor lintas tenant.
2. Parent tetap ownership-aware.

---

## 41.8 Test Boarding Tenant Scope

1. Buat asrama sekolah A.
2. Buat asrama sekolah B.
3. Login admin sekolah A.
4. Pastikan hanya boarding sekolah A tampil.
5. Login admin sekolah B.
6. Pastikan hanya boarding sekolah B tampil.

Target:

1. Boarding tidak bocor lintas tenant.
2. Assignment tetap valid.

---

# 42. Definition of Done

Phase 17 selesai jika:

1. Migration tenant berhasil.
2. `schools` punya tenant metadata.
3. `user_school_memberships` berjalan.
4. `tenant_settings` berjalan.
5. `tenant_modules` berjalan.
6. `tenant_audit_logs` berjalan.
7. Middleware tenant registered.
8. Active tenant context resolved saat login.
9. Tenant switcher berjalan.
10. User tanpa akses tenant mendapat 403.
11. Tenant suspended tidak bisa diakses user biasa.
12. Backfill `school_id` berhasil.
13. Tenant health check berhasil.
14. Admin hanya melihat data tenant aktif.
15. Kepala sekolah read-only tenant aktif.
16. Guru hanya melihat scope tenant aktif.
17. Parent hanya melihat anak sendiri.
18. Student hanya melihat diri sendiri.
19. Dashboard tenant-aware.
20. Student 360 tenant-aware.
21. Export tenant-aware.
22. Finance tenant-aware.
23. Boarding tenant-aware.
24. Tenant audit log mencatat action penting.
25. Tenant modules bisa enable/disable per sekolah.
26. Tenant settings bisa update per sekolah.
27. `npm run build` berhasil.
28. `php artisan app:system-health-check` berhasil.
29. `php artisan app:tenant-health-check` berhasil.
30. Dokumentasi dibuat.
31. `docs/project-progress.md` terupdate.

---

# 43. Commit Phase 17

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add multi-tenant foundation"
```

Jika remote tersedia:

```powershell
git push origin phase-17-multi-tenant-foundation
```

---

# 44. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 17 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Tenant metadata pada schools
- User-school memberships
- Tenant context resolver
- Tenant access middleware
- Tenant switcher
- Tenant settings
- Tenant modules
- Tenant audit logs
- Tenant-aware query foundation
- Tenant data backfill
- Tenant health check
- Role-based tenant access
- Ownership-based parent/student access tetap aman

Tabel dibuat:
- user_school_memberships
- tenant_settings
- tenant_modules
- tenant_audit_logs

Tabel diubah:
- schools
- tenant-owned tables yang membutuhkan school_id

Command dibuat:
- php artisan app:backfill-tenant-school-id
- php artisan app:tenant-health-check

Route dibuat:
- tenancy.dashboard
- tenancy.switcher
- tenancy.switch
- tenancy.memberships.*
- tenancy.settings.index
- tenancy.settings.update
- tenancy.modules.index
- tenancy.modules.update
- tenancy.audit-logs.index

Belum dibuat:
- White-label School App Builder
- Custom branding per sekolah
- Logo/color per sekolah
- Custom domain
- Subdomain per sekolah
- Tenant billing
- SaaS subscription
- Native mobile app
- Cashless kantin
- Merchant POS
- Wallet
- Payment gateway
- Multi-database tenancy

Status:
- Siap lanjut Phase 18 hanya setelah UAT Phase 17 aman.
```

---

# 45. Larangan Setelah Phase 17

Agent harus berhenti setelah Phase 17 selesai.

Jangan lanjut membuat:

1. White-Label School App Builder.
2. Custom domain.
3. Subdomain per sekolah.
4. Branding per sekolah.
5. Billing SaaS.
6. Subscription package.
7. Native mobile app.
8. Cashless Kantin.
9. Merchant POS.
10. Wallet.
11. Payment Gateway.
12. Multi-database deployment.

Semua itu masuk phase berikutnya.

---

# 46. Keputusan Akhir

Phase 17 hanya valid jika multi-tenant foundation aman dan tidak merusak:

1. Auth.
2. Role.
3. Master data.
4. Tahfizh.
5. Reports.
6. Parent portal.
7. Student portal.
8. Notification.
9. Export.
10. Mutabaah.
11. Attendance.
12. Tahsin.
13. Finance.
14. SchoolOS.
15. Boarding.

Prioritas setelah Phase 17:

1. UAT tenant isolation.
2. UAT tenant switcher.
3. UAT tenant report.
4. UAT export per tenant.
5. UAT parent/student ownership.
6. UAT finance tenant scope.
7. UAT boarding tenant scope.
8. Fix bug P0/P1.
9. Backup database.
10. Baru pertimbangkan Phase 18 — White-Label School App Builder.

Jangan masuk white-label kalau tenant isolation belum kuat. White-label tanpa tenant isolation hanya akan mempercantik produk yang secara data belum aman.
