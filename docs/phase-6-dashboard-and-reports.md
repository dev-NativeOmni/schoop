# Phase 6 — Dashboard and Reports

## Status

Phase 6 membangun dashboard dan laporan awal untuk HafizPlus School Platform.

## Output

1. Dashboard Tahfizh.
2. Laporan Bulanan Tahfizh.
3. Laporan Triwulan Tahfizh.
4. Filter kelas.
5. Filter santri.
6. Filter guru.
7. Filter status.
8. Filter periode.
9. Ringkasan aktivitas guru.
10. Ringkasan santri tertinggal.
11. Detail setoran per santri.

## Controller Baru

```text
App\Http\Controllers\Reports\TahfizhDashboardController
App\Http\Controllers\Reports\MonthlyTahfizhReportController
App\Http\Controllers\Reports\QuarterlyTahfizhReportController
```

## Request Baru

```text
App\Http\Requests\Reports\DashboardFilterRequest
App\Http\Requests\Reports\MonthlyTahfizhReportRequest
App\Http\Requests\Reports\QuarterlyTahfizhReportRequest
```

## Service Baru

```text
App\Services\Reports\ReportPeriodResolver
App\Services\Reports\TahfizhDashboardSummaryService
App\Services\Reports\MonthlyTahfizhReportService
App\Services\Reports\QuarterlyTahfizhReportService
```

## Route Baru

```text
reports.tahfizh.dashboard
reports.tahfizh.monthly.index
reports.tahfizh.monthly.show
reports.tahfizh.quarterly.index
reports.tahfizh.quarterly.show
```

## Role Access

| Role           | Dashboard | Bulanan | Triwulan |
| -------------- | --------- | ------- | -------- |
| Super Admin    | Ya        | Ya      | Ya       |
| Admin Sekolah  | Ya        | Ya      | Ya       |
| Kepala Sekolah | Ya        | Ya      | Ya       |
| Guru Tahfidz   | Ya        | Ya      | Ya       |
| Orang Tua      | Belum     | Belum   | Belum    |
| Santri         | Belum     | Belum   | Belum    |

## Data Sumber

Dashboard dan laporan membaca data dari:

1. `students`
2. `class_rooms`
3. `users`
4. `hafalan_records`
5. `tahfizh_targets`
6. `tahfizh_debts`

## Belum Dibuat

Phase 6 belum membuat:

1. Export PDF.
2. Export Excel.
3. Grafik interaktif.
4. Parent portal detail.
5. Student portal detail.
6. Notification center.
7. API mobile.
8. WhatsApp gateway.

## Definition of Done

Phase 6 selesai jika:

1. Dashboard Tahfizh bisa dibuka.
2. Laporan Bulanan bisa dibuka.
3. Laporan Triwulan bisa dibuka.
4. Filter berjalan.
5. Data setoran tampil benar.
6. Data target tampil benar.
7. Data hutang tampil benar.
8. Kepala Sekolah bisa melihat laporan.
9. Guru bisa melihat laporan.
10. Orang Tua dan Santri tidak bisa mengakses laporan internal.
11. Build frontend berhasil.
12. Dokumentasi Phase 6 dibuat.
