# Phase 22 Execution Guide — External API, Partner Integration & Developer Portal

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
Phase 22 — External API, Partner Integration & Developer Portal
```

---

# 1. Posisi Phase 22 dalam Roadmap

Phase 22 dikerjakan setelah:

1. Phase 17 Multi-Tenant Foundation.
2. Phase 18 White-Label School App Builder.
3. Phase 19 Cashless Kantin / Merchant POS.
4. Phase 20 Company/Product Scale & SaaS Operations.
5. Phase 21 Native Mobile Companion Apps & App Store Distribution.

Phase 22 bukan fase untuk menambah modul internal sekolah baru.

Phase 22 adalah fase untuk membuka HafizPlus School Platform agar bisa berintegrasi dengan pihak lain secara aman, terkontrol, dan tenant-aware.

Contoh kebutuhan:

1. Sekolah ingin mengambil data santri dari sistem lain.
2. Partner ingin membaca status tagihan tertentu.
3. Aplikasi mobile butuh API yang rapi dan terdokumentasi.
4. Sistem sekolah ingin mengirim data attendance ke dashboard eksternal.
5. Vendor payment gateway ingin diintegrasikan nanti, tetapi belum sekarang.
6. Partner konten ingin membaca daftar kelas/santri secara terbatas.
7. Admin internal butuh API key per tenant.
8. Developer butuh dokumentasi endpoint.
9. Sistem perlu rate limit, token, log, dan audit.
10. SaaS operations butuh kontrol integrasi aktif/nonaktif.

---

# 2. Keputusan Sebelum Phase 22

## 2.1 UAT Phase 21 Wajib

Sebelum menjalankan Phase 22, agent wajib memastikan Phase 21 Native Mobile Companion Apps sudah aman.

Phase 22 hanya boleh dieksekusi jika:

1. Mobile API internal berjalan stabil.
2. Parent mobile login aman.
3. Student mobile login aman.
4. Teacher mobile login aman.
5. Merchant mobile/POS companion, jika dibuat, aman.
6. Token mobile tidak bocor.
7. Tenant context mobile benar.
8. Parent hanya melihat anak sendiri.
9. Student hanya melihat data sendiri.
10. Teacher hanya melihat scope santri sendiri.
11. Merchant hanya melihat merchant sendiri.
12. `npm run build` berhasil.
13. `php artisan app:system-health-check` berhasil.
14. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0/P1, hentikan Phase 22 dan lakukan bug fix sprint dulu.

---

## 2.2 Kenapa Phase 22 Bukan Payment Gateway

Phase 22 bukan:

1. Payment gateway production.
2. Auto QRIS.
3. Virtual account.
4. Bank reconciliation.
5. Open banking.
6. Auto payout merchant.
7. Auto settlement ke rekening.
8. Marketplace eksternal.
9. Public API tanpa approval.
10. Data warehouse publik.

Phase 22 hanya membuat **fondasi API eksternal dan integrasi partner**.

Payment gateway atau integrasi bank bisa dipertimbangkan setelah Phase 22 jika:

1. API auth sudah aman.
2. Audit log lengkap.
3. Rate limit aktif.
4. Tenant isolation lulus audit.
5. Legal/privacy policy sudah direview.
6. Reconciliation process sudah didesain.
7. Finance/cashless ledger sudah terbukti stabil.

---

# 3. Tujuan Phase 22

Phase 22 bertujuan membuat layer integrasi eksternal yang aman dan terkontrol.

Fokus Phase 22:

1. API client management.
2. API key/token management.
3. API scope and permission.
4. Tenant-aware external API.
5. Developer portal sederhana.
6. API documentation page.
7. API request log.
8. API rate limiting.
9. Webhook endpoint management.
10. Webhook delivery log.
11. Partner integration registry.
12. Integration status monitor.
13. API versioning policy.
14. Data exposure policy.
15. Partner onboarding checklist.
16. Internal approval workflow untuk integrasi.
17. Dokumentasi Phase 22.

---

# 4. Batasan Phase 22

AI agent tidak boleh membuat fitur berikut pada Phase 22:

1. Payment gateway live integration.
2. Auto debit.
3. Auto virtual account.
4. Auto QRIS.
5. Bank callback production.
6. Merchant payout otomatis.
7. Open public registration untuk developer eksternal.
8. API publik tanpa approval admin.
9. OAuth marketplace kompleks.
10. Full API monetization billing.
11. External CRM automation.
12. WhatsApp blasting gateway.
13. Email marketing automation.
14. AI analytics eksternal.
15. Data lake.
16. Microservices rewrite.
17. GraphQL server.
18. Websocket realtime publik.
19. Full LMS API.
20. Marketplace plugin.
21. Third-party app store.
22. Auto domain provisioning.
23. Auto DNS management.
24. Native app rewrite.

Phase 22 tetap menggunakan Laravel monolith yang sudah ada.

---

# 5. Prinsip Keamanan Phase 22

## 5.1 API Eksternal Harus Tenant-Aware

Semua external API wajib terkait ke:

1. `school_id`.
2. API client.
3. Scope.
4. Token.
5. Rate limit.
6. Audit log.

Tidak boleh ada endpoint eksternal yang membaca seluruh data lintas tenant kecuali endpoint internal super admin yang tidak dipublikasikan sebagai partner API.

---

## 5.2 Default Deny

Semua API client default tidak punya akses apa pun.

Akses diberikan hanya melalui scope eksplisit.

Contoh scope:

```text
students:read
classes:read
attendance:read
attendance:write
tahfizh:read
finance:read
cashless:read
webhooks:manage
```

Jangan membuat scope terlalu luas seperti:

```text
all:read
all:write
admin:all
```

---

## 5.3 Jangan Bocorkan Data Sensitif

External API tidak boleh mengembalikan data berikut kecuali ada alasan legal/kontrak yang jelas:

1. Password hash.
2. Remember token.
3. Internal notes sensitif.
4. Audit internal lengkap.
5. Data lintas tenant.
6. Nomor HP orang tua jika tidak diperlukan.
7. Email parent/student jika scope tidak mengizinkan.
8. Detail saldo cashless jika scope tidak mengizinkan.
9. Detail finance jika scope tidak mengizinkan.
10. Data health/discipline boarding jika scope tidak mengizinkan.

---

## 5.4 Semua Request Harus Dilog

Setiap request API eksternal wajib mencatat minimal:

1. API client ID.
2. School ID.
3. Endpoint.
4. Method.
5. Response status.
6. IP address.
7. User agent.
8. Request ID.
9. Duration milliseconds.
10. Error message singkat jika gagal.
11. Timestamp.

Jangan log full body untuk data sensitif.

---

## 5.5 Token Tidak Boleh Disimpan Plain Text

API secret/token harus disimpan dalam bentuk hash.

Saat dibuat, token hanya ditampilkan satu kali.

Jika hilang, token harus regenerate.

---

# 6. Konsep Domain Phase 22

## 6.1 API Client

API Client adalah aplikasi eksternal atau partner yang diberi izin mengakses API.

Contoh:

1. Mobile app internal.
2. Sistem akademik sekolah.
3. Partner konten.
4. Partner analytics internal.
5. Future payment gateway adapter.
6. Future WhatsApp notification gateway.

Status API client:

| Status | Makna |
|---|---|
| `draft` | Baru dibuat, belum aktif |
| `active` | Bisa mengakses API |
| `suspended` | Diblokir sementara |
| `revoked` | Dicabut permanen |

---

## 6.2 API Scope

API Scope adalah permission granular.

Contoh:

| Scope | Makna |
|---|---|
| `students:read` | Membaca data santri minimal |
| `classes:read` | Membaca daftar kelas |
| `attendance:read` | Membaca data presensi |
| `attendance:write` | Menulis presensi dari sistem eksternal |
| `tahfizh:read` | Membaca progres tahfizh |
| `finance:read` | Membaca tagihan ringkas |
| `cashless:read` | Membaca transaksi cashless ringkas |
| `webhooks:manage` | Mengatur webhook |

---

## 6.3 API Version

API harus punya versioning.

Default route:

```text
/api/v1/...
```

Aturan:

1. Jangan ubah breaking response di versi yang sama.
2. Tambah field boleh.
3. Hapus/rename field tidak boleh tanpa versi baru.
4. Breaking change harus masuk `/api/v2` nanti.
5. Deprecated endpoint harus didokumentasikan.

---

## 6.4 Webhook

Webhook adalah mekanisme mengirim event dari HafizPlus ke endpoint partner.

Event awal Phase 22:

| Event | Keterangan |
|---|---|
| `student.created` | Santri dibuat |
| `student.updated` | Data santri berubah |
| `attendance.recorded` | Presensi tercatat |
| `tahfizh.recorded` | Setoran tahfizh tercatat |
| `finance.bill.created` | Tagihan dibuat |
| `finance.payment.recorded` | Pembayaran dicatat |
| `cashless.sale.posted` | Transaksi cashless posted |
| `support.ticket.created` | Ticket support dibuat |

Webhook Phase 22 belum harus realtime queue production kompleks.

Boleh pakai dispatch sederhana + retry command.

---

## 6.5 Partner Integration

Partner Integration adalah konfigurasi hubungan dengan sistem/partner tertentu.

Contoh tipe:

| Type | Keterangan |
|---|---|
| `internal_mobile` | Native mobile app resmi |
| `school_sis` | Sistem informasi sekolah eksternal |
| `content_partner` | Partner konten pembelajaran |
| `analytics_partner` | Dashboard analitik terbatas |
| `payment_gateway_future` | Placeholder gateway pembayaran masa depan |
| `notification_gateway_future` | Placeholder WhatsApp/email gateway masa depan |

---

# 7. Target Output Phase 22

Setelah Phase 22 selesai, aplikasi harus punya:

1. Menu **Developer Portal**.
2. Menu **API Clients**.
3. Menu **API Scopes**.
4. Menu **Partner Integrations**.
5. Menu **Webhook Endpoints**.
6. Menu **Webhook Deliveries**.
7. Menu **API Request Logs**.
8. Menu **API Documentation**.
9. Tabel:
   - `api_clients`
   - `api_client_tokens`
   - `api_scopes`
   - `api_client_scope`
   - `api_request_logs`
   - `partner_integrations`
   - `webhook_endpoints`
   - `webhook_deliveries`
   - `api_documentation_pages`
10. Model:
   - `ApiClient`
   - `ApiClientToken`
   - `ApiScope`
   - `ApiRequestLog`
   - `PartnerIntegration`
   - `WebhookEndpoint`
   - `WebhookDelivery`
   - `ApiDocumentationPage`
11. Controller internal:
   - `DeveloperPortalDashboardController`
   - `ApiClientController`
   - `ApiScopeController`
   - `PartnerIntegrationController`
   - `WebhookEndpointController`
   - `WebhookDeliveryController`
   - `ApiRequestLogController`
   - `ApiDocumentationPageController`
12. Public API controller:
   - `Api\V1\StudentApiController`
   - `Api\V1\ClassRoomApiController`
   - `Api\V1\AttendanceApiController`
   - `Api\V1\TahfizhApiController`
   - `Api\V1\FinanceApiController`
   - `Api\V1\CashlessApiController`
   - `Api\V1\WebhookTestController`
13. Request:
   - `StoreApiClientRequest`
   - `UpdateApiClientRequest`
   - `GenerateApiTokenRequest`
   - `StorePartnerIntegrationRequest`
   - `UpdatePartnerIntegrationRequest`
   - `StoreWebhookEndpointRequest`
   - `UpdateWebhookEndpointRequest`
   - `ApiRequestLogFilterRequest`
   - `StoreApiDocumentationPageRequest`
14. Middleware:
   - `AuthenticateApiClient`
   - `EnsureApiScope`
   - `LogExternalApiRequest`
   - `ApplyApiRateLimit`
15. Service:
   - `ApiClientTokenService`
   - `ApiScopeService`
   - `ApiAccessService`
   - `ApiRequestLogger`
   - `ApiResponseFormatter`
   - `PartnerIntegrationService`
   - `WebhookEventService`
   - `WebhookDeliveryService`
   - `WebhookSignatureService`
   - `DeveloperPortalService`
16. Command:
   - `php artisan app:retry-failed-webhooks`
   - `php artisan app:prune-api-request-logs`
   - `php artisan app:rotate-api-client-token {client}`
17. Seeder:
   - `ApiScopeSeeder`
   - `ApiDocumentationPageSeeder`
18. Dokumentasi:
   - `docs/phase-22-external-api-partner-integration.md`
   - `docs/api-versioning-policy.md`
   - `docs/api-security-policy.md`
   - `docs/webhook-policy.md`
   - `docs/partner-onboarding-checklist.md`
19. Update:
   - `docs/project-progress.md`

---

# 8. Role Access Phase 22

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
support_staff
customer_success
operations_manager
sales
merchant_owner
cashier
```

