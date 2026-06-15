# Phase 21 Execution Guide — Native Mobile Companion Apps & App Store Distribution

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

Framework backend:

```text
Laravel 12
```

Database backend:

```text
MySQL
```

Project folder lokal backend:

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
Phase 21 — Native Mobile Companion Apps & App Store Distribution
```

---

# 1. Keputusan Sebelum Phase 21

## 1.1 Phase 21 Bersifat Growth Phase

Phase 21 bukan bagian dari core MVP awal.

Phase 21 baru boleh dieksekusi setelah:

1. Phase 17 Multi-Tenant Foundation aman.
2. Phase 18 White-Label School App Builder aman.
3. Phase 19 Cashless Kantin / Merchant POS aman.
4. Phase 20 Company/Product Scale & SaaS Operations aman.
5. Tenant isolation sudah terbukti tidak bocor.
6. Parent/student ownership sudah terbukti aman.
7. Cashless ledger sudah stabil.
8. Support, documentation, onboarding, dan incident response sudah siap.

Jangan membuat native mobile app jika backend masih belum stabil.

---

## 1.2 Phase 21 Bukan Pengganti Web/PWA

Aplikasi native pada Phase 21 adalah **companion channel**.

Artinya:

1. Backend Laravel tetap sumber data utama.
2. Web admin tetap pusat konfigurasi.
3. PWA/web tetap dipertahankan.
4. Native mobile hanya memperkuat pengalaman parent, student, teacher, dan merchant.
5. Tidak ada logic bisnis utama yang hanya hidup di mobile.

Semua keputusan saldo, transaksi, akses data, tenant, role, dan ownership tetap divalidasi di server.

---

## 1.3 Kenapa Phase 21 Baru Setelah Phase 20

Native app menambah kompleksitas besar:

1. API contract harus stabil.
2. Auth token harus aman.
3. Device management harus jelas.
4. App versioning harus dikelola.
5. Release Android/iOS butuh SOP.
6. Privacy policy dan data safety harus konsisten.
7. Push notification butuh key management.
8. Crash report dan support flow harus siap.
9. Update app tidak secepat update web.
10. Perubahan breaking API bisa merusak app yang sudah dipakai user.

Karena itu Phase 21 tidak boleh dikerjakan sebelum product operations di Phase 20 siap.

---

# 2. Tujuan Phase 21

Phase 21 bertujuan membuat pondasi **native mobile companion apps** untuk HafizPlus School Platform.

Fokus utama:

1. Mobile API foundation.
2. Mobile token authentication.
3. Mobile device registry.
4. Mobile app bootstrap configuration.
5. Parent mobile companion app.
6. Student mobile companion app.
7. Teacher mobile companion app.
8. Merchant/cashier mobile companion app opsional.
9. Mobile notification readiness.
10. App version management.
11. Tenant-aware mobile branding.
12. App Store / Play Store release preparation.
13. Mobile QA checklist.
14. Mobile support SOP.
15. Dokumentasi Phase 21.

---

# 3. Batasan Phase 21

AI agent tidak boleh membuat fitur berikut pada Phase 21:

1. Backend baru terpisah dari Laravel.
2. Database mobile terpisah sebagai source of truth.
3. Offline cashless transaction.
4. Offline wallet balance sebagai sumber kebenaran.
5. Payment gateway baru.
6. QRIS otomatis baru.
7. Pinjaman / kredit siswa.
8. Inter-school wallet transfer.
9. Native admin panel penuh.
10. App builder native per sekolah secara otomatis.
11. Auto-publish ke Play Store/App Store.
12. Hardcode credential store.
13. Hardcode tenant/domain sekolah.
14. Hardcode API key di repository.
15. Menyimpan password di mobile storage.
16. Menyimpan token di plain text storage.
17. Mengirim data sensitif ke analytics tanpa persetujuan.
18. Mengubah ledger cashless dari mobile secara langsung tanpa server transaction.
19. Menghapus transaksi cashless dari mobile.
20. Menghapus data siswa dari mobile.
21. Mengubah tenant isolation.
22. Membuka data semua sekolah di app parent/student.
23. Membuat fitur chat real-time kompleks.
24. Membuat LMS/video learning penuh.
25. Membuat face recognition, fingerprint, RFID, atau NFC payment.

Phase 21 hanya membuat mobile companion layer yang aman di atas backend yang sudah ada.

---

# 4. Strategi Teknologi Mobile

## 4.1 Pilihan Default

Gunakan satu codebase cross-platform.

Rekomendasi default:

```text
Flutter
```

Alasan:

1. Satu codebase untuk Android dan iOS.
2. Cocok untuk UI mobile yang konsisten.
3. Build native package tersedia untuk kedua platform.
4. Struktur project jelas untuk tim kecil.
5. Bisa dipisahkan dari Laravel backend.

Alternatif yang boleh dipilih jika tim lebih siap:

```text
React Native
```

Namun untuk Phase 21, pilih **satu** saja. Jangan membuat Flutter dan React Native sekaligus.

---

## 4.2 Struktur Repository

Ada dua opsi.

### Opsi A — Monorepo Ringan

Jika masih satu tim kecil, mobile boleh ditempatkan di folder:

```text
C:\xampp\htdocs\hafizplus-school-platform\mobile\hafizplus_mobile
```

Struktur:

```text
hafizplus-school-platform/
├── app/
├── database/
├── resources/
├── routes/
├── docs/
└── mobile/
    └── hafizplus_mobile/
