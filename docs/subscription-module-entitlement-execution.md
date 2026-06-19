# Subscription Module Entitlement Execution Guide

## 0. Identitas Execution

Dokumen ini adalah instruksi eksekusi untuk AI coding agent di code editor.

Project:

```text
HafizPlus School Platform
```

Framework:

```text
Laravel 12
```

Database:

```text
MySQL
```

Project folder:

```text
C:\xampp\htdocs\hafizplus-school-platform
```

Database local:

```text
hafizplus_school_platform
```

Status proyek:

```text
Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.
```

Nama execution:

```text
Subscription Plan and Module Entitlement Engine
```

---

# 1. Tujuan Execution

Execution ini bertujuan membangun fondasi monetisasi SaaS untuk HafizPlus School Platform.

Fokus utama:

1. Mengatur subscription plan sekolah.
2. Mengatur modul aktif berdasarkan plan.
3. Mengatur modul yang terkunci karena plan tidak sesuai.
4. Mengatur override modul per sekolah.
5. Mengatur batasan usage seperti jumlah santri, guru, parent, export, dan storage.
6. Membuat middleware untuk membatasi akses route modul.
7. Membuat UI admin untuk mengelola plan, modul plan, subscription sekolah, dan override modul.
8. Membuat halaman module locked / upgrade required.
9. Memfilter sidebar/navigation berdasarkan modul yang aktif.
10. Menjaga role permission dan ownership access yang sudah ada.

Keputusan penting:

```text
Subscription plan melekat ke sekolah / tenant, bukan ke user.
```

---

# 2. Batasan Execution

AI agent tidak boleh membuat fitur berikut:

1. Payment gateway.
2. Midtrans.
3. Xendit.
4. Doku.
5. Tripay.
6. Duitku.
7. QRIS payment automation.
8. Virtual account.
9. Auto renewal payment.
10. Invoice PDF final.
11. Cashless kantin.
12. Wallet.
13. Merchant POS.
14. Mobile app.
15. White-label baru.
16. Multi-tenant ulang jika foundation sudah ada.
17. Package baru kecuali benar-benar wajib.
18. Hardcode plan di controller.
19. Perubahan besar pada auth.
20. Perubahan besar pada role system.

Execution ini hanya membuat:

```text
Plan -> Plan Modules -> School Subscription -> Module Access -> Route Protection -> Navigation Filtering -> Locked Module Page
```

---

# 3. Prinsip Arsitektur

## 3.1 Subscription Plan

Plan adalah paket bisnis.

Contoh plan:

1. Trial.
2. Basic.
3. Pro.
4. Enterprise.

Plan menentukan:

1. Modul yang tersedia.
2. Limit penggunaan.
3. Harga bulanan.
4. Harga tahunan.
5. Status aktif/tidak aktif.

---

## 3.2 System Module

System module adalah daftar modul global aplikasi.

Contoh module key:

```text
tahfizh
reports
notifications
exports
mutabaah
attendance
tahsin
finance
schoolos
boarding
lms_lite
ai_assistant
white_label
cashless_pos
```

Jika tabel `system_modules` sudah ada dari Phase 15, jangan buat ulang. Pakai dan rapikan tabel yang sudah ada.

Jika belum ada, buat tabel `system_modules`.

---

## 3.3 Plan Module / Entitlement

Plan module menentukan modul apa saja yang termasuk dalam plan tertentu.

Contoh:

| Plan | Module Included |
|---|---|
| Trial | tahfizh, reports, notifications |
| Basic | tahfizh, reports, notifications, exports |
| Pro | Basic + mutabaah, attendance, tahsin, finance, schoolos |
| Enterprise | Semua modul aktif |

---

## 3.4 School Subscription

School subscription adalah subscription aktif milik sekolah.

Plan tidak boleh disimpan di user.

Relasi utama:

```text
schools -> school_subscriptions -> subscription_plans
```

Satu sekolah hanya boleh punya satu subscription aktif pada satu waktu.

---

## 3.5 School Module Override

Override digunakan untuk:

1. Mengaktifkan modul add-on sementara.
2. Menonaktifkan modul tertentu untuk sekolah.
3. Trial modul.
4. Custom enterprise.
5. Manual exception.

Override harus menang atas plan default.

Contoh:

```text
Sekolah Basic + override attendance enabled = attendance aktif.
Sekolah Pro + override finance disabled = finance terkunci.
```

---

## 3.6 Role Permission

Role permission tetap menentukan aksi user di dalam modul.

Contoh:

1. Module finance aktif untuk sekolah.
2. Admin boleh membuat tagihan.
3. Kepala sekolah hanya read-only.
4. Parent hanya melihat tagihan anak sendiri.
5. Student hanya melihat tagihan pribadi.
6. Guru tidak boleh akses finance default.

Jangan campur module entitlement dengan role permission.

---

# 4. Formula Akses Final

Setiap request ke fitur modular harus mengikuti formula ini:

```text
1. User login
2. School / tenant context resolved
3. Subscription aktif
4. Module aktif berdasarkan plan atau override
5. Role user boleh mengakses fitur
6. Ownership access valid
```

Jika module tidak aktif:

1. Untuk admin / super admin: redirect ke halaman module locked / upgrade required.
2. Untuk parent / student / teacher: menu disembunyikan dan route menghasilkan `403 Forbidden` jika diakses langsung.

---

# 5. Validasi Awal Sebelum Coding

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

Pastikan Laravel 12 berjalan.

Lalu cek tabel dan role yang sudah ada:

```powershell
php artisan tinker
```

Jalankan:

```php
Schema::hasTable('schools');
Schema::hasTable('users');
Schema::hasTable('roles');
Schema::hasTable('system_modules');
App\Models\Role::query()->pluck('name')->all();
```

Catat nama role yang benar.

Role yang mungkin ada:

```text
super_admin
admin
admin_sekolah
kepala_sekolah
principal
teacher
guru
guru_tahfidz
parent
student
```

Jangan mengasumsikan nama role secara buta. Sesuaikan dengan data aktual.

---

# 6. Buat Branch Git

Jalankan:

```powershell
git checkout -b feature/subscription-module-entitlements
```

Jika branch sudah ada:

```powershell
git checkout feature/subscription-module-entitlements
```

---

# 7. Struktur File Target