| Role | API Client | Scope | Partner | Webhook | Logs | Docs |
|---|---|---|---|---|---|---|
| Super Admin | Semua | Semua | Semua | Semua | Semua | CRUD |
| Operations Manager | Semua tenant | Read/Assign | Manage | Manage | Semua | CRUD |
| Admin Sekolah | Tenant sendiri | Tidak | Limited | Tenant webhook | Tenant logs | Read |
| Customer Success | Read | Tidak | Read | Read | Read | Read |
| Support Staff | Read limited | Tidak | Read limited | Read limited | Read error | Read |
| Sales | Tidak | Tidak | Read prospect only | Tidak | Tidak | Read |
| Teacher/Guru | Tidak | Tidak | Tidak | Tidak | Tidak | Read public docs jika perlu |
| Parent | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Student | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Merchant | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |

Aturan keras:

1. Parent/student tidak boleh mengakses Developer Portal.
2. Teacher/guru tidak boleh membuat API client.
3. Merchant/cashier tidak boleh membuat API client.
4. Admin sekolah hanya boleh melihat integrasi tenant sendiri jika fitur self-service dibuka.
5. Super Admin dan Operations Manager boleh mengelola semua integrasi.
6. API scope hanya boleh dibuat oleh Super Admin.
7. API token tidak boleh ditampilkan ulang setelah dibuat.
8. Semua perubahan client/scope/webhook wajib audit log.

