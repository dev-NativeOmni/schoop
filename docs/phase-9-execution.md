# Phase 9 Execution Guide — Export PDF and Excel

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

Project folder:

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

---

# 1. Tujuan Phase 9

Phase 9 bertujuan membuat fitur export laporan tahfizh dalam format:

1. PDF.
2. Excel `.xlsx`.

Export yang dibuat:

1. Export laporan bulanan tahfizh.
2. Export laporan triwulan tahfizh.
3. Export ringkasan dashboard tahfizh.

Phase ini membaca data dari Phase 3–8.

Phase ini tidak membuat input data baru.

---

# 2. Batasan Phase 9

AI agent tidak boleh membuat fitur berikut pada Phase 9:

1. Import Excel.
2. Upload massal santri.
3. WhatsApp gateway.
4. Email otomatis.
5. Push notification.
6. Websocket.
7. API mobile.
8. Native Android.
9. Native iOS.
10. Attendance.
11. Mutabaah.
12. Tahsin.
13. Finance.
14. Cashless.
15. Payment gateway.
16. Multi-tenant kompleks.
17. Queue export production.
18. Export semua data tanpa filter.

Phase 9 hanya membuat export dari data laporan tahfizh yang sudah ada.

---

# 3. Prinsip Export

## 3.1 Export Harus Terfilter

Export tidak boleh langsung mengambil semua data tanpa batas.

Minimal filter:

1. Bulan.
2. Tahun.
3. Triwulan.
4. Kelas.
5. Santri.
6. Guru.
7. Status.

## 3.2 Export Harus Role-Based

Akses export:

| Role           |                           Boleh Export |
| -------------- | -------------------------------------: |
| Super Admin    |                                     Ya |
| Admin Sekolah  |                                     Ya |
| Kepala Sekolah |                                     Ya |
| Guru Tahfidz   | Ya, minimal data terkait guru tersebut |
| Orang Tua      |                     Tidak pada Phase 9 |
| Santri         |                     Tidak pada Phase 9 |

Parent/student export bisa dibuat nanti sebagai fitur portal lanjutan.

---

# 4. Target Output Phase 9

Setelah Phase 9 selesai, aplikasi harus punya:

1. Menu **Export Laporan**.
2. Package Laravel Excel.
3. Package Laravel DOMPDF.
4. Controller:

   * `TahfizhExportController`
5. Request:

   * `TahfizhExportRequest`
6. Export class:

   * `MonthlyTahfizhReportExport`
   * `QuarterlyTahfizhReportExport`
   * `DashboardTahfizhSummaryExport`
7. PDF view:

   * `exports/pdf/monthly-tahfizh-report.blade.php`
   * `exports/pdf/quarterly-tahfizh-report.blade.php`
   * `exports/pdf/dashboard-tahfizh-summary.blade.php`
8. Export page:

   * `exports/tahfizh/index.blade.php`
9. Route:

   * `exports.tahfizh.index`
   * `exports.tahfizh.monthly.excel`
   * `exports.tahfizh.monthly.pdf`
   * `exports.tahfizh.quarterly.excel`
   * `exports.tahfizh.quarterly.pdf`
   * `exports.tahfizh.dashboard.excel`
   * `exports.tahfizh.dashboard.pdf`
10. Dokumentasi Phase 9.

---