Buat atau ubah file berikut sesuai kebutuhan.

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Billing/
│   │       ├── SubscriptionPlanController.php
│   │       ├── PlanModuleController.php
│   │       ├── SchoolSubscriptionController.php
│   │       ├── SchoolModuleOverrideController.php
│   │       └── ModuleLockedController.php
│   ├── Middleware/
│   │   ├── EnsureSubscriptionIsActive.php
│   │   └── EnsureModuleIsEnabled.php
│   └── Requests/
│       └── Billing/
│           ├── StoreSubscriptionPlanRequest.php
│           ├── UpdateSubscriptionPlanRequest.php
│           ├── SyncPlanModulesRequest.php
│           ├── StoreSchoolSubscriptionRequest.php
│           ├── UpdateSchoolSubscriptionRequest.php
│           └── StoreSchoolModuleOverrideRequest.php
├── Models/
│   ├── SubscriptionPlan.php
│   ├── PlanModule.php
│   ├── SchoolSubscription.php
│   ├── SchoolModuleOverride.php
│   └── SystemModule.php
└── Services/
    └── Billing/
        ├── CurrentSchoolResolver.php
        ├── SubscriptionStatusService.php
        ├── ModuleAccessService.php
        ├── PlanLimitService.php
        └── BillingNavigationService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_subscription_plans_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_plan_modules_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_school_subscriptions_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_school_module_overrides_table.php
│   └── xxxx_xx_xx_xxxxxx_create_subscription_usages_table.php
└── seeders/
    ├── SubscriptionPlanSeeder.php
    ├── SystemModuleSeeder.php
    └── PlanModuleSeeder.php

resources/
└── views/
    └── billing/
        ├── plans/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        ├── plan-modules/
        │   └── edit.blade.php
        ├── school-subscriptions/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        ├── module-overrides/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        └── locked/
            └── module.blade.php

routes/
└── web.php

docs/
└── subscription-module-entitlement-engine.md
```

Jika sebagian file sudah ada, jangan duplikasi. Update file yang ada.

---

# 8. Database Design

## 8.1 Table `subscription_plans`

Buat model dan migration:

```powershell
php artisan make:model SubscriptionPlan -m
```

Isi migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table): void {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');

            $table->unsignedBigInteger('monthly_price')->default(0);
            $table->unsignedBigInteger('yearly_price')->default(0);

            $table->text('description')->nullable();

            $table->json('limits')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('code');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
```

Contoh `limits`:

```json
{
  "max_students": 300,
  "max_teachers": 30,
  "max_parents": 600,
  "max_exports_per_month": 100,
  "storage_mb": 1024
}
```

---

## 8.2 Table `system_modules`

Jika tabel `system_modules` sudah ada, cek struktur. Jangan buat ulang.

Jika belum ada, buat:

```powershell
php artisan make:model SystemModule -m
```

Isi migration:

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

            $table->string('module_key')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->string('route_name')->nullable();
            $table->string('icon')->nullable();

            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->boolean('is_core')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('module_key');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_modules');
    }
};
```

Jika tabel sudah ada tetapi belum punya kolom penting, buat migration alter table yang aman.

Jangan drop table existing.

---

## 8.3 Table `plan_modules`

Buat:

```powershell
php artisan make:model PlanModule -m
```

Isi migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_modules', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->cascadeOnDelete();

            $table->foreignId('system_module_id')
                ->constrained('system_modules')
                ->cascadeOnDelete();

            $table->boolean('is_included')->default(true);

            $table->json('limits')->nullable();
            $table->json('features')->nullable();

            $table->timestamps();

            $table->unique(['subscription_plan_id', 'system_module_id'], 'plan_module_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_modules');
    }
};
```

Contoh `features`:

```json
{
  "can_export_pdf": true,
  "can_export_excel": true,
  "can_use_parent_portal": true,
  "can_use_bulk_input": false
}
```

---

## 8.4 Table `school_subscriptions`

Buat:

```powershell
php artisan make:model SchoolSubscription -m
```

Isi migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_subscriptions', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('subscription_plan_id')
                ->constrained('subscription_plans')
                ->restrictOnDelete();

            $table->string('status')->default('trialing');
            // trialing, active, past_due, suspended, canceled, expired

            $table->date('starts_at')->nullable();
            $table->date('trial_ends_at')->nullable();
            $table->date('current_period_starts_at')->nullable();
            $table->date('current_period_ends_at')->nullable();
            $table->date('canceled_at')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['school_id', 'status']);
            $table->index('subscription_plan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_subscriptions');
    }
};
```

Catatan:

Untuk tahap ini, status subscription diatur manual oleh super admin / admin internal. Jangan buat payment gateway.

---

## 8.5 Table `school_module_overrides`

Buat:

```powershell
php artisan make:model SchoolModuleOverride -m
```

Isi migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_module_overrides', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->foreignId('system_module_id')
                ->constrained('system_modules')
                ->cascadeOnDelete();

            $table->boolean('is_enabled')->default(true);

            $table->string('reason')->nullable();
            // manual_enable, manual_disable, trial_addon, enterprise_custom

            $table->timestamp('expires_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['school_id', 'system_module_id'], 'school_module_override_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_module_overrides');
    }
};
```

---

## 8.6 Table `subscription_usages`

Buat migration:

```powershell
php artisan make:migration create_subscription_usages_table
```

Isi migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_usages', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            $table->string('usage_key');
            // students_count, teachers_count, parents_count, exports_this_month, storage_mb

            $table->unsignedBigInteger('used')->default(0);
            $table->unsignedBigInteger('limit')->nullable();

            $table->date('period_starts_at')->nullable();
            $table->date('period_ends_at')->nullable();

            $table->timestamps();

            $table->unique(['school_id', 'usage_key', 'period_starts_at'], 'subscription_usage_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_usages');
    }
};
```

---

# 9. Model Relationships

## 9.1 `SubscriptionPlan`

Buka:

```text
app/Models/SubscriptionPlan.php
```

Isi atau lengkapi:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'monthly_price',
        'yearly_price',
        'description',
        'limits',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'monthly_price' => 'integer',
        'yearly_price' => 'integer',
        'limits' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(SystemModule::class, 'plan_modules')
            ->withPivot(['is_included', 'limits', 'features'])
            ->withTimestamps();
    }

    public function planModules(): HasMany
    {
        return $this->hasMany(PlanModule::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SchoolSubscription::class);
    }
}
```

---

## 9.2 `SystemModule`

Buka:

```text
app/Models/SystemModule.php
```