---

# 9. Validasi Awal Sebelum Eksekusi

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
2. Phase 0 sampai Phase 21 selesai.
3. Phase 21 UAT aman.
4. Multi-tenant foundation aktif.
5. White-label tidak rusak.
6. Cashless ledger aman.
7. SaaS operations aktif.
8. Native mobile companion API internal stabil.
9. Tidak ada bug P0/P1.
10. Working tree bersih atau semua perubahan diketahui.

Jika Phase 21 belum aman, hentikan Phase 22.

---

# 10. Buat Branch Git Phase 22

Jalankan:

```powershell
git checkout -b phase-22-external-api-partner-integration
```

Jika branch sudah ada:

```powershell
git checkout phase-22-external-api-partner-integration
```

---

# 11. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Console/
│   └── Commands/
│       ├── RetryFailedWebhooksCommand.php
│       ├── PruneApiRequestLogsCommand.php
│       └── RotateApiClientTokenCommand.php
├── Http/
│   ├── Controllers/
│   │   ├── DeveloperPortal/
│   │   │   ├── DeveloperPortalDashboardController.php
│   │   │   ├── ApiClientController.php
│   │   │   ├── ApiScopeController.php
│   │   │   ├── PartnerIntegrationController.php
│   │   │   ├── WebhookEndpointController.php
│   │   │   ├── WebhookDeliveryController.php
│   │   │   ├── ApiRequestLogController.php
│   │   │   └── ApiDocumentationPageController.php
│   │   └── Api/
│   │       └── V1/
│   │           ├── StudentApiController.php
│   │           ├── ClassRoomApiController.php
│   │           ├── AttendanceApiController.php
│   │           ├── TahfizhApiController.php
│   │           ├── FinanceApiController.php
│   │           ├── CashlessApiController.php
│   │           └── WebhookTestController.php
│   ├── Middleware/
│   │   ├── AuthenticateApiClient.php
│   │   ├── EnsureApiScope.php
│   │   ├── LogExternalApiRequest.php
│   │   └── ApplyApiRateLimit.php
│   └── Requests/
│       └── DeveloperPortal/
│           ├── StoreApiClientRequest.php
│           ├── UpdateApiClientRequest.php
│           ├── GenerateApiTokenRequest.php
│           ├── StorePartnerIntegrationRequest.php
│           ├── UpdatePartnerIntegrationRequest.php
│           ├── StoreWebhookEndpointRequest.php
│           ├── UpdateWebhookEndpointRequest.php
│           ├── ApiRequestLogFilterRequest.php
│           └── StoreApiDocumentationPageRequest.php
├── Models/
│   ├── ApiClient.php
│   ├── ApiClientToken.php
│   ├── ApiScope.php
│   ├── ApiRequestLog.php
│   ├── PartnerIntegration.php
│   ├── WebhookEndpoint.php
│   ├── WebhookDelivery.php
│   └── ApiDocumentationPage.php
└── Services/
    └── DeveloperPortal/
        ├── ApiClientTokenService.php
        ├── ApiScopeService.php
        ├── ApiAccessService.php
        ├── ApiRequestLogger.php
        ├── ApiResponseFormatter.php
        ├── PartnerIntegrationService.php
        ├── WebhookEventService.php
        ├── WebhookDeliveryService.php
        ├── WebhookSignatureService.php
        └── DeveloperPortalService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_api_clients_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_api_client_tokens_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_api_scopes_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_api_client_scope_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_api_request_logs_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_partner_integrations_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_webhook_endpoints_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_webhook_deliveries_table.php
