# Phase 23 - Advanced Analytics & Executive Intelligence

Status: Done

## Tujuan

Phase 23 bertujuan membangun **Advanced Analytics & Executive Intelligence** untuk HafizPlus School Platform. Fitur ini dirancang untuk mempermudah pembuat keputusan (Executive internal HafizPlus, Admin Sekolah, dan Kepala Sekolah) dalam memantau tren perkembangan sekolah secara terpusat, aman (privacy-safe), dan hemat query (menggunakan snapshots harian).

## Scope Implementasi

- **Executive Dashboard (Internal)**: Menampilkan status kesehatan, trial status, open tickets, SLA breach rate, dan parameter adopsi seluruh tenant/sekolah secara agregat.
- **School Dashboard (Tenant)**: Menampilkan tren akademik (Tahfizh target rate, mutabaah completion, tahsin score), tren operasional (attendance rate, late rate, boarding logs), tren finansial (outstanding bills, cashless transactions), dan skor kesehatan sekolah.
- **Tenant Health Scoring System**: Sistem perhitungan skor kesehatan tenant otomatis (max 100) berdasar aktivitas modul, SLA support, status subscription, dan log insiden.
- **Metric Dictionary**: Kamus referensi metrik resmi platform lengkap dengan formula, kategori, unit, dan label sensitivitas data.
- **Privacy Guard**: Melindungi data sensitif (password, token, PIN, email, dll.) agar tidak bocor ke logs akses atau snapshots analitik.
- **Audit Access Logging**: Mencatat akses user ke halaman analitik di `analytics_access_logs`.
- **Artisan Commands**: `app:capture-analytics-snapshots`, `app:recalculate-tenant-health-scores`, `app:generate-executive-report`, `app:analytics-health-check`.

## Tabel Database Baru

- `analytics_metric_definitions`
- `analytics_snapshots`
- `tenant_health_scores`
- `tenant_health_score_components`
- `school_academic_snapshots`
- `school_operational_snapshots`
- `school_finance_snapshots`
- `school_support_snapshots`
- `mobile_api_usage_snapshots`
- `executive_report_runs`
- `executive_report_sections`
- `analytics_access_logs`

## Keamanan & Isolasi Tenant

- Sekolah/tenant **dilarang keras** mengakses data analitik sekolah lain.
- Parent & Student **dilarang keras** membuka dashboard analitik eksekutif.
- Seluruh penyaringan analitik didasarkan pada `school_id` yang terikat pada context user.