Isi atau lengkapi:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_key',
        'name',
        'description',
        'route_name',
        'icon',
        'sort_order',
        'is_core',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_core' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'plan_modules')
            ->withPivot(['is_included', 'limits', 'features'])
            ->withTimestamps();
    }

    public function planModules(): HasMany
    {
        return $this->hasMany(PlanModule::class);
    }
}
```

---

## 9.3 `PlanModule`

Buka:

```text
app/Models/PlanModule.php
```

Isi:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_plan_id',
        'system_module_id',
        'is_included',
        'limits',
        'features',
    ];

    protected $casts = [
        'is_included' => 'boolean',
        'limits' => 'array',
        'features' => 'array',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(SystemModule::class, 'system_module_id');
    }
}
```

---

## 9.4 `SchoolSubscription`

Buka:

```text
app/Models/SchoolSubscription.php
```

Isi:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'subscription_plan_id',
        'status',
        'starts_at',
        'trial_ends_at',
        'current_period_starts_at',
        'current_period_ends_at',
        'canceled_at',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'trial_ends_at' => 'date',
        'current_period_starts_at' => 'date',
        'current_period_ends_at' => 'date',
        'canceled_at' => 'date',
        'metadata' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['trialing', 'active'], true)
            && (
                is_null($this->current_period_ends_at)
                || $this->current_period_ends_at->isFuture()
                || $this->current_period_ends_at->isToday()
            );
    }
}
```

---

## 9.5 `SchoolModuleOverride`

Buka:

```text
app/Models/SchoolModuleOverride.php
```

Isi:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolModuleOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'system_module_id',
        'is_enabled',
        'reason',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(SystemModule::class, 'system_module_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
```

---

## 9.6 Update `School`

Buka:

```text
app/Models/School.php
```

Tambahkan relasi:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
```

Tambahkan method:

```php
public function subscriptions(): HasMany
{
    return $this->hasMany(SchoolSubscription::class);
}

public function activeSubscription(): HasOne
{
    return $this->hasOne(SchoolSubscription::class)
        ->whereIn('status', ['trialing', 'active'])
        ->latestOfMany();
}

public function moduleOverrides(): HasMany
{
    return $this->hasMany(SchoolModuleOverride::class);
}
```

---

# 10. Services

Buat folder:

```powershell
mkdir app\Services\Billing
```

Jika folder sudah ada, lanjut saja.

---

## 10.1 `CurrentSchoolResolver`

Buat file:

```text
app/Services/Billing/CurrentSchoolResolver.php
```

Isi:

```php
<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class CurrentSchoolResolver
{
    public function resolve(?User $user = null): ?School
    {
        $user ??= auth()->user();

        if (! $user) {
            return null;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole(['super_admin'])) {
            $requestedSchoolId = request()->input('school_id');

            if ($requestedSchoolId) {
                return School::query()->find($requestedSchoolId);
            }
        }

        if (isset($user->school_id) && $user->school_id) {
            return School::query()->find($user->school_id);
        }

        if (Schema::hasColumn('users', 'school_id')) {
            $schoolId = $user->getAttribute('school_id');

            if ($schoolId) {
                return School::query()->find($schoolId);
            }
        }

        return null;
    }
}
```

Catatan:

Jika project sudah punya tenant resolver sendiri, gunakan resolver existing dan sesuaikan service ini agar tidak duplikatif.

---

## 10.2 `SubscriptionStatusService`

Buat file:

```text
app/Services/Billing/SubscriptionStatusService.php
```

Isi:

```php
<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\SchoolSubscription;

class SubscriptionStatusService
{
    public function activeSubscriptionFor(School $school): ?SchoolSubscription
    {
        return SchoolSubscription::query()
            ->with('plan')
            ->where('school_id', $school->id)
            ->whereIn('status', ['trialing', 'active'])
            ->latest('id')
            ->first();
    }

    public function isActive(School $school): bool
    {
        $subscription = $this->activeSubscriptionFor($school);

        if (! $subscription) {
            return false;
        }

        return $subscription->isActive();
    }

    public function statusLabel(School $school): string
    {
        $subscription = SchoolSubscription::query()
            ->where('school_id', $school->id)
            ->latest('id')
            ->first();

        return $subscription?->status ?? 'none';
    }
}
```

---

## 10.3 `ModuleAccessService`

Buat file:

```text
app/Services/Billing/ModuleAccessService.php
```

Isi:

```php
<?php

namespace App\Services\Billing;

use App\Models\PlanModule;
use App\Models\School;
use App\Models\SchoolModuleOverride;
use App\Models\SchoolSubscription;
use App\Models\SystemModule;
use Illuminate\Support\Collection;

class ModuleAccessService
{
    public function isEnabledForSchool(School $school, string $moduleKey): bool
    {
        $module = SystemModule::query()
            ->where('module_key', $moduleKey)
            ->where('is_active', true)
            ->first();

        if (! $module) {
            return false;
        }

        $override = SchoolModuleOverride::query()
            ->where('school_id', $school->id)
            ->where('system_module_id', $module->id)
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest('id')
            ->first();

        if ($override) {
            return $override->is_enabled;
        }

        $subscription = SchoolSubscription::query()
            ->where('school_id', $school->id)
            ->whereIn('status', ['trialing', 'active'])
            ->latest('id')
            ->first();

        if (! $subscription || ! $subscription->isActive()) {
            return false;
        }

        return PlanModule::query()
            ->where('subscription_plan_id', $subscription->subscription_plan_id)
            ->where('system_module_id', $module->id)
            ->where('is_included', true)
            ->exists();
    }

    public function reasonForDeniedAccess(School $school, string $moduleKey): string
    {
        $module = SystemModule::query()
            ->where('module_key', $moduleKey)
            ->first();

        if (! $module) {
            return 'Module tidak ditemukan.';
        }

        if (! $module->is_active) {
            return 'Module sedang tidak aktif secara global.';
        }

        $override = SchoolModuleOverride::query()
            ->where('school_id', $school->id)
            ->where('system_module_id', $module->id)
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->latest('id')
            ->first();

        if ($override && ! $override->is_enabled) {
            return 'Module dinonaktifkan khusus untuk sekolah ini.';
        }

        $subscription = SchoolSubscription::query()
            ->with('plan')
            ->where('school_id', $school->id)
            ->latest('id')
            ->first();

        if (! $subscription) {
            return 'Sekolah belum memiliki subscription aktif.';
        }

        if (! in_array($subscription->status, ['trialing', 'active'], true)) {
            return 'Subscription sekolah tidak aktif.';
        }

        if (! $subscription->isActive()) {
            return 'Masa subscription sekolah sudah berakhir.';
        }

        return 'Module tidak termasuk dalam plan sekolah saat ini.';
    }

