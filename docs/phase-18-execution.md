# Phase 18 Execution Guide — White-Label School App Builder

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
Phase 18 — White-Label School App Builder
```

---

# 1. Keputusan Sebelum Phase 18

## 1.1 UAT Phase 17 Wajib

Sebelum menjalankan Phase 18, agent wajib memastikan **Phase 17 Multi-Tenant Foundation** sudah aman.

Jangan menambah white-label jika tenant isolation masih bermasalah.

Phase 18 hanya boleh dieksekusi jika:

1. Tenant context berjalan.
2. User selalu terikat ke tenant/school yang benar.
3. Data sekolah A tidak terlihat oleh sekolah B.
4. Query utama sudah terscope `school_id` atau tenant context.
5. Super Admin bisa berpindah tenant secara eksplisit.
6. Admin sekolah hanya mengelola tenant sendiri.
7. Parent hanya melihat anak sendiri dalam tenant sendiri.
8. Student hanya melihat data pribadi dalam tenant sendiri.
9. Teacher hanya melihat santri dalam tenant/scope yang benar.
10. Tenant middleware berjalan.
11. Tenant audit/check command berjalan.
12. `php artisan app:system-health-check` berhasil.
13. `npm run build` berhasil.
14. Backup database berhasil.
15. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0/P1, hentikan Phase 18 dan buat bug fix sprint dulu.

---

## 1.2 Kenapa Phase 18 Bukan Cashless / POS

Phase 18 bukan:

1. Cashless kantin.
2. Merchant POS.
3. Wallet.
4. Payment gateway.
5. QRIS otomatis.
6. Virtual account.
7. Rekonsiliasi bank.
8. Native Android app build.
9. Native iOS app build.
10. App Store deployment.
11. Play Store deployment.
12. LMS penuh.
13. Marketplace konten.
14. White-label mobile native generator.

Phase 18 adalah **white-label web/PWA layer** di atas multi-tenant foundation.

Tujuannya bukan membuat aplikasi native per sekolah.

Tujuannya adalah membuat setiap sekolah bisa punya tampilan, identitas, URL, dan konfigurasi publik yang berbeda, tanpa memecah backend inti.

---

## 1.3 Klasifikasi Bug Sebelum Phase 18

| Prioritas | Contoh Bug | Keputusan |
|---|---|---|
| P0 | Data sekolah A terlihat di sekolah B, tenant context salah, login masuk tenant keliru, custom domain membuka tenant salah | Wajib fix sebelum Phase 18 |
| P1 | Branding sekolah salah tampil, logo tenant bocor, theme tidak konsisten, manifest PWA salah tenant | Wajib fix sebelum lanjut |
| P2 | UI kurang rapi, warna kurang kontras, preview kurang halus | Boleh dicatat |
| P3 | Enhancement kosmetik | Boleh ditunda |

---

# 2. Tujuan Phase 18

Phase 18 bertujuan membuat **White-Label School App Builder**.

Modul ini digunakan untuk membuat HafizPlus School Platform bisa tampil sebagai platform milik masing-masing sekolah secara terpisah.

Fokus Phase 18:

1. Branding per sekolah.
2. Logo per sekolah.
3. Favicon per sekolah.
4. Warna utama dan sekunder per sekolah.
5. Login page per sekolah.
6. Public school landing page sederhana.
7. PWA manifest per sekolah.
8. PDF/report branding per sekolah.
9. Email/notification branding metadata.
10. Custom subdomain mapping.
11. Custom domain mapping sederhana.
12. Domain verification manual.
13. White-label preview.
14. White-label publish/unpublish.
15. Role-based access.
16. Tenant-based access.
17. Dokumentasi Phase 18.

---

# 3. Batasan Phase 18

AI agent tidak boleh membuat fitur berikut pada Phase 18:

1. Native Android generator.
2. Native iOS generator.
3. Play Store deployment automation.
4. App Store deployment automation.
5. Firebase push notification.
6. WhatsApp gateway.
7. Websocket.
8. Cashless kantin.
9. Merchant POS.
10. Wallet.
11. Payment gateway.
12. QRIS otomatis.
13. Billing SaaS production.
14. Auto DNS provider integration.
15. Cloudflare API integration.
16. cPanel automation.
17. Server provisioning automation.
18. Separate database per tenant.
19. Multi-region deployment.
20. LMS penuh.
21. Marketplace plugin.
22. Theme marketplace.
23. Drag-and-drop page builder kompleks.
24. User-generated CSS bebas tanpa sanitasi.

Phase 18 hanya membuat:

```text
White-label web/PWA branding, domain mapping, tenant branding config, dan preview/publish flow.
```

---

# 4. Prinsip Teknis Phase 18

## 4.1 White-Label Tidak Boleh Merusak Tenant Isolation

White-label hanya boleh mengubah tampilan dan konfigurasi tenant.

White-label tidak boleh mengubah:

1. Tenant ownership.
2. Data access.
3. Role access.
4. Parent ownership.
5. Student ownership.
6. Teacher scope.
7. Financial ledger isolation.
8. Audit log isolation.

Jika request datang dari domain tertentu, sistem harus resolve domain ke tenant yang benar.

Jika domain tidak dikenali, tampilkan fallback aman, bukan tenant random.

---

## 4.2 Branding Disimpan di Database dan Storage

Branding config disimpan di database.

File asset seperti logo, favicon, dan background disimpan di storage Laravel.

Jangan hardcode logo sekolah di Blade.

Jangan simpan path absolut Windows di database.

Gunakan disk public/storage yang sudah disiapkan.

---

## 4.3 Theme Harus Terbatas dan Aman

Jangan izinkan user memasukkan CSS bebas pada Phase 18.

Theme cukup berupa token:

1. Primary color.
2. Secondary color.
3. Accent color.
4. Text color.
5. Background color.
6. Login layout mode.
7. Dashboard style mode.
8. Radius style.
9. Logo position.

Alasan:

1. CSS bebas bisa merusak UI.
2. CSS bebas bisa membuka risiko injection.
3. Theme token lebih mudah divalidasi.
4. Theme token lebih aman untuk multi-tenant.

---

## 4.4 Domain Mapping Harus Manual Dulu

Phase 18 boleh membuat domain mapping table dan verifikasi manual.

Jangan membuat integrasi otomatis ke DNS provider.

Alur Phase 18:

1. Admin/Super Admin menambahkan domain atau subdomain.
2. Sistem membuat verification token.
3. User mengatur DNS secara manual.
4. Super Admin menjalankan verifikasi manual.
5. Jika valid, domain aktif.

Untuk local development, domain boleh disimulasikan lewat host header atau `.env` mapping.

---

## 4.5 PWA Per Sekolah

Phase 18 membuat manifest PWA dinamis per tenant.

Manifest harus menampilkan:

1. Nama sekolah.
2. Short name.
3. Theme color.
4. Background color.
5. Icon tenant jika tersedia.
6. Start URL tenant.

Phase 18 tidak membuat native mobile app.

---

# 5. Konsep White-Label

## 5.1 School Brand Profile

School brand profile adalah identitas visual tenant/sekolah.

Data minimal:

1. School ID.
2. Display name.
3. Short name.
4. Tagline.
5. Logo.
6. Favicon.
7. Login background.
8. Primary color.
9. Secondary color.
10. Accent color.
11. Public contact email.
12. Public contact phone.
13. Public address.
14. Public website URL.
15. Status aktif.

---

## 5.2 White-Label Theme

White-label theme adalah konfigurasi UI per sekolah.

Field minimal:

1. School ID.
2. Theme name.
3. Primary color.
4. Secondary color.
5. Accent color.
6. Text color.
7. Background color.
8. Sidebar style.
9. Header style.
10. Login layout.
11. Card radius.
12. Button radius.
13. Is active.

---

## 5.3 Domain Mapping

Domain mapping menghubungkan domain/subdomain ke tenant.

Contoh:

```text
alazhar7.hafizplus.id → SMA Islam Al Azhar 7
portal.smait-example.sch.id → SMAIT Example
```

Status domain:

| Status | Makna |
|---|---|
| `pending` | Baru dibuat, belum diverifikasi |
| `verified` | DNS/domain sudah diverifikasi |
| `active` | Domain aktif dipakai |
| `disabled` | Domain dinonaktifkan |
| `failed` | Verifikasi gagal |

---

## 5.4 White-Label Publication

Tidak semua konfigurasi langsung live.

Phase 18 harus mendukung:

1. Draft branding.
2. Preview branding.
3. Publish branding.
4. Rollback sederhana ke config sebelumnya.

Agar admin tidak merusak tampilan produksi hanya karena mencoba warna/logo.

---

# 6. Target Output Phase 18

Setelah Phase 18 selesai, aplikasi harus punya:

1. Menu **White-Label**.
2. Menu **Brand Profile**.
3. Menu **Theme Builder**.
4. Menu **Domain Mapping**.
5. Menu **PWA Settings**.
6. Menu **White-Label Preview**.
7. Login page tenant-aware.
8. Public landing page tenant-aware.
9. PWA manifest tenant-aware.
10. Report/PDF branding tenant-aware.
11. Tabel:
    - `school_brand_profiles`
    - `school_theme_settings`
    - `school_domain_mappings`
    - `school_pwa_settings`
    - `white_label_publications`
12. Model:
    - `SchoolBrandProfile`
    - `SchoolThemeSetting`
    - `SchoolDomainMapping`
    - `SchoolPwaSetting`
    - `WhiteLabelPublication`
13. Controller:
    - `WhiteLabelDashboardController`
    - `SchoolBrandProfileController`
    - `SchoolThemeSettingController`
    - `SchoolDomainMappingController`
    - `SchoolPwaSettingController`
    - `WhiteLabelPreviewController`
    - `TenantPublicLandingController`
    - `TenantPwaManifestController`
14. Request:
    - `UpdateSchoolBrandProfileRequest`
    - `UpdateSchoolThemeSettingRequest`
    - `StoreSchoolDomainMappingRequest`
    - `UpdateSchoolDomainMappingRequest`
    - `UpdateSchoolPwaSettingRequest`
    - `PublishWhiteLabelRequest`
15. Service:
    - `WhiteLabelAccessService`
    - `TenantDomainResolver`
    - `SchoolBrandingService`
    - `SchoolThemeService`
    - `SchoolDomainVerificationService`
    - `SchoolPwaManifestService`
    - `WhiteLabelPublicationService`
16. Middleware:
    - `ResolveTenantFromDomain`
17. Seeder:
    - `DefaultWhiteLabelSeeder`
18. View:
    - white-label dashboard
    - brand profile edit/show
    - theme edit/preview
    - domain mapping index/create/edit/show
    - pwa settings edit/show
    - preview page
    - public tenant landing page
19. Dokumentasi Phase 18.
20. Update `docs/project-progress.md`.

---

# 7. Role Access Phase 18

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

| Role | Brand Profile | Theme Builder | Domain Mapping | PWA Settings | Preview | Publish |
|---|---|---|---|---|---|---|
| Super Admin | Semua | Semua | Semua | Semua | Semua | Ya |
| Admin Sekolah | Tenant sendiri | Tenant sendiri | Request/terbatas | Tenant sendiri | Tenant sendiri | Ya jika diizinkan |
| Kepala Sekolah | Read-only | Read-only | Read-only | Read-only | Tenant sendiri | Tidak default |
| Teacher/Guru | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Boarding Supervisor | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Parent | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Student | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |

Aturan keras:

1. Admin sekolah hanya boleh mengubah branding tenant sendiri.
2. Admin sekolah tidak boleh mengubah domain tenant lain.
3. Domain mapping aktif harus disetujui/verifikasi Super Admin jika policy mengharuskan.
4. Parent/student tidak boleh mengakses menu white-label.
5. Teacher/guru tidak boleh mengakses menu white-label.
6. Kepala sekolah default read-only.
7. Super Admin boleh melihat semua tenant.
8. Semua upload asset harus divalidasi.
9. Semua file asset harus tenant-scoped.
10. Jangan membuat akses finance, payment, cashless, atau POS di Phase 18.

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
13. Phase 11 selesai.
14. Phase 12 selesai.
15. Phase 13 selesai.
16. Phase 14 selesai.
17. Phase 15 selesai.
18. Phase 16 selesai.
19. Phase 17 selesai dan UAT aman.
20. Tabel berikut sudah ada:
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
21. Tenant foundation dari Phase 17 sudah ada dan berjalan.
22. Working tree bersih atau semua perubahan diketahui.

Jika Phase 17 belum aman, hentikan Phase 18.

---

# 9. Buat Branch Git Phase 18

Jalankan:

```powershell
git checkout -b phase-18-white-label-school-app-builder
```

Jika branch sudah ada:

```powershell
git checkout phase-18-white-label-school-app-builder
```

---

# 10. Buat Model, Migration, Seeder, Controller, Request, Middleware

Jalankan:

```powershell
php artisan make:model SchoolBrandProfile -m
php artisan make:model SchoolThemeSetting -m
php artisan make:model SchoolDomainMapping -m
php artisan make:model SchoolPwaSetting -m
php artisan make:model WhiteLabelPublication -m