```

### Opsi B — Repository Terpisah

Jika project sudah dipakai banyak sekolah, lebih baik mobile dipisah:

```text
hafizplus-mobile-app
```

Untuk Phase 21, gunakan Opsi A dulu jika agent bekerja di repo Laravel yang sama.

---

## 4.3 Jangan Menggabungkan Logic Bisnis ke Mobile

Mobile hanya boleh:

1. Menampilkan data.
2. Mengirim request input sesuai role.
3. Menyimpan session token secara aman.
4. Menampilkan notifikasi.
5. Mengirim device token.
6. Mengambil konfigurasi branding dari server.

Mobile tidak boleh:

1. Menghitung saldo final.
2. Menghitung hutang final.
3. Menghitung ledger final.
4. Menentukan tenant sendiri tanpa server.
5. Menentukan role sendiri.
6. Menentukan hak akses sendiri.
7. Membuat transaksi cashless offline.

---

# 5. Target Aplikasi Mobile

## 5.1 Parent Mobile App

Parent app digunakan oleh orang tua.

Fitur utama:

1. Login parent.
2. Pilih sekolah jika parent punya akses lebih dari satu tenant.
3. Daftar anak.
4. Ringkasan progres tahfizh anak.
5. Riwayat setoran tahfizh anak.
6. Target dan hutang hafalan anak.
7. Mutabaah anak.
8. Attendance anak.
9. Tahsin anak.
10. Tagihan anak.
11. Riwayat pembayaran manual.
12. Saldo cashless anak.
13. Riwayat transaksi cashless anak.
14. Notification center.
15. Profil akun.
16. Bantuan/support.

Batasan parent app:

1. Parent tidak boleh melihat anak lain.
2. Parent tidak boleh input setoran tahfizh.
3. Parent tidak boleh edit tagihan.
4. Parent tidak boleh menjalankan POS.
5. Parent tidak boleh melihat laporan internal sekolah.

---

## 5.2 Student Mobile App

Student app digunakan oleh santri/siswa.

Fitur utama:

1. Login student.
2. Ringkasan progres pribadi.
3. Riwayat setoran tahfizh pribadi.
4. Target dan hutang hafalan pribadi.
5. Mutabaah pribadi.
6. Attendance pribadi.
7. Tahsin pribadi.
8. Tagihan pribadi read-only.
9. Saldo cashless pribadi.
10. Riwayat transaksi cashless pribadi.
11. QR identity card untuk attendance.
12. Notification center.
13. Profil akun.

Batasan student app:

1. Student tidak boleh melihat data santri lain.
2. Student tidak boleh edit data master.
3. Student tidak boleh membuat transaksi POS.
4. Student tidak boleh mengubah saldo.
5. Student tidak boleh melihat laporan internal.

---

## 5.3 Teacher Mobile App

Teacher app digunakan oleh guru.

Fitur utama:

1. Login teacher.
2. Dashboard guru.
3. Daftar kelas/santri scope guru.
4. Quick input setoran tahfizh.
5. Riwayat setoran santri scope.
6. Mutabaah input.
7. Attendance scanner atau manual attendance sesuai izin.
8. Tahsin assessment input.
9. Notification center.
10. Ringkasan santri butuh perhatian.
11. Profil akun.

Batasan teacher app:

1. Teacher hanya melihat santri scope-nya.
2. Teacher tidak boleh akses finance default.
3. Teacher tidak boleh akses cashless merchant default.
4. Teacher tidak boleh mengubah school branding.
5. Teacher tidak boleh mengubah tenant settings.

---

## 5.4 Merchant/Cashier Mobile App Opsional

Merchant/cashier app hanya boleh dibuat jika Phase 19 sudah stabil.

Fitur utama:

1. Login cashier.
2. Pilih merchant yang diizinkan.
3. Buka POS session.
4. Lihat katalog produk.
5. Scan cashless payment token siswa.
6. Checkout online server-authoritative.
7. Cetak/lihat receipt sederhana.
8. Void/refund terbatas sesuai role.
9. Cashier closing.
10. Riwayat transaksi merchant.

Batasan merchant app:

1. Tidak boleh offline purchase.
2. Tidak boleh menggunakan saldo cached sebagai sumber kebenaran.
3. Tidak boleh bypass server ledger.
4. Tidak boleh menyimpan payment token siswa lebih lama dari kebutuhan transaksi.
5. Tidak boleh melihat data akademik siswa.
6. Tidak boleh melihat data finance sekolah selain transaksi merchant.

Jika Phase 19 belum stabil, merchant app ditunda.

---

# 6. Mobile API Foundation

## 6.1 Prinsip API

Semua endpoint mobile harus:

1. Versioned.
2. Tenant-aware.
3. Role-aware.
4. Ownership-aware.
5. Rate-limited.
6. Audit-logged untuk aksi penting.
7. Mengembalikan response JSON konsisten.
8. Tidak mengembalikan data berlebihan.
9. Tidak mengandalkan ID dari client tanpa authorization server.
10. Tidak memakai session Blade web.

Gunakan prefix:

```text
/api/mobile/v1
```

---

## 6.2 Format Response JSON

Gunakan format umum:

```json
{
  "success": true,
  "message": "OK",
  "data": {},
  "meta": {}
}
```

Untuk error:

```json
{
  "success": false,
  "message": "Unauthorized",
  "errors": {}
}
```

Jangan mengembalikan stack trace ke mobile app.

---

## 6.3 Auth API

Endpoint wajib:

```text
POST   /api/mobile/v1/auth/login
POST   /api/mobile/v1/auth/logout
GET    /api/mobile/v1/auth/me
POST   /api/mobile/v1/auth/refresh
POST   /api/mobile/v1/auth/change-password
POST   /api/mobile/v1/auth/forgot-password/request
POST   /api/mobile/v1/auth/forgot-password/reset
```

Catatan:

1. Login mengembalikan access token.
2. Token harus bisa dicabut saat logout.
3. Token harus terikat user dan device.
4. Jangan mengembalikan password hash.
5. Jangan mengembalikan semua permission mentah jika tidak perlu.

---

## 6.4 Tenant API

Endpoint wajib:

```text
GET    /api/mobile/v1/tenants
POST   /api/mobile/v1/tenants/switch
GET    /api/mobile/v1/tenants/current
GET    /api/mobile/v1/bootstrap
```

`bootstrap` mengembalikan:

1. Active tenant.
2. User role.
3. Branding sekolah.
4. Enabled mobile modules.
5. App feature flags.
6. Minimum supported app version.
7. Support contact.
8. Basic navigation config.

---

## 6.5 Parent API

Endpoint parent:

```text
GET /api/mobile/v1/parent/children
GET /api/mobile/v1/parent/children/{student}/summary
GET /api/mobile/v1/parent/children/{student}/tahfizh
GET /api/mobile/v1/parent/children/{student}/mutabaah
GET /api/mobile/v1/parent/children/{student}/attendance
GET /api/mobile/v1/parent/children/{student}/tahsin
GET /api/mobile/v1/parent/children/{student}/finance
GET /api/mobile/v1/parent/children/{student}/cashless
GET /api/mobile/v1/parent/children/{student}/notifications
```

Semua endpoint wajib melewati ownership check.

Jika parent membuka student yang bukan anaknya:

```text
403 Forbidden
```

---

## 6.6 Student API

Endpoint student:

```text
GET /api/mobile/v1/student/me/summary
GET /api/mobile/v1/student/me/tahfizh
GET /api/mobile/v1/student/me/mutabaah
GET /api/mobile/v1/student/me/attendance
GET /api/mobile/v1/student/me/tahsin
GET /api/mobile/v1/student/me/finance
GET /api/mobile/v1/student/me/cashless
GET /api/mobile/v1/student/me/qr-card
GET /api/mobile/v1/student/me/notifications
```

Student tidak boleh mengirim `student_id` untuk memilih data orang lain.

Server wajib resolve student dari:

```text
students.user_id = auth()->id()
```

---

## 6.7 Teacher API

Endpoint teacher:

```text
GET  /api/mobile/v1/teacher/dashboard
GET  /api/mobile/v1/teacher/classes
GET  /api/mobile/v1/teacher/students
GET  /api/mobile/v1/teacher/students/{student}/summary
POST /api/mobile/v1/teacher/tahfizh/records
GET  /api/mobile/v1/teacher/tahfizh/records
POST /api/mobile/v1/teacher/mutabaah/records
GET  /api/mobile/v1/teacher/attendance/sessions
POST /api/mobile/v1/teacher/attendance/scan
POST /api/mobile/v1/teacher/attendance/manual-records
POST /api/mobile/v1/teacher/tahsin/assessments
GET  /api/mobile/v1/teacher/notifications
```

Semua endpoint teacher wajib scope-limited.

Teacher tidak boleh melihat semua santri lintas sekolah.

---

## 6.8 Merchant API Opsional

Endpoint merchant:

```text
GET  /api/mobile/v1/merchant/profile
GET  /api/mobile/v1/merchant/products
POST /api/mobile/v1/merchant/pos-sessions/open
POST /api/mobile/v1/merchant/pos-sessions/{session}/close
POST /api/mobile/v1/merchant/sales/preview
POST /api/mobile/v1/merchant/sales/checkout
POST /api/mobile/v1/merchant/sales/{sale}/void
GET  /api/mobile/v1/merchant/sales
GET  /api/mobile/v1/merchant/settlements
```

Checkout harus:

1. Online.
2. Atomic.
3. Pakai DB transaction.
4. Pakai row lock wallet.
5. Pakai idempotency key.
6. Server-authoritative.
7. Menolak saldo kurang.
8. Menulis ledger.
9. Menulis sale record.
10. Mengembalikan receipt.

---

# 7. Backend Target Output

Setelah Phase 21 selesai, backend Laravel harus punya:

1. Mobile API route group.
2. Mobile auth controller.
3. Mobile bootstrap controller.
4. Mobile tenant controller.
5. Parent mobile controllers.
6. Student mobile controllers.
7. Teacher mobile controllers.
8. Merchant mobile controllers opsional.
9. Mobile device registry.
10. Mobile app version management.
11. Mobile API audit log.
12. Mobile response helper.
13. Mobile API resources.
14. Rate limiter untuk mobile.
15. Token revoke flow.
16. Device token registration endpoint.
17. Dokumentasi API mobile.
18. Update `docs/project-progress.md`.

---

# 8. Backend Struktur File

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── Mobile/
│   │           └── V1/
│   │               ├── Auth/
│   │               │   └── MobileAuthController.php
│   │               ├── Bootstrap/
│   │               │   └── MobileBootstrapController.php
│   │               ├── Tenant/
│   │               │   └── MobileTenantController.php
│   │               ├── Parent/
│   │               │   └── ParentMobilePortalController.php
│   │               ├── Student/
│   │               │   └── StudentMobilePortalController.php
│   │               ├── Teacher/
│   │               │   └── TeacherMobilePortalController.php
│   │               ├── Merchant/
│   │               │   └── MerchantMobilePosController.php
│   │               ├── Notification/
│   │               │   └── MobileNotificationController.php
│   │               └── Device/
│   │                   └── MobileDeviceController.php
│   ├── Middleware/
│   │   ├── EnsureMobileTenantContext.php
│   │   ├── EnsureMobileAppVersion.php
│   │   └── MobileApiResponseMiddleware.php
│   ├── Requests/
│   │   └── Api/
│   │       └── Mobile/
│   │           └── V1/
│   │               ├── LoginMobileRequest.php
│   │               ├── SwitchMobileTenantRequest.php
│   │               ├── RegisterMobileDeviceRequest.php
│   │               ├── StoreMobileTahfizhRecordRequest.php
│   │               ├── StoreMobileMutabaahRecordRequest.php
│   │               ├── StoreMobileTahsinAssessmentRequest.php
│   │               └── MobileMerchantCheckoutRequest.php
│   └── Resources/
│       └── Mobile/
│           └── V1/
│               ├── MobileUserResource.php
│               ├── MobileTenantResource.php
│               ├── MobileStudentSummaryResource.php
│               ├── MobileNotificationResource.php
│               ├── MobileFinanceSummaryResource.php
│               ├── MobileCashlessSummaryResource.php
│               └── MobileMerchantSaleResource.php
├── Models/
│   ├── MobileDevice.php
│   ├── MobileAppVersion.php
│   └── MobileApiAuditLog.php
└── Services/
    └── Mobile/
        ├── MobileAuthService.php
        ├── MobileBootstrapService.php
        ├── MobileTenantService.php
        ├── MobileDeviceService.php
        ├── MobileAccessService.php
        ├── MobilePortalSummaryService.php
        ├── MobileTeacherWorkflowService.php
        ├── MobileMerchantWorkflowService.php
        └── MobileApiAuditLogger.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_mobile_devices_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_mobile_app_versions_table.php
│   └── xxxx_xx_xx_xxxxxx_create_mobile_api_audit_logs_table.php
└── seeders/
    └── MobileAppVersionSeeder.php

routes/
└── api.php

docs/
├── phase-21-execution.md
├── phase-21-native-mobile-companion-apps.md
└── api-mobile-v1.md
```

