# Phase 23 Execution Guide — Advanced Analytics & Executive Intelligence

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
Phase 23 — Advanced Analytics & Executive Intelligence
```

---

# 1. Posisi Phase 23

Phase 23 adalah **growth extension**, bukan fase wajib core roadmap.

Roadmap utama sudah melewati:

1. Core tahfizh.
2. Parent/student portal.
3. Notification.
4. Reports/export.
5. Production hardening.
6. Mutabaah.
7. QR attendance.
8. Tahsin.
9. Student finance ledger.
10. SchoolOS Mini.
11. Boarding.
12. Multi-tenant.
13. White-label.
14. Cashless POS.
15. SaaS operations.
16. Native mobile companion apps.
17. External API dan partner integration.

Phase 23 tidak boleh dipakai untuk menambah modul besar baru secara liar.

Phase 23 bertujuan membuat HafizPlus mulai memiliki **decision intelligence layer**:

1. Data ringkasan lintas modul.
2. Dashboard eksekutif.
3. Health score sekolah.
4. Tren akademik dan operasional.
5. Analisis usage tenant.
6. Analisis support/SLA.
7. Analisis finance/cashless risk.
8. Laporan eksekutif periodik.
9. Metric dictionary.
10. Privacy-safe analytics.

---

# 2. Keputusan Sebelum Phase 23

## 2.1 UAT Phase 22 Wajib

Sebelum menjalankan Phase 23, agent wajib memastikan Phase 22 sudah aman.

Jangan membuat analytics layer jika API external/partner integration masih berisiko.

Phase 23 hanya boleh dieksekusi jika:

1. Phase 22 External API berjalan.
2. API key/token partner aman.
3. Partner scope tidak bocor lintas tenant.
4. API rate limit berjalan.
5. API audit log berjalan.
6. Developer portal tidak menampilkan secret.
7. Endpoint external tidak membocorkan data siswa.
8. Tenant isolation tetap aman.
9. Parent/student ownership tetap aman.
10. Cashless ledger tetap aman.
11. `php artisan app:system-health-check` berhasil.
12. `npm run build` berhasil.
13. Tidak ada bug P0/P1 terbuka.

Jika masih ada bug P0/P1, hentikan Phase 23 dan lakukan bug fix sprint.

---

## 2.2 Klasifikasi Bug Sebelum Phase 23

| Prioritas | Contoh Bug | Keputusan |
|---|---|---|
| P0 | Tenant data leak, API partner melihat data sekolah lain, saldo cashless salah, parent melihat anak lain | Wajib fix sebelum Phase 23 |
| P1 | API audit log tidak lengkap, support SLA salah, health score salah ekstrem, report finance salah | Wajib fix sebelum Phase 23 |
| P2 | Tampilan dashboard kurang rapi, filter kurang nyaman, label chart kurang jelas | Boleh dicatat |
| P3 | Enhancement kosmetik, warna chart, layout minor | Boleh ditunda |

---

# 3. Tujuan Phase 23

Phase 23 bertujuan membuat **Advanced Analytics & Executive Intelligence**.

Tujuan besarnya:

1. Menyediakan dashboard eksekutif untuk internal HafizPlus.
2. Menyediakan dashboard eksekutif untuk masing-masing sekolah.
3. Membuat tenant health score berbasis data penggunaan dan operasional.
4. Membuat analytics snapshot harian/bulanan.
5. Membuat trend analysis untuk tahfizh, mutabaah, attendance, tahsin, finance, cashless, support, dan mobile/API usage.
6. Membuat laporan eksekutif bulanan yang bisa diekspor.
7. Membuat metric dictionary agar semua angka punya definisi jelas.
8. Membuat privacy guard agar analytics tidak membocorkan data sensitif.
9. Membuat access log untuk dashboard analytics.
10. Membuat command capture analytics snapshot.

Phase 23 tidak membuat AI autopilot.

Phase 23 tidak membuat prediksi otomatis yang mengambil keputusan untuk sekolah.

Phase 23 hanya membuat data intelligence yang membantu manusia mengambil keputusan.

---

# 4. Batasan Phase 23

AI agent tidak boleh membuat fitur berikut pada Phase 23:

1. Data warehouse terpisah.
2. Microservices analytics.
3. External BI SaaS integration default.
4. Mengirim data siswa ke Google Analytics, Mixpanel, Amplitude, Meta Pixel, atau tool external tanpa approval.
5. Machine learning prediction otomatis.
6. AI recommendation engine otomatis.
7. AI yang menilai kualitas hafalan/tahsin.
8. Auto suspend tenant berdasarkan health score.
9. Auto intervention ke siswa/orang tua.
10. Auto billing action.
11. Auto refund/cashless action.
12. Auto WhatsApp blast.
13. Auto email marketing.
14. Data mining lintas tenant yang terlihat oleh sekolah.
15. Cross-tenant benchmark yang membuka identitas sekolah lain.
16. Raw export semua siswa lintas tenant.
17. Public analytics dashboard.
18. Public API analytics tanpa authorization.
19. Native mobile analytics SDK tambahan.
20. Full LMS.
21. Payment gateway.
22. Payroll.
23. Accounting full suite.
24. CRM eksternal.
25. BI drag-and-drop builder kompleks.

Phase 23 harus tetap memakai:

1. Laravel 12.
2. MySQL.
3. Blade.
4. Eloquent.
5. Service classes.
6. Scheduled commands.
7. Privacy-safe aggregate tables.

---

# 5. Prinsip Phase 23

## 5.1 Analytics Harus Privacy-Safe

Analytics tidak boleh menjadi celah kebocoran data.

Aturan keras:

1. Dashboard internal boleh melihat agregat lintas tenant.
2. Sekolah hanya boleh melihat analytics tenant sendiri.
3. Parent hanya boleh melihat ringkasan anak sendiri jika nanti dibuka, tetapi Phase 23 tidak fokus ke parent analytics.
4. Student hanya boleh melihat data sendiri jika nanti dibuka, tetapi Phase 23 tidak fokus ke student analytics.
5. Merchant hanya boleh melihat analytics merchant sendiri.
6. Support staff tidak otomatis boleh melihat semua data siswa.
7. Analytics snapshot tidak boleh menyimpan password, PIN, token, API key, secret, full request payload, atau data sensitif yang tidak perlu.
8. Access ke analytics harus diaudit.

---

## 5.2 Analytics Harus Explainable

Setiap angka harus bisa dijelaskan.

Contoh buruk:

```text
Health Score = 78
```

Contoh benar:

```text
Health Score = 78
Komponen:
- Usage score: 30/40
- Academic activity score: 18/25
- Parent engagement score: 10/15
- Support risk score: 8/10
- Incident risk score: 6/10
- Subscription status score: 6/10
```

Agent wajib membuat metric dictionary.

---

## 5.3 Snapshot, Bukan Query Berat Setiap Dashboard

Dashboard eksekutif tidak boleh terus-menerus melakukan query berat ke banyak tabel live.

Gunakan pendekatan:

1. Capture snapshot harian.
2. Simpan hasil agregat.
3. Dashboard membaca tabel snapshot.
4. Drill-down terbatas boleh query live dengan pagination.
5. Query besar harus dipindahkan ke command/scheduler.

---

## 5.4 Analytics Tidak Mengganti Audit Log

Analytics bukan audit log.

Audit log menjawab:

```text
Siapa melakukan apa, kapan, terhadap data apa.
```

Analytics menjawab:

```text
Apa tren, pola, risiko, dan ringkasan performa.
```

Jangan mengganti audit log dengan analytics snapshot.

---

## 5.5 Analytics Tidak Mengambil Keputusan Otomatis

Phase 23 tidak boleh membuat sistem yang otomatis:

1. Menonaktifkan tenant.
2. Menahan akun siswa.
3. Mengirim teguran ke orang tua.
4. Mengubah nilai.
5. Mengubah saldo.
6. Mengubah tagihan.
7. Mengubah status subscription.
8. Mengirim pesan massal.

Analytics hanya memberi insight dan rekomendasi manual.

---

# 6. Domain Analytics Phase 23

## 6.1 Internal Executive Analytics

Untuk Super Admin, Operations Manager, Customer Success, dan Founder/Product Owner.

Data yang ditampilkan:

1. Jumlah tenant aktif.
2. Tenant trial.
3. Tenant grace period.
4. Tenant suspended.
5. Tenant onboarding.
6. Tenant health score distribution.
7. Module adoption.
8. Active users harian/mingguan/bulanan.
9. Parent engagement.
10. Teacher input activity.
11. Mobile app usage summary.
12. API usage summary.
13. Support ticket load.
14. SLA breach rate.
15. Incident count.
16. Revenue manual summary dari tenant invoices.
17. Cashless transaction health.
18. Top risk tenants.
19. Top growing tenants.
20. Monthly executive summary.

---

## 6.2 School Executive Analytics

Untuk Admin Sekolah dan Kepala Sekolah.

Data yang ditampilkan hanya untuk tenant sendiri:

1. Jumlah santri aktif.
2. Jumlah guru aktif.
3. Jumlah parent aktif.
4. Tahfizh progress trend.
5. Santri tertinggal target.
6. Guru aktif input.
7. Mutabaah completion trend.
8. Attendance trend.
9. Late/absent trend.
10. Tahsin level distribution.
11. Finance outstanding summary.
12. Cashless transaction summary.
13. Notification read rate.
14. Mobile app adoption.
15. Module usage status.
16. Support tickets submitted.
17. School health score.
18. Monthly school executive report.

---

## 6.3 Academic Analytics

Fokus Qur'an dan pembelajaran:

1. Tahfizh total lines submitted.
2. Tahfizh target achievement rate.
3. Average daily lines per student.
4. Hafalan debt trend.
5. Students needing attention.
6. Teacher input consistency.
7. Tahsin assessment trend.
8. Tahsin skill weakness distribution.
9. Mutabaah completion rate.
10. Mutabaah activity low completion.

---

## 6.4 Operational Analytics

Fokus operasional sekolah:

1. Attendance present rate.
2. Late rate.
3. Absent rate.
4. Sick/permission trend.
5. Boarding roll call risk summary jika Phase 16 aktif.
6. Leave request trend jika boarding aktif.
7. Health log count jika boarding aktif.
8. Discipline log count jika boarding aktif.

---

## 6.5 Finance & Cashless Analytics

Fokus uang, tetapi tetap aggregate dan safe.

Finance Ledger:

1. Total bills issued.
2. Total paid.
3. Outstanding balance.
4. Overdue bills.
5. Payment method distribution.
6. Void count.
7. Manual adjustment count.

Cashless POS:

1. Total top-up.
2. Total purchase.
3. Total refund.
4. Void count.
5. Merchant sales summary.
6. Wallet frozen count.
7. Negative balance anomaly count.
8. Idempotency conflict count jika tercatat.
9. Settlement pending count.

Aturan keras:

1. Jangan tampilkan saldo detail semua siswa lintas tenant kepada internal non-authorized.
2. Jangan tampilkan PIN/token wallet.
3. Jangan expose ledger raw tanpa role finance/super admin.
4. Dashboard analytics harus aggregate by tenant/period/module.

---

## 6.6 Support & SLA Analytics

Dari Phase 20 SaaS Operations:

1. Open tickets.
2. Tickets by priority.
3. Tickets by category.
4. First response SLA breach.
5. Resolution SLA breach.
6. Average first response time.
7. Average resolution time.
8. Incidents by severity.
9. Repeated issue pattern.
10. Tenant support risk.

---

## 6.7 Mobile & API Analytics

Dari Phase 21 dan 22:

Mobile:

1. Registered devices.
2. Active devices.
3. Platform distribution Android/iOS.
4. App version distribution.
5. Force update required count.
6. Parent mobile usage.
7. Teacher mobile usage.
8. Student mobile usage.
9. Merchant mobile usage jika aktif.

API:

1. Partner applications count.
2. API requests by partner.
3. API requests by endpoint group.
4. API error rate.
5. Rate limit hits.
6. Failed auth attempts.
7. Webhook delivery success/failure.
8. Top partner integration risk.

---

# 7. Target Output Phase 23

Setelah Phase 23 selesai, aplikasi harus punya:

1. Menu **Analytics**.
2. Menu **Executive Dashboard**.
3. Menu **School Analytics**.
4. Menu **Tenant Health**.
5. Menu **Academic Analytics**.
6. Menu **Operational Analytics**.
7. Menu **Finance Analytics**.
8. Menu **Support Analytics**.
9. Menu **Mobile & API Analytics**.
10. Menu **Executive Reports**.
11. Menu **Metric Dictionary**.
12. Tabel analytics snapshot.
13. Service analytics privacy guard.
14. Scheduled command capture snapshot.
15. Command generate monthly executive report.
16. Analytics access log.
17. Dokumentasi metric dictionary.
18. Dokumentasi privacy policy analytics.
19. Update `docs/project-progress.md`.

---

# 8. Database Tables

Phase 23 membuat tabel berikut:

1. `analytics_metric_definitions`
2. `analytics_snapshots`
3. `tenant_health_scores`
4. `tenant_health_score_components`
5. `school_academic_snapshots`
6. `school_operational_snapshots`
7. `school_finance_snapshots`
8. `school_support_snapshots`
9. `mobile_api_usage_snapshots`
10. `executive_report_runs`
11. `executive_report_sections`
12. `analytics_access_logs`

---

# 9. Model yang Dibuat

Buat model:

1. `AnalyticsMetricDefinition`
2. `AnalyticsSnapshot`
3. `TenantHealthScore`
4. `TenantHealthScoreComponent`
5. `SchoolAcademicSnapshot`
6. `SchoolOperationalSnapshot`
7. `SchoolFinanceSnapshot`
8. `SchoolSupportSnapshot`
9. `MobileApiUsageSnapshot`
10. `ExecutiveReportRun`
11. `ExecutiveReportSection`
12. `AnalyticsAccessLog`

---

# 10. Controller yang Dibuat

Buat controller di namespace `Analytics`:

1. `ExecutiveAnalyticsDashboardController`
2. `SchoolAnalyticsDashboardController`
3. `TenantHealthAnalyticsController`
4. `AcademicAnalyticsController`
5. `OperationalAnalyticsController`
6. `FinanceAnalyticsController`
7. `SupportAnalyticsController`
8. `MobileApiAnalyticsController`
9. `ExecutiveReportController`
10. `MetricDictionaryController`

---

# 11. Request yang Dibuat

Buat Form Request:

1. `AnalyticsDateRangeRequest`
2. `SchoolAnalyticsFilterRequest`
3. `TenantHealthFilterRequest`
4. `GenerateExecutiveReportRequest`
5. `StoreMetricDefinitionRequest`
6. `UpdateMetricDefinitionRequest`

---

# 12. Service yang Dibuat

Buat service di `app/Services/Analytics`:

1. `AnalyticsAccessService`
2. `AnalyticsPrivacyGuard`
3. `AnalyticsPeriodResolver`
4. `MetricDictionaryService`
5. `AnalyticsSnapshotService`
6. `TenantHealthScoreService`
7. `AcademicAnalyticsService`
8. `OperationalAnalyticsService`
9. `FinanceAnalyticsService`
10. `SupportAnalyticsService`
11. `MobileApiAnalyticsService`
12. `ExecutiveDashboardService`
13. `SchoolAnalyticsDashboardService`
14. `ExecutiveReportService`
15. `AnalyticsAccessLogger`

---

# 13. Commands yang Dibuat

Buat Artisan commands:

1. `app:capture-analytics-snapshots`
2. `app:generate-executive-report`
3. `app:analytics-health-check`
4. `app:recalculate-tenant-health-scores`

---

# 14. Seeder yang Dibuat

Buat seeder:

1. `AnalyticsMetricDefinitionSeeder`

Seeder ini mengisi metric dictionary awal.

---

# 15. Views yang Dibuat

Buat Blade views:

```text
resources/views/analytics/
├── executive/
│   └── dashboard.blade.php
├── school/
│   └── dashboard.blade.php
├── tenant-health/
│   ├── index.blade.php
│   └── show.blade.php
├── academic/
│   └── dashboard.blade.php
├── operational/
│   └── dashboard.blade.php
├── finance/
│   └── dashboard.blade.php
├── support/
│   └── dashboard.blade.php
├── mobile-api/
│   └── dashboard.blade.php
├── executive-reports/
│   ├── index.blade.php
│   ├── show.blade.php
│   └── print.blade.php
└── metric-dictionary/
    ├── index.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    └── show.blade.php