# 5. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
php artisan --version
php artisan migrate:status
php artisan route:list
git status
```

Target:

1. Laravel 12 berjalan.
2. Phase 1 selesai.
3. Phase 2 selesai.
4. Phase 3 selesai.
5. Phase 4 selesai.
6. Phase 5 selesai.
7. Phase 6 selesai.
8. Phase 7 selesai.
9. Phase 8 selesai.
10. Route report bulanan dan triwulan tersedia.
11. Tabel `hafalan_records`, `tahfizh_debts`, `students`, `class_rooms`, dan `users` tersedia.

Jika Phase 8 belum selesai, hentikan eksekusi.

---

# 6. Cek Extension PHP untuk Export

Jalankan:

```powershell
php -m | findstr /i "zip gd mbstring xml"
```

Minimal yang diharapkan:

```text
zip
gd
mbstring
xml
```

Jika `zip` belum aktif, buka:

```text
C:\xampp\php\php.ini
```

Cari:

```ini
;extension=zip
```

Ubah menjadi:

```ini
extension=zip
```

Jika `gd` belum aktif, cari:

```ini
;extension=gd
```

Ubah menjadi:

```ini
extension=gd
```

Setelah itu restart terminal dan jalankan ulang:

```powershell
php -m | findstr /i "zip gd mbstring xml"
```

---

# 7. Buat Branch Git Phase 9

Jalankan:

```powershell
git checkout -b phase-9-export-pdf-excel
```

Jika branch sudah ada:

```powershell
git checkout phase-9-export-pdf-excel
```

---

# 8. Install Package Export

Jalankan:

```powershell
composer require maatwebsite/excel barryvdh/laravel-dompdf
```

Lalu bersihkan cache:

```powershell
php artisan optimize:clear
```

Jika composer gagal karena extension `zip`, aktifkan extension `zip` di `php.ini`.

Jika composer gagal karena extension `gd`, aktifkan extension `gd` di `php.ini`.

---

# 9. Struktur File yang Akan Dibuat

AI agent harus membuat atau mengubah file berikut:

```text
app/
├── Exports/
│   └── Tahfizh/
│       ├── MonthlyTahfizhReportExport.php
│       ├── QuarterlyTahfizhReportExport.php
│       └── DashboardTahfizhSummaryExport.php
├── Http/
│   ├── Controllers/
│   │   └── Exports/
│   │       └── TahfizhExportController.php
│   └── Requests/
│       └── Exports/
│           └── TahfizhExportRequest.php

resources/
└── views/
    └── exports/
        ├── tahfizh/
        │   └── index.blade.php
        └── pdf/
            ├── monthly-tahfizh-report.blade.php
            ├── quarterly-tahfizh-report.blade.php
            └── dashboard-tahfizh-summary.blade.php

routes/
└── web.php

docs/
└── phase-9-export-pdf-excel.md
```

---

# 10. Buat Folder dan File

Jalankan:

```powershell
mkdir app\Exports
mkdir app\Exports\Tahfizh
mkdir app\Http\Controllers\Exports
mkdir app\Http\Requests\Exports

mkdir resources\views\exports
mkdir resources\views\exports\tahfizh
mkdir resources\views\exports\pdf
```

Buat file:

```powershell
New-Item app\Exports\Tahfizh\MonthlyTahfizhReportExport.php
New-Item app\Exports\Tahfizh\QuarterlyTahfizhReportExport.php
New-Item app\Exports\Tahfizh\DashboardTahfizhSummaryExport.php

php artisan make:controller Exports/TahfizhExportController
php artisan make:request Exports/TahfizhExportRequest
```

---

# 11. Request `TahfizhExportRequest`

Buka:

```text
app/Http/Requests/Exports/TahfizhExportRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Exports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TahfizhExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole([
            'super_admin',
            'admin',
            'principal',
            'teacher',
        ]) ?? false;
    }

    public function rules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],

            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'quarter' => ['nullable', 'integer', Rule::in([1, 2, 3, 4])],

            'date_from' => ['nullable', 'date'],
            'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],

            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'month' => 'bulan',
            'year' => 'tahun',
            'quarter' => 'triwulan',
            'date_from' => 'tanggal mulai',
            'date_until' => 'tanggal akhir',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'status' => 'status',
        ];
    }
}
```

---

# 12. Export Class `MonthlyTahfizhReportExport`

Buka:

```text
app/Exports/Tahfizh/MonthlyTahfizhReportExport.php
```

Isi lengkap:

```php
<?php

namespace App\Exports\Tahfizh;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyTahfizhReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly Collection $rows,
        private readonly CarbonInterface $periodStart,
        private readonly CarbonInterface $periodEnd,
    ) {
        //
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Santri',
            'Kelas',
            'Jumlah Setoran',
            'Total Baris',
            'Target Baris',
            'Hutang Baris',
            'Lebih Baris',
            'Akumulasi Hutang',
            'Status',
            'Catatan',
            'Periode Mulai',
            'Periode Akhir',
        ];
    }

    public function map($row): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            $row['student']?->full_name ?? '-',
            $row['class_room']?->name ?? '-',
            $row['record_count'] ?? 0,
            $row['actual_lines'] ?? 0,
            $row['target_lines'] ?? 0,
            $row['debt_lines'] ?? 0,
            $row['surplus_lines'] ?? 0,
            $row['cumulative_debt_lines'] ?? 0,
            $row['status'] ?? '-',
            $row['notes'] ?? '-',
            $this->periodStart->format('Y-m-d'),
            $this->periodEnd->format('Y-m-d'),
        ];
    }

    public function title(): string
    {
        return 'Laporan Bulanan';
    }
}
```

---

# 13. Export Class `QuarterlyTahfizhReportExport`

Buka:

```text
app/Exports/Tahfizh/QuarterlyTahfizhReportExport.php
```

Isi lengkap:

```php
<?php

