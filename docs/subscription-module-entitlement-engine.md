# Subscription Plan and Module Entitlement Engine

Engine ini menyediakan pengelolaan monetisasi SaaS untuk HafizPlus School Platform. Mengaitkan Sekolah, Paket Langganan, Modul Sistem, Override Modul, dan Batasan Penggunaan secara dinamis.

---

## 1. Tujuan Fitur

1. **Paket Berlangganan (SaaS Monetization)**: Menyediakan paket berlangganan fleksibel (Trial, Basic, Pro, Enterprise) yang menentukan hak akses fitur (modul) dan batasan data sekolah.
2. **Pengaturan Modul Dinamis (Entitlement Engine)**: Melindungi rute dan menyaring item navigasi/sidebar agar hanya modul yang aktif yang dapat diakses oleh user.
3. **Override Hak Akses Modul**: Memungkinkan Super Admin untuk mengesampingkan (override) status modul untuk sekolah tertentu (misal: mengaktifkan modul uji coba atau menonaktifkan modul sementara).
4. **Batasan Penggunaan (Limit Enforcement)**: Membatasi resource seperti jumlah santri (`max_students`) secara dinamis sesuai plan sekolah.

---

## 2. Struktur Tabel

Engine ini menggunakan 5 tabel utama yang saling berelasi:

### A. `subscription_plans`
Menyimpan data paket berlangganan teoretis dan batasan penggunaan global.
* `id` (PK)
* `code` (Unique String) - Contoh: `trial`, `basic`, `pro`, `enterprise`
* `name` (String)
* `monthly_price` (Unsigned BigInt)
* `yearly_price` (Unsigned BigInt)
* `description` (Text)
* `limits` (JSON) - Contoh: `{"max_students": 300, "max_teachers": 30, ...}`
* `is_active` (Boolean)
* `sort_order` (Unsigned SmallInt)

### B. `plan_modules`
Tabel pivot yang menghubungkan paket berlangganan dengan modul sistem.
* `id` (PK)
* `subscription_plan_id` (FK to `subscription_plans`, Cascade on Delete)
* `system_module_id` (FK to `system_modules`, Cascade on Delete)
* `is_included` (Boolean)
* `limits` (JSON)
* `features` (JSON)

### C. `school_subscriptions`
Mencatat status langganan riil untuk setiap sekolah/tenant.
* `id` (PK)
* `school_id` (FK to `schools`, Cascade on Delete)
* `subscription_plan_id` (FK to `subscription_plans`, Restrict on Delete)
* `status` (String) - Nilai valid: `trialing`, `active`, `past_due`, `suspended`, `canceled`, `expired`
* `starts_at` (Date)
* `trial_ends_at` (Date)
* `current_period_starts_at` (Date)
* `current_period_ends_at` (Date)
* `canceled_at` (Date)
* `metadata` (JSON)

### D. `school_module_overrides`
Menyimpan override manual per modul per sekolah (dapat berupa paksa aktif atau paksa nonaktif).
* `id` (PK)
* `school_id` (FK to `schools`, Cascade on Delete)
* `system_module_id` (FK to `system_modules`, Cascade on Delete)
* `is_enabled` (Boolean)
* `reason` (String) - Contoh: `manual_enable`, `manual_disable`, `trial_addon`
* `expires_at` (Timestamp)
* `created_by` (FK to `users`, Null on Delete)

### E. `subscription_usages`
Menyimpan data penggunaan resource sekolah saat ini.
* `id` (PK)
* `school_id` (FK to `schools`, Cascade on Delete)
* `usage_key` (String) - Contoh: `students_count`
* `used` (Unsigned BigInt)
* `limit` (Unsigned BigInt)
* `period_starts_at` (Date)
* `period_ends_at` (Date)

---

## 3. Relasi Data (Database Relationships)

* **`School`**:
  * `subscriptions()` -> Hubungan `HasMany` ke `SchoolSubscription`.
  * `activeSubscription()` -> Hubungan `HasOne` ke `SchoolSubscription` (mengambil data subscription dengan status `trialing` atau `active` yang paling baru).
  * `moduleOverrides()` -> Hubungan `HasMany` ke `SchoolModuleOverride`.

* **`SubscriptionPlan`**:
  * `modules()` -> Hubungan `BelongsToMany` ke `SystemModule` melalui `plan_modules`.
  * `subscriptions()` -> Hubungan `HasMany` ke `SchoolSubscription`.

* **`SystemModule`**:
  * `plans()` -> Hubungan `BelongsToMany` ke `SubscriptionPlan` melalui `plan_modules`.

---

## 4. Daftar Plan Default & Limit

Berikut adalah konfigurasi bawaan dari seeder:

| Kode Plan | Nama Plan | Harga Bulanan | Limit Santri | Limit Guru | Modul Termasuk |
|---|---|---|---|---|---|
| `trial` | Trial | Rp 0 | 50 | 5 | `tahfizh`, `reports`, `notifications` |
| `basic` | Basic | Rp 299.000 | 300 | 30 | `tahfizh`, `reports`, `notifications`, `exports` |
| `pro` | Pro | Rp 699.000 | 1000 | 100 | Modul Basic + `mutabaah`, `attendance`, `tahsin`, `finance`, `schoolos` |
| `enterprise`| Enterprise| Custom | 999.999 | 999.999 | Semua modul aktif di sistem |