```

---

# 16. Dokumentasi yang Dibuat

Buat dokumen:

```text
docs/phase-23-execution.md
docs/phase-23-advanced-analytics-executive-intelligence.md
docs/analytics-metric-dictionary.md
docs/analytics-privacy-policy.md
docs/executive-dashboard-guide.md
docs/school-analytics-dashboard-guide.md
docs/monthly-executive-report-template.md
docs/tenant-health-score-policy.md
```

---

# 17. Role Access Phase 23

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
operations_manager
customer_success
support_staff
sales
teacher
guru
guru_tahfidz
parent
student
merchant_owner
cashier
```

| Role | Internal Executive | School Analytics | Tenant Health | Finance Analytics | Support Analytics | Metric Dictionary |
|---|---|---|---|---|---|---|
| Super Admin | Ya | Semua | Semua | Semua | Semua | CRUD |
| Operations Manager | Ya | Semua | Semua | Semua read | Semua | CRUD terbatas |
| Customer Success | Ya terbatas | Tenant assigned | Tenant assigned | Summary | Ticket summary | Read |
| Support Staff | Terbatas | Tenant assigned | Risk summary | Tidak default | Ya | Read |
| Sales | Terbatas | Usage summary | Health summary | Subscription summary | Tidak default | Read |
| Admin Sekolah | Tidak | Tenant sendiri | Tenant sendiri | Tenant sendiri | Ticket sendiri | Read |
| Kepala Sekolah | Tidak | Tenant sendiri read-only | Tenant sendiri read-only | Summary tenant | Ticket sendiri | Read |
| Teacher/Guru | Tidak | Tidak default | Tidak | Tidak | Tidak | Tidak |
| Parent | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Student | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Merchant Owner | Tidak | Tidak | Tidak | Merchant summary sendiri jika diaktifkan | Tidak | Tidak |
| Cashier | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |

Aturan keras:

1. Sekolah tidak boleh melihat analytics sekolah lain.
2. Parent/student tidak boleh masuk menu Analytics internal.
3. Teacher tidak otomatis melihat executive analytics.
4. Merchant hanya boleh analytics merchant sendiri jika route khusus diaktifkan.
5. Support staff tidak boleh melihat finance detail kecuali diberi izin eksplisit.
6. Sales tidak boleh melihat data siswa raw.
7. Customer success hanya boleh tenant yang ditugaskan jika assignment system sudah ada.
8. Semua akses analytics penting harus masuk `analytics_access_logs`.

---

# 18. Validasi Awal Sebelum Eksekusi

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
21. Phase 19 selesai.
22. Phase 20 selesai.
23. Phase 21 selesai.
24. Phase 22 selesai dan UAT aman.
25. Tidak ada P0/P1 terbuka.
26. Working tree bersih atau semua perubahan diketahui.

Jika Phase 22 belum aman, hentikan Phase 23.

---

# 19. Buat Branch Git Phase 23

Jalankan:

```powershell
git checkout -b phase-23-advanced-analytics-executive-intelligence
```

Jika branch sudah ada:

```powershell
git checkout phase-23-advanced-analytics-executive-intelligence
```

---

# 20. Buat Model, Migration, Seeder, Controller, Request, Command

Jalankan:

```powershell
php artisan make:model AnalyticsMetricDefinition -m
php artisan make:model AnalyticsSnapshot -m
php artisan make:model TenantHealthScore -m
php artisan make:model TenantHealthScoreComponent -m
php artisan make:model SchoolAcademicSnapshot -m
php artisan make:model SchoolOperationalSnapshot -m
php artisan make:model SchoolFinanceSnapshot -m
php artisan make:model SchoolSupportSnapshot -m
php artisan make:model MobileApiUsageSnapshot -m
php artisan make:model ExecutiveReportRun -m
php artisan make:model ExecutiveReportSection -m
php artisan make:model AnalyticsAccessLog -m

php artisan make:seeder AnalyticsMetricDefinitionSeeder

php artisan make:controller Analytics/ExecutiveAnalyticsDashboardController
php artisan make:controller Analytics/SchoolAnalyticsDashboardController
php artisan make:controller Analytics/TenantHealthAnalyticsController
php artisan make:controller Analytics/AcademicAnalyticsController
php artisan make:controller Analytics/OperationalAnalyticsController
php artisan make:controller Analytics/FinanceAnalyticsController
php artisan make:controller Analytics/SupportAnalyticsController
php artisan make:controller Analytics/MobileApiAnalyticsController
php artisan make:controller Analytics/ExecutiveReportController
php artisan make:controller Analytics/MetricDictionaryController --resource

php artisan make:request Analytics/AnalyticsDateRangeRequest
php artisan make:request Analytics/SchoolAnalyticsFilterRequest
php artisan make:request Analytics/TenantHealthFilterRequest
php artisan make:request Analytics/GenerateExecutiveReportRequest
php artisan make:request Analytics/StoreMetricDefinitionRequest
php artisan make:request Analytics/UpdateMetricDefinitionRequest

php artisan make:command CaptureAnalyticsSnapshotsCommand
php artisan make:command GenerateExecutiveReportCommand
php artisan make:command AnalyticsHealthCheckCommand
php artisan make:command RecalculateTenantHealthScoresCommand
```

Buat folder service:

```powershell
mkdir app\Services\Analytics
```

Buat file service:

```powershell
New-Item app\Services\Analytics\AnalyticsAccessService.php
New-Item app\Services\Analytics\AnalyticsPrivacyGuard.php
New-Item app\Services\Analytics\AnalyticsPeriodResolver.php
New-Item app\Services\Analytics\MetricDictionaryService.php
New-Item app\Services\Analytics\AnalyticsSnapshotService.php
New-Item app\Services\Analytics\TenantHealthScoreService.php
New-Item app\Services\Analytics\AcademicAnalyticsService.php
New-Item app\Services\Analytics\OperationalAnalyticsService.php
New-Item app\Services\Analytics\FinanceAnalyticsService.php
New-Item app\Services\Analytics\SupportAnalyticsService.php
New-Item app\Services\Analytics\MobileApiAnalyticsService.php
New-Item app\Services\Analytics\ExecutiveDashboardService.php
New-Item app\Services\Analytics\SchoolAnalyticsDashboardService.php
New-Item app\Services\Analytics\ExecutiveReportService.php
New-Item app\Services\Analytics\AnalyticsAccessLogger.php
```