    public function enabledModulesForSchool(School $school): Collection
    {
        return SystemModule::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (SystemModule $module): bool => $this->isEnabledForSchool($school, $module->module_key))
            ->values();
    }

    public function moduleStatusForSchool(School $school, string $moduleKey): array
    {
        $enabled = $this->isEnabledForSchool($school, $moduleKey);

        return [
            'module_key' => $moduleKey,
            'enabled' => $enabled,
            'reason' => $enabled ? 'Module aktif.' : $this->reasonForDeniedAccess($school, $moduleKey),
        ];
    }
}
```

---

## 10.4 `PlanLimitService`

Buat file:

```text
app/Services/Billing/PlanLimitService.php
```

Isi:

```php
<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\SchoolSubscription;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlanLimitService
{
    public function getPlanLimit(School $school, string $limitKey): ?int
    {
        $subscription = SchoolSubscription::query()
            ->with('plan')
            ->where('school_id', $school->id)
            ->whereIn('status', ['trialing', 'active'])
            ->latest('id')
            ->first();

        if (! $subscription || ! $subscription->plan) {
            return null;
        }

        $limits = $subscription->plan->limits ?? [];

        return isset($limits[$limitKey]) ? (int) $limits[$limitKey] : null;
    }

    public function getUsage(School $school, string $usageKey): int
    {
        return match ($usageKey) {
            'students_count' => $this->countStudents($school),
            'teachers_count' => $this->countTeacherProfiles($school),
            'parents_count' => $this->countParentProfiles($school),
            'exports_this_month' => 0,
            default => 0,
        };
    }

    public function isWithinLimit(School $school, string $limitKey, int $nextValue = 1): bool
    {
        $limit = $this->getPlanLimit($school, $limitKey);

        if ($limit === null) {
            return true;
        }

        $usageKey = match ($limitKey) {
            'max_students' => 'students_count',
            'max_teachers' => 'teachers_count',
            'max_parents' => 'parents_count',
            'max_exports_per_month' => 'exports_this_month',
            default => null,
        };

        if (! $usageKey) {
            return true;
        }

        return ($this->getUsage($school, $usageKey) + $nextValue) <= $limit;
    }

    public function refreshUsage(School $school): void
    {
        DB::table('subscription_usages')->updateOrInsert(
            [
                'school_id' => $school->id,
                'usage_key' => 'students_count',
                'period_starts_at' => null,
            ],
            [
                'used' => $this->countStudents($school),
                'limit' => $this->getPlanLimit($school, 'max_students'),
                'period_ends_at' => null,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function countStudents(School $school): int
    {
        if (! Schema::hasTable('students')) {
            return 0;
        }

        return Student::query()
            ->where('school_id', $school->id)
            ->count();
    }

    private function countTeacherProfiles(School $school): int
    {
        if (! Schema::hasTable('teacher_profiles')) {
            return 0;
        }

        return DB::table('teacher_profiles')
            ->where('school_id', $school->id)
            ->count();
    }

    private function countParentProfiles(School $school): int
    {
        if (! Schema::hasTable('parent_profiles')) {
            return 0;
        }

        return DB::table('parent_profiles')
            ->where('school_id', $school->id)
            ->count();
    }
}
```

---

## 10.5 `BillingNavigationService`

Buat file:

```text
app/Services/Billing/BillingNavigationService.php
```

Isi:

```php
<?php

namespace App\Services\Billing;

use App\Models\School;
use App\Models\User;

class BillingNavigationService
{
    public function __construct(
        private readonly ModuleAccessService $moduleAccessService,
    ) {}

    public function visibleModulesFor(User $user, School $school): array
    {
        $items = [
            [
                'module_key' => 'tahfizh',
                'label' => 'Tahfizh',
                'route' => 'tahfizh.hafalan-records.index',
            ],
            [
                'module_key' => 'reports',
                'label' => 'Reports',
                'route' => 'reports.tahfizh.dashboard',
            ],
            [
                'module_key' => 'notifications',
                'label' => 'Notifications',
                'route' => 'notifications.index',
            ],
            [
                'module_key' => 'exports',
                'label' => 'Export',
                'route' => 'exports.tahfizh.index',
            ],
            [
                'module_key' => 'mutabaah',
                'label' => 'Mutabaah',
                'route' => 'mutabaah.activities.index',
            ],
            [
                'module_key' => 'attendance',
                'label' => 'Attendance',
                'route' => 'attendance.sessions.index',
            ],
            [
                'module_key' => 'tahsin',
                'label' => 'Tahsin',
                'route' => 'tahsin.levels.index',
            ],
            [
                'module_key' => 'finance',
                'label' => 'Finance',
                'route' => 'finance.reports.dashboard',
            ],
            [
                'module_key' => 'schoolos',
                'label' => 'SchoolOS',
                'route' => 'schoolos.dashboard',
            ],
        ];

        $isAdminLike = method_exists($user, 'hasRole')
            && $user->hasRole(['super_admin', 'admin', 'admin_sekolah']);

        return collect($items)
            ->map(function (array $item) use ($school, $isAdminLike): ?array {
                $enabled = $this->moduleAccessService->isEnabledForSchool($school, $item['module_key']);

                if (! $enabled && ! $isAdminLike) {
                    return null;
                }

                $item['enabled'] = $enabled;
                $item['locked'] = ! $enabled;

                return $item;
            })
            ->filter()
            ->values()
            ->all();
    }
}
```

Catatan:

Jika nama route berbeda dari project aktual, sesuaikan. Jangan membuat route modul baru hanya demi navigation jika fiturnya belum ada.

---

# 11. Middleware

## 11.1 `EnsureSubscriptionIsActive`

Buat:

```powershell
php artisan make:middleware EnsureSubscriptionIsActive
```

Buka:

```text
app/Http/Middleware/EnsureSubscriptionIsActive.php
```

Isi:

```php
<?php

namespace App\Http\Middleware;

use App\Services\Billing\CurrentSchoolResolver;
use App\Services\Billing\SubscriptionStatusService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSubscriptionIsActive
{
    public function __construct(
        private readonly CurrentSchoolResolver $currentSchoolResolver,
        private readonly SubscriptionStatusService $subscriptionStatusService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole(['super_admin'])) {
            return $next($request);
        }

        $school = $this->currentSchoolResolver->resolve($user);

        if (! $school) {
            abort(403, 'School context tidak ditemukan.');
        }

        if (! $this->subscriptionStatusService->isActive($school)) {
            if (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'admin_sekolah'])) {
                return redirect()->route('billing.locked.module', ['moduleKey' => 'subscription']);
            }

            abort(403, 'Subscription sekolah tidak aktif.');
        }

        return $next($request);
    }
}
```

Register middleware alias di Laravel 12 sesuai struktur `bootstrap/app.php`.

Tambahkan alias:

```php
'subscription.active' => \App\Http\Middleware\EnsureSubscriptionIsActive::class,
```

---

## 11.2 `EnsureModuleIsEnabled`

Buat:

```powershell
php artisan make:middleware EnsureModuleIsEnabled
```

Buka:

```text
app/Http/Middleware/EnsureModuleIsEnabled.php
```

Isi:

```php
<?php