---

# 9. Backend Artisan Commands

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform

git checkout -b phase-21-native-mobile-companion-apps
```

Jika branch sudah ada:

```powershell
git checkout phase-21-native-mobile-companion-apps
```

Buat model dan migration:

```powershell
php artisan make:model MobileDevice -m
php artisan make:model MobileAppVersion -m
php artisan make:model MobileApiAuditLog -m
```

Buat seeder:

```powershell
php artisan make:seeder MobileAppVersionSeeder
```

Buat controller:

```powershell
php artisan make:controller Api/Mobile/V1/Auth/MobileAuthController
php artisan make:controller Api/Mobile/V1/Bootstrap/MobileBootstrapController
php artisan make:controller Api/Mobile/V1/Tenant/MobileTenantController
php artisan make:controller Api/Mobile/V1/Parent/ParentMobilePortalController
php artisan make:controller Api/Mobile/V1/Student/StudentMobilePortalController
php artisan make:controller Api/Mobile/V1/Teacher/TeacherMobilePortalController
php artisan make:controller Api/Mobile/V1/Merchant/MerchantMobilePosController
php artisan make:controller Api/Mobile/V1/Notification/MobileNotificationController
php artisan make:controller Api/Mobile/V1/Device/MobileDeviceController
```

Buat request:

```powershell
php artisan make:request Api/Mobile/V1/LoginMobileRequest
php artisan make:request Api/Mobile/V1/SwitchMobileTenantRequest
php artisan make:request Api/Mobile/V1/RegisterMobileDeviceRequest
php artisan make:request Api/Mobile/V1/StoreMobileTahfizhRecordRequest
php artisan make:request Api/Mobile/V1/StoreMobileMutabaahRecordRequest
php artisan make:request Api/Mobile/V1/StoreMobileTahsinAssessmentRequest
php artisan make:request Api/Mobile/V1/MobileMerchantCheckoutRequest
```

Buat middleware:

```powershell
php artisan make:middleware EnsureMobileTenantContext
php artisan make:middleware EnsureMobileAppVersion
php artisan make:middleware MobileApiResponseMiddleware
```

Buat folder service dan resource:

```powershell
mkdir app\Services\Mobile
mkdir app\Http\Resources\Mobile
mkdir app\Http\Resources\Mobile\V1
```

Buat file service:

```powershell
New-Item app\Services\Mobile\MobileAuthService.php
New-Item app\Services\Mobile\MobileBootstrapService.php
New-Item app\Services\Mobile\MobileTenantService.php
New-Item app\Services\Mobile\MobileDeviceService.php
New-Item app\Services\Mobile\MobileAccessService.php
New-Item app\Services\Mobile\MobilePortalSummaryService.php
New-Item app\Services\Mobile\MobileTeacherWorkflowService.php
New-Item app\Services\Mobile\MobileMerchantWorkflowService.php
New-Item app\Services\Mobile\MobileApiAuditLogger.php
```

---

# 10. Migration Design

## 10.1 `mobile_devices`

Kolom:

```text
id
school_id nullable FK schools
user_id FK users
device_uuid string indexed
platform string
platform_version string nullable
app_version string nullable
device_name string nullable
push_token text nullable
push_provider string nullable
last_ip string nullable
last_seen_at timestamp nullable
revoked_at timestamp nullable
is_active boolean default true
created_at
updated_at
```

Index:

```text
user_id
school_id
device_uuid
platform
is_active
last_seen_at
```

Unique:

```text
user_id + device_uuid
```

---

## 10.2 `mobile_app_versions`

Kolom:

```text
id
platform string
version string
build_number integer nullable
minimum_supported_version string nullable
is_force_update boolean default false
is_active boolean default true
release_notes text nullable
released_at timestamp nullable
created_at
updated_at
```

Index:

```text
platform
version
is_active
is_force_update
```

---

## 10.3 `mobile_api_audit_logs`

Kolom:

```text
id
school_id nullable FK schools
user_id nullable FK users
mobile_device_id nullable FK mobile_devices
method string
path string
status_code unsignedSmallInteger nullable
ip_address string nullable
user_agent text nullable
action string nullable
request_id string nullable
metadata json nullable
created_at
updated_at
```

Index:

```text
school_id
user_id
mobile_device_id
status_code
action
created_at
```

Catatan:

1. Jangan simpan password.
2. Jangan simpan token.
3. Jangan simpan PIN.
4. Jangan simpan payload sensitif penuh.

---

# 11. Mobile App Structure

Jika memakai Flutter, buat project:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
mkdir mobile
cd mobile
flutter create hafizplus_mobile
```