│   └── xxxx_xx_xx_xxxxxx_create_api_documentation_pages_table.php
└── seeders/
    ├── ApiScopeSeeder.php
    └── ApiDocumentationPageSeeder.php

resources/
└── views/
    └── developer-portal/
        ├── dashboard.blade.php
        ├── api-clients/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        ├── api-scopes/
        │   ├── index.blade.php
        │   └── show.blade.php
        ├── partner-integrations/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        ├── webhooks/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   ├── edit.blade.php
        │   └── show.blade.php
        ├── webhook-deliveries/
        │   ├── index.blade.php
        │   └── show.blade.php
        ├── request-logs/
        │   ├── index.blade.php
        │   └── show.blade.php
        └── docs/
            ├── index.blade.php
            ├── show.blade.php
            └── edit.blade.php

routes/
├── web.php
└── api.php

docs/
├── phase-22-execution.md
├── phase-22-external-api-partner-integration.md
├── api-versioning-policy.md
├── api-security-policy.md
├── webhook-policy.md
└── partner-onboarding-checklist.md
```

---

# 12. Buat Model, Migration, Seeder, Controller, Request, Command

Jalankan:

```powershell
php artisan make:model ApiClient -m
php artisan make:model ApiClientToken -m
php artisan make:model ApiScope -m
php artisan make:migration create_api_client_scope_table
php artisan make:model ApiRequestLog -m
php artisan make:model PartnerIntegration -m
php artisan make:model WebhookEndpoint -m
php artisan make:model WebhookDelivery -m
php artisan make:model ApiDocumentationPage -m

php artisan make:seeder ApiScopeSeeder
php artisan make:seeder ApiDocumentationPageSeeder

php artisan make:controller DeveloperPortal/DeveloperPortalDashboardController
php artisan make:controller DeveloperPortal/ApiClientController --resource
php artisan make:controller DeveloperPortal/ApiScopeController
php artisan make:controller DeveloperPortal/PartnerIntegrationController --resource
php artisan make:controller DeveloperPortal/WebhookEndpointController --resource
php artisan make:controller DeveloperPortal/WebhookDeliveryController
php artisan make:controller DeveloperPortal/ApiRequestLogController
php artisan make:controller DeveloperPortal/ApiDocumentationPageController --resource

php artisan make:controller Api/V1/StudentApiController
php artisan make:controller Api/V1/ClassRoomApiController
php artisan make:controller Api/V1/AttendanceApiController
php artisan make:controller Api/V1/TahfizhApiController
php artisan make:controller Api/V1/FinanceApiController
php artisan make:controller Api/V1/CashlessApiController
php artisan make:controller Api/V1/WebhookTestController

php artisan make:middleware AuthenticateApiClient
php artisan make:middleware EnsureApiScope
php artisan make:middleware LogExternalApiRequest
php artisan make:middleware ApplyApiRateLimit

php artisan make:request DeveloperPortal/StoreApiClientRequest
php artisan make:request DeveloperPortal/UpdateApiClientRequest
php artisan make:request DeveloperPortal/GenerateApiTokenRequest
php artisan make:request DeveloperPortal/StorePartnerIntegrationRequest
php artisan make:request DeveloperPortal/UpdatePartnerIntegrationRequest
php artisan make:request DeveloperPortal/StoreWebhookEndpointRequest
php artisan make:request DeveloperPortal/UpdateWebhookEndpointRequest
php artisan make:request DeveloperPortal/ApiRequestLogFilterRequest
php artisan make:request DeveloperPortal/StoreApiDocumentationPageRequest