namespace App\Http\Middleware;

use App\Services\Billing\CurrentSchoolResolver;
use App\Services\Billing\ModuleAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleIsEnabled
{
    public function __construct(
        private readonly CurrentSchoolResolver $currentSchoolResolver,
        private readonly ModuleAccessService $moduleAccessService,
    ) {}

    public function handle(Request $request, Closure $next, string $moduleKey): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole(['super_admin'])) {
            return $next($request);
        }

        $school = $this->currentSchoolResolver->resolve($user);

        if (! $school) {
            abort(403, 'School context tidak ditemukan.');
        }

        if ($this->moduleAccessService->isEnabledForSchool($school, $moduleKey)) {
            return $next($request);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'admin_sekolah'])) {
            return redirect()->route('billing.locked.module', ['moduleKey' => $moduleKey]);
        }

        abort(403, 'Module tidak aktif untuk sekolah ini.');
    }
}
```

Register middleware alias di Laravel 12 sesuai struktur `bootstrap/app.php`.

Tambahkan alias:

```php
'module' => \App\Http\Middleware\EnsureModuleIsEnabled::class,
```

---

# 12. Form Requests

Buat request:

```powershell
php artisan make:request Billing/StoreSubscriptionPlanRequest
php artisan make:request Billing/UpdateSubscriptionPlanRequest
php artisan make:request Billing/SyncPlanModulesRequest
php artisan make:request Billing/StoreSchoolSubscriptionRequest
php artisan make:request Billing/UpdateSchoolSubscriptionRequest
php artisan make:request Billing/StoreSchoolModuleOverrideRequest
```

## 12.1 `StoreSubscriptionPlanRequest`

Isi rules:

```php
public function authorize(): bool
{
    return $this->user()?->hasRole(['super_admin', 'admin', 'admin_sekolah']) ?? false;
}

public function rules(): array
{
    return [
        'code' => ['required', 'string', 'max:100', 'unique:subscription_plans,code'],
        'name' => ['required', 'string', 'max:150'],
        'monthly_price' => ['nullable', 'integer', 'min:0'],
        'yearly_price' => ['nullable', 'integer', 'min:0'],
        'description' => ['nullable', 'string'],
        'limits' => ['nullable', 'array'],
        'is_active' => ['nullable', 'boolean'],
        'sort_order' => ['nullable', 'integer', 'min:0'],
    ];
}
```

---

## 12.2 `UpdateSubscriptionPlanRequest`

Isi rules:

```php
public function authorize(): bool
{
    return $this->user()?->hasRole(['super_admin', 'admin', 'admin_sekolah']) ?? false;
}

public function rules(): array
{
    $planId = $this->route('plan')?->id ?? $this->route('subscription_plan')?->id ?? $this->route('id');

    return [
        'code' => ['required', 'string', 'max:100', 'unique:subscription_plans,code,' . $planId],
        'name' => ['required', 'string', 'max:150'],
        'monthly_price' => ['nullable', 'integer', 'min:0'],
        'yearly_price' => ['nullable', 'integer', 'min:0'],
        'description' => ['nullable', 'string'],
        'limits' => ['nullable', 'array'],
        'is_active' => ['nullable', 'boolean'],
        'sort_order' => ['nullable', 'integer', 'min:0'],
    ];
}
```

---

## 12.3 `SyncPlanModulesRequest`

Isi rules:

```php
public function authorize(): bool
{
    return $this->user()?->hasRole(['super_admin', 'admin', 'admin_sekolah']) ?? false;
}

public function rules(): array
{
    return [
        'modules' => ['nullable', 'array'],
        'modules.*.system_module_id' => ['required', 'exists:system_modules,id'],
        'modules.*.is_included' => ['nullable', 'boolean'],
        'modules.*.limits' => ['nullable', 'array'],
        'modules.*.features' => ['nullable', 'array'],
    ];
}
```

---

## 12.4 `StoreSchoolSubscriptionRequest`

Isi rules:

```php
public function authorize(): bool
{
    return $this->user()?->hasRole(['super_admin', 'admin', 'admin_sekolah']) ?? false;
}

public function rules(): array
{
    return [
        'school_id' => ['required', 'exists:schools,id'],
        'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
        'status' => ['required', 'in:trialing,active,past_due,suspended,canceled,expired'],
        'starts_at' => ['nullable', 'date'],
        'trial_ends_at' => ['nullable', 'date'],
        'current_period_starts_at' => ['nullable', 'date'],
        'current_period_ends_at' => ['nullable', 'date', 'after_or_equal:current_period_starts_at'],
        'metadata' => ['nullable', 'array'],
    ];
}
```

---

## 12.5 `UpdateSchoolSubscriptionRequest`

Isi rules sama seperti store, tetapi `school_id` boleh tetap required agar jelas.

---

## 12.6 `StoreSchoolModuleOverrideRequest`

Isi rules:

```php
public function authorize(): bool
{
    return $this->user()?->hasRole(['super_admin', 'admin', 'admin_sekolah']) ?? false;
}