Buat folder view:

```powershell
mkdir resources\views\analytics
mkdir resources\views\analytics\executive
mkdir resources\views\analytics\school
mkdir resources\views\analytics\tenant-health
mkdir resources\views\analytics\academic
mkdir resources\views\analytics\operational
mkdir resources\views\analytics\finance
mkdir resources\views\analytics\support
mkdir resources\views\analytics\mobile-api
mkdir resources\views\analytics\executive-reports
mkdir resources\views\analytics\metric-dictionary
```

---

# 21. Migration `analytics_metric_definitions`

Isi migration:

```php
Schema::create('analytics_metric_definitions', function (Blueprint $table): void {
    $table->id();

    $table->string('metric_key')->unique();
    $table->string('name');
    $table->string('category')->index();
    $table->text('description')->nullable();
    $table->text('formula')->nullable();
    $table->string('unit')->nullable();
    $table->string('aggregation_type')->nullable();
    $table->boolean('is_sensitive')->default(false);
    $table->boolean('is_active')->default(true);
    $table->unsignedSmallInteger('sort_order')->default(0);

    $table->timestamps();
    $table->softDeletes();

    $table->index(['category', 'is_active']);
});
```

---

# 22. Migration `analytics_snapshots`

Isi migration:

```php
Schema::create('analytics_snapshots', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->nullable()
        ->constrained('schools')
        ->nullOnDelete();

    $table->date('snapshot_date');
    $table->string('period_type')->default('daily');
    $table->string('scope')->default('school');
    $table->string('metric_key')->index();
    $table->decimal('metric_value', 20, 4)->default(0);
    $table->json('dimensions')->nullable();
    $table->json('metadata')->nullable();

    $table->timestamps();

    $table->unique([
        'school_id',
        'snapshot_date',
        'period_type',
        'scope',
        'metric_key',
    ], 'analytics_snapshot_unique');

    $table->index(['snapshot_date', 'period_type']);
    $table->index(['school_id', 'snapshot_date']);
});
```

Catatan:

1. `school_id = null` dipakai untuk internal/global aggregate.
2. Sekolah tidak boleh mengakses snapshot global.
3. Snapshot ini metric-level general purpose.

---

# 23. Migration `tenant_health_scores`

Isi migration:

```php
Schema::create('tenant_health_scores', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->constrained('schools')
        ->cascadeOnDelete();

    $table->date('score_date');
    $table->unsignedTinyInteger('score')->default(0);
    $table->string('status')->default('unknown');
    $table->text('summary')->nullable();
    $table->json('risk_flags')->nullable();
    $table->json('recommendations')->nullable();

    $table->timestamps();

    $table->unique(['school_id', 'score_date']);
    $table->index(['score_date', 'status']);
    $table->index(['school_id', 'score_date']);
});
```

Status:

| Status | Score |
|---|---:|
| `healthy` | 80–100 |
| `watch` | 60–79 |
| `risk` | 40–59 |
| `critical` | 0–39 |
| `unknown` | tidak cukup data |

---

# 24. Migration `tenant_health_score_components`

Isi migration:

```php
Schema::create('tenant_health_score_components', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('tenant_health_score_id')
        ->constrained('tenant_health_scores')
        ->cascadeOnDelete();

    $table->string('component_key');
    $table->string('name');
    $table->unsignedSmallInteger('max_score')->default(0);
    $table->unsignedSmallInteger('score')->default(0);
    $table->text('explanation')->nullable();
    $table->json('metadata')->nullable();

    $table->timestamps();

    $table->index(['tenant_health_score_id', 'component_key']);
});
```

Komponen default:

1. `usage_activity`
2. `academic_activity`
3. `parent_engagement`
4. `finance_health`
5. `support_risk`
6. `incident_risk`
7. `subscription_status`
8. `mobile_api_adoption`

---

# 25. Migration `school_academic_snapshots`

Isi migration:

```php
Schema::create('school_academic_snapshots', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->constrained('schools')
        ->cascadeOnDelete();

    $table->date('snapshot_date');
    $table->string('period_type')->default('daily');

    $table->unsignedInteger('active_students_count')->default(0);
    $table->unsignedInteger('active_teachers_count')->default(0);
    $table->unsignedInteger('hafalan_records_count')->default(0);
    $table->unsignedInteger('hafalan_total_lines')->default(0);
    $table->decimal('tahfizh_target_achievement_rate', 8, 2)->default(0);
    $table->unsignedInteger('students_behind_target_count')->default(0);
    $table->unsignedInteger('mutabaah_records_count')->default(0);
    $table->decimal('mutabaah_completion_rate', 8, 2)->default(0);
    $table->unsignedInteger('tahsin_assessments_count')->default(0);
    $table->decimal('tahsin_average_score', 8, 2)->default(0);

    $table->json('raw_metrics')->nullable();
    $table->timestamps();

    $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_academic_snapshot_unique');
    $table->index(['snapshot_date', 'period_type']);
});
```

---

# 26. Migration `school_operational_snapshots`

Isi migration:

```php
Schema::create('school_operational_snapshots', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->constrained('schools')
        ->cascadeOnDelete();

    $table->date('snapshot_date');
    $table->string('period_type')->default('daily');

    $table->unsignedInteger('attendance_records_count')->default(0);
    $table->unsignedInteger('present_count')->default(0);
    $table->unsignedInteger('late_count')->default(0);
    $table->unsignedInteger('absent_count')->default(0);
    $table->unsignedInteger('sick_count')->default(0);
    $table->unsignedInteger('permission_count')->default(0);
    $table->decimal('attendance_rate', 8, 2)->default(0);
    $table->decimal('late_rate', 8, 2)->default(0);

    $table->unsignedInteger('boarding_roll_call_records_count')->default(0);
    $table->unsignedInteger('boarding_leave_requests_count')->default(0);
    $table->unsignedInteger('boarding_health_logs_count')->default(0);
    $table->unsignedInteger('boarding_discipline_logs_count')->default(0);

    $table->json('raw_metrics')->nullable();
    $table->timestamps();

    $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_operational_snapshot_unique');
    $table->index(['snapshot_date', 'period_type']);
});
```

Jika boarding tables belum ada atau modul disabled, isi nilai boarding dengan 0 tanpa error.

---

# 27. Migration `school_finance_snapshots`

Isi migration:

```php
Schema::create('school_finance_snapshots', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->constrained('schools')
        ->cascadeOnDelete();

    $table->date('snapshot_date');
    $table->string('period_type')->default('daily');

    $table->decimal('student_bills_total', 20, 2)->default(0);
    $table->decimal('student_payments_total', 20, 2)->default(0);
    $table->decimal('student_outstanding_total', 20, 2)->default(0);
    $table->unsignedInteger('overdue_bills_count')->default(0);
    $table->unsignedInteger('void_bills_count')->default(0);
    $table->unsignedInteger('void_payments_count')->default(0);

    $table->decimal('cashless_topup_total', 20, 2)->default(0);
    $table->decimal('cashless_purchase_total', 20, 2)->default(0);
    $table->decimal('cashless_refund_total', 20, 2)->default(0);
    $table->unsignedInteger('cashless_void_count')->default(0);
    $table->unsignedInteger('cashless_negative_balance_anomaly_count')->default(0);
    $table->unsignedInteger('cashless_pending_settlement_count')->default(0);

    $table->json('raw_metrics')->nullable();
    $table->timestamps();

    $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_finance_snapshot_unique');
    $table->index(['snapshot_date', 'period_type']);
});
```