php artisan make:seeder DefaultWhiteLabelSeeder

php artisan make:controller WhiteLabel/WhiteLabelDashboardController
php artisan make:controller WhiteLabel/SchoolBrandProfileController
php artisan make:controller WhiteLabel/SchoolThemeSettingController
php artisan make:controller WhiteLabel/SchoolDomainMappingController
php artisan make:controller WhiteLabel/SchoolPwaSettingController
php artisan make:controller WhiteLabel/WhiteLabelPreviewController
php artisan make:controller Public/TenantPublicLandingController
php artisan make:controller Public/TenantPwaManifestController

php artisan make:request WhiteLabel/UpdateSchoolBrandProfileRequest
php artisan make:request WhiteLabel/UpdateSchoolThemeSettingRequest
php artisan make:request WhiteLabel/StoreSchoolDomainMappingRequest
php artisan make:request WhiteLabel/UpdateSchoolDomainMappingRequest
php artisan make:request WhiteLabel/UpdateSchoolPwaSettingRequest
php artisan make:request WhiteLabel/PublishWhiteLabelRequest

php artisan make:middleware ResolveTenantFromDomain
```

Buat folder service:

```powershell
mkdir app\Services\WhiteLabel
```

Buat file service:

```powershell
New-Item app\Services\WhiteLabel\WhiteLabelAccessService.php
New-Item app\Services\WhiteLabel\TenantDomainResolver.php
New-Item app\Services\WhiteLabel\SchoolBrandingService.php
New-Item app\Services\WhiteLabel\SchoolThemeService.php
New-Item app\Services\WhiteLabel\SchoolDomainVerificationService.php
New-Item app\Services\WhiteLabel\SchoolPwaManifestService.php
New-Item app\Services\WhiteLabel\WhiteLabelPublicationService.php
```

Buat folder view:

```powershell
mkdir resources\views\white-label
mkdir resources\views\white-label\brand
mkdir resources\views\white-label\themes
mkdir resources\views\white-label\domains
mkdir resources\views\white-label\pwa
mkdir resources\views\white-label\preview
mkdir resources\views\public
mkdir resources\views\public\tenant
```

---

# 11. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── WhiteLabel/
│   │   │   ├── WhiteLabelDashboardController.php
│   │   │   ├── SchoolBrandProfileController.php
│   │   │   ├── SchoolThemeSettingController.php
│   │   │   ├── SchoolDomainMappingController.php
│   │   │   ├── SchoolPwaSettingController.php
│   │   │   └── WhiteLabelPreviewController.php
│   │   └── Public/
│   │       ├── TenantPublicLandingController.php
│   │       └── TenantPwaManifestController.php
│   ├── Middleware/
│   │   └── ResolveTenantFromDomain.php
│   └── Requests/
│       └── WhiteLabel/
│           ├── UpdateSchoolBrandProfileRequest.php
│           ├── UpdateSchoolThemeSettingRequest.php
│           ├── StoreSchoolDomainMappingRequest.php
│           ├── UpdateSchoolDomainMappingRequest.php
│           ├── UpdateSchoolPwaSettingRequest.php
│           └── PublishWhiteLabelRequest.php
├── Models/
│   ├── SchoolBrandProfile.php
│   ├── SchoolThemeSetting.php
│   ├── SchoolDomainMapping.php
│   ├── SchoolPwaSetting.php
│   └── WhiteLabelPublication.php
└── Services/
    └── WhiteLabel/
        ├── WhiteLabelAccessService.php
        ├── TenantDomainResolver.php
        ├── SchoolBrandingService.php
        ├── SchoolThemeService.php
        ├── SchoolDomainVerificationService.php
        ├── SchoolPwaManifestService.php
        └── WhiteLabelPublicationService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_school_brand_profiles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_school_theme_settings_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_school_domain_mappings_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_school_pwa_settings_table.php
│   └── xxxx_xx_xx_xxxxxx_create_white_label_publications_table.php
└── seeders/
    └── DefaultWhiteLabelSeeder.php

resources/
└── views/
    ├── white-label/
    │   ├── dashboard.blade.php
    │   ├── brand/
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── themes/
    │   │   ├── edit.blade.php
    │   │   └── preview.blade.php
    │   ├── domains/
    │   │   ├── index.blade.php
    │   │   ├── create.blade.php
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   ├── pwa/
    │   │   ├── edit.blade.php
    │   │   └── show.blade.php
    │   └── preview/
    │       └── show.blade.php
    └── public/
        └── tenant/
            └── landing.blade.php

routes/
├── web.php
└── tenant.php

docs/
├── phase-18-execution.md
└── phase-18-white-label-school-app-builder.md
```