php artisan make:command RetryFailedWebhooksCommand
php artisan make:command PruneApiRequestLogsCommand
php artisan make:command RotateApiClientTokenCommand
```

Buat folder service:

```powershell
mkdir app\Services\DeveloperPortal
```

Buat file service:

```powershell
New-Item app\Services\DeveloperPortal\ApiClientTokenService.php
New-Item app\Services\DeveloperPortal\ApiScopeService.php
New-Item app\Services\DeveloperPortal\ApiAccessService.php
New-Item app\Services\DeveloperPortal\ApiRequestLogger.php
New-Item app\Services\DeveloperPortal\ApiResponseFormatter.php
New-Item app\Services\DeveloperPortal\PartnerIntegrationService.php
New-Item app\Services\DeveloperPortal\WebhookEventService.php
New-Item app\Services\DeveloperPortal\WebhookDeliveryService.php
New-Item app\Services\DeveloperPortal\WebhookSignatureService.php
New-Item app\Services\DeveloperPortal\DeveloperPortalService.php
```

---

# 13. Migration Schema

## 13.1 `api_clients`

Kolom wajib:

```text
id
school_id nullable foreign key
name
client_code unique
description nullable
owner_name nullable
owner_email nullable
status enum draft,active,suspended,revoked
rate_limit_per_minute default 60
allowed_ips json nullable
last_used_at nullable
created_by nullable
updated_by nullable
revoked_at nullable
revoked_by nullable
revoked_reason nullable
timestamps
softDeletes
```

Catatan:

1. `school_id` nullable agar super admin bisa membuat internal/global client.
2. Partner tenant harus punya `school_id`.
3. Global client hanya boleh untuk internal resmi.

---

## 13.2 `api_client_tokens`

Kolom wajib:

```text
id
api_client_id foreign key
token_name
token_prefix
token_hash
status enum active,revoked,expired
expires_at nullable
last_used_at nullable
created_by nullable
revoked_at nullable
revoked_by nullable
revoked_reason nullable
timestamps
softDeletes
```

Catatan:

1. Simpan `token_hash`, bukan token asli.
2. `token_prefix` untuk identifikasi token di UI.
3. Token asli hanya tampil sekali saat generate.

---

## 13.3 `api_scopes`

Kolom wajib:

```text
id
code unique
name
description nullable
category nullable
is_sensitive boolean default false
is_active boolean default true
sort_order default 0
timestamps
softDeletes
```

---

## 13.4 `api_client_scope`

Kolom wajib:

```text
id
api_client_id foreign key
api_scope_id foreign key
granted_by nullable
granted_at nullable
timestamps
unique api_client_id + api_scope_id
```

---

## 13.5 `api_request_logs`

Kolom wajib:

```text
id
api_client_id nullable foreign key
school_id nullable foreign key
request_id unique
method
path
scope_checked nullable
response_status unsigned small integer
ip_address nullable
user_agent nullable
duration_ms nullable
error_message nullable
created_at
```

Catatan:

1. Tidak perlu `updated_at`.
2. Jangan simpan full request body.
3. Bisa prune berkala.

---

## 13.6 `partner_integrations`

Kolom wajib:

```text
id
school_id nullable foreign key
api_client_id nullable foreign key
name
integration_type
provider_name nullable
status enum draft,active,suspended,disabled
configuration json nullable
notes nullable
approved_by nullable
approved_at nullable
created_by nullable
updated_by nullable
timestamps
softDeletes
```

---

## 13.7 `webhook_endpoints`

Kolom wajib:

```text
id
school_id nullable foreign key
api_client_id foreign key
name
url
secret_hash nullable
subscribed_events json
status enum active,inactive,suspended
last_success_at nullable
last_failure_at nullable
created_by nullable
updated_by nullable
timestamps
softDeletes
```

---

## 13.8 `webhook_deliveries`

Kolom wajib:

```text
id
webhook_endpoint_id foreign key
school_id nullable foreign key
event_type
payload json
status enum pending,delivered,failed,retrying,cancelled
attempt_count default 0
last_attempt_at nullable
next_retry_at nullable
response_status nullable
response_body_excerpt nullable
error_message nullable
created_at
updated_at
```

---

## 13.9 `api_documentation_pages`

Kolom wajib:

```text
id
slug unique
title
category nullable
content longText
visibility enum internal,partner,public
status enum draft,published,archived
sort_order default 0
created_by nullable
updated_by nullable
published_at nullable
timestamps
softDeletes
```

---

# 14. Model Relationships

## 14.1 `ApiClient`

Relasi:

```php
public function school(): BelongsTo
public function tokens(): HasMany
public function scopes(): BelongsToMany
public function requestLogs(): HasMany
public function webhooks(): HasMany
public function partnerIntegrations(): HasMany
```

## 14.2 `ApiClientToken`

Relasi:

```php
public function client(): BelongsTo
```

## 14.3 `ApiScope`

Relasi:

```php
public function clients(): BelongsToMany
```

## 14.4 `WebhookEndpoint`

Relasi:

```php
public function client(): BelongsTo
public function school(): BelongsTo
public function deliveries(): HasMany
```

## 14.5 `WebhookDelivery`

Relasi:

```php
public function endpoint(): BelongsTo
public function school(): BelongsTo
```

---

# 15. Service Rules

## 15.1 `ApiClientTokenService`

Wajib punya method:

```php
public function generate(ApiClient $client, array $data, User $actor): array
public function validateToken(string $plainToken): ?ApiClientToken
public function revoke(ApiClientToken $token, User $actor, string $reason): void
public function rotate(ApiClient $client, User $actor): array
```

Rules:

1. Generate token pakai random secure string.
2. Simpan hash token.
3. Return plain token hanya sekali.
4. Token revoked tidak valid.
5. Token expired tidak valid.
6. Update `last_used_at` saat valid.

---

## 15.2 `ApiAccessService`

Wajib punya method:

```php
public function canAccessTenant(ApiClient $client, int $schoolId): bool
public function hasScope(ApiClient $client, string $scope): bool
public function assertScope(ApiClient $client, string $scope): void
public function assertTenant(ApiClient $client, int $schoolId): void
```

Rules:

1. Client tenant hanya boleh akses `school_id` sendiri.
2. Global client hanya untuk internal resmi.
3. Scope wajib eksplisit.
4. Sensitive scope harus marked dan hanya super admin yang bisa assign.

---

## 15.3 `ApiResponseFormatter`

Response standar:

```json
{
  "success": true,
  "data": {},
  "meta": {
    "request_id": "...",
    "version": "v1"
  }
}
```

Error standar:

```json
{
  "success": false,
  "error": {
    "code": "forbidden",
    "message": "Access denied."
  },
  "meta": {
    "request_id": "...",
    "version": "v1"
  }
}
```

---

## 15.4 `WebhookSignatureService`

Wajib punya method:

```php
public function signPayload(array $payload, string $secret): string
public function verifySignature(string $payload, string $signature, string $secret): bool
```

Header webhook:

```text
X-HafizPlus-Event
X-HafizPlus-Delivery
X-HafizPlus-Signature
X-HafizPlus-Timestamp
```

---

## 15.5 `WebhookDeliveryService`

Wajib punya method:

```php
public function queueEvent(string $eventType, int $schoolId, array $payload): void
public function deliver(WebhookDelivery $delivery): void
public function retryFailed(): int
public function markFailed(WebhookDelivery $delivery, string $message): void
```

Rules:

1. Kirim hanya ke endpoint aktif.
2. Kirim hanya event yang subscribed.
3. Jangan retry tanpa batas.
4. Simpan response excerpt, bukan full response besar.
5. Jangan expose secret di log.

---

# 16. Middleware Rules

## 16.1 `AuthenticateApiClient`

Token dikirim via header:

```text
Authorization: Bearer {token}
```

Middleware harus:

1. Ambil token.
2. Validasi token hash.
3. Cek client aktif.
4. Cek token aktif.
5. Inject `apiClient` ke request.
6. Reject jika invalid.

---

## 16.2 `EnsureApiScope`

Penggunaan route:

```php
->middleware('api.scope:students:read')
```

Middleware harus:

1. Ambil client dari request.
2. Cek scope.
3. Return 403 jika tidak punya scope.

---

## 16.3 `LogExternalApiRequest`

Middleware harus:

1. Generate request ID.
2. Hitung durasi request.
3. Catat status response.
4. Catat error singkat jika ada.
5. Tidak menyimpan full body.

---

## 16.4 `ApplyApiRateLimit`

Middleware harus:

1. Gunakan limit per client.
2. Default 60 request/minute.
3. Return 429 jika melewati limit.

---

# 17. API Endpoint v1

Buat route di `routes/api.php`:

```php
Route::prefix('v1')
    ->middleware([
        'api.client',
        'api.rate_limit',
        'api.request_log',
    ])
    ->group(function (): void {
        Route::get('/students', [StudentApiController::class, 'index'])
            ->middleware('api.scope:students:read');

        Route::get('/students/{student}', [StudentApiController::class, 'show'])
            ->middleware('api.scope:students:read');

        Route::get('/classes', [ClassRoomApiController::class, 'index'])
            ->middleware('api.scope:classes:read');

        Route::get('/attendance-records', [AttendanceApiController::class, 'index'])
            ->middleware('api.scope:attendance:read');

        Route::post('/attendance-records', [AttendanceApiController::class, 'store'])
            ->middleware('api.scope:attendance:write');

        Route::get('/tahfizh/progress', [TahfizhApiController::class, 'progress'])
            ->middleware('api.scope:tahfizh:read');

        Route::get('/finance/bills', [FinanceApiController::class, 'bills'])
            ->middleware('api.scope:finance:read');

        Route::get('/cashless/transactions', [CashlessApiController::class, 'transactions'])
            ->middleware('api.scope:cashless:read');

        Route::post('/webhooks/test', [WebhookTestController::class, 'store'])
            ->middleware('api.scope:webhooks:manage');
    });