Struktur yang diharapkan:

```text
mobile/
└── hafizplus_mobile/
    ├── lib/
    │   ├── app/
    │   │   ├── app.dart
    │   │   ├── routes.dart
    │   │   └── theme.dart
    │   ├── core/
    │   │   ├── api/
    │   │   │   ├── api_client.dart
    │   │   │   ├── api_response.dart
    │   │   │   └── endpoints.dart
    │   │   ├── auth/
    │   │   │   ├── token_storage.dart
    │   │   │   └── auth_state.dart
    │   │   ├── config/
    │   │   │   └── app_config.dart
    │   │   ├── tenant/
    │   │   │   └── tenant_context.dart
    │   │   └── widgets/
    │   ├── features/
    │   │   ├── auth/
    │   │   ├── bootstrap/
    │   │   ├── parent_portal/
    │   │   ├── student_portal/
    │   │   ├── teacher_portal/
    │   │   ├── merchant_pos/
    │   │   ├── notifications/
    │   │   └── profile/
    │   └── main.dart
    ├── test/
    └── pubspec.yaml
```

Jika Flutter belum tersedia di komputer, agent tidak boleh memaksa install secara destruktif. Catat sebagai blocker:

```text
Flutter SDK belum tersedia. Backend mobile API tetap bisa dikerjakan, mobile client skeleton ditunda.
```