Aturan:

1. Jangan hitung saldo dari cached frontend.
2. Finance/cashless angka harus berasal dari ledger/server database.
3. Jika modul cashless belum aktif untuk tenant, nilai cashless = 0.

---

# 28. Migration `school_support_snapshots`

Isi migration:

```php
Schema::create('school_support_snapshots', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->nullable()
        ->constrained('schools')
        ->nullOnDelete();

    $table->date('snapshot_date');
    $table->string('period_type')->default('daily');

    $table->unsignedInteger('open_tickets_count')->default(0);
    $table->unsignedInteger('critical_tickets_count')->default(0);
    $table->unsignedInteger('high_tickets_count')->default(0);
    $table->unsignedInteger('medium_tickets_count')->default(0);
    $table->unsignedInteger('low_tickets_count')->default(0);
    $table->unsignedInteger('sla_breached_tickets_count')->default(0);
    $table->unsignedInteger('incidents_count')->default(0);
    $table->unsignedInteger('sev1_incidents_count')->default(0);
    $table->unsignedInteger('sev2_incidents_count')->default(0);
    $table->decimal('average_first_response_minutes', 10, 2)->default(0);
    $table->decimal('average_resolution_minutes', 10, 2)->default(0);

    $table->json('raw_metrics')->nullable();
    $table->timestamps();

    $table->unique(['school_id', 'snapshot_date', 'period_type'], 'school_support_snapshot_unique');
    $table->index(['snapshot_date', 'period_type']);
});
```

`school_id = null` untuk internal/global support summary.

---

# 29. Migration `mobile_api_usage_snapshots`

Isi migration:

```php
Schema::create('mobile_api_usage_snapshots', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->nullable()
        ->constrained('schools')
        ->nullOnDelete();

    $table->date('snapshot_date');
    $table->string('period_type')->default('daily');

    $table->unsignedInteger('mobile_devices_count')->default(0);
    $table->unsignedInteger('active_mobile_devices_count')->default(0);
    $table->unsignedInteger('android_devices_count')->default(0);
    $table->unsignedInteger('ios_devices_count')->default(0);
    $table->unsignedInteger('force_update_devices_count')->default(0);

    $table->unsignedInteger('api_requests_count')->default(0);
    $table->unsignedInteger('api_error_count')->default(0);
    $table->unsignedInteger('api_rate_limit_hits_count')->default(0);
    $table->unsignedInteger('api_failed_auth_count')->default(0);
    $table->unsignedInteger('webhook_success_count')->default(0);
    $table->unsignedInteger('webhook_failed_count')->default(0);

    $table->json('raw_metrics')->nullable();
    $table->timestamps();

    $table->unique(['school_id', 'snapshot_date', 'period_type'], 'mobile_api_usage_snapshot_unique');
    $table->index(['snapshot_date', 'period_type']);
});
```

Jika Phase 21/22 tables belum lengkap, command harus skip gracefully dan mencatat warning.

---

# 30. Migration `executive_report_runs`

Isi migration:

```php
Schema::create('executive_report_runs', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->nullable()
        ->constrained('schools')
        ->nullOnDelete();

    $table->string('report_type')->default('monthly');
    $table->string('status')->default('draft');
    $table->date('period_start');
    $table->date('period_end');
    $table->string('title');
    $table->text('summary')->nullable();
    $table->json('highlights')->nullable();
    $table->json('risks')->nullable();
    $table->json('recommendations')->nullable();

    $table->foreignId('generated_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('generated_at')->nullable();
    $table->timestamp('published_at')->nullable();

    $table->timestamps();
    $table->softDeletes();

    $table->index(['school_id', 'period_start', 'period_end']);
    $table->index(['report_type', 'status']);
});
```

`school_id = null` untuk internal HafizPlus executive report.

---

# 31. Migration `executive_report_sections`

Isi migration:

```php
Schema::create('executive_report_sections', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('executive_report_run_id')
        ->constrained('executive_report_runs')
        ->cascadeOnDelete();

    $table->string('section_key');
    $table->string('title');
    $table->longText('content')->nullable();
    $table->json('metrics')->nullable();
    $table->json('charts')->nullable();
    $table->unsignedSmallInteger('sort_order')->default(0);

    $table->timestamps();

    $table->index(['executive_report_run_id', 'section_key']);
});
```

---

# 32. Migration `analytics_access_logs`

Isi migration:

```php
Schema::create('analytics_access_logs', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('school_id')
        ->nullable()
        ->constrained('schools')
        ->nullOnDelete();

    $table->foreignId('user_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->string('analytics_area');
    $table->string('action')->default('view');
    $table->string('route_name')->nullable();
    $table->string('ip_address')->nullable();
    $table->text('user_agent')->nullable();
    $table->json('filters')->nullable();
    $table->json('metadata')->nullable();

    $table->timestamps();

    $table->index(['school_id', 'analytics_area']);
    $table->index(['user_id', 'created_at']);
});
```

Aturan:

1. Jangan simpan token/API key/password/PIN.
2. Filter boleh disimpan jika tidak memuat data sensitif.
3. Simpan minimal area/action/user/school/time.

---

# 33. Model Fillable dan Relasi

Setiap model harus memiliki:

1. `$fillable` eksplisit.
2. Cast untuk `json`, `date`, `datetime`, `decimal`, `boolean`.
3. Relasi `school()` jika ada `school_id`.
4. Relasi `user()` jika ada `user_id`.

Contoh `TenantHealthScore`:

```php
protected $fillable = [
    'school_id',
    'score_date',
    'score',
    'status',
    'summary',
    'risk_flags',
    'recommendations',
];

protected $casts = [
    'score_date' => 'date',
    'score' => 'integer',
    'risk_flags' => 'array',
    'recommendations' => 'array',
];
```

---

# 34. Service `AnalyticsAccessService`

Buat method minimal:

```php
public function canViewInternalExecutiveAnalytics(User $user): bool
public function canViewSchoolAnalytics(User $user, ?int $schoolId): bool
public function canViewTenantHealth(User $user, ?int $schoolId): bool
public function canManageMetricDictionary(User $user): bool
public function canGenerateExecutiveReport(User $user, ?int $schoolId): bool
public function resolveAccessibleSchoolIds(User $user): array
```

Aturan:

1. Super admin dapat semua.
2. Operations manager dapat internal analytics.
3. Customer success hanya assigned tenants jika assignment tersedia.
4. Admin sekolah hanya school sendiri.
5. Kepala sekolah hanya school sendiri read-only.
6. Parent/student tidak boleh.
7. Fallback aman: deny by default.

---

# 35. Service `AnalyticsPrivacyGuard`

Buat method minimal:

```php
public function sanitizeFilters(array $filters): array
public function sanitizeMetadata(array $metadata): array
public function assertSchoolScope(?int $requestedSchoolId, User $user): void
public function removeSensitiveKeys(array $payload): array
public function canExposeMetricToSchool(string $metricKey): bool
```

Sensitive keys yang wajib dibuang:

```text
password
password_confirmation
token
api_token
access_token
refresh_token
secret
api_key
private_key
pin
wallet_pin
remember_token
session_id
cookie
authorization
nisn
phone
email
address
full_payload
```

Catatan:

1. Email/phone/address tidak selalu haram di aplikasi, tetapi tidak perlu di analytics snapshot.
2. Jika butuh contact info, ambil dari module authorized, bukan analytics.

---

# 36. Service `TenantHealthScoreService`

Health score maksimal 100.

Komponen default:

| Component | Max |
|---|---:|
| Usage activity | 20 |
| Academic activity | 20 |
| Parent engagement | 15 |
| Finance health | 10 |
| Support risk | 10 |
| Incident risk | 10 |
| Subscription status | 10 |
| Mobile/API adoption | 5 |

Total: 100.

Aturan score:

1. Score harus explainable.
2. Setiap component disimpan di `tenant_health_score_components`.
3. Jangan beri score tinggi jika data tidak cukup.
4. Jika data tidak cukup, status `unknown`.
5. Jangan otomatis suspend/cancel tenant.

Method minimal:

```php
public function calculateForSchool(int $schoolId, CarbonInterface $date): TenantHealthScore
public function calculateUsageActivity(int $schoolId, CarbonInterface $date): array
public function calculateAcademicActivity(int $schoolId, CarbonInterface $date): array
public function calculateParentEngagement(int $schoolId, CarbonInterface $date): array
public function calculateFinanceHealth(int $schoolId, CarbonInterface $date): array
public function calculateSupportRisk(int $schoolId, CarbonInterface $date): array
public function calculateIncidentRisk(int $schoolId, CarbonInterface $date): array
public function calculateSubscriptionStatus(int $schoolId, CarbonInterface $date): array
public function calculateMobileApiAdoption(int $schoolId, CarbonInterface $date): array
public function resolveStatus(int $score): string
```

---

# 37. Service `AnalyticsSnapshotService`

Tanggung jawab:

1. Capture all snapshots.
2. Capture per school.
3. Capture global aggregate.
4. Skip missing optional module safely.
5. Store idempotently with update-or-create.
6. Avoid heavy dashboard live queries.

Method minimal:

```php
public function captureDaily(?CarbonInterface $date = null): void
public function captureForSchool(int $schoolId, CarbonInterface $date): void
public function captureGlobal(CarbonInterface $date): void
public function captureAcademic(int $schoolId, CarbonInterface $date): void
public function captureOperational(int $schoolId, CarbonInterface $date): void
public function captureFinance(int $schoolId, CarbonInterface $date): void
public function captureSupport(?int $schoolId, CarbonInterface $date): void
public function captureMobileApi(?int $schoolId, CarbonInterface $date): void
```

---

# 38. Service `ExecutiveReportService`

Tanggung jawab:

1. Generate report run.
2. Generate sections.
3. Pull latest snapshots.
4. Produce highlights.
5. Produce risks.
6. Produce recommendations manual-style.
7. Avoid AI-generated unsupported claims.

Method minimal:

```php
public function generateMonthlyInternalReport(CarbonInterface $periodStart, CarbonInterface $periodEnd, ?int $generatedBy = null): ExecutiveReportRun
public function generateMonthlySchoolReport(int $schoolId, CarbonInterface $periodStart, CarbonInterface $periodEnd, ?int $generatedBy = null): ExecutiveReportRun
public function buildHighlights(array $metrics): array
public function buildRisks(array $metrics): array
public function buildRecommendations(array $metrics): array
```

Recommendation harus rule-based sederhana, contoh:

1. Jika parent engagement rendah, rekomendasi: training parent portal.
2. Jika teacher input rendah, rekomendasi: audit workflow guru.
3. Jika support SLA breach tinggi, rekomendasi: review support capacity.
4. Jika attendance late rate tinggi, rekomendasi: evaluasi jam kedatangan.

Jangan buat klaim prediktif seperti:

```text
Sekolah ini pasti churn bulan depan.
```

Gunakan bahasa:

```text
Tenant ini masuk kategori risk karena indikator usage dan support memburuk.
```

---

# 39. Request Rules

## 39.1 `AnalyticsDateRangeRequest`

Rules:

```php
return [
    'date_from' => ['nullable', 'date'],
    'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],
    'period_type' => ['nullable', 'in:daily,weekly,monthly,quarterly,yearly'],
];
```

Default period:

1. `date_from`: awal bulan berjalan.
2. `date_until`: hari ini.
3. `period_type`: `daily`.

---

## 39.2 `SchoolAnalyticsFilterRequest`

Rules:

```php
return [
    'school_id' => ['nullable', 'exists:schools,id'],
    'date_from' => ['nullable', 'date'],
    'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],
    'period_type' => ['nullable', 'in:daily,weekly,monthly,quarterly,yearly'],
    'module' => ['nullable', 'in:tahfizh,mutabaah,attendance,tahsin,finance,cashless,boarding,mobile,api,support'],
];
```

Authorization harus memakai `AnalyticsAccessService`.

---

## 39.3 `TenantHealthFilterRequest`

Rules:

```php
return [
    'school_id' => ['nullable', 'exists:schools,id'],
    'status' => ['nullable', 'in:healthy,watch,risk,critical,unknown'],
    'date_from' => ['nullable', 'date'],
    'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],
];
```

---

## 39.4 `GenerateExecutiveReportRequest`

Rules:

```php
return [
    'school_id' => ['nullable', 'exists:schools,id'],
    'report_type' => ['required', 'in:monthly,quarterly,yearly'],
    'period_start' => ['required', 'date'],
    'period_end' => ['required', 'date', 'after_or_equal:period_start'],
];
```

---

# 40. Routes

Tambahkan route group di `routes/web.php`:

```php
use App\Http\Controllers\Analytics\AcademicAnalyticsController;
use App\Http\Controllers\Analytics\ExecutiveAnalyticsDashboardController;
use App\Http\Controllers\Analytics\ExecutiveReportController;
use App\Http\Controllers\Analytics\FinanceAnalyticsController;
use App\Http\Controllers\Analytics\MetricDictionaryController;
use App\Http\Controllers\Analytics\MobileApiAnalyticsController;
use App\Http\Controllers\Analytics\OperationalAnalyticsController;
use App\Http\Controllers\Analytics\SchoolAnalyticsDashboardController;
use App\Http\Controllers\Analytics\SupportAnalyticsController;
use App\Http\Controllers\Analytics\TenantHealthAnalyticsController;

Route::middleware(['auth'])->prefix('analytics')->name('analytics.')->group(function (): void {
    Route::get('/executive', [ExecutiveAnalyticsDashboardController::class, 'index'])
        ->name('executive.dashboard');

    Route::get('/school', [SchoolAnalyticsDashboardController::class, 'index'])
        ->name('school.dashboard');

    Route::get('/tenant-health', [TenantHealthAnalyticsController::class, 'index'])
        ->name('tenant-health.index');
    Route::get('/tenant-health/{school}', [TenantHealthAnalyticsController::class, 'show'])
        ->name('tenant-health.show');

    Route::get('/academic', [AcademicAnalyticsController::class, 'index'])
        ->name('academic.dashboard');

    Route::get('/operational', [OperationalAnalyticsController::class, 'index'])
        ->name('operational.dashboard');

    Route::get('/finance', [FinanceAnalyticsController::class, 'index'])
        ->name('finance.dashboard');

    Route::get('/support', [SupportAnalyticsController::class, 'index'])
        ->name('support.dashboard');

    Route::get('/mobile-api', [MobileApiAnalyticsController::class, 'index'])
        ->name('mobile-api.dashboard');

    Route::get('/executive-reports', [ExecutiveReportController::class, 'index'])
        ->name('executive-reports.index');
    Route::get('/executive-reports/create', [ExecutiveReportController::class, 'create'])
        ->name('executive-reports.create');
    Route::post('/executive-reports', [ExecutiveReportController::class, 'store'])
        ->name('executive-reports.store');
    Route::get('/executive-reports/{report}', [ExecutiveReportController::class, 'show'])
        ->name('executive-reports.show');
    Route::get('/executive-reports/{report}/print', [ExecutiveReportController::class, 'print'])
        ->name('executive-reports.print');

    Route::resource('metric-dictionary', MetricDictionaryController::class);
});
```