```

Catatan:

1. Sesuaikan alias middleware dengan implementasi Laravel 12 di project.
2. Jangan expose endpoint write selain attendance pada Phase 22.
3. Finance/cashless read-only dulu.
4. Student API harus minimal data.

---

# 18. Data Exposure Policy per Endpoint

## 18.1 Students Index

Field yang boleh keluar:

```json
{
  "id": 1,
  "school_id": 1,
  "class_room_id": 1,
  "nis": "...",
  "name": "...",
  "status": "active"
}
```

Jangan kirim:

1. Parent phone.
2. Parent email.
3. Internal notes.
4. Password/user credential.
5. Finance balance.
6. Cashless balance.

---

## 18.2 Attendance Records

Field yang boleh keluar:

```json
{
  "student_id": 1,
  "date": "2026-06-15",
  "status": "present",
  "check_in_at": "07:00:00",
  "check_out_at": "15:00:00"
}
```

---

## 18.3 Tahfizh Progress

Field yang boleh keluar:

```json
{
  "student_id": 1,
  "total_lines": 120,
  "current_page": 10,
  "current_line": 5,
  "target_status": "on_track"
}
```

Jangan kirim catatan guru sensitif kecuali scope khusus dibuat nanti.

---

## 18.4 Finance Bills

Field yang boleh keluar:

```json
{
  "student_id": 1,
  "bill_number": "INV-...",
  "status": "unpaid",
  "total_amount": "500000.00",
  "paid_amount": "0.00",
  "balance_amount": "500000.00",
  "due_date": "2026-07-10"
}
```

Jangan expose payment detail lengkap kecuali dibutuhkan.

---

## 18.5 Cashless Transactions

Field yang boleh keluar:

```json
{
  "student_id": 1,
  "transaction_number": "CLS-...",
  "type": "purchase",
  "amount": "15000.00",
  "posted_at": "2026-06-15 10:00:00"
}
```

Jangan expose PIN, token internal, or raw wallet lock data.

---

# 19. Developer Portal UI

Menu:

```text
Developer Portal
├── Dashboard
├── API Clients
├── API Scopes
├── Partner Integrations
├── Webhook Endpoints
├── Webhook Deliveries
├── API Request Logs
└── Documentation
```

Dashboard cards:

1. Active API clients.
2. Suspended clients.
3. Requests today.
4. Failed requests today.
5. Active webhooks.
6. Failed webhook deliveries.
7. Sensitive scopes assigned.
8. Top endpoints by usage.

---

# 20. Seeder

## 20.1 `ApiScopeSeeder`

Seed scope berikut:

```text
students:read
classes:read
attendance:read
attendance:write
tahfizh:read
finance:read
cashless:read
webhooks:manage
```

Sensitive scopes:

```text
finance:read
cashless:read
attendance:write
webhooks:manage
```

---

## 20.2 `ApiDocumentationPageSeeder`

Seed docs:

1. Getting Started.
2. Authentication.
3. API Versioning.
4. Rate Limits.
5. Error Format.
6. Students API.
7. Classes API.
8. Attendance API.
9. Tahfizh API.
10. Finance API.
11. Cashless API.
12. Webhooks.
13. Security Policy.

---

# 21. Commands

## 21.1 Retry Failed Webhooks

Command:

```powershell
php artisan app:retry-failed-webhooks
```

Rules:

1. Ambil delivery status `failed` atau `retrying`.
2. Cek `next_retry_at`.
3. Maksimal attempt 5.
4. Update status.
5. Laporkan jumlah sukses/gagal.

---

## 21.2 Prune API Request Logs

Command:

```powershell
php artisan app:prune-api-request-logs --days=90
```

Rules:

1. Hapus log lebih lama dari parameter days.
2. Default 90 hari.
3. Jangan hapus webhook delivery.
4. Tampilkan jumlah log yang dihapus.

---

## 21.3 Rotate API Client Token

Command:

```powershell
php artisan app:rotate-api-client-token {client}
```

Rules:

1. Revoke token aktif lama.
2. Generate token baru.
3. Tampilkan token baru hanya sekali di terminal.
4. Audit actor system.

---

# 22. Scheduler

Tambahkan ke scheduler jika sudah siap:

```php
Schedule::command('app:retry-failed-webhooks')->everyTenMinutes();
Schedule::command('app:prune-api-request-logs --days=90')->dailyAt('02:30');
```

Jika scheduler belum stabil, dokumentasikan manual run dulu.

---

# 23. Dokumentasi yang Wajib Dibuat

## 23.1 `docs/phase-22-external-api-partner-integration.md`

Isi:

1. Tujuan phase.
2. Scope.
3. Batasan.
4. Tabel yang dibuat.
5. Endpoint API.
6. Middleware.
7. Role access.
8. UAT checklist.

---

## 23.2 `docs/api-versioning-policy.md`

Isi:

1. Current version v1.
2. Breaking change policy.
3. Deprecation policy.
4. Response compatibility rule.
5. Version lifecycle.

---

## 23.3 `docs/api-security-policy.md`

Isi:

1. Token storage.
2. Scope policy.
3. Tenant isolation.
4. Rate limit.
5. Log policy.
6. Sensitive data policy.
7. Incident response jika token bocor.

---

## 23.4 `docs/webhook-policy.md`

Isi:

1. Event list.
2. Payload format.
3. Signature header.
4. Retry policy.
5. Failure handling.
6. Secret rotation.

---

## 23.5 `docs/partner-onboarding-checklist.md`

Isi:

1. Partner identity.
2. Legal agreement status.
3. Data scope approval.
4. Technical contact.
5. API client setup.
6. Token handover.
7. Webhook test.
8. Security verification.
9. Go-live approval.

---

# 24. Testing Manual

## 24.1 API Client Test

1. Super admin create API client.
2. Assign tenant.
3. Generate token.
4. Copy token satu kali.
5. Assign scope `students:read`.
6. Call `/api/v1/students`.
7. Pastikan hanya data tenant tersebut keluar.
8. Revoke token.
9. Call lagi harus gagal.

---

## 24.2 Scope Test

1. Client hanya punya `students:read`.
2. Call `/api/v1/students` harus sukses.
3. Call `/api/v1/finance/bills` harus 403.
4. Assign `finance:read`.
5. Call finance harus sukses.
6. Remove scope.
7. Call finance harus 403.

---

## 24.3 Tenant Isolation Test

1. Buat client tenant A.
2. Buat data tenant A dan tenant B.
3. Client tenant A call students.
4. Pastikan tenant B tidak muncul.
5. Coba request ID tenant B secara langsung.
6. Harus 403/404.

---

## 24.4 Rate Limit Test

1. Set rate limit client 5/minute.
2. Call endpoint 6 kali cepat.
3. Request ke-6 harus 429.
4. Log tetap tercatat.

---

## 24.5 Webhook Test

1. Buat webhook endpoint test.
2. Subscribe event `student.created`.
3. Trigger test webhook.
4. Delivery status harus `delivered` jika endpoint valid.
5. Ubah URL endpoint invalid.
6. Delivery status harus `failed`.
7. Jalankan retry command.
8. Attempt count bertambah.

---

## 24.6 Request Log Test

1. Call API sukses.
2. Call API gagal.
3. Cek request logs.
4. Pastikan method, path, status, duration, IP, client tercatat.
5. Pastikan body sensitif tidak tersimpan.

---

# 25. Bug Priority Phase 22

| Priority | Contoh | Keputusan |
|---|---|---|
| P0 | API client bisa akses tenant lain | Wajib fix sebelum selesai |
| P0 | Token tersimpan plain text | Wajib fix sebelum selesai |
| P0 | Scope tidak dicek | Wajib fix sebelum selesai |
| P0 | Parent/student bisa masuk Developer Portal | Wajib fix sebelum selesai |
| P1 | Rate limit tidak jalan | Wajib fix sebelum selesai |
| P1 | Request log tidak tercatat | Wajib fix sebelum selesai |
| P1 | Webhook mengirim secret/log sensitif | Wajib fix sebelum selesai |
| P2 | UI kurang rapi | Boleh dicatat |
| P3 | Copywriting docs kurang rapi | Boleh ditunda |

---

# 26. Definition of Done Phase 22

Phase 22 dianggap selesai jika:

1. Tabel API client dibuat.
2. Tabel API token dibuat.
3. Token disimpan hash.
4. Token asli hanya tampil sekali.
5. Tabel API scope dibuat.
6. Scope seeded.
7. Client-scope pivot berjalan.
8. API client bisa dibuat.
9. API token bisa generate/revoke/rotate.
10. Middleware auth API client berjalan.
11. Middleware scope berjalan.
12. Middleware request log berjalan.
13. Middleware rate limit berjalan.
14. Endpoint `/api/v1/students` berjalan.
15. Endpoint `/api/v1/classes` berjalan.
16. Endpoint `/api/v1/attendance-records` read berjalan.
17. Endpoint attendance write berjalan jika scope benar.
18. Endpoint tahfizh read berjalan.
19. Endpoint finance read berjalan.
20. Endpoint cashless read berjalan.
21. Tenant isolation API lolos test.
22. Parent/student tidak bisa akses Developer Portal.
23. API request logs tampil.
24. Partner integrations bisa dibuat.
25. Webhook endpoints bisa dibuat.
26. Webhook delivery log berjalan.
27. Retry webhook command berjalan.
28. Prune log command berjalan.
29. API documentation pages tersedia.
30. API security policy dibuat.
31. Webhook policy dibuat.
32. Partner onboarding checklist dibuat.
33. `php artisan migrate` berhasil.
34. `php artisan db:seed --class=ApiScopeSeeder` berhasil.
35. `php artisan db:seed --class=ApiDocumentationPageSeeder` berhasil.
36. `php artisan route:list` tidak error.
37. `php artisan app:system-health-check` berhasil.
38. `npm run build` berhasil.
39. `docs/project-progress.md` diupdate.
40. Tidak ada bug P0/P1.

---

# 27. Update Project Progress

Update file:

```text
docs/project-progress.md
```

Tambahkan:

```md
## Phase 22 — External API, Partner Integration & Developer Portal