Jika `routes/tenant.php` belum ada, buat file baru dan load dari bootstrap sesuai struktur Laravel 12 yang dipakai project.

Jika project tidak memakai file route terpisah, boleh simpan route tenant di `routes/web.php`, tetapi beri komentar jelas.

---

# 12. Migration Detail

## 12.1 `school_brand_profiles`

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->string('display_name');
$table->string('short_name')->nullable();
$table->string('tagline')->nullable();
$table->string('logo_path')->nullable();
$table->string('favicon_path')->nullable();
$table->string('login_background_path')->nullable();
$table->string('public_contact_email')->nullable();
$table->string('public_contact_phone')->nullable();
$table->text('public_address')->nullable();
$table->string('public_website_url')->nullable();
$table->boolean('is_active')->default(true);
$table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();

$table->unique('school_id');
$table->index('is_active');
```

## 12.2 `school_theme_settings`

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->string('theme_name')->default('Default');
$table->string('primary_color', 20)->default('#2563eb');
$table->string('secondary_color', 20)->default('#0f172a');
$table->string('accent_color', 20)->default('#22c55e');
$table->string('text_color', 20)->default('#111827');
$table->string('background_color', 20)->default('#f8fafc');
$table->string('sidebar_style')->default('default');
$table->string('header_style')->default('default');
$table->string('login_layout')->default('centered');
$table->string('card_radius')->default('md');
$table->string('button_radius')->default('md');
$table->boolean('is_active')->default(true);
$table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();

$table->unique('school_id');
$table->index('is_active');
```