---

# 12. Mobile Screens Target

## 12.1 Auth Screens

Wajib:

1. Splash screen.
2. Login screen.
3. Tenant/school selector.
4. Forgot password request.
5. Reset password.
6. Change password.
7. Logout confirmation.

---

## 12.2 Parent Screens

Wajib:

1. Parent home.
2. Child selector.
3. Child summary.
4. Tahfizh summary.
5. Tahfizh records.
6. Mutabaah summary.
7. Attendance summary.
8. Tahsin summary.
9. Finance summary.
10. Cashless summary.
11. Cashless transaction history.
12. Notification list.
13. Notification detail.

---

## 12.3 Student Screens

Wajib:

1. Student home.
2. Tahfizh summary.
3. Mutabaah summary.
4. Attendance summary.
5. Tahsin summary.
6. Finance summary.
7. Cashless balance.
8. Cashless transaction history.
9. QR card.
10. Notification list.
11. Notification detail.

---

## 12.4 Teacher Screens

Wajib:

1. Teacher home.
2. Class list.
3. Student list.
4. Student summary.
5. Tahfizh quick input.
6. Mutabaah input.
7. Attendance scanner/manual.
8. Tahsin assessment input.
9. Notification list.
10. Notification detail.

---

## 12.5 Merchant Screens Opsional