namespace App\Exports\Tahfizh;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class QuarterlyTahfizhReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly Collection $rows,
        private readonly CarbonInterface $periodStart,
        private readonly CarbonInterface $periodEnd,
        private readonly string $periodLabel,
    ) {
        //
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Santri',
            'Kelas',
            'Jumlah Setoran',
            'Total Baris',
            'Target Baris',
            'Hutang Baris',
            'Lebih Baris',
            'Akumulasi Hutang',
            'Status',
            'Catatan',
            'Label Periode',
            'Periode Mulai',
            'Periode Akhir',
        ];
    }

    public function map($row): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            $row['student']?->full_name ?? '-',
            $row['class_room']?->name ?? '-',
            $row['record_count'] ?? 0,
            $row['actual_lines'] ?? 0,
            $row['target_lines'] ?? 0,
            $row['debt_lines'] ?? 0,
            $row['surplus_lines'] ?? 0,
            $row['cumulative_debt_lines'] ?? 0,
            $row['status'] ?? '-',
            $row['notes'] ?? '-',
            $this->periodLabel,
            $this->periodStart->format('Y-m-d'),
            $this->periodEnd->format('Y-m-d'),
        ];
    }

    public function title(): string
    {
        return 'Laporan Triwulan';
    }
}
```

---

# 14. Export Class `DashboardTahfizhSummaryExport`

Buka:

```text
app/Exports/Tahfizh/DashboardTahfizhSummaryExport.php
```

Isi lengkap:

```php
<?php

namespace App\Exports\Tahfizh;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DashboardTahfizhSummaryExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly array $summary,
        private readonly CarbonInterface $periodStart,
        private readonly CarbonInterface $periodEnd,
    ) {
        //
    }

    public function collection(): Collection
    {
        return collect([
            ['Total Santri', $this->summary['total_students'] ?? 0],
            ['Total Setoran', $this->summary['total_records'] ?? 0],
            ['Total Baris', $this->summary['total_lines'] ?? 0],
            ['Total Hutang Baris', $this->summary['total_debt_lines'] ?? 0],
            ['Total Lebih Baris', $this->summary['total_surplus_lines'] ?? 0],
            ['Total Akumulasi Hutang', $this->summary['total_cumulative_debt_lines'] ?? 0],
            ['Santri Tertinggal', $this->summary['behind_count'] ?? 0],
            ['Santri Tercapai', $this->summary['met_count'] ?? 0],
            ['Santri Lebih Target', $this->summary['ahead_count'] ?? 0],
            ['Santri Tanpa Target', $this->summary['no_target_count'] ?? 0],
            ['Periode Mulai', $this->periodStart->format('Y-m-d')],
            ['Periode Akhir', $this->periodEnd->format('Y-m-d')],
        ]);
    }

    public function headings(): array
    {
        return [
            'Metrik',
            'Nilai',
        ];
    }

    public function title(): string
    {
        return 'Dashboard Tahfizh';
    }
}
```

---

# 15. Controller `TahfizhExportController`

Buka:

```text
app/Http/Controllers/Exports/TahfizhExportController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Exports;