## 12.3 `school_domain_mappings`

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->string('domain')->unique();
$table->string('type')->default('subdomain'); // subdomain, custom_domain
$table->string('status')->default('pending');
$table->string('verification_token')->nullable();
$table->dateTime('verified_at')->nullable();
$table->dateTime('activated_at')->nullable();
$table->dateTime('disabled_at')->nullable();
$table->text('notes')->nullable();
$table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
$table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();
$table->softDeletes();

$table->index(['school_id', 'status']);
$table->index(['type', 'status']);
```

## 12.4 `school_pwa_settings`

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->string('app_name');
$table->string('short_name')->nullable();
$table->string('theme_color', 20)->default('#2563eb');
$table->string('background_color', 20)->default('#ffffff');
$table->string('icon_192_path')->nullable();
$table->string('icon_512_path')->nullable();
$table->string('start_url')->default('/');
$table->string('display_mode')->default('standalone');
$table->boolean('is_enabled')->default(true);
$table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
$table->timestamps();

$table->unique('school_id');
$table->index('is_enabled');
```

## 12.5 `white_label_publications`

```php
$table->id();
$table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
$table->string('status')->default('draft'); // draft, published, rolled_back
$table->json('brand_snapshot')->nullable();
$table->json('theme_snapshot')->nullable();
$table->json('pwa_snapshot')->nullable();
$table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
$table->dateTime('published_at')->nullable();
$table->text('notes')->nullable();
$table->timestamps();

$table->index(['school_id', 'status']);
$table->index('published_at');
```