public function rules(): array
{
    return [
        'school_id' => ['required', 'exists:schools,id'],
        'system_module_id' => ['required', 'exists:system_modules,id'],
        'is_enabled' => ['required', 'boolean'],
        'reason' => ['nullable', 'string', 'max:100'],
        'expires_at' => ['nullable', 'date'],
    ];
}
```

---

# 13. Controllers

Buat controller:

```powershell
php artisan make:controller Billing/SubscriptionPlanController --resource
php artisan make:controller Billing/PlanModuleController
php artisan make:controller Billing/SchoolSubscriptionController --resource
php artisan make:controller Billing/SchoolModuleOverrideController --resource
php artisan make:controller Billing/ModuleLockedController
```

---

## 13.1 `SubscriptionPlanController`

Fitur wajib:

1. Index plan.
2. Create plan.
3. Store plan.
4. Edit plan.
5. Update plan.
6. Show plan.
7. Jangan hard delete plan yang sudah pernah dipakai subscription.
8. Jika perlu disable, gunakan `is_active = false`.

Logic minimal:

```php
public function index()
{
    $plans = SubscriptionPlan::query()
        ->withCount('planModules')
        ->orderBy('sort_order')
        ->orderBy('id')
        ->paginate(20);

    return view('billing.plans.index', compact('plans'));
}
```

---

## 13.2 `PlanModuleController`

Fitur wajib:

1. Tampilkan semua system module.
2. Checkbox module included untuk plan.
3. Input limits/features JSON sederhana.
4. Simpan sync ke `plan_modules`.
5. Jangan hardcode module per plan di controller.

Method minimal:

```php
public function edit(SubscriptionPlan $plan)
{
    $modules = SystemModule::query()
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    $planModules = PlanModule::query()
        ->where('subscription_plan_id', $plan->id)
        ->get()
        ->keyBy('system_module_id');

    return view('billing.plan-modules.edit', compact('plan', 'modules', 'planModules'));
}
```

---

## 13.3 `SchoolSubscriptionController`

Fitur wajib:

1. Lihat subscription semua sekolah.
2. Assign plan ke sekolah.
3. Update status subscription.
4. Update tanggal trial/current period.
5. Tidak membuat payment.

Status valid:

```text
trialing
active
past_due
suspended
canceled
expired
```

---

## 13.4 `SchoolModuleOverrideController`

Fitur wajib:

1. List override.
2. Create override.
3. Enable/disable module per school.
4. Set reason.
5. Set expires_at.
6. Edit override.
7. Delete override jika aman.

---

## 13.5 `ModuleLockedController`

Fitur wajib:

1. Menampilkan module key.
2. Menampilkan alasan akses ditolak.
3. Menampilkan plan aktif sekolah.
4. Menampilkan status subscription.
5. Menampilkan CTA upgrade.
6. Tidak menampilkan data sensitif.

---

# 14. Routes

Update:

```text
routes/web.php
```

Tambahkan import controller sesuai namespace aktual.

Route billing admin:

```php
use App\Http\Controllers\Billing\ModuleLockedController;
use App\Http\Controllers\Billing\PlanModuleController;
use App\Http\Controllers\Billing\SchoolModuleOverrideController;
use App\Http\Controllers\Billing\SchoolSubscriptionController;
use App\Http\Controllers\Billing\SubscriptionPlanController;

Route::middleware(['auth', 'role:super_admin,admin,admin_sekolah'])
    ->prefix('billing')
    ->name('billing.')
    ->group(function (): void {
        Route::resource('plans', SubscriptionPlanController::class);

        Route::get('plans/{plan}/modules', [PlanModuleController::class, 'edit'])
            ->name('plans.modules.edit');

        Route::put('plans/{plan}/modules', [PlanModuleController::class, 'update'])
            ->name('plans.modules.update');

        Route::resource('school-subscriptions', SchoolSubscriptionController::class);

        Route::resource('module-overrides', SchoolModuleOverrideController::class)
            ->except(['show']);

        Route::get('locked/module/{moduleKey}', [ModuleLockedController::class, 'show'])
            ->name('locked.module');
    });
```

Sesuaikan middleware role dengan nama role aktual di project.

---

# 15. Route Protection Existing Modules

Cari route existing untuk modul:

1. Tahfizh.
2. Reports.
3. Notifications.
4. Export.
5. Mutabaah.
6. Attendance.
7. Tahsin.
8. Finance.
9. SchoolOS.
10. Boarding.
11. LMS Lite.
12. AI Assistant.

Tambahkan middleware `subscription.active` dan `module:{module_key}` dengan hati-hati.

Contoh:

```php
Route::middleware(['auth', 'subscription.active', 'module:tahfizh'])
    ->prefix('tahfizh')
    ->group(function (): void {
        // existing tahfizh routes
    });

Route::middleware(['auth', 'subscription.active', 'module:finance'])
    ->prefix('finance')
    ->group(function (): void {
        // existing finance routes
    });

Route::middleware(['auth', 'subscription.active', 'module:attendance'])
    ->prefix('attendance')
    ->group(function (): void {
        // existing attendance routes
    });

Route::middleware(['auth', 'subscription.active', 'module:tahsin'])
    ->prefix('tahsin')
    ->group(function (): void {
        // existing tahsin routes
    });

Route::middleware(['auth', 'subscription.active', 'module:mutabaah'])
    ->prefix('mutabaah')
    ->group(function (): void {
        // existing mutabaah routes
    });
```

Jangan merusak ownership access existing di parent/student portal.

Route yang tidak boleh dikunci module:

1. Login.
2. Logout.
3. Dashboard dasar.
4. Admin system status.
5. Health check `/up`.
6. Billing locked page.
7. Billing plan management untuk super admin/admin internal.

---

# 16. Seeder

Buat seeder:

```powershell
php artisan make:seeder SystemModuleSeeder
php artisan make:seeder SubscriptionPlanSeeder
php artisan make:seeder PlanModuleSeeder
```

---

## 16.1 `SystemModuleSeeder`

Gunakan `updateOrCreate`, jangan insert buta.

Isi minimal:

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
            ['module_key' => 'tahfizh', 'name' => 'Tahfizh', 'is_core' => true, 'sort_order' => 10],
            ['module_key' => 'reports', 'name' => 'Reports', 'is_core' => true, 'sort_order' => 20],
            ['module_key' => 'notifications', 'name' => 'Notifications', 'is_core' => true, 'sort_order' => 30],
            ['module_key' => 'exports', 'name' => 'Export PDF/Excel', 'is_core' => false, 'sort_order' => 40],
            ['module_key' => 'mutabaah', 'name' => 'Mutabaah Yaumiyah', 'is_core' => false, 'sort_order' => 50],
            ['module_key' => 'attendance', 'name' => 'QR Attendance', 'is_core' => false, 'sort_order' => 60],
            ['module_key' => 'tahsin', 'name' => 'Tahsin', 'is_core' => false, 'sort_order' => 70],
            ['module_key' => 'finance', 'name' => 'Student Finance Ledger', 'is_core' => false, 'sort_order' => 80],
            ['module_key' => 'schoolos', 'name' => 'SchoolOS Mini', 'is_core' => false, 'sort_order' => 90],
            ['module_key' => 'boarding', 'name' => 'Boarding School', 'is_core' => false, 'sort_order' => 100],
            ['module_key' => 'white_label', 'name' => 'White Label', 'is_core' => false, 'sort_order' => 110],
            ['module_key' => 'cashless_pos', 'name' => 'Cashless POS', 'is_core' => false, 'sort_order' => 120],
            ['module_key' => 'lms_lite', 'name' => 'LMS Lite', 'is_core' => false, 'sort_order' => 130],
            ['module_key' => 'ai_assistant', 'name' => 'AI Assistant', 'is_core' => false, 'sort_order' => 140],
        ];

        foreach ($modules as $module) {
            SystemModule::query()->updateOrCreate(
                ['module_key' => $module['module_key']],
                array_merge($module, ['is_active' => true])
            );
        }
    }
}
```