Catatan:

1. Jika middleware role/permission khusus sudah ada, tambahkan.
2. Jangan expose analytics route public.
3. Controller harus tetap cek access service.

---

# 41. Controller Behavior

Setiap controller analytics wajib:

1. Cek authorization via `AnalyticsAccessService`.
2. Resolve accessible school scope.
3. Sanitize filter via `AnalyticsPrivacyGuard`.
4. Log access via `AnalyticsAccessLogger`.
5. Read snapshot tables, bukan query raw berat.
6. Return Blade view.
7. Jangan return sensitive metadata.

Contoh alur:

```text
Request → FormRequest → AccessService → PrivacyGuard → DashboardService → AccessLogger → View
```

---

# 42. Seeder `AnalyticsMetricDefinitionSeeder`

Isi metric awal minimal:

| metric_key | category | name | unit | sensitive |
|---|---|---|---|---|
| `tenant.active_count` | tenant | Active Tenants | count | false |
| `tenant.health_score` | tenant | Tenant Health Score | score | false |
| `usage.active_users` | usage | Active Users | count | false |
| `academic.hafalan_records` | academic | Hafalan Records | count | false |
| `academic.tahfizh_achievement_rate` | academic | Tahfizh Achievement Rate | percent | false |
| `academic.students_behind_target` | academic | Students Behind Target | count | true |
| `mutabaah.completion_rate` | academic | Mutabaah Completion Rate | percent | false |
| `attendance.attendance_rate` | operational | Attendance Rate | percent | false |
| `attendance.late_rate` | operational | Late Rate | percent | false |
| `tahsin.average_score` | academic | Tahsin Average Score | score | false |
| `finance.outstanding_total` | finance | Outstanding Total | currency | true |
| `cashless.purchase_total` | cashless | Cashless Purchase Total | currency | true |
| `support.sla_breach_rate` | support | SLA Breach Rate | percent | false |
| `mobile.active_devices` | mobile | Active Mobile Devices | count | false |
| `api.error_rate` | api | API Error Rate | percent | false |

Catatan:

1. Sensitive tidak berarti dilarang, tetapi perlu role ketat.
2. Definisi formula harus ditulis di `description` atau `formula`.

---

# 43. Command `app:capture-analytics-snapshots`

Signature:

```php
protected $signature = 'app:capture-analytics-snapshots {--date=} {--school_id=}';
```

Behavior:

1. Jika `--date` kosong, pakai hari ini.
2. Jika `--school_id` ada, capture tenant itu saja.
3. Jika tidak ada `--school_id`, capture semua sekolah aktif.
4. Capture global summary.
5. Capture tenant health score.
6. Log output ringkas.
7. Jangan crash jika modul opsional belum ada; tulis warning.

Command harus aman dijalankan berulang karena snapshot harus `updateOrCreate`.

---

# 44. Command `app:generate-executive-report`

Signature:

```php
protected $signature = 'app:generate-executive-report {--school_id=} {--period=monthly} {--period_start=} {--period_end=}';
```

Behavior:

1. Jika `school_id` kosong, generate internal report.
2. Jika `school_id` ada, generate report sekolah tersebut.
3. Default period monthly bulan berjalan atau bulan lalu, pilih konsisten dan dokumentasikan.
4. Report status awal `draft`.
5. Tidak auto publish.
6. Tidak auto email.
7. Tidak auto WhatsApp.

---

# 45. Command `app:analytics-health-check`

Signature:

```php
protected $signature = 'app:analytics-health-check';
```

Cek:

1. Tabel analytics tersedia.
2. Metric definition tersedia.
3. Snapshot hari ini atau kemarin tersedia.
4. Tenant health score tersedia.
5. Tidak ada snapshot duplicate aneh.
6. Access log table writable.
7. Optional modules safely detected.

Output contoh:

```text
Analytics Health Check
- Metric definitions: OK
- Daily snapshots: OK
- Tenant health scores: OK
- Access logs: OK
- Optional module cashless: OK
- Optional module mobile: OK
Status: OK
```

---

# 46. Scheduler

Tambahkan di `routes/console.php` atau scheduler config yang dipakai project:

```php
Schedule::command('app:capture-analytics-snapshots')
    ->dailyAt('23:45')
    ->withoutOverlapping();

Schedule::command('app:recalculate-tenant-health-scores')
    ->dailyAt('23:55')
    ->withoutOverlapping();
```

Jangan jadwalkan executive report auto-publish.

Report generation boleh manual dulu.

---

# 47. Dashboard UI Minimal

Gunakan Blade sederhana.

Tidak wajib install chart library baru.

Jika project sudah punya chart helper, boleh pakai.

Jika belum, gunakan:

1. Card angka.
2. Tabel ringkasan.
3. Progress bar CSS sederhana.
4. Badge status.
5. Trend table.

Jangan install dashboard admin template besar.

---

# 48. Executive Dashboard Cards

Internal executive dashboard minimal punya card:

1. Active tenants.
2. Trial tenants.
3. Risk tenants.
4. Critical tenants.
5. Active users this month.
6. Parent engagement rate.
7. Teacher activity rate.
8. Open support tickets.
9. SLA breach count.
10. Incident count.
11. Manual revenue total.
12. Cashless transaction total.
13. Mobile active devices.
14. API error rate.

---

# 49. School Dashboard Cards

School dashboard minimal punya card:

1. Active students.
2. Active teachers.
3. Active parents.
4. Tahfizh achievement rate.
5. Students behind target.
6. Mutabaah completion rate.
7. Attendance rate.
8. Late rate.
9. Tahsin average score.
10. Outstanding finance total.
11. Cashless purchases.
12. Notifications read rate jika tersedia.
13. Mobile active devices.
14. School health score.

---

# 50. Tenant Health UI

Index:

| School | Score | Status | Main Risk | Last Snapshot | Action |
|---|---:|---|---|---|---|

Show:

1. Score besar.
2. Status badge.
3. Component breakdown.
4. Risk flags.
5. Recommendations.
6. Trend 30 hari.
7. Latest support snapshot.
8. Latest academic snapshot.
9. Latest finance snapshot.
10. Latest mobile/API snapshot.

---

# 51. Executive Report Sections

Default sections:

1. Executive summary.
2. Tenant growth and status.
3. Product usage.
4. Academic activity.
5. Parent engagement.
6. Finance and cashless summary.
7. Support and SLA.
8. Incidents and risks.
9. Mobile and API adoption.
10. Recommendations.

School report sections:

1. School summary.
2. Student and teacher activity.
3. Tahfizh performance.
4. Mutabaah performance.
5. Attendance trend.
6. Tahsin progress.
7. Finance and cashless summary.
8. Parent/mobile engagement.
9. Risks.
10. Recommendations.

---

# 52. Security Tests Manual

Wajib uji:

1. Parent buka `/analytics/executive` → 403.
2. Student buka `/analytics/school` → 403.
3. Teacher buka `/analytics/executive` → 403.
4. Admin sekolah A mencoba `school_id` sekolah B → 403.
5. Kepala sekolah hanya read-only.
6. Support staff tidak melihat finance detail.
7. Sales tidak melihat data siswa raw.
8. Metric dictionary CRUD hanya role internal authorized.
9. Analytics access log tercatat setelah buka dashboard.
10. Snapshot metadata tidak menyimpan token/password/PIN.

---

# 53. Automated Tests Minimal

Buat feature tests jika project sudah punya testing setup stabil.

Test minimal:

1. Super admin can view executive analytics.
2. Admin sekolah can view own school analytics.
3. Admin sekolah cannot view other school analytics.
4. Parent cannot view analytics dashboard.
5. Student cannot view analytics dashboard.
6. Analytics snapshot command runs successfully.
7. Tenant health score generated.
8. Metric dictionary seeded.
9. Executive report can be generated as draft.
10. Analytics privacy guard removes sensitive keys.

Jika test framework belum siap, buat manual test checklist di docs.

---

# 54. Validation Commands

Setelah implementasi, jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform

php artisan migrate
php artisan db:seed --class=AnalyticsMetricDefinitionSeeder
php artisan app:capture-analytics-snapshots
php artisan app:recalculate-tenant-health-scores
php artisan app:generate-executive-report --period=monthly
php artisan app:analytics-health-check
php artisan route:list --path=analytics
php artisan app:system-health-check
npm run build
git status
```

Jika ada test:

```powershell
php artisan test
```

---

# 55. Documentation: `analytics-metric-dictionary.md`

Isi minimal:

1. Daftar metric.
2. Definisi metric.
3. Formula.
4. Source table.
5. Refresh schedule.
6. Access level.
7. Sensitive flag.
8. Notes.

Contoh:

```md
## tahfizh_achievement_rate

Category: Academic  
Unit: Percent  
Formula: total actual lines / total target lines * 100  
Source: hafalan_records, tahfizh_targets, tahfizh_debts  
Refresh: daily snapshot  
Sensitive: no for aggregate, yes for student-level drilldown  
Access: internal executive, school admin, principal own tenant
```

---

# 56. Documentation: `analytics-privacy-policy.md`

Isi minimal:

1. Tujuan analytics.
2. Data yang digunakan.
3. Data yang tidak boleh disimpan analytics.
4. Role access.
5. Tenant isolation.
6. Data retention.
7. Export limitation.
8. External analytics prohibition.
9. Access logging.
10. Incident handling jika analytics leak.

---

# 57. Documentation: `tenant-health-score-policy.md`

Isi minimal:

1. Definisi tenant health score.
2. Component score.
3. Formula.
4. Status healthy/watch/risk/critical/unknown.
5. Keterbatasan score.
6. Score tidak boleh dipakai auto suspend.
7. Cara membaca recommendations.
8. Cara customer success memakai score.

---

# 58. Update Navigation

Tambahkan menu internal:

```text
Analytics
├── Executive Dashboard
├── School Analytics
├── Tenant Health
├── Academic Analytics
├── Operational Analytics
├── Finance Analytics
├── Support Analytics
├── Mobile & API Analytics
├── Executive Reports
└── Metric Dictionary
```

Menu untuk sekolah:

```text
Analytics Sekolah
├── Dashboard Sekolah
├── Academic Analytics
├── Operational Analytics
├── Finance Summary
└── Executive Report
```

Jangan tampilkan menu analytics ke parent/student.

---

# 59. Update `docs/project-progress.md`

Tambahkan:

```md
## Phase 23 — Advanced Analytics & Executive Intelligence

Status: Done

Output:
- Analytics snapshot foundation.
- Executive dashboard.
- School analytics dashboard.
- Tenant health score.
- Academic analytics.
- Operational analytics.
- Finance/cashless analytics.
- Support/SLA analytics.
- Mobile/API usage analytics.
- Executive report generation.
- Metric dictionary.
- Analytics privacy guard.
- Analytics access log.

Catatan:
- Phase 23 tidak membuat AI autopilot.
- Phase 23 tidak mengirim data siswa ke external analytics provider.
- Phase 23 tidak mengambil keputusan otomatis.
```

---

# 60. Definition of Done Phase 23

Phase 23 dianggap selesai jika semua poin berikut terpenuhi:

1. Semua migration analytics berhasil.
2. Semua model analytics memiliki fillable dan casts.
3. Metric definition seeder berjalan.
4. Analytics access service berjalan.
5. Analytics privacy guard berjalan.
6. Analytics access log tercatat.
7. Snapshot command berjalan tanpa error.
8. Tenant health score berhasil dihitung.
9. Executive dashboard tampil.
10. School analytics dashboard tampil.
11. Tenant health index/show tampil.
12. Academic analytics tampil.
13. Operational analytics tampil.
14. Finance analytics tampil.
15. Support analytics tampil.
16. Mobile/API analytics tampil.
17. Executive report bisa digenerate draft.
18. Metric dictionary bisa dilihat.
19. Metric dictionary CRUD hanya role authorized.
20. Admin sekolah hanya melihat tenant sendiri.
21. Parent/student tidak bisa mengakses analytics.
22. Sensitive data tidak masuk snapshot metadata.
23. No external analytics provider added.
24. `php artisan app:analytics-health-check` berhasil.
25. `php artisan app:system-health-check` berhasil.
26. `npm run build` berhasil.
27. Dokumentasi analytics dibuat.
28. `docs/project-progress.md` diupdate.
29. Tidak ada P0/P1 terbuka.
30. Commit dibuat.

---

# 61. Bug Priority Phase 23

| Priority | Criteria | Action |
|---|---|---|
| P0 | Analytics membuka data tenant lain, parent/student bisa masuk analytics, sensitive token/password/PIN tersimpan | Fix langsung, jangan deploy |
| P1 | Health score salah besar, finance aggregate salah, report executive salah angka, access log tidak jalan | Fix sebelum lanjut |
| P2 | Filter tanggal tidak nyaman, table lambat, UI kurang rapi | Boleh masuk polish sprint |
| P3 | Label, spacing, chart enhancement | Boleh ditunda |

---

# 62. Commit

Setelah semua selesai:

```powershell
git add .
git commit -m "feat: add advanced analytics and executive intelligence"
```

---

# 63. Final Report untuk Agent

Setelah selesai, agent wajib melaporkan:

1. File yang dibuat.
2. Tabel yang dibuat.
3. Route analytics yang tersedia.
4. Command analytics yang tersedia.
5. Role access yang diterapkan.
6. Snapshot yang berhasil dicapture.
7. Tenant health score sample.
8. Executive report sample.
9. Hasil `app:analytics-health-check`.
10. Hasil `app:system-health-check`.
11. Hasil `npm run build`.
12. Bug/known issue jika ada.
13. Status akhir Phase 23.

Format final:

```text
Phase 23 — Advanced Analytics & Executive Intelligence selesai.

Status:
- Migration: OK
- Seeder: OK
- Snapshot command: OK
- Tenant health score: OK
- Executive dashboard: OK
- School analytics: OK
- Privacy guard: OK
- Access log: OK
- Executive report: OK
- System health: OK
- NPM build: OK

Known issues:
- ...
```

---

# 64. Setelah Phase 23

Jangan langsung membuat Phase 24 kecuali Phase 23 sudah dipakai dan divalidasi.

Urutan setelah Phase 23 yang benar:

1. Analytics UAT internal.
2. Validasi angka dengan data manual.
3. Cek tenant isolation analytics.
4. Cek role access analytics.
5. Cek sensitive metadata.
6. Presentasi dashboard ke internal owner/kepala sekolah.
7. Perbaiki metric yang salah.
8. Stabilkan snapshot scheduler.
9. Baru pertimbangkan Phase 24.

Jika tetap lanjut Phase 24, kandidat paling masuk akal:

```text
Phase 24 — LMS Lite / Learning Content Module
```

Namun Phase 24 hanya boleh dibuat jika:

1. Analytics sudah valid.
2. Core school platform stabil.
3. Ada kebutuhan nyata dari sekolah.
4. Tidak mengganggu cashless/finance/mobile/API stability.