---

# 13. Model Relationship Minimal

## 13.1 Update Model `School`

Tambahkan jika belum ada:

```php
public function brandProfile()
{
    return $this->hasOne(SchoolBrandProfile::class);
}

public function themeSetting()
{
    return $this->hasOne(SchoolThemeSetting::class);
}

public function domainMappings()
{
    return $this->hasMany(SchoolDomainMapping::class);
}

public function pwaSetting()
{
    return $this->hasOne(SchoolPwaSetting::class);
}

public function whiteLabelPublications()
{
    return $this->hasMany(WhiteLabelPublication::class);
}
```

## 13.2 `SchoolBrandProfile`

```php
protected $fillable = [
    'school_id',
    'display_name',
    'short_name',
    'tagline',
    'logo_path',
    'favicon_path',
    'login_background_path',
    'public_contact_email',
    'public_contact_phone',
    'public_address',
    'public_website_url',
    'is_active',
    'updated_by',
];

protected $casts = [
    'is_active' => 'boolean',
];

public function school()
{
    return $this->belongsTo(School::class);
}
```

## 13.3 `SchoolThemeSetting`

```php
protected $fillable = [
    'school_id',
    'theme_name',
    'primary_color',
    'secondary_color',
    'accent_color',
    'text_color',
    'background_color',
    'sidebar_style',
    'header_style',
    'login_layout',
    'card_radius',
    'button_radius',
    'is_active',
    'updated_by',
];

protected $casts = [
    'is_active' => 'boolean',
];

public function school()
{
    return $this->belongsTo(School::class);
}
```

## 13.4 `SchoolDomainMapping`

```php
protected $fillable = [
    'school_id',
    'domain',
    'type',
    'status',
    'verification_token',
    'verified_at',
    'activated_at',
    'disabled_at',
    'notes',
    'created_by',
    'verified_by',
];

protected $casts = [
    'verified_at' => 'datetime',
    'activated_at' => 'datetime',
    'disabled_at' => 'datetime',
];

public function school()
{
    return $this->belongsTo(School::class);
}
```

## 13.5 `SchoolPwaSetting`

```php
protected $fillable = [
    'school_id',
    'app_name',
    'short_name',
    'theme_color',
    'background_color',
    'icon_192_path',
    'icon_512_path',
    'start_url',
    'display_mode',
    'is_enabled',
    'updated_by',
];

protected $casts = [
    'is_enabled' => 'boolean',
];

public function school()
{
    return $this->belongsTo(School::class);
}
```

## 13.6 `WhiteLabelPublication`

```php
protected $fillable = [
    'school_id',
    'status',
    'brand_snapshot',
    'theme_snapshot',
    'pwa_snapshot',
    'published_by',
    'published_at',
    'notes',
];

protected $casts = [
    'brand_snapshot' => 'array',
    'theme_snapshot' => 'array',
    'pwa_snapshot' => 'array',
    'published_at' => 'datetime',
];

public function school()
{
    return $this->belongsTo(School::class);
}
```

---

# 14. Request Validation Rules

## 14.1 `UpdateSchoolBrandProfileRequest`

Rules minimal:

```php
return [
    'display_name' => ['required', 'string', 'max:150'],
    'short_name' => ['nullable', 'string', 'max:50'],
    'tagline' => ['nullable', 'string', 'max:255'],
    'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:2048'],
    'favicon' => ['nullable', 'image', 'mimes:png,ico,svg', 'max:512'],
    'login_background' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:4096'],
    'public_contact_email' => ['nullable', 'email', 'max:150'],
    'public_contact_phone' => ['nullable', 'string', 'max:50'],
    'public_address' => ['nullable', 'string', 'max:1000'],
    'public_website_url' => ['nullable', 'url', 'max:255'],
];
```

## 14.2 `UpdateSchoolThemeSettingRequest`

Rules minimal:

```php
return [
    'theme_name' => ['required', 'string', 'max:100'],
    'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    'secondary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    'text_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    'sidebar_style' => ['required', 'in:default,compact,expanded'],
    'header_style' => ['required', 'in:default,minimal,branded'],
    'login_layout' => ['required', 'in:centered,split,card'],
    'card_radius' => ['required', 'in:none,sm,md,lg,xl'],
    'button_radius' => ['required', 'in:none,sm,md,lg,xl'],
];
```

## 14.3 `StoreSchoolDomainMappingRequest`

Rules minimal:

```php
return [
    'domain' => ['required', 'string', 'max:255', 'unique:school_domain_mappings,domain'],
    'type' => ['required', 'in:subdomain,custom_domain'],
    'notes' => ['nullable', 'string', 'max:1000'],
];
```

Validasi tambahan wajib di service:

1. Domain harus lowercase.
2. Domain tidak boleh mengandung protocol `http://` atau `https://`.
3. Domain tidak boleh mengandung path.
4. Domain tidak boleh memakai domain milik tenant lain.
5. Domain root utama HafizPlus hanya boleh dipakai oleh Super Admin.