---

## 16.2 `SubscriptionPlanSeeder`

Isi:

```php
<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => 'trial',
                'name' => 'Trial',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'description' => 'Trial terbatas untuk demo sekolah.',
                'limits' => [
                    'max_students' => 50,
                    'max_teachers' => 5,
                    'max_parents' => 100,
                    'max_exports_per_month' => 10,
                    'storage_mb' => 256,
                ],
                'sort_order' => 10,
            ],
            [
                'code' => 'basic',
                'name' => 'Basic',
                'monthly_price' => 299000,
                'yearly_price' => 2990000,
                'description' => 'Plan awal untuk sekolah kecil.',
                'limits' => [
                    'max_students' => 300,
                    'max_teachers' => 30,
                    'max_parents' => 600,
                    'max_exports_per_month' => 100,
                    'storage_mb' => 1024,
                ],
                'sort_order' => 20,
            ],
            [
                'code' => 'pro',
                'name' => 'Pro',
                'monthly_price' => 699000,
                'yearly_price' => 6990000,
                'description' => 'Plan lengkap untuk sekolah aktif.',
                'limits' => [
                    'max_students' => 1000,
                    'max_teachers' => 100,
                    'max_parents' => 2000,
                    'max_exports_per_month' => 1000,
                    'storage_mb' => 5120,
                ],
                'sort_order' => 30,
            ],
            [
                'code' => 'enterprise',
                'name' => 'Enterprise',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'description' => 'Plan custom untuk sekolah besar dan kebutuhan khusus.',
                'limits' => [
                    'max_students' => 999999,
                    'max_teachers' => 999999,
                    'max_parents' => 999999,
                    'max_exports_per_month' => 999999,
                    'storage_mb' => 51200,
                ],
                'sort_order' => 40,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::query()->updateOrCreate(
                ['code' => $plan['code']],
                array_merge($plan, ['is_active' => true])
            );
        }
    }
}
```

---

## 16.3 `PlanModuleSeeder`

Isi:

```php
<?php

namespace Database\Seeders;

use App\Models\PlanModule;
use App\Models\SubscriptionPlan;
use App\Models\SystemModule;
use Illuminate\Database\Seeder;

class PlanModuleSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'trial' => [
                'tahfizh',
                'reports',
                'notifications',
            ],
            'basic' => [
                'tahfizh',
                'reports',
                'notifications',
                'exports',
            ],
            'pro' => [
                'tahfizh',
                'reports',
                'notifications',
                'exports',
                'mutabaah',
                'attendance',
                'tahsin',
                'finance',
                'schoolos',
            ],
            'enterprise' => SystemModule::query()
                ->where('is_active', true)
                ->pluck('module_key')
                ->all(),
        ];

        foreach ($mapping as $planCode => $moduleKeys) {
            $plan = SubscriptionPlan::query()->where('code', $planCode)->first();

            if (! $plan) {
                continue;
            }

            foreach ($moduleKeys as $moduleKey) {
                $module = SystemModule::query()->where('module_key', $moduleKey)->first();

                if (! $module) {
                    continue;
                }

                PlanModule::query()->updateOrCreate(
                    [
                        'subscription_plan_id' => $plan->id,
                        'system_module_id' => $module->id,
                    ],
                    [
                        'is_included' => true,
                        'limits' => null,
                        'features' => null,
                    ]
                );
            }
        }
    }
}
```

Tambahkan ke `DatabaseSeeder` jika sesuai:

```php
$this->call([
    SystemModuleSeeder::class,
    SubscriptionPlanSeeder::class,
    PlanModuleSeeder::class,
]);
```

---

# 17. UI Requirements

Gunakan Blade biasa. Jangan install admin panel baru.

---

## 17.1 Plans Index

Tampilkan:

1. Code.
2. Name.
3. Monthly price.
4. Yearly price.
5. Active/inactive.
6. Jumlah modul included.
7. Tombol edit.
8. Tombol modules.
9. Tombol show.

---

## 17.2 Plan Modules Edit

Tampilkan list modul dengan checkbox.

Kolom:

1. Module name.
2. Module key.
3. Included?
4. Limits JSON textarea.
5. Features JSON textarea.

Validasi JSON sederhana. Jika input JSON tidak valid, tampilkan error.

---

## 17.3 School Subscriptions Index

Tampilkan:

1. School.
2. Plan.
3. Status.
4. Current period.
5. Trial ends.
6. Actions.

---

## 17.4 Module Overrides Index

Tampilkan:

1. School.
2. Module.
3. Enabled / Disabled.
4. Reason.
5. Expires At.
6. Created By.

---

## 17.5 Module Locked Page

Tampilkan:

```text
Modul ini tidak tersedia pada plan sekolah Anda.
```

Detail:

1. Nama modul.
2. Plan aktif.
3. Status subscription.
4. Alasan akses ditolak.
5. Saran upgrade.

Untuk parent/student, jangan tampilkan halaman promosi berlebihan. Cukup 403 atau pesan singkat.

---

# 18. Navigation Filtering

Update layout navigation agar menu modular hanya muncul jika modul aktif.

Gunakan `BillingNavigationService`.

Jangan melakukan query module access berulang-ulang di setiap item menu tanpa caching sederhana per request.

Minimal cache local variable di view composer / controller.

Menu yang harus dikontrol:

1. Tahfizh: `module:tahfizh`.
2. Reports: `module:reports`.
3. Notifications: `module:notifications`.
4. Export: `module:exports`.
5. Mutabaah: `module:mutabaah`.
6. Attendance: `module:attendance`.
7. Tahsin: `module:tahsin`.
8. Finance: `module:finance`.
9. SchoolOS: `module:schoolos`.
10. Boarding: `module:boarding`.
11. LMS Lite: `module:lms_lite`.
12. AI Assistant: `module:ai_assistant`.