use App\Exports\Tahfizh\DashboardTahfizhSummaryExport;
use App\Exports\Tahfizh\MonthlyTahfizhReportExport;
use App\Exports\Tahfizh\QuarterlyTahfizhReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exports\TahfizhExportRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\MonthlyTahfizhReportService;
use App\Services\Reports\QuarterlyTahfizhReportService;
use App\Services\Reports\ReportPeriodResolver;
use App\Services\Reports\TahfizhDashboardSummaryService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TahfizhExportController extends Controller
{
    public function index(): View
    {
        return view('exports.tahfizh.index', [
            'classRooms' => ClassRoom::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

            'students' => Student::query()
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),

            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),

            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
                HafalanRecord::STATUS_TIDAK_HADIR,
                HafalanRecord::STATUS_IZIN,
                HafalanRecord::STATUS_SAKIT,
            ],
        ]);
    }

    public function monthlyExcel(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        MonthlyTahfizhReportService $reportService
    ): BinaryFileResponse {
        [$periodStart, $periodEnd] = $periodResolver->month($request->input('month'));

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-bulanan-' . $periodStart->format('Y-m') . '.xlsx';

        return Excel::download(
            new MonthlyTahfizhReportExport($rows, $periodStart, $periodEnd),
            $filename
        );
    }

    public function monthlyPdf(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        MonthlyTahfizhReportService $reportService
    ): Response {
        [$periodStart, $periodEnd] = $periodResolver->month($request->input('month'));

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-bulanan-' . $periodStart->format('Y-m') . '.pdf';

        $pdf = Pdf::loadView('exports.pdf.monthly-tahfizh-report', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'generatedBy' => $request->user(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function quarterlyExcel(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        QuarterlyTahfizhReportService $reportService
    ): BinaryFileResponse {
        [$periodStart, $periodEnd] = $periodResolver->quarter(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        $periodLabel = $periodResolver->quarterLabel(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-triwulan-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new QuarterlyTahfizhReportExport($rows, $periodStart, $periodEnd, $periodLabel),
            $filename
        );
    }

    public function quarterlyPdf(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        QuarterlyTahfizhReportService $reportService
    ): Response {
        [$periodStart, $periodEnd] = $periodResolver->quarter(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        $periodLabel = $periodResolver->quarterLabel(
            year: $this->nullableInteger($request->input('year')),
            quarter: $this->nullableInteger($request->input('quarter'))
        );

        $rows = $reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'laporan-tahfizh-triwulan-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('exports.pdf.quarterly-tahfizh-report', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'periodLabel' => $periodLabel,
            'generatedBy' => $request->user(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    public function dashboardExcel(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        TahfizhDashboardSummaryService $summaryService
    ): BinaryFileResponse {
        [$periodStart, $periodEnd] = $periodResolver->custom(
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        $summary = $summaryService->summarize(
            user: $request->user(),
            dateFrom: $periodStart,
            dateUntil: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'ringkasan-dashboard-tahfizh-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new DashboardTahfizhSummaryExport($summary, $periodStart, $periodEnd),
            $filename
        );
    }

    public function dashboardPdf(
        TahfizhExportRequest $request,
        ReportPeriodResolver $periodResolver,
        TahfizhDashboardSummaryService $summaryService
    ): Response {
        [$periodStart, $periodEnd] = $periodResolver->custom(
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        $summary = $summaryService->summarize(
            user: $request->user(),
            dateFrom: $periodStart,
            dateUntil: $periodEnd,
            classRoomId: $this->nullableInteger($request->input('class_room_id')),
            studentId: $this->nullableInteger($request->input('student_id')),
            teacherId: $this->nullableInteger($request->input('teacher_id')),
            status: $request->input('status')
        );

        $filename = 'ringkasan-dashboard-tahfizh-' . $periodStart->format('Y-m-d') . '-' . $periodEnd->format('Y-m-d') . '.pdf';

        $pdf = Pdf::loadView('exports.pdf.dashboard-tahfizh-summary', [
            'summary' => $summary,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'generatedBy' => $request->user(),
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    private function nullableInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
```

---

# 16. View Export Index

Buat file:

```text
resources/views/exports/tahfizh/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Export Laporan Tahfizh</h2>
        <p class="text-sm text-slate-500">
            Export laporan tahfizh dalam format PDF dan Excel.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <div class="font-bold">Validasi gagal:</div>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-6">
        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Export Laporan Bulanan</h3>

            <form method="GET" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold">Bulan</label>
                    <input type="month" name="month" value="{{ now()->format('Y-m') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                @include('exports.tahfizh.partials.filters')

                <div class="flex items-end gap-2">
                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.monthly.excel') }}"
                            class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        Excel
                    </button>

                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.monthly.pdf') }}"
                            class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        PDF
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Export Laporan Triwulan</h3>

            <form method="GET" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold">Tahun</label>
                    <input type="number" name="year" value="{{ now()->format('Y') }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Triwulan</label>
                    <select name="quarter" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                        <option value="1">Triwulan 1</option>
                        <option value="2">Triwulan 2</option>
                        <option value="3">Triwulan 3</option>
                        <option value="4">Triwulan 4</option>
                    </select>
                </div>

                @include('exports.tahfizh.partials.filters')

                <div class="flex items-end gap-2">
                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.quarterly.excel') }}"
                            class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        Excel
                    </button>

                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.quarterly.pdf') }}"
                            class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        PDF
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Export Ringkasan Dashboard</h3>

            <form method="GET" class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold">Tanggal Mulai</label>
                    <input type="date" name="date_from" value="{{ now()->startOfMonth()->toDateString() }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold">Tanggal Akhir</label>
                    <input type="date" name="date_until" value="{{ now()->toDateString() }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>

                @include('exports.tahfizh.partials.filters')

                <div class="flex items-end gap-2">
                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.dashboard.excel') }}"
                            class="rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        Excel
                    </button>

                    <button type="submit"
                            formaction="{{ route('exports.tahfizh.dashboard.pdf') }}"
                            class="rounded-lg bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        PDF
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
```

---

# 17. Buat Partial Filter Export

Buat folder:

```powershell
mkdir resources\views\exports\tahfizh\partials
```

Buat file:

```text
resources/views/exports/tahfizh/partials/filters.blade.php
```

Isi:

```blade
<div>
    <label class="mb-1 block text-sm font-semibold">Kelas</label>
    <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Kelas</option>
        @foreach ($classRooms as $classRoom)
            <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Santri</label>
    <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Santri</option>
        @foreach ($students as $student)
            <option value="{{ $student->id }}">{{ $student->full_name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Guru</label>
    <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Guru</option>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="mb-1 block text-sm font-semibold">Status</label>
    <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2">
        <option value="">Semua Status</option>
        @foreach ($statuses as $status)
            <option value="{{ $status }}">{{ str_replace('_', ' ', strtoupper($status)) }}</option>
        @endforeach
    </select>
</div>
```

---

# 18. PDF View Bulanan

Buat file:

```text
resources/views/exports/pdf/monthly-tahfizh-report.blade.php
```

Isi lengkap:

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahfizh Bulanan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        h1, h2, h3 {
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 18px;
            border-bottom: 2px solid #111827;
            padding-bottom: 10px;
        }

        .meta {
            margin-top: 6px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Tahfizh Bulanan</h1>
        <div class="meta">
            Periode: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}<br>
            Dicetak oleh: {{ $generatedBy->name }}<br>
            Waktu cetak: {{ $generatedAt->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Santri</th>
                <th>Kelas</th>
                <th>Setoran</th>
                <th>Total Baris</th>
                <th>Target</th>
                <th>Hutang</th>
                <th>Lebih</th>
                <th>Akumulasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['student']?->full_name ?? '-' }}</td>
                    <td>{{ $row['class_room']?->name ?? '-' }}</td>
                    <td class="text-right">{{ $row['record_count'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['actual_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['target_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['debt_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['surplus_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['cumulative_debt_lines'] ?? 0 }}</td>
                    <td>{{ $row['status'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan otomatis oleh HafizPlus School Platform.
    </div>
</body>
</html>
```

---

# 19. PDF View Triwulan

Buat file:

```text
resources/views/exports/pdf/quarterly-tahfizh-report.blade.php
```

Isi lengkap:

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tahfizh Triwulan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        .header {
            margin-bottom: 18px;
            border-bottom: 2px solid #111827;
            padding-bottom: 10px;
        }

        .meta {
            margin-top: 6px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Tahfizh Triwulan</h1>
        <div class="meta">
            {{ $periodLabel }}<br>
            Periode: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}<br>
            Dicetak oleh: {{ $generatedBy->name }}<br>
            Waktu cetak: {{ $generatedAt->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Santri</th>
                <th>Kelas</th>
                <th>Setoran</th>
                <th>Total Baris</th>
                <th>Target</th>
                <th>Hutang</th>
                <th>Lebih</th>
                <th>Akumulasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['student']?->full_name ?? '-' }}</td>
                    <td>{{ $row['class_room']?->name ?? '-' }}</td>
                    <td class="text-right">{{ $row['record_count'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['actual_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['target_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['debt_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['surplus_lines'] ?? 0 }}</td>
                    <td class="text-right">{{ $row['cumulative_debt_lines'] ?? 0 }}</td>
                    <td>{{ $row['status'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan otomatis oleh HafizPlus School Platform.
    </div>
</body>
</html>
```

---

# 20. PDF View Dashboard Summary

Buat file:

```text
resources/views/exports/pdf/dashboard-tahfizh-summary.blade.php
```

Isi lengkap:

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Dashboard Tahfizh</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111827;
        }

        .header {
            margin-bottom: 18px;
            border-bottom: 2px solid #111827;
            padding-bottom: 10px;
        }

        .meta {
            margin-top: 6px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ringkasan Dashboard Tahfizh</h1>
        <div class="meta">
            Periode: {{ $periodStart->format('d/m/Y') }} - {{ $periodEnd->format('d/m/Y') }}<br>
            Dicetak oleh: {{ $generatedBy->name }}<br>
            Waktu cetak: {{ $generatedAt->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Metrik</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Santri</td>
                <td class="text-right">{{ $summary['total_students'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Setoran</td>
                <td class="text-right">{{ $summary['total_records'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Baris</td>
                <td class="text-right">{{ $summary['total_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Hutang Baris</td>
                <td class="text-right">{{ $summary['total_debt_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Lebih Baris</td>
                <td class="text-right">{{ $summary['total_surplus_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Total Akumulasi Hutang</td>
                <td class="text-right">{{ $summary['total_cumulative_debt_lines'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Tertinggal</td>
                <td class="text-right">{{ $summary['behind_count'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Tercapai</td>
                <td class="text-right">{{ $summary['met_count'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Lebih Target</td>
                <td class="text-right">{{ $summary['ahead_count'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Santri Tanpa Target</td>
                <td class="text-right">{{ $summary['no_target_count'] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan otomatis oleh HafizPlus School Platform.
    </div>
</body>
</html>
```

---

# 21. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Exports\TahfizhExportController;
```

Di dalam group `auth`, tambahkan:

```php
Route::middleware('role:super_admin,admin,principal,teacher')
    ->prefix('exports/tahfizh')
    ->name('exports.tahfizh.')
    ->group(function (): void {
        Route::get('/', [TahfizhExportController::class, 'index'])
            ->name('index');

        Route::get('monthly/excel', [TahfizhExportController::class, 'monthlyExcel'])
            ->name('monthly.excel');

        Route::get('monthly/pdf', [TahfizhExportController::class, 'monthlyPdf'])
            ->name('monthly.pdf');

        Route::get('quarterly/excel', [TahfizhExportController::class, 'quarterlyExcel'])
            ->name('quarterly.excel');

        Route::get('quarterly/pdf', [TahfizhExportController::class, 'quarterlyPdf'])
            ->name('quarterly.pdf');

        Route::get('dashboard/excel', [TahfizhExportController::class, 'dashboardExcel'])
            ->name('dashboard.excel');

        Route::get('dashboard/pdf', [TahfizhExportController::class, 'dashboardPdf'])
            ->name('dashboard.pdf');
    });
```

---

# 22. Update Navigasi

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan link untuk role admin, kepala sekolah, dan guru:

```blade
@if (auth()->user()->hasRole(['super_admin', 'admin', 'principal', 'teacher']))
    <a href="{{ route('exports.tahfizh.index') }}"
       class="font-semibold text-slate-700 hover:text-slate-950">
        Export Laporan
    </a>
@endif
```

Sesuaikan class dengan style navigation yang sudah ada.

---

# 23. Validasi Route

Jalankan:

```powershell
php artisan route:list
```

Pastikan route berikut muncul:

```text
exports.tahfizh.index
exports.tahfizh.monthly.excel
exports.tahfizh.monthly.pdf
exports.tahfizh.quarterly.excel
exports.tahfizh.quarterly.pdf
exports.tahfizh.dashboard.excel
exports.tahfizh.dashboard.pdf
```

---

# 24. Build Frontend

Jalankan:

```powershell
npm run build
```

---

# 25. Test Manual Export

Jalankan server:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/exports/tahfizh
```

Login sebagai:

```text
admin@hafizplus.test
password
```

Test:

1. Export bulanan Excel.
2. Export bulanan PDF.
3. Export triwulan Excel.
4. Export triwulan PDF.
5. Export dashboard Excel.
6. Export dashboard PDF.

Target:

1. File `.xlsx` berhasil terunduh.
2. File `.pdf` berhasil terunduh.
3. Nama file sesuai periode.
4. Isi file sesuai data filter.
5. Tidak ada error permission.
6. Parent dan student tidak bisa mengakses halaman export.

---

# 26. Test Akses Role

## 26.1 Admin

Buka:

```text
/exports/tahfizh
```

Target:

```text
Bisa akses
```

## 26.2 Kepala Sekolah

Buka:

```text
/exports/tahfizh
```

Target:

```text
Bisa akses
```

## 26.3 Guru

Buka:

```text
/exports/tahfizh
```

Target:

```text
Bisa akses
```

Catatan:

Jika service report Phase 6 sudah membatasi guru hanya pada data guru tersebut, export juga mengikuti pembatasan itu.

## 26.4 Parent

Buka:

```text
/exports/tahfizh
```

Target:

```text
403 Forbidden
```

## 26.5 Student

Buka:

```text
/exports/tahfizh
```

Target:

```text
403 Forbidden
```

---

# 27. Troubleshooting

## 27.1 Composer Error `ext-zip`

Aktifkan extension zip.

Buka:

```text
C:\xampp\php\php.ini
```

Cari:

```ini
;extension=zip
```

Ubah:

```ini
extension=zip
```

Restart terminal.

Cek:

```powershell
php -m | findstr /i "zip"
```

---

## 27.2 Composer Error `ext-gd`

Aktifkan extension gd.

Buka:

```text
C:\xampp\php\php.ini
```

Cari:

```ini
;extension=gd
```

Ubah:

```ini
extension=gd
```

Restart terminal.

Cek:

```powershell
php -m | findstr /i "gd"
```

---

## 27.3 Error `Class "Maatwebsite\Excel\Facades\Excel" not found`

Jalankan:

```powershell
composer dump-autoload
php artisan optimize:clear
```

Pastikan package terpasang:

```powershell
composer show maatwebsite/excel
```

---

## 27.4 Error `Class "Barryvdh\DomPDF\Facade\Pdf" not found`

Jalankan:

```powershell
composer dump-autoload
php artisan optimize:clear
```

Pastikan package terpasang:

```powershell
composer show barryvdh/laravel-dompdf
```

---

## 27.5 PDF Berantakan

Pastikan PDF view tidak memakai Tailwind/Vite langsung.

Gunakan CSS sederhana di dalam file Blade PDF.

Jangan mengandalkan:

```blade
@vite
```

di PDF view.

---

## 27.6 PDF Lambat

Solusi sementara Phase 9:

1. Filter data lebih kecil.
2. Jangan export semua kelas sekaligus jika data besar.
3. Mulai dari bulan/triwulan tertentu.
4. Queue export masuk Phase production hardening, bukan Phase 9.

---

# 28. Dokumentasi Phase 9

Buat file:

```text
docs/phase-9-export-pdf-excel.md
```

Isi lengkap:

````md
# Phase 9 — Export PDF and Excel

## Status

Phase 9 membangun fitur export laporan tahfizh dalam format PDF dan Excel.

## Package

```text
maatwebsite/excel
barryvdh/laravel-dompdf
````

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

````

---

# 29. Update Project Progress

Buka:

```text
docs/project-progress.md
````

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
| 10 | Production Hardening | Pending |
```

---

# 30. Validasi Akhir

Jalankan:

```powershell
php artisan route:list
php artisan migrate:status
npm run build
```

Lalu:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/exports/tahfizh
```

---

# 31. Commit Phase 9

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add tahfizh PDF and Excel exports"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-9-export-pdf-excel
```

---

# 32. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 9 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Export laporan bulanan PDF
- Export laporan bulanan Excel
- Export laporan triwulan PDF
- Export laporan triwulan Excel
- Export ringkasan dashboard PDF
- Export ringkasan dashboard Excel
- Halaman Export Laporan
- Role-based export access

Package digunakan:
- maatwebsite/excel
- barryvdh/laravel-dompdf

Route dibuat:
- exports.tahfizh.index
- exports.tahfizh.monthly.excel
- exports.tahfizh.monthly.pdf
- exports.tahfizh.quarterly.excel
- exports.tahfizh.quarterly.pdf
- exports.tahfizh.dashboard.excel
- exports.tahfizh.dashboard.pdf

Belum dibuat:
- Import Excel
- Queue export
- Email export
- Scheduled export
- Parent/student personal export
- Export audit log khusus

Status:
- Siap lanjut Phase 10 setelah validasi manual.
```

---

# 33. Larangan Setelah Phase 9

Agent harus berhenti setelah Phase 9 selesai.

Jangan lanjut membuat:

1. Production hardening.
2. Backup automation.
3. API mobile.
4. Queue export.
5. Email export.
6. WhatsApp export.
7. Attendance.
8. Mutabaah.
9. Tahsin.
10. Finance.
11. Cashless.
12. Multi-tenant.

Semua itu masuk fase berikutnya.
