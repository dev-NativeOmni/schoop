# Phase 20 Execution Guide — Company/Product Scale & SaaS Operations

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
Phase 20 — Company/Product Scale & SaaS Operations
```

---

# 1. Keputusan Sebelum Phase 20

## 1.1 UAT Phase 19 Wajib

Sebelum menjalankan Phase 20, agent wajib memastikan **Phase 19 Cashless Kantin / Merchant POS** sudah aman.

Jangan membangun sistem scale, support, subscription, atau onboarding jika modul cashless masih bermasalah.

Phase 20 hanya boleh dieksekusi jika:

1. Merchant/kantin bisa login sesuai scope.
2. Merchant tidak bisa melihat transaksi merchant lain.
3. Student wallet balance benar.
4. Top up manual membuat ledger saldo yang benar.
5. Transaksi POS mengurangi saldo secara tepat.
6. Transaksi tidak bisa dobel karena refresh/retry.
7. Transaction PIN berjalan.
8. Refund berjalan dan membuat ledger pembalik.
9. Void/refund meninggalkan audit trail.
10. Daily settlement bisa dibuat.
11. Parent hanya melihat transaksi anak sendiri.
12. Student hanya melihat saldo/transaksi pribadi.
13. Admin bisa melihat laporan cashless sesuai tenant.
14. Tidak ada saldo negatif tanpa aturan eksplisit.
15. `php artisan app:system-health-check` berhasil.
16. `npm run build` berhasil.
17. Tidak ada bug P0/P1 terbuka.

Jika masih ada error P0/P1, hentikan Phase 20 dan buat bug fix sprint dulu.

---

## 1.2 Klasifikasi Bug Sebelum Phase 20

| Prioritas | Contoh Bug | Keputusan |
|---|---|---|
| P0 | Data tenant bocor, saldo wallet salah, transaksi dobel, refund merusak saldo, parent melihat anak lain | Wajib fix sebelum Phase 20 |
| P1 | Settlement salah, laporan merchant salah, transaksi lambat, audit trail tidak lengkap | Wajib fix sebelum Phase 20 |
| P2 | UI kurang rapi, wording kurang jelas, filter kurang nyaman | Boleh dicatat |
| P3 | Enhancement kosmetik | Boleh ditunda |

---

# 2. Tujuan Phase 20

Phase 20 bertujuan mengubah HafizPlus dari aplikasi besar yang sudah lengkap menjadi produk SaaS yang bisa dijual, di-onboard, didukung, diaudit, dan dioperasikan secara profesional.

Fokus Phase 20:

1. SaaS operations dashboard.
2. Subscription plan manual.
3. School subscription management.
4. Tenant invoice manual untuk sekolah.
5. Implementation/onboarding project per sekolah.
6. Onboarding checklist per sekolah.
7. Support ticket system.
8. SLA policy dan SLA tracking.
9. Incident report system.
10. Release note management.
11. Product announcement internal.
12. Knowledge base sederhana.
13. Customer success notes.
14. Usage snapshot per tenant.
15. School health score.
16. Go-to-market documentation.
17. Legal/compliance documentation checklist.
18. Product scale documentation.

Phase 20 bukan menambah modul santri baru.

Phase ini membuat lapisan operasional perusahaan dan produk.

---

# 3. Batasan Phase 20

AI agent tidak boleh membuat fitur berikut pada Phase 20:

1. Payment gateway SaaS otomatis.
2. Auto debit.
3. Kartu kredit.
4. Virtual account otomatis.
5. QRIS otomatis untuk subscription.
6. Bank reconciliation otomatis.
7. E-sign contract.
8. CRM eksternal.
9. WhatsApp gateway massal.
10. Push notification massal.
11. Email marketing automation.
12. Native Android/iOS baru.
13. Rebuild UI besar-besaran.
14. LMS penuh.
15. Marketplace konten.
16. AI tutor.
17. AI tahsin voice correction.
18. Payroll karyawan.
19. Accounting penuh.
20. Pajak otomatis.
21. HRIS internal.
22. Call center integration.
23. External analytics tracker yang mengirim data siswa.
24. Data warehouse kompleks.
25. Microservices rewrite.

Phase 20 tetap Laravel monolith yang terkontrol.

Semua billing pada Phase 20 bersifat **manual record**, bukan payment automation.

---

# 4. Prinsip Utama Phase 20

## 4.1 Produk Sudah Masuk Tahap Operasional

Setelah Phase 0–19, sistem sudah mencakup:

1. Tahfizh.
2. Parent portal.
3. Student portal.
4. Notification.
5. Export.
6. Mutabaah.
7. QR attendance.
8. Tahsin.
9. Finance ledger.
10. Boarding school management.
11. SchoolOS.
12. Multi-tenant.
13. White-label.
14. Cashless POS.

Phase 20 tidak bertujuan menambah fitur sekolah baru.

Tujuan Phase 20 adalah membuat produk bisa ditawarkan ke sekolah lain dengan proses yang rapi.

---

## 4.2 Jangan Operasikan SaaS Tanpa Support System

Produk sekolah menyimpan data anak, orang tua, guru, transaksi, dan aktivitas harian.

Jika sudah dijual ke sekolah lain, harus ada:

1. Onboarding checklist.
2. Dokumentasi implementasi.
3. Support ticket.
4. SLA response.
5. Incident report.
6. Release note.
7. Customer success log.
8. Subscription status.
9. Tenant health dashboard.
10. Backup dan security checklist.

Tanpa ini, produk akan terlihat besar tapi operasionalnya rapuh.

---

## 4.3 Billing SaaS Manual Dulu

Phase 20 boleh membuat pencatatan subscription sekolah, tetapi belum payment gateway.

Yang boleh:

1. Paket subscription.
2. Status subscription.
3. Invoice manual untuk sekolah.
4. Record pembayaran manual subscription.
5. Due date.
6. Grace period.
7. Internal note.
8. Export invoice sederhana.

Yang belum boleh:

1. Auto charge.
2. Callback payment gateway.
3. Payment link otomatis.
4. QRIS dinamis.
5. Virtual account otomatis.
6. Suspend otomatis tanpa review manual.

---

## 4.4 Data Sekolah Tetap Terisolasi

Karena Phase 17 sudah membuat multi-tenant foundation, semua fitur Phase 20 wajib menjaga isolasi tenant.

Aturan keras:

1. Super Admin bisa melihat semua tenant.
2. Staff internal HafizPlus hanya bisa melihat tenant sesuai izin.
3. Admin sekolah hanya melihat subscription/support/onboarding sekolahnya sendiri jika route dibuka untuk sekolah.
4. Parent/student/guru tidak boleh mengakses dashboard SaaS operations.
5. Support ticket dari sekolah tidak boleh terlihat oleh sekolah lain.
6. Usage snapshot tidak boleh membocorkan data siswa lintas tenant.

---

# 5. Konsep Domain Phase 20

## 5.1 Subscription Plan

Subscription plan adalah paket layanan HafizPlus untuk sekolah.

Contoh paket:

| Plan | Isi |
|---|---|
| Basic | Tahfizh + Parent Portal |
| Standard | Tahfizh + Parent + Report + Notification |
| Pro | Tambah Mutabaah + Attendance + Tahsin |
| Enterprise | Multi-campus + White-label + Boarding + Finance + Cashless |

Plan tidak otomatis mengaktifkan payment gateway.

Plan hanya menentukan hak layanan dan catatan komersial.

---

## 5.2 School Subscription

School subscription adalah status langganan sekolah.

Status:

| Status | Makna |
|---|---|
| `trial` | Uji coba |
| `active` | Aktif |
| `grace_period` | Lewat jatuh tempo tapi masih diberi akses |
| `suspended` | Akses dibatasi manual |
| `cancelled` | Dibatalkan |
| `expired` | Berakhir |

Phase 20 tidak membuat suspend otomatis yang bisa mematikan operasional sekolah tanpa review.

---

## 5.3 Tenant Invoice Manual

Tenant invoice adalah tagihan bisnis HafizPlus ke sekolah.

Contoh:

1. Setup fee.
2. Subscription bulanan.
3. Subscription tahunan.
4. White-label setup fee.
5. Support premium.
6. Training tambahan.
7. Custom development.

Invoice ini berbeda dari `student_bills` pada Phase 14.

`student_bills` = tagihan santri kepada sekolah.

`tenant_invoices` = tagihan HafizPlus kepada sekolah.

---

## 5.4 Implementation Project

Implementation project adalah proses onboarding sekolah baru.

Tahapan contoh:

| Stage | Keterangan |
|---|---|
| `lead` | Calon sekolah |
| `demo_scheduled` | Jadwal demo |
| `proposal_sent` | Penawaran dikirim |
| `agreement` | Setuju implementasi |
| `data_collection` | Kumpulkan data sekolah |
| `configuration` | Konfigurasi tenant |
| `training` | Training admin/guru |
| `pilot` | Uji coba data kecil |
| `go_live` | Live terbatas/production |
| `handover` | Serah terima support |

---

## 5.5 Support Ticket

Support ticket adalah kanal pelaporan masalah dari sekolah atau internal team.

Kategori:

| Category | Contoh |
|---|---|
| `bug` | Error aplikasi |
| `access` | Masalah login/role |
| `data_correction` | Perbaikan data |
| `training` | Butuh panduan |
| `feature_request` | Permintaan fitur |
| `billing` | Pertanyaan invoice subscription |
| `incident` | Gangguan besar |

Prioritas:

| Priority | Target |
|---|---|
| `critical` | Gangguan data/security/transaksi |
| `high` | Fitur utama tidak jalan |
| `medium` | Bug mengganggu tapi ada workaround |
| `low` | Minor issue/enhancement |

---

## 5.6 Incident Report

Incident report dipakai untuk kejadian serius.

Contoh incident:

1. Data tenant bocor.
2. Login massal gagal.
3. Cashless POS down.
4. Saldo wallet salah.
5. Backup gagal.
6. Export data sensitif terkirim salah.
7. Server production down.
8. Migration production gagal.

Incident wajib punya:

1. Severity.
2. Timeline.
3. Impact.
4. Root cause.
5. Mitigation.
6. Corrective action.
7. Owner.
8. Status.

---

## 5.7 Customer Health Score

Health score membantu melihat sekolah mana yang sehat atau berisiko churn.

Sumber data:

1. Jumlah login admin/guru.
2. Jumlah input tahfizh.
3. Jumlah parent aktif.
4. Jumlah support ticket terbuka.
5. Status subscription.
6. Jumlah incident.
7. Kelengkapan onboarding.
8. Aktivasi module.

Skor sederhana:

| Score | Status |
|---:|---|
| 80–100 | Healthy |
| 60–79 | Watch |
| 40–59 | Risk |
| 0–39 | Critical |

---

# 6. Target Output Phase 20

Setelah Phase 20 selesai, aplikasi harus punya:

1. Menu **SaaS Operations**.
2. Menu **Product Scale Dashboard**.
3. Menu **Subscription Plans**.
4. Menu **School Subscriptions**.
5. Menu **Tenant Invoices**.
6. Menu **Implementation Projects**.
7. Menu **Onboarding Checklists**.
8. Menu **Support Tickets**.
9. Menu **SLA Policies**.
10. Menu **Incident Reports**.
11. Menu **Release Notes**.
12. Menu **Knowledge Base**.
13. Menu **Customer Success Notes**.
14. Menu **Usage Snapshots**.
15. Tabel:
    - `saas_subscription_plans`
    - `saas_school_subscriptions`
    - `saas_tenant_invoices`
    - `saas_tenant_invoice_items`
    - `saas_tenant_payments`
    - `implementation_projects`
    - `onboarding_checklist_items`
    - `onboarding_checklist_records`
    - `support_tickets`
    - `support_ticket_messages`
    - `sla_policies`
    - `incident_reports`
    - `release_notes`
    - `knowledge_base_articles`
    - `customer_success_notes`
    - `product_usage_snapshots`
16. Model:
    - `SaasSubscriptionPlan`
    - `SaasSchoolSubscription`
    - `SaasTenantInvoice`
    - `SaasTenantInvoiceItem`
    - `SaasTenantPayment`
    - `ImplementationProject`
    - `OnboardingChecklistItem`
    - `OnboardingChecklistRecord`
    - `SupportTicket`
    - `SupportTicketMessage`
    - `SlaPolicy`
    - `IncidentReport`
    - `ReleaseNote`
    - `KnowledgeBaseArticle`
    - `CustomerSuccessNote`
    - `ProductUsageSnapshot`
17. Controller:
    - `ProductScaleDashboardController`
    - `SaasSubscriptionPlanController`
    - `SaasSchoolSubscriptionController`
    - `SaasTenantInvoiceController`
    - `ImplementationProjectController`
    - `OnboardingChecklistController`
    - `SupportTicketController`
    - `SlaPolicyController`
    - `IncidentReportController`
    - `ReleaseNoteController`
    - `KnowledgeBaseArticleController`
    - `CustomerSuccessNoteController`
    - `ProductUsageSnapshotController`
18. Request:
    - Store/update request untuk setiap domain utama.
19. Service:
    - `SaasOperationsAccessService`
    - `SubscriptionStatusService`
    - `TenantBillingService`
    - `OnboardingWorkflowService`
    - `SupportTicketService`
    - `SupportSlaService`
    - `IncidentResponseService`
    - `ReleaseManagementService`
    - `KnowledgeBaseService`
    - `CustomerSuccessHealthService`
    - `UsageSnapshotService`
20. Command:
    - `php artisan app:capture-product-usage-snapshots`
    - `php artisan app:check-support-sla-breaches`
    - `php artisan app:generate-tenant-invoices --dry-run`
21. Seeder:
    - `SaasSubscriptionPlanSeeder`
    - `SlaPolicySeeder`
    - `OnboardingChecklistItemSeeder`
22. Dokumentasi:
    - `docs/phase-20-company-product-scale.md`
    - `docs/saas-operations-playbook.md`
    - `docs/customer-onboarding-playbook.md`
    - `docs/support-sla-policy.md`
    - `docs/incident-response-playbook.md`
    - `docs/release-management-policy.md`
    - `docs/go-to-market-checklist.md`
    - `docs/legal-compliance-checklist.md`
    - update `docs/project-progress.md`

---

# 7. Role Access Phase 20

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
merchant
support_staff
customer_success
sales
operations_manager
```