## 14.4 `UpdateSchoolPwaSettingRequest`

Rules minimal:

```php
return [
    'app_name' => ['required', 'string', 'max:150'],
    'short_name' => ['nullable', 'string', 'max:50'],
    'theme_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    'background_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    'icon_192' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:1024'],
    'icon_512' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
    'start_url' => ['required', 'string', 'max:255'],
    'display_mode' => ['required', 'in:standalone,fullscreen,minimal-ui,browser'],
    'is_enabled' => ['nullable', 'boolean'],
];
```

---

# 15. Service Rules

## 15.1 `WhiteLabelAccessService`

Service ini wajib menjaga:

1. Super Admin bisa mengakses semua tenant.
2. Admin sekolah hanya bisa mengakses tenant sendiri.
3. Kepala sekolah read-only tenant sendiri.
4. Parent/student/teacher tidak bisa membuka white-label admin.
5. Request tanpa tenant context harus ditolak kecuali Super Admin.
6. Domain mapping tenant lain tidak boleh terlihat.

## 15.2 `TenantDomainResolver`

Service ini wajib:

1. Membaca host dari request.
2. Normalisasi host ke lowercase.
3. Menghapus port dari host lokal.
4. Cari domain mapping status `active` atau `verified` sesuai policy.
5. Resolve ke school/tenant.
6. Jika tidak ditemukan, fallback ke tenant dari session/subdomain default jika valid.
7. Jika tetap tidak valid, jangan memilih tenant random.

## 15.3 `SchoolBrandingService`

Service ini wajib:

1. Mengambil brand profile aktif.
2. Membuat default brand jika tenant belum punya config.
3. Menyimpan file logo/favicon/background secara tenant-scoped.
4. Menghapus/mengganti asset lama hanya jika asset baru valid.
5. Mengembalikan asset URL aman.

Storage path yang disarankan:

```text
white-label/{school_id}/brand/logo.ext
white-label/{school_id}/brand/favicon.ext
white-label/{school_id}/brand/login-background.ext
white-label/{school_id}/pwa/icon-192.ext
white-label/{school_id}/pwa/icon-512.ext
```

## 15.4 `SchoolThemeService`

Service ini wajib:

1. Validasi color token.
2. Mengambil theme aktif tenant.
3. Membuat CSS variables aman.
4. Tidak menerima CSS bebas.
5. Menghasilkan preview theme.

CSS variable yang boleh dibuat:

```css
--school-primary
--school-secondary
--school-accent
--school-text
--school-background
```

## 15.5 `SchoolDomainVerificationService`

Service ini wajib:

1. Generate verification token.
2. Menyimpan token domain.
3. Menandai domain verified secara manual.
4. Menandai domain active.
5. Menonaktifkan domain.
6. Tidak mengaktifkan dua tenant untuk domain yang sama.

Phase 18 tidak wajib melakukan DNS lookup otomatis.

Jika DNS lookup ingin dibuat, jadikan optional dan jangan menjadi blocker.

## 15.6 `SchoolPwaManifestService`

Service ini wajib:

1. Membuat manifest JSON tenant-aware.
2. Menggunakan nama sekolah.
3. Menggunakan theme color tenant.
4. Menggunakan icon tenant jika ada.
5. Menggunakan fallback icon jika tidak ada.
6. Tidak cache manifest lintas tenant tanpa key tenant.

## 15.7 `WhiteLabelPublicationService`

Service ini wajib:

1. Membuat snapshot brand/theme/PWA.
2. Publish konfigurasi aktif.
3. Menyimpan published_by.
4. Menyimpan published_at.
5. Menyediakan rollback sederhana ke snapshot sebelumnya.
6. Tidak publish jika data wajib kosong.

---

# 16. Middleware `ResolveTenantFromDomain`

Middleware ini harus:

1. Ambil host dari request.
2. Resolve host ke `SchoolDomainMapping`.
3. Jika ditemukan, set tenant context ke school tersebut.
4. Jika tidak ditemukan, lanjutkan fallback tenant resolver dari Phase 17.
5. Jangan override tenant context Super Admin secara sembarangan.
6. Jangan memilih school pertama sebagai fallback.
7. Simpan tenant hasil resolve ke container/service context yang sudah dibuat Phase 17.

Pseudo logic:

```php
$host = strtolower($request->getHost());
$school = $tenantDomainResolver->resolve($host);

if ($school) {
    $tenantContext->setSchool($school);
}

return $next($request);
```

---

# 17. Route Phase 18

Tambahkan route dalam middleware `auth` untuk admin white-label.

Nama route wajib:

```text
white-label.dashboard
white-label.brand.show
white-label.brand.edit
white-label.brand.update
white-label.themes.edit
white-label.themes.update
white-label.themes.preview
white-label.domains.index
white-label.domains.create
white-label.domains.store
white-label.domains.show
white-label.domains.edit
white-label.domains.update
white-label.domains.verify
white-label.domains.activate
white-label.domains.disable
white-label.pwa.show
white-label.pwa.edit
white-label.pwa.update
white-label.preview.show
white-label.publish
white-label.rollback
```

Tambahkan route public tenant-aware:

```text
tenant.public.landing
tenant.pwa.manifest
```

Contoh URL:

```text
/
/manifest.json
/white-label
/white-label/brand
/white-label/theme
/white-label/domains
/white-label/pwa
/white-label/preview
```

---

# 18. Update Login Page Tenant-Aware

Buka login view:

```text
resources/views/auth/login.blade.php
```

Update agar menggunakan branding tenant:

1. Logo sekolah jika tenant ditemukan.
2. Display name sekolah.
3. Tagline sekolah.
4. Theme color tenant.
5. Login background tenant jika ada.
6. Fallback ke HafizPlus jika tenant tidak ditemukan.

Aturan:

1. Jangan crash jika tenant belum punya brand profile.
2. Jangan hardcode sekolah tertentu.
3. Jangan tampilkan data tenant lain.

---

# 19. Update Layout Utama

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan:

1. Dynamic page title dari tenant brand.
2. Dynamic logo dari tenant brand.
3. CSS variables dari tenant theme.
4. Favicon tenant.
5. Link manifest tenant-aware.
6. Menu White-Label hanya untuk Super Admin/Admin Sekolah yang berhak.

Contoh:

```html
<link rel="manifest" href="{{ route('tenant.pwa.manifest') }}">
```

Pastikan route ini tenant-aware.

---

# 20. Update PDF/Export Branding

Jika Phase 9 PDF export sudah memakai view PDF, update header/footer agar memakai branding tenant:

1. Logo sekolah.
2. Nama sekolah.
3. Alamat sekolah.
4. Kontak sekolah.
5. Warna header tenant.

Jangan mengubah data report.

Hanya ubah tampilan branding.

---

# 21. Update Notification Branding Metadata

Jika notification center sudah ada, tambahkan helper/service agar notifikasi bisa membawa metadata brand:

1. Sender display name tenant.
2. Logo tenant jika nanti dibutuhkan.
3. Public school name.

Phase 18 tidak membuat email/WA/push otomatis.

---

# 22. Update Module Registry

Jika tabel `system_modules` sudah ada, tambahkan module:

```text
module_key: white_label
name: White Label
label: White-Label
route_name: white-label.dashboard
sort_order: 95
is_enabled: true
is_core: false
```

Jalankan seeder setelah update.

---

# 23. Seeder `DefaultWhiteLabelSeeder`

Seeder harus:

1. Loop semua sekolah.
2. Buat brand profile default jika belum ada.
3. Buat theme setting default jika belum ada.
4. Buat PWA setting default jika belum ada.
5. Tidak overwrite config existing.

Contoh default:

```text
display_name = school.name
short_name = school.name pendek jika tersedia
primary_color = #2563eb
secondary_color = #0f172a
accent_color = #22c55e
theme_color = #2563eb
background_color = #ffffff
```

---