---

## 5. Daftar Module Key

Modul-modul global berikut terdaftar di sistem:
* `tahfizh` (Tahfizh)
* `reports` (Reports)
* `notifications` (Notifications)
* `exports` (Export PDF/Excel)
* `mutabaah` (Mutabaah Yaumiyah)
* `attendance` (QR Attendance)
* `tahsin` (Tahsin)
* `finance` (Student Finance Ledger)
* `schoolos` (SchoolOS Mini)
* `boarding` (Boarding School)
* `white_label` (White Label)
* `cashless_pos` (Cashless POS)
* `lms_lite` (LMS Lite)
* `ai_assistant` (AI Assistant)

---

## 6. Alur Operasional Billing

Akses administrasi billing dilindungi oleh middleware role (`super_admin`, `admin`, `admin_sekolah`) pada prefix `/billing`.

### A. Assign Subscription ke Sekolah
1. Super Admin mengakses menu **Billing & Paket > Langganan Sekolah** (`/billing/school-subscriptions`).
2. Klik **Tambah Langganan**, pilih Sekolah dan Plan, lalu atur status (`active` / `trialing`) dan masa berlaku.

### B. Mengatur Plan Modules
1. Masuk ke **Billing & Paket > Paket Langganan** (`/billing/plans`).
2. Pada baris paket yang diinginkan, pilih tombol **Edit Modul** (rute `billing.plans.modules.edit`).
3. Beri tanda centang pada modul-modul yang ingin dimasukkan ke dalam paket, lalu simpan.

### C. Membuat Module Override
1. Buka **Billing & Paket > Override Modul** (`/billing/module-overrides`).
2. Klik **Tambah Override**, pilih Sekolah dan Modul, atur status (`Enabled` atau `Disabled`), masukkan alasan (reason), serta tanggal kadaluarsa override (jika ada).

---

## 7. Cara Kerja Proteksi & Filter Navigasi

### A. Middleware Proteksi Rute
* **`EnsureSubscriptionIsActive`** (`subscription.active`): Memeriksa apakah sekolah tempat user bernaung memiliki subscription dengan status `active` atau `trialing`. Jika tidak aktif, akses akan ditolak (abort `403` untuk non-admin, redirect ke halaman locked untuk admin). Bypass otomatis berlaku untuk `super_admin`.
* **`EnsureModuleIsEnabled:module_key`** (`module:{module_key}`): Memeriksa hak modul sekolah bersangkutan. Jika modul nonaktif, user dialihkan ke halaman Locked. Bypass berlaku untuk `super_admin`.

### B. Penyaringan Navigasi Sidebar
Sidebar menu disaring menggunakan callback dinamis di [navbar.blade.php](file:///c:/xampp/htdocs/hafizplus-school-platform/resources/views/components/navbar.blade.php) yang memanggil `ModuleAccessService` untuk mengecek keaktifan modul sekolah. Menu yang tidak aktif otomatis disembunyikan untuk user non-admin, dan ditampilkan dengan status terkunci (locked) untuk user admin/super admin sebagai petunjuk untuk upgrade paket.

---

## 8. Limit Enforcement (Pembatasan Santri)

Sebelum data santri ditambahkan ke database, `StudentController@store` akan memanggil:
```php
app(\App\Services\Billing\PlanLimitService::class)->isWithinLimit($school, 'max_students', 1);
```
Jika batas jumlah santri terlampaui:
* Pengguna Admin/Super Admin akan menerima pesan error: `"Limit jumlah santri pada plan ini sudah tercapai."` dan dialihkan kembali ke formulir.
* Pengguna lainnya akan menerima respon HTTP `403 Forbidden`.
* Setelah santri sukses tersimpan, sistem memanggil `refreshUsage($school)` untuk menyinkronkan data hitungan santri di tabel `subscription_usages`.

---

## 9. Menambah Modul Baru ke Entitlement Engine

Untuk mendaftarkan modul baru ke dalam sistem:
1. Jalankan seeder baru atau masukkan entri secara langsung ke tabel `system_modules` dengan `module_key` yang unik.
2. Daftarkan rute modul baru di `routes/web.php` dan bungkus dengan middleware proteksi:
   ```php
   Route::middleware(['auth', 'subscription.active', 'module:module_key_baru'])
       ->prefix('modul-baru')
       ->group(function() { ... });
   ```
3. Tambahkan modul baru tersebut ke pemetaan item menu di `BillingNavigationService` atau `navbar.blade.php` sesuai kebutuhan.
4. Hubungkan modul baru ke paket langganan yang sesuai di halaman edit paket.

---

## 10. Batasan Teknis

* **Tanpa Payment Gateway Otomatis**: Belum ada integrasi dengan penyedia gateway pembayaran otomatis (Midtrans, Xendit, dll.). Semua siklus tagihan, masa aktif, dan status diubah secara manual oleh pihak manajemen platform di menu admin billing.