Wajib jika merchant mobile diaktifkan:

1. Merchant home.
2. POS session open/close.
3. Product list.
4. Cart.
5. Student token scan.
6. Sale preview.
7. Checkout confirmation.
8. Receipt.
9. Sales history.
10. Closing summary.

---

# 13. Security Rules

## 13.1 Token Security

1. Access token tidak boleh disimpan di plain text storage.
2. Token harus bisa dicabut dari backend.
3. Logout harus revoke token.
4. Password tidak boleh disimpan di mobile.
5. Refresh/token rotation harus diatur server-side.
6. Device registry harus mencatat last_seen_at.
7. Admin harus bisa revoke device jika akun hilang.

---

## 13.2 Tenant Security

1. Mobile request wajib punya active tenant context.
2. Tenant context harus berasal dari server, bukan client trust penuh.
3. Parent/student tidak boleh switch ke tenant tanpa membership.
4. Teacher tidak boleh melihat tenant lain.
5. Merchant tidak boleh mengakses merchant tenant lain.

---

## 13.3 Cashless Security

Untuk mobile cashless:

1. Balance di mobile hanya display.
2. Balance final dihitung server.
3. Transaction history berasal dari server.
4. Purchase harus online.
5. Checkout harus idempotent.
6. Tidak boleh offline purchase.
7. Tidak boleh direct update balance dari mobile.
8. Refund/void harus melalui server policy.
9. Parent/student tidak boleh membuat refund.
10. Merchant tidak boleh melihat data akademik siswa.

---

## 13.4 Privacy Rules

1. Data anak harus minimal.
2. Jangan kirim data sensitif ke crash/analytics provider tanpa kajian.
3. Jangan menampilkan NIK/NISN jika tidak diperlukan.
4. Jangan menampilkan nomor HP parent ke merchant.
5. Jangan menampilkan alamat lengkap siswa di merchant app.
6. Jangan menyimpan file rahasia di repository.
7. Privacy policy harus sinkron dengan data yang dikumpulkan app.

---

# 14. App Store / Play Store Preparation

Phase 21 menyiapkan dokumen dan build flow, bukan otomatis publish.

Dokumen yang harus dibuat:

```text
docs/mobile-release-checklist.md
docs/mobile-privacy-checklist.md
docs/mobile-support-sop.md
docs/mobile-api-v1.md
docs/mobile-test-plan.md
```

Checklist minimal sebelum publish:

1. Nama aplikasi final.
2. Logo aplikasi final.
3. Screenshot final.
4. Deskripsi aplikasi.
5. Privacy policy URL.
6. Support email.
7. Support phone jika ada.
8. Data collection checklist.
9. Demo/test account.
10. API production URL.
11. Crash-free smoke test.
12. Login/logout test.
13. Parent ownership test.
14. Student ownership test.
15. Teacher scope test.
16. Merchant transaction test jika merchant aktif.
17. Force update test.
18. Token revoke test.
19. Device revoke test.
20. Notification opt-in test jika push aktif.

Jangan publish sebelum semua item ini aman.

---

# 15. Mobile Versioning

Gunakan versi semantic untuk app:

```text
1.0.0
```

Gunakan build number terpisah untuk masing-masing platform.

Backend `mobile_app_versions` harus bisa mengatur:

1. Versi minimum yang didukung.
2. Force update.
3. Release notes.
4. Platform Android/iOS.
5. Active/inactive release.

Jika versi app terlalu lama, API bootstrap mengembalikan:

```json
{
  "success": false,
  "message": "App update required",
  "errors": {
    "update_required": true
  }
}
```

---

# 16. Rate Limiting

Tambahkan rate limiter untuk mobile API:

```text
mobile-login
mobile-api
mobile-checkout
mobile-password-reset
```

Rekomendasi:

1. Login lebih ketat.
2. Password reset lebih ketat.
3. Checkout harus dibatasi dan idempotent.
4. Read endpoints boleh lebih longgar.
5. Audit suspicious traffic.

Jangan memakai limit yang terlalu longgar untuk auth.

---

# 17. Testing Plan

## 17.1 Backend API Test

Minimal test:

1. Login parent sukses.
2. Login student sukses.
3. Login teacher sukses.
4. Login merchant sukses jika aktif.
5. Parent hanya melihat anak sendiri.
6. Student hanya melihat diri sendiri.
7. Teacher hanya melihat scope santri.
8. Merchant hanya melihat merchant sendiri.
9. Tenant switch hanya untuk membership valid.
10. Token revoke membuat request berikutnya gagal.
11. Device registration sukses.
12. Force update response bekerja.
13. Cashless checkout idempotent.
14. Cashless insufficient balance ditolak.
15. API tidak mengembalikan field sensitif.

---

## 17.2 Mobile UI Smoke Test

Minimal test:

1. App terbuka.
2. Splash ke login.
3. Login berhasil.
4. Tenant selector tampil jika user multi-tenant.
5. Dashboard sesuai role.
6. Pull-to-refresh data berjalan.
7. Logout berhasil.
8. Token expired diarahkan ke login.
9. Error network tampil manusiawi.
10. Empty state tampil rapi.
11. Loading state tampil rapi.
12. Dark mode opsional tidak merusak UI.

---

## 17.3 Parent UAT

Parent wajib dites:

1. Parent A tidak bisa melihat anak Parent B.
2. Parent bisa melihat ringkasan anak.
3. Parent bisa melihat tahfizh.
4. Parent bisa melihat attendance.
5. Parent bisa melihat finance.
6. Parent bisa melihat cashless.
7. Parent tidak bisa input data internal.
8. Parent logout aman.

---

## 17.4 Student UAT

Student wajib dites:

1. Student A tidak bisa melihat Student B.
2. Student bisa melihat progress pribadi.
3. Student bisa melihat QR card.
4. Student bisa melihat cashless balance.
5. Student tidak bisa mengubah saldo.
6. Student tidak bisa mengakses teacher endpoint.

---

## 17.5 Teacher UAT

Teacher wajib dites:

1. Teacher melihat dashboard.
2. Teacher melihat kelas scope.
3. Teacher input tahfizh berhasil.
4. Teacher input mutabaah berhasil.
5. Teacher input tahsin assessment berhasil.
6. Teacher tidak bisa melihat santri luar scope.
7. Teacher tidak bisa akses finance default.

---

## 17.6 Merchant UAT Opsional

Merchant wajib dites jika aktif:

1. Cashier login.
2. Buka POS session.
3. Tambah item ke cart.
4. Scan token siswa.
5. Checkout berhasil.
6. Saldo berkurang di server.
7. Ledger tercatat.
8. Receipt tampil.
9. Duplicate checkout dengan idempotency key tidak menggandakan charge.
10. Saldo kurang ditolak.
11. Close session berhasil.

---

# 18. Documentation Target

Buat/update dokumentasi:

```text
docs/phase-21-execution.md
docs/phase-21-native-mobile-companion-apps.md
docs/api-mobile-v1.md
docs/mobile-release-checklist.md
docs/mobile-privacy-checklist.md
docs/mobile-support-sop.md
docs/mobile-test-plan.md
docs/project-progress.md
```

`docs/project-progress.md` harus menambahkan:

```md
## Phase 21 — Native Mobile Companion Apps & App Store Distribution

Status: Done / In Progress / Blocked

Output:
- Mobile API v1 foundation
- Mobile device registry
- Mobile app version management
- Parent mobile app skeleton
- Student mobile app skeleton
- Teacher mobile app skeleton
- Merchant mobile app optional
- Mobile release checklist
- Mobile privacy checklist
- Mobile support SOP
```

---

# 19. Validation Commands

Jalankan backend:

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

Setelah migration:

```powershell
php artisan migrate
php artisan db:seed --class=MobileAppVersionSeeder
php artisan route:list --path=api/mobile/v1
php artisan app:system-health-check
```