Jika role internal belum ada, agent boleh membuat role internal baru lewat seeder:

1. `support_staff`
2. `customer_success`
3. `sales`
4. `operations_manager`

Jangan mengubah role lama secara destruktif.

| Role | SaaS Dashboard | Subscription | Invoice Tenant | Onboarding | Support | Incident | Release | Knowledge Base |
|---|---|---|---|---|---|---|---|---|
| Super Admin | Semua | CRUD | CRUD | CRUD | CRUD | CRUD | CRUD | CRUD |
| Operations Manager | Semua | Manage | Manage | Manage | Manage | Manage | Manage | CRUD |
| Customer Success | Tenant assigned | Read | Read | Manage | Manage | Read | Read | CRUD |
| Support Staff | Tenant assigned | Tidak | Tidak | Read | Manage | Create incident | Read | CRUD draft |
| Sales | Pipeline/lead | Read | Create draft | Create | Read | Tidak | Read | Read |
| Admin Sekolah | Sekolah sendiri jika route dibuka | Read own | Read own | Read own | Create ticket | Read own incident summary | Read public | Read public |
| Kepala Sekolah | Sekolah sendiri jika route dibuka | Read own | Read own | Read own | Create ticket | Read own incident summary | Read public | Read public |
| Teacher/Guru | Tidak | Tidak | Tidak | Tidak | Create ticket opsional | Tidak | Read public | Read public |
| Parent | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Read public jika portal | Read public jika portal |
| Student | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Merchant | Tidak | Tidak | Tidak | Tidak | Create ticket merchant | Tidak | Read public jika merchant portal | Read public jika merchant portal |