Jika route modul belum ada, jangan buat fitur baru. Cukup siapkan module key dan navigation logic.

---

# 19. Limit Enforcement Minimal

Implement minimal limit untuk student creation.

Cari controller store santri.

Sebelum create student, cek:

```php
app(\App\Services\Billing\PlanLimitService::class)
    ->isWithinLimit($school, 'max_students', 1);
```

Jika limit terlampaui:

1. Admin: tampilkan error `Limit jumlah santri pada plan ini sudah tercapai.`
2. Non-admin: abort 403.

Jangan enforce semua limit sekaligus jika terlalu banyak risiko.

Minimal buat service dan implement di student creation dulu.

Tambahkan TODO dokumentasi untuk:

1. `max_teachers`.
2. `max_parents`.
3. `max_exports_per_month`.
4. `storage_mb`.

---

# 20. Testing Manual

Setelah selesai, jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=SystemModuleSeeder
php artisan db:seed --class=SubscriptionPlanSeeder
php artisan db:seed --class=PlanModuleSeeder
php artisan optimize:clear
php artisan route:list
npm run build
```

---

## 20.1 Case 1 — Basic Plan

1. Assign sekolah ke Basic.
2. Login admin sekolah.
3. Pastikan Tahfizh, Reports, Notifications, Export muncul.
4. Pastikan Finance, Attendance, Tahsin, Mutabaah terkunci atau tidak muncul sesuai role.
5. Akses URL finance langsung harus ditolak / locked.

---

## 20.2 Case 2 — Pro Plan

1. Assign sekolah ke Pro.
2. Login admin sekolah.
3. Pastikan Finance, Attendance, Tahsin, Mutabaah aktif.
4. Parent tetap hanya melihat data anak sendiri.
5. Student tetap hanya melihat data sendiri.

---

## 20.3 Case 3 — Override Enable

1. Sekolah Basic.
2. Enable module `attendance` lewat override.
3. Attendance harus aktif walaupun Basic tidak punya attendance.

---

## 20.4 Case 4 — Override Disable

1. Sekolah Pro.
2. Disable module `finance` lewat override.
3. Finance harus terkunci walaupun Pro punya finance.

---

## 20.5 Case 5 — Expired Override

1. Buat override dengan `expires_at` masa lalu.
2. Override tidak boleh berlaku.
3. Sistem harus kembali ke rule plan.

---

## 20.6 Case 6 — Suspended Subscription

1. Set subscription status ke `suspended`.
2. Semua module protected harus ditolak.
3. Billing admin route tetap bisa dibuka oleh super admin/admin internal.

---

## 20.7 Case 7 — Student Limit

1. Set plan max_students kecil.
2. Coba tambah santri melebihi limit.
3. Sistem harus menolak dengan pesan jelas.

---

# 21. Dokumentasi

Buat file:

```text
docs/subscription-module-entitlement-engine.md
```

Isi dokumentasi minimal:

1. Tujuan fitur.
2. Struktur tabel.
3. Relasi data.
4. Daftar plan default.
5. Daftar module key.
6. Cara assign subscription ke sekolah.
7. Cara mengatur plan modules.
8. Cara membuat module override.
9. Cara middleware module bekerja.
10. Cara navigation filtering bekerja.
11. Cara menambah modul baru.
12. Batasan: belum ada payment gateway otomatis.

Tambahkan juga update ke:

```text
docs/project-progress.md
```

Jika file tersebut ada.

---

# 22. Aturan Keras Saat Implementasi

1. Jangan hardcode plan di controller.
2. Jangan simpan `plan_id` di users.
3. Jangan hapus tabel existing.
4. Jangan mengubah auth besar-besaran.
5. Jangan mengubah role system besar-besaran.
6. Jangan membuat payment gateway.
7. Jangan membuat cashless POS.
8. Jangan membuat wallet.
9. Jangan membuat invoice otomatis.
10. Jangan membuat multi-tenant ulang jika sudah ada.
11. Jangan menghilangkan ownership access parent/student.
12. Jangan expose modul terkunci ke parent/student.
13. Jangan membuat package baru kecuali sangat wajib.
14. Semua route modul penting harus dilindungi middleware.
15. UI admin harus bisa mengelola plan dan module entitlement tanpa edit database manual.
16. Override module harus menang atas plan.
17. Expired override tidak boleh berlaku.
18. Suspended subscription harus memblokir protected module.
19. Super admin boleh mengelola billing walaupun school subscription bermasalah.
20. Jangan merusak route login, logout, dashboard dasar, dan health check.

---

# 23. Definition of Done

Fitur dianggap selesai jika:

1. Migration berjalan tanpa error.
2. Seeder plan dan module berjalan tanpa duplikasi.
3. Plan bisa dibuat, diedit, dan dinonaktifkan.
4. Module bisa dihubungkan ke plan.
5. Sekolah bisa diberi subscription.
6. Status subscription bisa diubah.
7. Module override bisa dibuat.
8. Override enable mengaktifkan modul.
9. Override disable mematikan modul.
10. Override expired tidak berlaku.
11. Middleware `subscription.active` berjalan.
12. Middleware `module:{key}` berjalan.
13. Sidebar hanya menampilkan modul yang aktif sesuai role.
14. Halaman locked module tampil untuk admin.
15. Parent/student tetap aman dari akses data sekolah lain.
16. Limit santri minimal berjalan.
17. `php artisan route:list` tidak error.
18. `npm run build` berhasil.
19. Dokumentasi dibuat.
20. Tidak ada payment gateway atau fitur billing otomatis yang dibuat.

---

# 24. Laporan Akhir Agent

Setelah selesai, laporkan:

1. File apa saja yang dibuat.
2. File apa saja yang diubah.
3. Migration yang dibuat.
4. Seeder yang dibuat.
5. Route baru.
6. Middleware baru.
7. Cara test manual.
8. Risiko atau TODO yang tersisa.
9. Apakah ada konflik dengan `system_modules` existing.
10. Apakah role name aktual berbeda dari asumsi dokumen ini.

---

# 25. Catatan Strategis

Jangan lanjut ke payment gateway sebelum entitlement engine ini stabil.

Payment gateway hanya alat menerima pembayaran.

Yang menentukan SaaS rapi atau berantakan adalah:

```text
Plan -> Entitlement -> School Subscription -> Module Access -> Role Permission -> Ownership Access
```

Jika fondasi ini salah, seluruh monetisasi akan jadi tambal-sulam.

