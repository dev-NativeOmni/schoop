# Phase 9 — Export PDF and Excel

## Status

Phase 9 membangun fitur export laporan tahfizh dalam format PDF dan Excel.

## Package

```text
maatwebsite/excel
barryvdh/laravel-dompdf
```

## Output

1. Export laporan bulanan PDF.
2. Export laporan bulanan Excel.
3. Export laporan triwulan PDF.
4. Export laporan triwulan Excel.
5. Export ringkasan dashboard PDF.
6. Export ringkasan dashboard Excel.

## Route Baru

```text
exports.tahfizh.index
exports.tahfizh.monthly.excel
exports.tahfizh.monthly.pdf
exports.tahfizh.quarterly.excel
exports.tahfizh.quarterly.pdf
exports.tahfizh.dashboard.excel
exports.tahfizh.dashboard.pdf
```

## Controller Baru

```text
App\Http\Controllers\Exports\TahfizhExportController
```

## Request Baru

```text
App\Http\Requests\Exports\TahfizhExportRequest
```

## Export Class Baru

```text
App\Exports\Tahfizh\MonthlyTahfizhReportExport
App\Exports\Tahfizh\QuarterlyTahfizhReportExport
App\Exports\Tahfizh\DashboardTahfizhSummaryExport
```

## PDF View Baru

```text
resources/views/exports/pdf/monthly-tahfizh-report.blade.php
resources/views/exports/pdf/quarterly-tahfizh-report.blade.php
resources/views/exports/pdf/dashboard-tahfizh-summary.blade.php
```

## Role Access

| Role           | Export |
| -------------- | -----: |
| Super Admin    |     Ya |
| Admin Sekolah  |     Ya |
| Kepala Sekolah |     Ya |
| Guru Tahfidz   |     Ya |
| Orang Tua      |  Tidak |
| Santri         |  Tidak |

## Belum Dibuat

Phase 9 belum membuat:

1. Import Excel.
2. Queue export.
3. Email export.
4. Scheduled export.
5. Parent/student export pribadi.
6. Export audit log khusus.
7. Export dengan tanda tangan digital.
8. Export multi-tenant kompleks.

## Definition of Done

Phase 9 selesai jika:

1. Package Excel dan PDF berhasil terpasang.
2. Route export tersedia.
3. Halaman export bisa dibuka role internal.
4. Export bulanan Excel berhasil.
5. Export bulanan PDF berhasil.
6. Export triwulan Excel berhasil.
7. Export triwulan PDF berhasil.
8. Export dashboard Excel berhasil.
9. Export dashboard PDF berhasil.
10. Parent dan student tidak bisa mengakses export internal.
11. Dokumentasi Phase 9 selesai.