Aturan keras:

1. Parent/student tidak boleh masuk SaaS operations.
2. Merchant tidak boleh melihat subscription sekolah.
3. Admin sekolah tidak boleh melihat subscription sekolah lain.
4. Support staff tidak boleh melihat invoice tenant kecuali diberi izin eksplisit.
5. Sales tidak boleh melihat data siswa detail.
6. Customer success tidak boleh melihat data siswa detail kecuali lewat aggregate/health score.
7. Semua fitur Phase 20 wajib memakai tenant scope.
8. Semua perubahan invoice, subscription, incident, dan support ticket harus punya audit trail minimal.

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
19. Phase 17 selesai.
20. Phase 18 selesai.
21. Phase 19 selesai dan UAT aman.
22. Tabel multi-tenant tersedia.
23. Tabel white-label tersedia.
24. Tabel cashless tersedia.
25. Working tree bersih atau semua perubahan diketahui.

Jika Phase 19 belum aman, hentikan Phase 20.

---

# 9. Buat Branch Git Phase 20

Jalankan:

```powershell
git checkout -b phase-20-company-product-scale
```

Jika branch sudah ada:

```powershell
git checkout phase-20-company-product-scale
```

---

# 10. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Console/
│   └── Commands/
│       ├── CaptureProductUsageSnapshotsCommand.php
│       ├── CheckSupportSlaBreachesCommand.php
│       └── GenerateTenantInvoicesCommand.php
├── Http/
│   ├── Controllers/
│   │   └── SaasOps/
│   │       ├── ProductScaleDashboardController.php
│   │       ├── SaasSubscriptionPlanController.php
│   │       ├── SaasSchoolSubscriptionController.php
│   │       ├── SaasTenantInvoiceController.php
│   │       ├── ImplementationProjectController.php
│   │       ├── OnboardingChecklistController.php
│   │       ├── SupportTicketController.php
│   │       ├── SlaPolicyController.php
│   │       ├── IncidentReportController.php
│   │       ├── ReleaseNoteController.php
│   │       ├── KnowledgeBaseArticleController.php
│   │       ├── CustomerSuccessNoteController.php
│   │       └── ProductUsageSnapshotController.php
│   └── Requests/
│       └── SaasOps/
│           ├── StoreSaasSubscriptionPlanRequest.php
│           ├── UpdateSaasSubscriptionPlanRequest.php
│           ├── StoreSaasSchoolSubscriptionRequest.php
│           ├── UpdateSaasSchoolSubscriptionRequest.php
│           ├── StoreSaasTenantInvoiceRequest.php
│           ├── StoreSaasTenantPaymentRequest.php
│           ├── StoreImplementationProjectRequest.php
│           ├── UpdateImplementationProjectRequest.php
│           ├── StoreSupportTicketRequest.php
│           ├── StoreSupportTicketMessageRequest.php
│           ├── UpdateSupportTicketStatusRequest.php
│           ├── StoreSlaPolicyRequest.php
│           ├── StoreIncidentReportRequest.php
│           ├── UpdateIncidentReportRequest.php
│           ├── StoreReleaseNoteRequest.php
│           ├── StoreKnowledgeBaseArticleRequest.php
│           └── StoreCustomerSuccessNoteRequest.php
├── Models/
│   ├── SaasSubscriptionPlan.php
│   ├── SaasSchoolSubscription.php
│   ├── SaasTenantInvoice.php
│   ├── SaasTenantInvoiceItem.php
│   ├── SaasTenantPayment.php
│   ├── ImplementationProject.php
│   ├── OnboardingChecklistItem.php
│   ├── OnboardingChecklistRecord.php
│   ├── SupportTicket.php
│   ├── SupportTicketMessage.php
│   ├── SlaPolicy.php
│   ├── IncidentReport.php
│   ├── ReleaseNote.php
│   ├── KnowledgeBaseArticle.php
│   ├── CustomerSuccessNote.php
│   └── ProductUsageSnapshot.php
└── Services/
    └── SaasOps/
        ├── SaasOperationsAccessService.php
        ├── SubscriptionStatusService.php
        ├── TenantBillingService.php
        ├── OnboardingWorkflowService.php
        ├── SupportTicketService.php
        ├── SupportSlaService.php
        ├── IncidentResponseService.php
        ├── ReleaseManagementService.php
        ├── KnowledgeBaseService.php
        ├── CustomerSuccessHealthService.php
        └── UsageSnapshotService.php