# 24. Update `docs/project-progress.md`

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
| 18 | White-Label School App Builder | Done |
| 19 | Cashless Kantin / Merchant POS | Pending |
```

---

# 25. Validasi Akhir

Jalankan:

```powershell
php artisan migrate
php artisan db:seed --class=DefaultWhiteLabelSeeder
php artisan route:list
php artisan app:system-health-check
npm run build
```

Jalankan server:

```powershell
php artisan serve
```

Buka URL berikut:

```text
http://127.0.0.1:8000/white-label
http://127.0.0.1:8000/white-label/brand
http://127.0.0.1:8000/white-label/theme
http://127.0.0.1:8000/white-label/domains
http://127.0.0.1:8000/white-label/pwa
http://127.0.0.1:8000/white-label/preview
http://127.0.0.1:8000/manifest.json
```

---

# 26. Test Manual Wajib

## 26.1 Test Brand Profile

1. Login sebagai Super Admin.
2. Pilih tenant/sekolah.
3. Buka White-Label → Brand Profile.
4. Update display name.
5. Upload logo.
6. Upload favicon.
7. Upload login background.
8. Simpan.
9. Logout.
10. Buka login tenant.

Target:

1. Logo tampil.
2. Display name tampil.
3. Background tampil.
4. Favicon tidak error.
5. Tenant lain tidak berubah.

---

## 26.2 Test Theme Builder

1. Login sebagai Admin Sekolah.
2. Buka White-Label → Theme Builder.
3. Ubah primary color.
4. Ubah accent color.
5. Preview.
6. Simpan.
7. Reload dashboard.

Target:

1. CSS variable berubah.
2. Warna tenant berubah.
3. Tenant lain tidak berubah.
4. Tidak ada CSS bebas yang tersimpan.

---

## 26.3 Test Domain Mapping

1. Super Admin buat domain mapping.
2. Status awal `pending`.
3. Generate verification token.
4. Mark verified manual.
5. Activate domain.
6. Akses domain simulasi/local host.

Target:

1. Domain resolve ke tenant benar.
2. Domain tidak resolve ke tenant lain.
3. Domain duplicate ditolak.
4. Domain disabled tidak aktif.

---

## 26.4 Test PWA Manifest

1. Set app name sekolah.
2. Set short name.
3. Upload icon 192.
4. Upload icon 512.
5. Buka `/manifest.json`.

Target JSON:

1. Name sesuai tenant.
2. Short name sesuai tenant.
3. Theme color sesuai tenant.
4. Icon tenant muncul.
5. Tenant lain tidak terpengaruh.

---

## 26.5 Test Login Tenant-Aware

1. Akses tenant A.
2. Login page menampilkan brand tenant A.
3. Akses tenant B.
4. Login page menampilkan brand tenant B.
5. Akses domain tidak dikenal.

Target:

1. Tenant A benar.
2. Tenant B benar.
3. Domain tidak dikenal tidak membuka tenant random.
4. Fallback aman tampil.

---

## 26.6 Test PDF/Report Branding

1. Login tenant A.
2. Export report PDF.
3. Cek logo/nama sekolah.
4. Login tenant B.
5. Export report PDF.

Target:

1. PDF tenant A menampilkan brand tenant A.
2. PDF tenant B menampilkan brand tenant B.
3. Data report tetap tenant-scoped.

---

## 26.7 Test Access Control

1. Teacher buka `/white-label`.
2. Parent buka `/white-label`.
3. Student buka `/white-label`.
4. Admin sekolah A coba edit brand sekolah B.

Target:

1. Teacher mendapat 403.
2. Parent mendapat 403.
3. Student mendapat 403.
4. Admin sekolah A tidak bisa edit sekolah B.

---

# 27. Definition of Done

Phase 18 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. Menu White-Label tampil untuk role yang berhak.
4. Brand profile bisa diedit per tenant.
5. Logo tenant tampil di layout.
6. Favicon tenant tampil.
7. Login page tenant-aware.
8. Theme token bisa diubah per tenant.
9. Theme tidak memakai CSS bebas.
10. Domain mapping bisa dibuat.
11. Domain verification manual berjalan.
12. Domain active resolve ke tenant benar.
13. Domain tidak dikenal tidak membuka tenant random.
14. PWA manifest tenant-aware berjalan.
15. PDF/report memakai branding tenant.
16. Preview branding berjalan.
17. Publish white-label berjalan.
18. Rollback sederhana berjalan.
19. Tenant lain tidak terdampak perubahan brand tenant tertentu.
20. Parent/student/teacher tidak bisa mengakses white-label admin.
21. Admin sekolah hanya mengelola tenant sendiri.
22. Super Admin bisa mengelola semua tenant.
23. `npm run build` berhasil.
24. `php artisan app:system-health-check` berhasil.
25. Dokumentasi dibuat.
26. `docs/project-progress.md` terupdate.

---

# 28. Commit Phase 18

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add white-label school app builder"
```

Jika remote tersedia:

```powershell
git push origin phase-18-white-label-school-app-builder
```

---

# 29. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 18 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- White-Label Dashboard
- School Brand Profile
- Theme Builder
- Domain Mapping
- Domain Verification Manual
- PWA Settings
- Tenant-aware Login Page
- Tenant-aware Public Landing Page
- Tenant-aware PWA Manifest
- Tenant-aware PDF/Report Branding
- White-label Preview
- White-label Publish/Rollback
- Role-based access
- Tenant-based access

Tabel dibuat:
- school_brand_profiles
- school_theme_settings
- school_domain_mappings
- school_pwa_settings
- white_label_publications

Route dibuat:
- white-label.dashboard
- white-label.brand.*
- white-label.themes.*
- white-label.domains.*
- white-label.pwa.*
- white-label.preview.show
- white-label.publish
- white-label.rollback
- tenant.public.landing
- tenant.pwa.manifest

Belum dibuat:
- Native Android generator
- Native iOS generator
- Play Store deployment automation
- App Store deployment automation
- Cashless Kantin
- Merchant POS
- Wallet
- Payment Gateway
- QRIS otomatis
- Virtual Account
- Auto DNS provider integration
- Billing SaaS production

Status:
- Siap lanjut Phase 19 hanya setelah UAT Phase 18 aman.
```

---

# 30. Larangan Setelah Phase 18

Agent harus berhenti setelah Phase 18 selesai.

Jangan lanjut membuat:

1. Cashless Kantin.
2. Merchant POS.
3. Wallet.
4. Payment Gateway.
5. QRIS otomatis.
6. Virtual Account.
7. Refund otomatis.
8. Rekonsiliasi bank otomatis.
9. Native mobile app.
10. App Store deployment.
11. Play Store deployment.
12. Billing SaaS production.

Semua itu masuk phase berikutnya.

---

# 31. Keputusan Akhir

Phase 18 hanya valid jika white-label berjalan di atas multi-tenant yang aman.

Prioritas setelah Phase 18:

1. UAT white-label.
2. Cek tenant branding.
3. Cek domain mapping.
4. Cek PWA manifest.
5. Cek login tenant-aware.
6. Cek PDF branding.
7. Cek akses admin sekolah.
8. Cek akses parent/student/teacher.
9. Backup database.
10. Baru pertimbangkan Phase 19 — Cashless Kantin / Merchant POS.

Jangan masuk cashless sebelum white-label dan multi-tenant benar-benar aman. Cashless membawa risiko uang, saldo, fraud, refund, audit, dan transaksi gagal. Jika tenant isolation atau domain resolver masih lemah, cashless akan memperbesar risiko bisnis dan hukum.