Jika mobile Flutter tersedia:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform\mobile\hafizplus_mobile
flutter doctor
flutter pub get
flutter analyze
flutter test
flutter build apk --debug
```

Jika Flutter belum tersedia, catat blocker dan lanjutkan backend API + docs.

---

# 20. Definition of Done

Phase 21 selesai jika:

1. Mobile API v1 tersedia.
2. Mobile auth berjalan.
3. Token logout/revoke berjalan.
4. Device registry berjalan.
5. App version management berjalan.
6. Bootstrap config tenant-aware berjalan.
7. Parent API ownership aman.
8. Student API ownership aman.
9. Teacher API scope aman.
10. Merchant API aman jika diaktifkan.
11. Cashless mobile tidak memakai offline balance.
12. Rate limiter mobile aktif.
13. Mobile API audit log aktif untuk aksi penting.
14. Dokumentasi API mobile tersedia.
15. Mobile release checklist tersedia.
16. Mobile privacy checklist tersedia.
17. Mobile support SOP tersedia.
18. Mobile test plan tersedia.
19. Mobile app skeleton tersedia jika SDK siap.
20. Build debug mobile berhasil jika SDK siap.
21. `npm run build` backend berhasil.
22. `php artisan app:system-health-check` berhasil.
23. Tidak ada bug P0/P1 terbuka.
24. `docs/project-progress.md` diperbarui.

---

# 21. Bug Priority

| Priority | Contoh | Keputusan |
|---|---|---|
| P0 | Data parent bocor, token tidak bisa dicabut, tenant bocor, saldo bisa diubah client | Wajib fix sebelum release |
| P1 | Login mobile gagal, bootstrap salah tenant, teacher scope salah, checkout double-charge | Wajib fix sebelum release |
| P2 | UI kurang rapi, loading state kurang jelas, wording error kurang baik | Bisa masuk polish sprint |
| P3 | Enhancement kecil, animasi, kosmetik | Bisa ditunda |

---

# 22. Git Commit

Setelah semua selesai:

```powershell
git status
git add .
git commit -m "feat: add native mobile companion app foundation"
```

Jika mobile app skeleton belum dibuat karena Flutter SDK belum tersedia:

```powershell
git add .
git commit -m "feat: add mobile api foundation"
```

---

# 23. Final Report Format untuk Agent

Setelah selesai, agent wajib melaporkan:

```md
# Phase 21 Final Report

## Status
Done / Partial / Blocked

## Completed
- Mobile API v1
- Mobile auth
- Mobile device registry
- Mobile app version management
- Parent endpoints
- Student endpoints
- Teacher endpoints
- Merchant endpoints optional
- Documentation

## Validation
- php artisan migrate: passed/failed
- php artisan route:list --path=api/mobile/v1: passed/failed
- php artisan app:system-health-check: passed/failed
- npm run build: passed/failed
- flutter analyze: passed/failed/skipped
- flutter test: passed/failed/skipped
- flutter build apk --debug: passed/failed/skipped

## Blockers
- None / list blockers

## Notes
- Mobile app is companion channel.
- Backend remains source of truth.
- No offline cashless transaction.
- No app store publishing before privacy and release checklist are complete.
```

---

# 24. Setelah Phase 21

Setelah Phase 21, jangan langsung menambah fitur besar.

Urutan setelah Phase 21:

1. Mobile UAT internal.
2. Mobile UAT parent/student/teacher terbatas.
3. Security review API.
4. Privacy/data safety review.
5. Crash/error monitoring readiness.
6. Store listing preparation.
7. Closed testing.
8. Public release terbatas.
9. Support monitoring.
10. Bug fix sprint.

Phase 22 baru boleh dipertimbangkan setelah mobile app benar-benar stabil.

Rekomendasi Phase 22:

```text
Phase 22 — Analytics, BI, and Executive Decision Intelligence
```

Namun Phase 22 jangan dikerjakan sebelum Phase 21 stabil di perangkat nyata.

---

# Phase 21 Execution Result

## Status

Partial.

Backend mobile API foundation is implemented. Native Flutter client skeleton is skipped because Flutter SDK is not available in the local PATH.

## Completed

- Mobile API v1 route group.
- Mobile auth with hashed Bearer token storage.
- Mobile token revoke and refresh.
- Mobile device registry.
- Mobile app version management.
- Mobile API audit log.
- Tenant-aware bootstrap.
- Parent endpoints with ownership check.
- Student endpoints resolved from authenticated user.
- Teacher endpoints scoped to active tenant.
- Merchant endpoints using existing cashless ledger services.
- Mobile API docs.
- Release, privacy, support, and test plan docs.

## Blocker

- `flutter --version` failed because `flutter` is not recognized in PowerShell. Flutter analyze/test/debug APK build are skipped.

## Notes

- Laravel backend remains source of truth.
- Mobile app is a companion channel.
- No offline cashless transaction.
- No app store publishing in Phase 21.