database/
├── migrations/
│   ├── xxxx_xx_xx_xxxxxx_create_saas_subscription_plans_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_saas_school_subscriptions_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_saas_tenant_invoices_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_saas_tenant_invoice_items_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_saas_tenant_payments_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_implementation_projects_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_onboarding_checklist_items_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_onboarding_checklist_records_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_support_tickets_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_support_ticket_messages_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_sla_policies_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_incident_reports_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_release_notes_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_knowledge_base_articles_table.php
│   ├── xxxx_xx_xx_xxxxxx_create_customer_success_notes_table.php
│   └── xxxx_xx_xx_xxxxxx_create_product_usage_snapshots_table.php
└── seeders/
    ├── SaasSubscriptionPlanSeeder.php
    ├── SlaPolicySeeder.php
    └── OnboardingChecklistItemSeeder.php

resources/
└── views/
    └── saas-ops/
        ├── dashboard.blade.php
        ├── subscription-plans/
        ├── school-subscriptions/
        ├── tenant-invoices/
        ├── implementation-projects/
        ├── onboarding/
        ├── support-tickets/
        ├── sla-policies/
        ├── incident-reports/
        ├── release-notes/
        ├── knowledge-base/
        ├── customer-success/
        └── usage-snapshots/

routes/
└── web.php

docs/
├── phase-20-execution.md
├── phase-20-company-product-scale.md
├── saas-operations-playbook.md
├── customer-onboarding-playbook.md
├── support-sla-policy.md
├── incident-response-playbook.md
├── release-management-policy.md
├── go-to-market-checklist.md
└── legal-compliance-checklist.md
```

---

# 11. Buat Model, Migration, Controller, Request, Command, dan Seeder

Jalankan:

```powershell
php artisan make:model SaasSubscriptionPlan -m
php artisan make:model SaasSchoolSubscription -m
php artisan make:model SaasTenantInvoice -m
php artisan make:model SaasTenantInvoiceItem -m
php artisan make:model SaasTenantPayment -m
php artisan make:model ImplementationProject -m
php artisan make:model OnboardingChecklistItem -m
php artisan make:model OnboardingChecklistRecord -m
php artisan make:model SupportTicket -m
php artisan make:model SupportTicketMessage -m
php artisan make:model SlaPolicy -m
php artisan make:model IncidentReport -m
php artisan make:model ReleaseNote -m
php artisan make:model KnowledgeBaseArticle -m
php artisan make:model CustomerSuccessNote -m
php artisan make:model ProductUsageSnapshot -m

php artisan make:controller SaasOps/ProductScaleDashboardController
php artisan make:controller SaasOps/SaasSubscriptionPlanController --resource
php artisan make:controller SaasOps/SaasSchoolSubscriptionController
php artisan make:controller SaasOps/SaasTenantInvoiceController
php artisan make:controller SaasOps/ImplementationProjectController
php artisan make:controller SaasOps/OnboardingChecklistController
php artisan make:controller SaasOps/SupportTicketController
php artisan make:controller SaasOps/SlaPolicyController --resource
php artisan make:controller SaasOps/IncidentReportController
php artisan make:controller SaasOps/ReleaseNoteController
php artisan make:controller SaasOps/KnowledgeBaseArticleController
php artisan make:controller SaasOps/CustomerSuccessNoteController
php artisan make:controller SaasOps/ProductUsageSnapshotController

php artisan make:command CaptureProductUsageSnapshotsCommand
php artisan make:command CheckSupportSlaBreachesCommand
php artisan make:command GenerateTenantInvoicesCommand