Status: Done

Output:

- API Client Management selesai.
- API Token Management selesai.
- API Scope Management selesai.
- External API v1 selesai.
- API Request Logging selesai.
- API Rate Limiting selesai.
- Partner Integration Registry selesai.
- Webhook Endpoint Management selesai.
- Webhook Delivery Log selesai.
- Developer Portal selesai.
- API Documentation selesai.
- API Security Policy selesai.
- Webhook Policy selesai.
- Partner Onboarding Checklist selesai.

Catatan:

- Phase 22 tidak membuat payment gateway production.
- Phase 22 tidak membuat public developer marketplace.
- Phase 22 tidak membuat microservices rewrite.
- Semua API eksternal wajib tenant-aware, scope-based, logged, dan rate-limited.
```

---

# 28. Commit

Setelah semua selesai:

```powershell
git status
git add .
git commit -m "feat: add external api partner integration and developer portal"
```

---

# 29. Laporan Akhir ke User

Agent harus melaporkan:

1. File yang dibuat.
2. Migration yang dibuat.
3. Seeder yang dijalankan.
4. Endpoint API yang tersedia.
5. Middleware yang aktif.
6. Scope yang tersedia.
7. Webhook event yang tersedia.
8. Hasil test tenant isolation.
9. Hasil test token revoke.
10. Hasil test rate limit.
11. Hasil system health check.
12. Hasil npm build.
13. Catatan fitur yang sengaja belum dibuat.

Format akhir:

```text
Phase 22 selesai.

Yang dibuat:
- Developer Portal
- API Client Management
- API Token Management
- API Scope Management
- External API v1
- API Request Logs
- Partner Integration Registry
- Webhook Management
- API Documentation

Tidak dibuat:
- Payment gateway production
- Auto QRIS/VA
- Public developer marketplace
- Microservices rewrite

Validasi:
- php artisan migrate: OK
- ApiScopeSeeder: OK
- ApiDocumentationPageSeeder: OK
- route:list: OK
- app:system-health-check: OK
- npm run build: OK

Status: Ready for Phase 23 planning.
```

---

# 30. Setelah Phase 22

Jangan otomatis lanjut ke payment gateway.

Setelah Phase 22, lakukan:

1. Security review API token.
2. Tenant isolation audit khusus API.
3. Partner onboarding simulation.
4. Webhook retry test.
5. API documentation review.
6. Legal review untuk data sharing.
7. Rate limit stress test.
8. Integration sandbox test.

Phase 23 baru boleh dipilih berdasarkan kebutuhan nyata.

Rekomendasi Phase 23:

```text
Phase 23 — Payment Gateway Sandbox & Billing Automation Preparation
```

Tetapi Phase 23 belum boleh langsung production payment.

Phase 23 harus sandbox dulu.