php artisan make:seeder SaasSubscriptionPlanSeeder
php artisan make:seeder SlaPolicySeeder
php artisan make:seeder OnboardingChecklistItemSeeder
```

Buat folder service:

```powershell
mkdir app\Services\SaasOps
```

Buat file service:

```powershell
New-Item app\Services\SaasOps\SaasOperationsAccessService.php
New-Item app\Services\SaasOps\SubscriptionStatusService.php
New-Item app\Services\SaasOps\TenantBillingService.php
New-Item app\Services\SaasOps\OnboardingWorkflowService.php
New-Item app\Services\SaasOps\SupportTicketService.php
New-Item app\Services\SaasOps\SupportSlaService.php
New-Item app\Services\SaasOps\IncidentResponseService.php
New-Item app\Services\SaasOps\ReleaseManagementService.php
New-Item app\Services\SaasOps\KnowledgeBaseService.php
New-Item app\Services\SaasOps\CustomerSuccessHealthService.php
New-Item app\Services\SaasOps\UsageSnapshotService.php
```

---

# 12. Migration Schema Ringkas

## 12.1 `saas_subscription_plans`

Kolom wajib:

```text
id
code unique
name
billing_cycle monthly|yearly|one_time
price decimal(15,2)
currency default IDR
max_students nullable
max_users nullable
included_modules json nullable
is_active boolean
sort_order integer
description text nullable
created_at
updated_at
soft_deletes
```

---

## 12.2 `saas_school_subscriptions`

Kolom wajib:

```text
id
school_id foreign
saas_subscription_plan_id foreign
status trial|active|grace_period|suspended|cancelled|expired
started_at nullable
trial_ends_at nullable
current_period_starts_at nullable
current_period_ends_at nullable
cancelled_at nullable
suspended_at nullable
suspension_reason text nullable
internal_notes text nullable
created_by foreign users nullable
updated_by foreign users nullable
created_at
updated_at
soft_deletes
```

Index:

```text
school_id
saas_subscription_plan_id
status
current_period_ends_at
```

---

## 12.3 `saas_tenant_invoices`

Kolom wajib:

```text
id
school_id foreign
saas_school_subscription_id foreign nullable
invoice_number unique
status draft|issued|paid|partial|void|overdue
issue_date date
due_date date nullable
paid_at datetime nullable
subtotal decimal(15,2)
discount_amount decimal(15,2) default 0
tax_amount decimal(15,2) default 0
total_amount decimal(15,2)
paid_amount decimal(15,2) default 0
balance_amount decimal(15,2)
notes text nullable
void_reason text nullable
voided_at datetime nullable
voided_by foreign users nullable
created_by foreign users nullable
created_at
updated_at
soft_deletes
```

---

## 12.4 `saas_tenant_invoice_items`

Kolom wajib:

```text
id
saas_tenant_invoice_id foreign
description string
quantity decimal(12,2) default 1
unit_price decimal(15,2)
total_price decimal(15,2)
created_at
updated_at
```

---

## 12.5 `saas_tenant_payments`

Kolom wajib:

```text
id
school_id foreign
saas_tenant_invoice_id foreign
payment_number unique
payment_date date
amount decimal(15,2)
method cash|bank_transfer|qris_external|adjustment
reference_number nullable
status posted|void
notes text nullable
void_reason text nullable
voided_at datetime nullable
voided_by foreign users nullable
created_by foreign users nullable
created_at
updated_at
soft_deletes
```

Catatan:

`qris_external` hanya catatan manual. Jangan buat QRIS otomatis.

---

## 12.6 `implementation_projects`

Kolom wajib:

```text
id
school_id foreign nullable
project_code unique
school_name_snapshot string
contact_name nullable
contact_phone nullable
contact_email nullable
stage lead|demo_scheduled|proposal_sent|agreement|data_collection|configuration|training|pilot|go_live|handover|closed|lost
status open|blocked|done|cancelled
target_go_live_date date nullable
actual_go_live_date date nullable
owner_user_id foreign nullable
notes text nullable
created_at
updated_at
soft_deletes
```

---

## 12.7 `onboarding_checklist_items`

Kolom wajib:

```text
id
code unique
title
category data|configuration|training|testing|go_live|handover
is_required boolean
sort_order integer
description text nullable
created_at
updated_at
soft_deletes
```

---

## 12.8 `onboarding_checklist_records`

Kolom wajib:

```text
id
implementation_project_id foreign
onboarding_checklist_item_id foreign
status pending|in_progress|done|skipped|blocked
completed_at datetime nullable
completed_by foreign users nullable
notes text nullable
created_at
updated_at
```

Unique:

```text
implementation_project_id + onboarding_checklist_item_id
```

---

## 12.9 `support_tickets`

Kolom wajib:

```text
id
school_id foreign nullable
submitted_by_user_id foreign nullable
assigned_to_user_id foreign nullable
ticket_number unique
subject string
category bug|access|data_correction|training|feature_request|billing|incident|other
priority low|medium|high|critical
status open|waiting_customer|in_progress|resolved|closed|cancelled
first_response_due_at datetime nullable
resolution_due_at datetime nullable
first_responded_at datetime nullable
resolved_at datetime nullable
closed_at datetime nullable
sla_breached boolean default false
description text
internal_notes text nullable
created_at
updated_at
soft_deletes
```

---

## 12.10 `support_ticket_messages`

Kolom wajib:

```text
id
support_ticket_id foreign
user_id foreign nullable
visibility public|internal
message text
created_at
updated_at
soft_deletes
```

---

## 12.11 `sla_policies`

Kolom wajib:

```text
id
name
priority low|medium|high|critical
first_response_minutes integer
resolution_minutes integer
is_active boolean
created_at
updated_at
soft_deletes
```

---

## 12.12 `incident_reports`

Kolom wajib:

```text
id
school_id foreign nullable
incident_number unique
title
severity sev1|sev2|sev3|sev4
status open|investigating|mitigated|resolved|closed
started_at datetime
resolved_at datetime nullable
owner_user_id foreign nullable
impact_summary text
root_cause text nullable
mitigation text nullable
corrective_action text nullable
timeline json nullable
created_at
updated_at
soft_deletes
```

---

## 12.13 `release_notes`

Kolom wajib:

```text
id
version string
release_date date
status draft|published|archived
title
summary text
changes json nullable
breaking_changes text nullable
published_by foreign users nullable
published_at datetime nullable
created_at
updated_at
soft_deletes
```

---

## 12.14 `knowledge_base_articles`

Kolom wajib:

```text
id
slug unique
title
category getting_started|admin|teacher|parent|student|finance|cashless|troubleshooting|release|other
visibility internal|school_admin|public
status draft|published|archived
content longText
published_at datetime nullable
created_by foreign users nullable
updated_by foreign users nullable
created_at
updated_at
soft_deletes
```

---

## 12.15 `customer_success_notes`

Kolom wajib:

```text
id
school_id foreign
user_id foreign nullable
note_type call|meeting|training|check_in|risk|renewal|other
summary string
content text nullable
follow_up_date date nullable
created_at
updated_at
soft_deletes
```

---

## 12.16 `product_usage_snapshots`

Kolom wajib:

```text
id
school_id foreign
snapshot_date date
active_users_count integer default 0
teacher_login_count integer default 0
parent_login_count integer default 0
hafalan_records_count integer default 0
mutabaah_records_count integer default 0
attendance_records_count integer default 0
tahsin_assessments_count integer default 0
finance_transactions_count integer default 0
cashless_transactions_count integer default 0
open_support_tickets_count integer default 0
health_score integer default 0
health_status healthy|watch|risk|critical
raw_metrics json nullable
created_at
updated_at
```

Unique:

```text
school_id + snapshot_date
```

---

# 13. Service Rules

## 13.1 `SaasOperationsAccessService`

Wajib menyediakan method:

```php
canAccessSaasOps(User $user): bool
canManageSubscriptions(User $user): bool
canManageTenantInvoices(User $user): bool
canManageSupport(User $user): bool
canManageIncidents(User $user): bool
canAccessSchoolOps(User $user, int $schoolId): bool
```

Aturan:

1. Super admin semua akses.
2. Operations manager akses operasional penuh.
3. Support staff akses support dan incident terbatas.
4. Customer success akses tenant assigned.
5. Sales akses pipeline/implementation terbatas.
6. Admin sekolah hanya akses self-service jika route dibuka.

---

## 13.2 `SubscriptionStatusService`

Wajib menangani:

1. Trial status.
2. Active status.
3. Grace period.
4. Manual suspend.
5. Manual cancel.
6. Expiry warning.
7. Tidak melakukan suspend otomatis tanpa flag eksplisit.

Method minimal:

```php
activate(SaasSchoolSubscription $subscription): SaasSchoolSubscription
markGracePeriod(SaasSchoolSubscription $subscription): SaasSchoolSubscription
suspend(SaasSchoolSubscription $subscription, string $reason, User $actor): SaasSchoolSubscription
cancel(SaasSchoolSubscription $subscription, string $reason, User $actor): SaasSchoolSubscription
isAccessible(SaasSchoolSubscription $subscription): bool
```

---

## 13.3 `TenantBillingService`

Wajib menangani:

1. Generate draft invoice.
2. Issue invoice.
3. Post manual payment.
4. Update paid/balance amount.
5. Void invoice.
6. Void payment.
7. Tidak menghapus invoice/payment.
8. Tidak memanggil payment gateway.

---

## 13.4 `OnboardingWorkflowService`

Wajib menangani:

1. Membuat project onboarding.
2. Membuat checklist records dari master item.
3. Mengubah stage.
4. Menandai checklist selesai.
5. Menghitung progress persentase.
6. Mencegah go-live jika required checklist belum selesai.

---

## 13.5 `SupportTicketService`

Wajib menangani:

1. Membuat ticket number.
2. Set priority.
3. Assign support staff.
4. Add message.
5. Resolve ticket.
6. Close ticket.
7. Reopen ticket.
8. Membuat incident dari ticket critical.

---

## 13.6 `SupportSlaService`

Wajib menangani:

1. Menentukan first response due date.
2. Menentukan resolution due date.
3. Menandai SLA breach.
4. Menyediakan list ticket yang mendekati breach.
5. Menyediakan command check harian/jam-an.

---

## 13.7 `IncidentResponseService`

Wajib menangani:

1. Membuat incident number.
2. Update severity.
3. Update status.
4. Tambah timeline event.
5. Catat root cause.
6. Catat corrective action.
7. Close incident.

---

## 13.8 `CustomerSuccessHealthService`

Wajib menghitung health score dari:

1. Activity usage.
2. Support ticket volume.
3. Open critical issue.
4. Subscription status.
5. Onboarding completion.
6. Incident count.

Score awal boleh sederhana.

Jangan membuat machine learning.

---

# 14. Routes

Tambahkan route group:

```php
Route::middleware(['auth'])->prefix('saas-ops')->name('saas-ops.')->group(function (): void {
    Route::get('/dashboard', [ProductScaleDashboardController::class, 'index'])->name('dashboard');

    Route::resource('subscription-plans', SaasSubscriptionPlanController::class);
    Route::get('school-subscriptions', [SaasSchoolSubscriptionController::class, 'index'])->name('school-subscriptions.index');
    Route::get('school-subscriptions/{subscription}', [SaasSchoolSubscriptionController::class, 'show'])->name('school-subscriptions.show');
    Route::post('school-subscriptions/{subscription}/activate', [SaasSchoolSubscriptionController::class, 'activate'])->name('school-subscriptions.activate');
    Route::post('school-subscriptions/{subscription}/suspend', [SaasSchoolSubscriptionController::class, 'suspend'])->name('school-subscriptions.suspend');
    Route::post('school-subscriptions/{subscription}/cancel', [SaasSchoolSubscriptionController::class, 'cancel'])->name('school-subscriptions.cancel');

    Route::get('tenant-invoices', [SaasTenantInvoiceController::class, 'index'])->name('tenant-invoices.index');
    Route::get('tenant-invoices/create', [SaasTenantInvoiceController::class, 'create'])->name('tenant-invoices.create');
    Route::post('tenant-invoices', [SaasTenantInvoiceController::class, 'store'])->name('tenant-invoices.store');
    Route::get('tenant-invoices/{invoice}', [SaasTenantInvoiceController::class, 'show'])->name('tenant-invoices.show');
    Route::post('tenant-invoices/{invoice}/issue', [SaasTenantInvoiceController::class, 'issue'])->name('tenant-invoices.issue');
    Route::post('tenant-invoices/{invoice}/payments', [SaasTenantInvoiceController::class, 'storePayment'])->name('tenant-invoices.payments.store');
    Route::post('tenant-invoices/{invoice}/void', [SaasTenantInvoiceController::class, 'void'])->name('tenant-invoices.void');

    Route::resource('implementation-projects', ImplementationProjectController::class);
    Route::get('implementation-projects/{project}/onboarding', [OnboardingChecklistController::class, 'show'])->name('onboarding.show');
    Route::post('implementation-projects/{project}/onboarding/{record}/complete', [OnboardingChecklistController::class, 'complete'])->name('onboarding.complete');

    Route::resource('support-tickets', SupportTicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('support-tickets/{ticket}/messages', [SupportTicketController::class, 'storeMessage'])->name('support-tickets.messages.store');
    Route::post('support-tickets/{ticket}/assign', [SupportTicketController::class, 'assign'])->name('support-tickets.assign');
    Route::post('support-tickets/{ticket}/resolve', [SupportTicketController::class, 'resolve'])->name('support-tickets.resolve');
    Route::post('support-tickets/{ticket}/close', [SupportTicketController::class, 'close'])->name('support-tickets.close');

    Route::resource('sla-policies', SlaPolicyController::class);
    Route::resource('incident-reports', IncidentReportController::class);
    Route::resource('release-notes', ReleaseNoteController::class);
    Route::resource('knowledge-base', KnowledgeBaseArticleController::class);
    Route::resource('customer-success-notes', CustomerSuccessNoteController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('usage-snapshots', [ProductUsageSnapshotController::class, 'index'])->name('usage-snapshots.index');
});
```

Sesuaikan import controller di `routes/web.php`.

---

# 15. Navigation

Tambahkan menu internal:

```text
SaaS Operations
├── Product Scale Dashboard
├── Subscription Plans
├── School Subscriptions
├── Tenant Invoices
├── Implementation Projects
├── Support Tickets
├── Incidents
├── Release Notes
├── Knowledge Base
├── Customer Success
└── Usage Snapshots
```

Menu ini hanya tampil untuk:

1. Super Admin.
2. Operations Manager.
3. Support Staff.
4. Customer Success.
5. Sales untuk route yang relevan.

Jangan tampilkan menu ini kepada parent/student.

---

# 16. Seeder

## 16.1 `SaasSubscriptionPlanSeeder`

Isi paket awal:

| Code | Name | Billing Cycle | Notes |
|---|---|---|---|
| `basic` | Basic | monthly/yearly | Tahfizh + Parent Portal |
| `standard` | Standard | monthly/yearly | Reports + Notification |
| `pro` | Pro | monthly/yearly | Mutabaah + Attendance + Tahsin |
| `enterprise` | Enterprise | yearly | Multi-campus + White-label + Boarding + Finance + Cashless |

Harga boleh pakai placeholder `0` atau angka internal.

Jangan hardcode harga final jika belum ada keputusan bisnis.

---

## 16.2 `SlaPolicySeeder`

Default:

| Priority | First Response | Resolution |
|---|---:|---:|
| critical | 30 menit | 4 jam |
| high | 2 jam | 1 hari kerja |
| medium | 1 hari kerja | 3 hari kerja |
| low | 2 hari kerja | 7 hari kerja |

---

## 16.3 `OnboardingChecklistItemSeeder`

Checklist awal:

1. Data sekolah diterima.
2. Data admin sekolah diterima.
3. Data kelas diterima.
4. Data guru diterima.
5. Data santri diterima.
6. Data orang tua diterima.
7. Tenant dibuat.
8. Module aktif dikonfigurasi.
9. Branding dasar dikonfigurasi.
10. Admin training selesai.
11. Guru training selesai.
12. Parent portal diuji.
13. Tahfizh workflow diuji.
14. Report diuji.
15. Backup diuji.
16. Go-live approval diterima.
17. Handover ke support selesai.

---

# 17. Commands

## 17.1 Capture Usage Snapshots

Command:

```powershell
php artisan app:capture-product-usage-snapshots
```

Tugas:

1. Loop semua school aktif.
2. Hitung aktivitas harian.
3. Hitung health score.
4. Simpan ke `product_usage_snapshots`.
5. Jangan menyimpan nama siswa/orang tua di raw metrics.

---

## 17.2 Check SLA Breaches

Command:

```powershell
php artisan app:check-support-sla-breaches
```

Tugas:

1. Cari ticket open/in progress.
2. Cek first response due.
3. Cek resolution due.
4. Tandai `sla_breached = true` jika lewat batas.
5. Jangan kirim WhatsApp/email otomatis pada Phase 20.

---

## 17.3 Generate Tenant Invoices

Command:

```powershell
php artisan app:generate-tenant-invoices --dry-run
```

Tugas:

1. Cari subscription yang perlu ditagih.
2. Generate draft invoice jika bukan dry-run.
3. Tidak mengirim tagihan otomatis.
4. Tidak memproses payment otomatis.
5. Log hasil ke console.

---

# 18. Scheduler

Tambahkan ke `routes/console.php` atau scheduler Laravel 12 project:

```php
Schedule::command('app:capture-product-usage-snapshots')->dailyAt('23:30');
Schedule::command('app:check-support-sla-breaches')->hourly();
```

Jangan schedule generate tenant invoice otomatis dulu kecuali sudah siap secara bisnis.

---

# 19. Dokumentasi Wajib

## 19.1 `docs/saas-operations-playbook.md`

Isi minimal:

1. Role internal.
2. Alur subscription.
3. Alur invoice tenant.
4. Alur support ticket.
5. Alur incident.
6. Alur release note.
7. Alur customer success.
8. Daily operation checklist.
9. Weekly operation checklist.
10. Monthly operation checklist.

---

## 19.2 `docs/customer-onboarding-playbook.md`

Isi minimal:

1. Tahap lead ke demo.
2. Tahap proposal.
3. Tahap agreement.
4. Data yang harus dikumpulkan.
5. Checklist konfigurasi tenant.
6. Checklist training admin.
7. Checklist training guru.
8. Checklist pilot.
9. Checklist go-live.
10. Handover support.

---

## 19.3 `docs/support-sla-policy.md`

Isi minimal:

1. Definisi priority.
2. Target first response.
3. Target resolution.
4. Escalation path.
5. Apa yang masuk incident.
6. Apa yang bukan support scope.
7. Jam operasional support.
8. Template respons.

---

## 19.4 `docs/incident-response-playbook.md`

Isi minimal:

1. Definisi incident.
2. Severity SEV1–SEV4.
3. Incident commander.
4. Langkah mitigasi awal.
5. Komunikasi ke sekolah.
6. Root cause analysis.
7. Postmortem.
8. Corrective action.

---

## 19.5 `docs/release-management-policy.md`

Isi minimal:

1. Branching release.
2. Versioning.
3. Staging validation.
4. Production deploy checklist.
5. Rollback rule.
6. Release note format.
7. Hotfix rule.
8. Regression test checklist.

---

## 19.6 `docs/go-to-market-checklist.md`

Isi minimal:

1. ICP sekolah.
2. Paket harga internal.
3. Demo script.
4. Proposal template outline.
5. Pilot program.
6. Training plan.
7. Success metric sekolah.
8. Renewal process.

---

## 19.7 `docs/legal-compliance-checklist.md`

Isi minimal:

1. Terms of service draft checklist.
2. Privacy policy draft checklist.
3. DPA / perjanjian pemrosesan data checklist.
4. Data retention policy.
5. Data deletion request process.
6. Backup retention process.
7. Access control policy.
8. Incident notification process.
9. School data export process.
10. Admin responsibility note.

Catatan:

Dokumen legal ini bukan nasihat hukum final. Harus direview konsultan hukum sebelum dipakai komersial.

---

# 20. Testing Manual

## 20.1 Subscription

Tes:

1. Super admin membuat plan.
2. Super admin membuat subscription sekolah.
3. Status trial tampil.
4. Status active tampil.
5. Status grace period tampil.
6. Suspend manual membutuhkan alasan.
7. Cancel manual membutuhkan alasan.
8. Admin sekolah tidak melihat subscription sekolah lain.

---

## 20.2 Tenant Invoice

Tes:

1. Buat draft invoice.
2. Tambah invoice item.
3. Issue invoice.
4. Record payment manual.
5. Payment partial mengubah status partial.
6. Payment penuh mengubah status paid.
7. Void invoice membutuhkan alasan.
8. Void payment membutuhkan alasan.
9. Invoice sekolah A tidak terlihat sekolah B.

---

## 20.3 Onboarding

Tes:

1. Buat implementation project.
2. Generate checklist records.
3. Checklist required bisa diselesaikan.
4. Checklist blocked bisa diberi catatan.
5. Progress percentage benar.
6. Go-live ditolak jika required checklist belum selesai.
7. Go-live berhasil jika semua required checklist selesai.

---

## 20.4 Support Ticket

Tes:

1. Buat ticket sebagai internal.
2. Buat ticket sebagai admin sekolah jika route self-service dibuka.
3. Ticket number unique.
4. Assign support staff.
5. Tambah public message.
6. Tambah internal note.
7. Resolve ticket.
8. Close ticket.
9. SLA breach terdeteksi command.
10. Ticket sekolah A tidak terlihat sekolah B.

---

## 20.5 Incident

Tes:

1. Buat incident SEV1.
2. Tambah timeline.
3. Update mitigation.
4. Update root cause.
5. Update corrective action.
6. Resolve incident.
7. Close incident.
8. Incident terkait sekolah hanya bisa dilihat sesuai izin.

---

## 20.6 Usage Snapshot

Tes:

1. Jalankan command snapshot.
2. Snapshot dibuat per sekolah.
3. Health score muncul.
4. Tidak ada nama siswa/orang tua di `raw_metrics`.
5. Dashboard menampilkan status healthy/watch/risk/critical.

---

# 21. Definition of Done Phase 20

Phase 20 selesai jika:

1. Migration berhasil.
2. Seeder berhasil.
3. Route SaaS Operations tersedia.
4. SaaS operations dashboard tampil.
5. Subscription plan bisa dibuat.
6. School subscription bisa dibuat.
7. Status subscription bisa diubah manual.
8. Tenant invoice bisa dibuat.
9. Tenant payment manual bisa dicatat.
10. Invoice/payment bisa divoid dengan alasan.
11. Implementation project bisa dibuat.
12. Onboarding checklist bisa dipakai.
13. Support ticket bisa dibuat.
14. Support ticket message bisa dibuat.
15. SLA policy berjalan.
16. SLA breach command berjalan.
17. Incident report bisa dibuat.
18. Release note bisa dibuat.
19. Knowledge base bisa dibuat.
20. Customer success note bisa dibuat.
21. Usage snapshot command berjalan.
22. Health score tenant tampil.
23. Parent/student tidak bisa mengakses SaaS operations.
24. School admin tidak bisa melihat tenant lain.
25. Support staff tidak bisa melihat invoice tanpa izin.
26. Sales tidak bisa melihat data siswa detail.
27. `php artisan app:system-health-check` berhasil.
28. `npm run build` berhasil.
29. Dokumentasi Phase 20 dibuat.
30. `docs/project-progress.md` diperbarui.

---

# 22. Update `docs/project-progress.md`

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
| 19 | Cashless Kantin / Merchant POS | Done |
| 20 | Company/Product Scale & SaaS Operations | Done |
```

---

# 23. Commit Phase 20

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add company product scale and saas operations"
```

Jika remote tersedia:

```powershell
git push origin phase-20-company-product-scale
```

---

# 24. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 20 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- SaaS Operations Dashboard
- Subscription Plan Management
- School Subscription Management
- Tenant Invoice Manual
- Tenant Payment Manual
- Implementation Project
- Onboarding Checklist
- Support Ticket System
- SLA Policy
- SLA Breach Checker
- Incident Report
- Release Notes
- Knowledge Base
- Customer Success Notes
- Product Usage Snapshot
- Tenant Health Score
- Role-based internal operations access
- Tenant-scoped support access

Tabel dibuat:
- saas_subscription_plans
- saas_school_subscriptions
- saas_tenant_invoices
- saas_tenant_invoice_items
- saas_tenant_payments
- implementation_projects
- onboarding_checklist_items
- onboarding_checklist_records
- support_tickets
- support_ticket_messages
- sla_policies
- incident_reports
- release_notes
- knowledge_base_articles
- customer_success_notes
- product_usage_snapshots

Command dibuat:
- app:capture-product-usage-snapshots
- app:check-support-sla-breaches
- app:generate-tenant-invoices

Dokumentasi dibuat:
- phase-20-company-product-scale.md
- saas-operations-playbook.md
- customer-onboarding-playbook.md
- support-sla-policy.md
- incident-response-playbook.md
- release-management-policy.md
- go-to-market-checklist.md
- legal-compliance-checklist.md

Belum dibuat:
- Payment gateway subscription otomatis
- Auto debit
- Virtual account otomatis
- QRIS otomatis subscription
- E-sign contract
- CRM eksternal
- Email marketing automation
- Data warehouse kompleks
- Microservices rewrite

Status:
- HafizPlus School Platform siap masuk tahap pilot komersial dan operational scale dengan proses support, onboarding, incident, release, dan customer success yang rapi.
```

---

# 25. Larangan Setelah Phase 20

Agent harus berhenti setelah Phase 20 selesai.

Jangan lanjut membuat:

1. Payment gateway otomatis.
2. Auto billing production.
3. CRM eksternal.
4. Email marketing automation.
5. E-sign legal contract.
6. Data warehouse.
7. Microservices rewrite.
8. Native app rewrite.
9. AI/ML analytics.
10. Fitur baru di luar scope Phase 20.

Semua itu masuk roadmap lanjutan setelah pilot komersial tervalidasi.

---

# 26. Keputusan Akhir Phase 20

Phase 20 adalah fase penutup untuk membuat HafizPlus School Platform siap diskalakan sebagai produk SaaS sekolah Islam.

Produk tidak lagi hanya dinilai dari jumlah fitur.

Produk dinilai dari:

1. Stabilitas production.
2. Keamanan tenant.
3. Keakuratan data uang.
4. Kemudahan onboarding sekolah.
5. Kecepatan support.
6. Kejelasan SLA.
7. Kerapian release.
8. Kejelasan incident response.
9. Health score pelanggan.
10. Dokumentasi operasional.

Setelah Phase 20, prioritas bukan langsung coding fitur baru.

Prioritas berikutnya:

1. Pilot komersial 1–3 sekolah.
2. Perbaikan berdasarkan data penggunaan nyata.
3. Review legal dan privacy policy.
4. Audit keamanan tenant.
5. Review pricing.
6. Training support/internal team.
7. Stabilkan SLA.
8. Baru susun roadmap Phase 21 jika ada kebutuhan nyata dari sekolah.
