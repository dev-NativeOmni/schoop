# Phase 6 Execution Guide — Dashboard and Reports

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

# 1. Tujuan Phase 6

Phase 6 bertujuan membuat dashboard dan laporan awal berdasarkan data yang sudah dibuat pada fase sebelumnya.

Data sumber:

1. `students`
2. `class_rooms`
3. `teacher_profiles`
4. `hafalan_records`
5. `tahfizh_targets`
6. `tahfizh_debts`
7. `users`

Output utama Phase 6:

1. Dashboard Tahfizh.
2. Dashboard ringkas per role.
3. Laporan bulanan tahfizh.
4. Laporan triwulan tahfizh.
5. Filter kelas, santri, guru, status, dan periode.
6. Ringkasan capaian target.
7. Ringkasan hutang hafalan.
8. Daftar santri tertinggal.
9. Daftar guru aktif input.
10. Dokumentasi Phase 6.

---

# 2. Batasan Phase 6

AI agent tidak boleh membuat fitur berikut pada Phase 6:

1. Export PDF.
2. Export Excel.
3. Parent portal detail.
4. Student portal detail.
5. Notification center.
6. WhatsApp gateway.
7. API mobile.
8. Chart.js.
9. Recharts.
10. Livewire.
11. Filament.
12. Payment.
13. Attendance.
14. Mutabaah.
15. Tahsin.
16. Finance.
17. Cashless.
18. Multi-tenant kompleks.

Phase 6 cukup memakai:

1. Laravel Controller.
2. Form Request.
3. Service class.
4. Blade.
5. Eloquent query.
6. Tabel HTML.
7. Card statistik sederhana.

Jangan memasang package baru.

---

# 3. Target Output Phase 6

Setelah Phase 6 selesai, aplikasi harus punya:

1. Menu **Dashboard Tahfizh**.
2. Menu **Laporan Bulanan**.
3. Menu **Laporan Triwulan**.
4. Controller:

   * `TahfizhDashboardController`
   * `MonthlyTahfizhReportController`
   * `QuarterlyTahfizhReportController`
5. Request:

   * `DashboardFilterRequest`
   * `MonthlyTahfizhReportRequest`
   * `QuarterlyTahfizhReportRequest`
6. Service:

   * `ReportPeriodResolver`
   * `TahfizhDashboardSummaryService`
   * `MonthlyTahfizhReportService`
   * `QuarterlyTahfizhReportService`
7. View:

   * `dashboard/index.blade.php`
   * `reports/monthly/index.blade.php`
   * `reports/monthly/show.blade.php`
   * `reports/quarterly/index.blade.php`
   * `reports/quarterly/show.blade.php`
8. Role access:

   * Super Admin: lihat semua dashboard dan report.
   * Admin Sekolah: lihat semua dashboard dan report sekolah.
   * Kepala Sekolah: lihat semua dashboard dan report.
   * Guru Tahfidz: lihat data input dan santri terkait, minimal read-only.
   * Orang Tua: belum akses.
   * Santri: belum akses.

---

# 4. Validasi Awal Sebelum Eksekusi

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
7. Tabel berikut sudah ada:

   * `students`
   * `class_rooms`
   * `users`
   * `roles`
   * `hafalan_records`
   * `tahfizh_targets`
   * `tahfizh_debts`
8. Route target dan hutang sudah tersedia.
9. Working tree bersih atau perubahan sudah diketahui.

Jika Phase 5 belum selesai, hentikan eksekusi.

---

# 5. Buat Branch Git Phase 6

Jalankan:

```powershell
git checkout -b phase-6-dashboard-and-reports
```

Jika branch sudah ada:

```powershell
git checkout phase-6-dashboard-and-reports
```

---

# 6. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Reports/
│   │       ├── TahfizhDashboardController.php
│   │       ├── MonthlyTahfizhReportController.php
│   │       └── QuarterlyTahfizhReportController.php
│   └── Requests/
│       └── Reports/
│           ├── DashboardFilterRequest.php
│           ├── MonthlyTahfizhReportRequest.php
│           └── QuarterlyTahfizhReportRequest.php
├── Services/
│   └── Reports/
│       ├── ReportPeriodResolver.php
│       ├── TahfizhDashboardSummaryService.php
│       ├── MonthlyTahfizhReportService.php
│       └── QuarterlyTahfizhReportService.php

resources/
└── views/
    └── reports/
        └── tahfizh/
            ├── dashboard/
            │   └── index.blade.php
            ├── monthly/
            │   ├── index.blade.php
            │   └── show.blade.php
            └── quarterly/
                ├── index.blade.php
                └── show.blade.php

routes/
└── web.php

docs/
└── phase-6-dashboard-and-reports.md
```

---

# 7. Buat Controller dan Request

Jalankan:

```powershell
php artisan make:controller Reports/TahfizhDashboardController
php artisan make:controller Reports/MonthlyTahfizhReportController
php artisan make:controller Reports/QuarterlyTahfizhReportController

php artisan make:request Reports/DashboardFilterRequest
php artisan make:request Reports/MonthlyTahfizhReportRequest
php artisan make:request Reports/QuarterlyTahfizhReportRequest
```

Buat folder service:

```powershell
mkdir app\Services\Reports
```

Buat file service:

```powershell
New-Item app\Services\Reports\ReportPeriodResolver.php
New-Item app\Services\Reports\TahfizhDashboardSummaryService.php
New-Item app\Services\Reports\MonthlyTahfizhReportService.php
New-Item app\Services\Reports\QuarterlyTahfizhReportService.php
```

---

# 8. Request `DashboardFilterRequest`

Buka:

```text
app/Http/Requests/Reports/DashboardFilterRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class DashboardFilterRequest extends FormRequest
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

# 9. Request `MonthlyTahfizhReportRequest`

Buka:

```text
app/Http/Requests/Reports/MonthlyTahfizhReportRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyTahfizhReportRequest extends FormRequest
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
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'month' => 'bulan laporan',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'status' => 'status',
        ];
    }
}
```

---

# 10. Request `QuarterlyTahfizhReportRequest`

Buka:

```text
app/Http/Requests/Reports/QuarterlyTahfizhReportRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuarterlyTahfizhReportRequest extends FormRequest
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
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
            'quarter' => ['nullable', 'integer', Rule::in([1, 2, 3, 4])],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'year' => 'tahun',
            'quarter' => 'triwulan',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'status' => 'status',
        ];
    }
}
```

---

# 11. Service `ReportPeriodResolver`

Buka:

```text
app/Services/Reports/ReportPeriodResolver.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Reports;

use Illuminate\Support\Carbon;
use InvalidArgumentException;

class ReportPeriodResolver
{
    public function custom(?string $dateFrom, ?string $dateUntil): array
    {
        $start = $dateFrom
            ? Carbon::parse($dateFrom)->startOfDay()
            : now()->startOfMonth();

        $end = $dateUntil
            ? Carbon::parse($dateUntil)->endOfDay()
            : now()->endOfDay();

        if ($end->lt($start)) {
            throw new InvalidArgumentException('Tanggal akhir tidak boleh lebih awal dari tanggal mulai.');
        }

        return [$start, $end];
    }

    public function month(?string $month): array
    {
        $date = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : now()->startOfMonth();

        return [
            $date->copy()->startOfMonth(),
            $date->copy()->endOfMonth(),
        ];
    }

    public function quarter(?int $year, ?int $quarter): array
    {
        $year = $year ?: (int) now()->format('Y');
        $quarter = $quarter ?: (int) ceil(now()->month / 3);

        if (! in_array($quarter, [1, 2, 3, 4], true)) {
            throw new InvalidArgumentException('Triwulan harus bernilai 1 sampai 4.');
        }

        $startMonth = (($quarter - 1) * 3) + 1;

        $start = Carbon::create($year, $startMonth, 1)->startOfMonth();
        $end = $start->copy()->addMonths(2)->endOfMonth();

        return [$start, $end];
    }

    public function quarterLabel(?int $year, ?int $quarter): string
    {
        $year = $year ?: (int) now()->format('Y');
        $quarter = $quarter ?: (int) ceil(now()->month / 3);

        return "Triwulan {$quarter} Tahun {$year}";
    }
}
```

---

# 12. Service `TahfizhDashboardSummaryService`

Buka:

```text
app/Services/Reports/TahfizhDashboardSummaryService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Reports;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

class TahfizhDashboardSummaryService
{
    public function summarize(
        User $user,
        CarbonInterface $dateFrom,
        CarbonInterface $dateUntil,
        ?int $classRoomId = null,
        ?int $studentId = null,
        ?int $teacherId = null,
        ?string $status = null
    ): array {
        $studentQuery = Student::query()
            ->where('is_active', true)
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('id', $studentId);
            });

        $recordQuery = HafalanRecord::query()
            ->whereBetween('record_date', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->whereHas('student', function (Builder $studentQuery) use ($classRoomId): void {
                    $studentQuery->where('class_room_id', $classRoomId);
                });
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('student_id', $studentId);
            })
            ->when($teacherId, function (Builder $query) use ($teacherId): void {
                $query->where('teacher_id', $teacherId);
            })
            ->when($status, function (Builder $query) use ($status): void {
                $query->where('status', $status);
            });

        if ($user->hasRole('teacher')) {
            $recordQuery->where('teacher_id', $user->id);
        }

        $debtQuery = TahfizhDebt::query()
            ->whereBetween('period_start', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('student_id', $studentId);
            });

        $totalStudents = (clone $studentQuery)->count();
        $totalRecords = (clone $recordQuery)->count();
        $totalLines = (int) (clone $recordQuery)->sum('total_lines');

        $totalDebtLines = (int) (clone $debtQuery)->sum('debt_lines');
        $totalSurplusLines = (int) (clone $debtQuery)->sum('surplus_lines');
        $totalCumulativeDebtLines = (int) (clone $debtQuery)->sum('cumulative_debt_lines');

        $behindCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_BEHIND)
            ->count();

        $metCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_MET)
            ->count();

        $aheadCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_AHEAD)
            ->count();

        $noTargetCount = (clone $debtQuery)
            ->where('status', TahfizhDebt::STATUS_NO_TARGET)
            ->count();

        $teacherActivity = HafalanRecord::query()
            ->selectRaw('teacher_id, COUNT(*) as total_records, SUM(total_lines) as total_lines')
            ->with('teacher')
            ->whereBetween('record_date', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->whereHas('student', function (Builder $studentQuery) use ($classRoomId): void {
                    $studentQuery->where('class_room_id', $classRoomId);
                });
            })
            ->when($user->hasRole('teacher'), function (Builder $query) use ($user): void {
                $query->where('teacher_id', $user->id);
            })
            ->groupBy('teacher_id')
            ->orderByDesc('total_records')
            ->limit(10)
            ->get();

        $atRiskStudents = TahfizhDebt::query()
            ->with(['student.classRoom'])
            ->where('status', TahfizhDebt::STATUS_BEHIND)
            ->whereBetween('period_start', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ])
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->orderByDesc('cumulative_debt_lines')
            ->limit(10)
            ->get();

        return [
            'total_students' => $totalStudents,
            'total_records' => $totalRecords,
            'total_lines' => $totalLines,
            'total_debt_lines' => $totalDebtLines,
            'total_surplus_lines' => $totalSurplusLines,
            'total_cumulative_debt_lines' => $totalCumulativeDebtLines,
            'behind_count' => $behindCount,
            'met_count' => $metCount,
            'ahead_count' => $aheadCount,
            'no_target_count' => $noTargetCount,
            'teacher_activity' => $teacherActivity,
            'at_risk_students' => $atRiskStudents,
        ];
    }
}
```

---

# 13. Service `MonthlyTahfizhReportService`

Buka:

```text
app/Services/Reports/MonthlyTahfizhReportService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Reports;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MonthlyTahfizhReportService
{
    public function build(
        User $user,
        CarbonInterface $periodStart,
        CarbonInterface $periodEnd,
        ?int $classRoomId = null,
        ?int $studentId = null,
        ?int $teacherId = null,
        ?string $status = null
    ): Collection {
        $students = Student::query()
            ->with(['classRoom', 'school'])
            ->where('is_active', true)
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('id', $studentId);
            })
            ->orderBy('full_name')
            ->get();

        return $students->map(function (Student $student) use (
            $user,
            $periodStart,
            $periodEnd,
            $teacherId,
            $status
        ): array {
            $recordQuery = HafalanRecord::query()
                ->where('student_id', $student->id)
                ->whereBetween('record_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])
                ->when($teacherId, function (Builder $query) use ($teacherId): void {
                    $query->where('teacher_id', $teacherId);
                })
                ->when($status, function (Builder $query) use ($status): void {
                    $query->where('status', $status);
                });

            if ($user->hasRole('teacher')) {
                $recordQuery->where('teacher_id', $user->id);
            }

            $actualLines = (int) (clone $recordQuery)->sum('total_lines');
            $recordCount = (clone $recordQuery)->count();

            $debt = TahfizhDebt::query()
                ->where('student_id', $student->id)
                ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
                ->whereDate('period_start', $periodStart->toDateString())
                ->whereDate('period_end', $periodEnd->toDateString())
                ->first();

            return [
                'student' => $student,
                'class_room' => $student->classRoom,
                'record_count' => $recordCount,
                'actual_lines' => $actualLines,
                'target_lines' => $debt?->target_lines ?? 0,
                'debt_lines' => $debt?->debt_lines ?? 0,
                'surplus_lines' => $debt?->surplus_lines ?? 0,
                'cumulative_debt_lines' => $debt?->cumulative_debt_lines ?? 0,
                'status' => $debt?->status ?? TahfizhDebt::STATUS_NO_TARGET,
                'notes' => $debt?->notes ?? 'Belum ada perhitungan hutang bulanan.',
            ];
        });
    }
}
```

---

# 14. Service `QuarterlyTahfizhReportService`

Buka:

```text
app/Services/Reports/QuarterlyTahfizhReportService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Reports;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class QuarterlyTahfizhReportService
{
    public function build(
        User $user,
        CarbonInterface $periodStart,
        CarbonInterface $periodEnd,
        ?int $classRoomId = null,
        ?int $studentId = null,
        ?int $teacherId = null,
        ?string $status = null
    ): Collection {
        $students = Student::query()
            ->with(['classRoom', 'school'])
            ->where('is_active', true)
            ->when($classRoomId, function (Builder $query) use ($classRoomId): void {
                $query->where('class_room_id', $classRoomId);
            })
            ->when($studentId, function (Builder $query) use ($studentId): void {
                $query->where('id', $studentId);
            })
            ->orderBy('full_name')
            ->get();

        return $students->map(function (Student $student) use (
            $user,
            $periodStart,
            $periodEnd,
            $teacherId,
            $status
        ): array {
            $recordQuery = HafalanRecord::query()
                ->where('student_id', $student->id)
                ->whereBetween('record_date', [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ])
                ->when($teacherId, function (Builder $query) use ($teacherId): void {
                    $query->where('teacher_id', $teacherId);
                })
                ->when($status, function (Builder $query) use ($status): void {
                    $query->where('status', $status);
                });

            if ($user->hasRole('teacher')) {
                $recordQuery->where('teacher_id', $user->id);
            }

            $actualLines = (int) (clone $recordQuery)->sum('total_lines');
            $recordCount = (clone $recordQuery)->count();

            $monthlyDebts = TahfizhDebt::query()
                ->where('student_id', $student->id)
                ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
                ->whereDate('period_start', '>=', $periodStart->toDateString())
                ->whereDate('period_end', '<=', $periodEnd->toDateString())
                ->orderBy('period_start')
                ->get();

            $targetLines = (int) $monthlyDebts->sum('target_lines');
            $debtLines = (int) $monthlyDebts->sum('debt_lines');
            $surplusLines = (int) $monthlyDebts->sum('surplus_lines');
            $cumulativeDebtLines = (int) ($monthlyDebts->last()?->cumulative_debt_lines ?? 0);

            $statusValue = match (true) {
                $targetLines <= 0 => TahfizhDebt::STATUS_NO_TARGET,
                $debtLines > 0 => TahfizhDebt::STATUS_BEHIND,
                $surplusLines > 0 => TahfizhDebt::STATUS_AHEAD,
                default => TahfizhDebt::STATUS_MET,
            };

            return [
                'student' => $student,
                'class_room' => $student->classRoom,
                'record_count' => $recordCount,
                'actual_lines' => $actualLines,
                'target_lines' => $targetLines,
                'debt_lines' => $debtLines,
                'surplus_lines' => $surplusLines,
                'cumulative_debt_lines' => $cumulativeDebtLines,
                'status' => $statusValue,
                'monthly_debts' => $monthlyDebts,
            ];
        });
    }
}
```

---

# 15. Controller `TahfizhDashboardController`

Buka:

```text
app/Http/Controllers/Reports/TahfizhDashboardController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\DashboardFilterRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\ReportPeriodResolver;
use App\Services\Reports\TahfizhDashboardSummaryService;
use Illuminate\View\View;

class TahfizhDashboardController extends Controller
{
    public function __construct(
        private readonly ReportPeriodResolver $periodResolver,
        private readonly TahfizhDashboardSummaryService $summaryService,
    ) {
        //
    }

    public function __invoke(DashboardFilterRequest $request): View
    {
        [$dateFrom, $dateUntil] = $this->periodResolver->custom(
            $request->input('date_from'),
            $request->input('date_until')
        );

        $summary = $this->summaryService->summarize(
            user: $request->user(),
            dateFrom: $dateFrom,
            dateUntil: $dateUntil,
            classRoomId: $request->integer('class_room_id') ?: null,
            studentId: $request->integer('student_id') ?: null,
            teacherId: $request->integer('teacher_id') ?: null,
            status: $request->input('status')
        );

        return view('reports.tahfizh.dashboard.index', [
            'summary' => $summary,
            'dateFrom' => $dateFrom,
            'dateUntil' => $dateUntil,
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ],
        ]);
    }
}
```

---

# 16. Controller `MonthlyTahfizhReportController`

Buka:

```text
app/Http/Controllers/Reports/MonthlyTahfizhReportController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\MonthlyTahfizhReportRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\MonthlyTahfizhReportService;
use App\Services\Reports\ReportPeriodResolver;
use Illuminate\View\View;

class MonthlyTahfizhReportController extends Controller
{
    public function __construct(
        private readonly ReportPeriodResolver $periodResolver,
        private readonly MonthlyTahfizhReportService $reportService,
    ) {
        //
    }

    public function index(MonthlyTahfizhReportRequest $request): View
    {
        [$periodStart, $periodEnd] = $this->periodResolver->month(
            $request->input('month')
        );

        $rows = $this->reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $request->integer('class_room_id') ?: null,
            studentId: $request->integer('student_id') ?: null,
            teacherId: $request->integer('teacher_id') ?: null,
            status: $request->input('status')
        );

        return view('reports.tahfizh.monthly.index', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'month' => $periodStart->format('Y-m'),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ],
        ]);
    }

    public function show(MonthlyTahfizhReportRequest $request, Student $student): View
    {
        [$periodStart, $periodEnd] = $this->periodResolver->month(
            $request->input('month')
        );

        $records = $student->hafalanRecords()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->latest('record_date')
            ->get();

        return view('reports.tahfizh.monthly.show', [
            'student' => $student->load(['classRoom', 'school']),
            'records' => $records,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'month' => $periodStart->format('Y-m'),
        ]);
    }
}
```

---

# 17. Controller `QuarterlyTahfizhReportController`

Buka:

```text
app/Http/Controllers/Reports/QuarterlyTahfizhReportController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\QuarterlyTahfizhReportRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\Reports\QuarterlyTahfizhReportService;
use App\Services\Reports\ReportPeriodResolver;
use Illuminate\View\View;

class QuarterlyTahfizhReportController extends Controller
{
    public function __construct(
        private readonly ReportPeriodResolver $periodResolver,
        private readonly QuarterlyTahfizhReportService $reportService,
    ) {
        //
    }

    public function index(QuarterlyTahfizhReportRequest $request): View
    {
        $year = $request->integer('year') ?: (int) now()->format('Y');
        $quarter = $request->integer('quarter') ?: (int) ceil(now()->month / 3);

        [$periodStart, $periodEnd] = $this->periodResolver->quarter(
            $year,
            $quarter
        );

        $rows = $this->reportService->build(
            user: $request->user(),
            periodStart: $periodStart,
            periodEnd: $periodEnd,
            classRoomId: $request->integer('class_room_id') ?: null,
            studentId: $request->integer('student_id') ?: null,
            teacherId: $request->integer('teacher_id') ?: null,
            status: $request->input('status')
        );

        return view('reports.tahfizh.quarterly.index', [
            'rows' => $rows,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'year' => $year,
            'quarter' => $quarter,
            'label' => $this->periodResolver->quarterLabel($year, $quarter),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ],
        ]);
    }

    public function show(QuarterlyTahfizhReportRequest $request, Student $student): View
    {
        $year = $request->integer('year') ?: (int) now()->format('Y');
        $quarter = $request->integer('quarter') ?: (int) ceil(now()->month / 3);

        [$periodStart, $periodEnd] = $this->periodResolver->quarter(
            $year,
            $quarter
        );

        $records = $student->hafalanRecords()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->latest('record_date')
            ->get();

        return view('reports.tahfizh.quarterly.show', [
            'student' => $student->load(['classRoom', 'school']),
            'records' => $records,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'year' => $year,
            'quarter' => $quarter,
            'label' => $this->periodResolver->quarterLabel($year, $quarter),
        ]);
    }
}
```

---

# 18. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Reports\MonthlyTahfizhReportController;
use App\Http\Controllers\Reports\QuarterlyTahfizhReportController;
use App\Http\Controllers\Reports\TahfizhDashboardController;
```

Di dalam group `Route::middleware('auth')->group(...)`, tambahkan:

```php
Route::middleware('role:super_admin,admin,principal,teacher')
    ->prefix('reports/tahfizh')
    ->name('reports.tahfizh.')
    ->group(function (): void {
        Route::get('dashboard', TahfizhDashboardController::class)
            ->name('dashboard');

        Route::get('monthly', [MonthlyTahfizhReportController::class, 'index'])
            ->name('monthly.index');

        Route::get('monthly/students/{student}', [MonthlyTahfizhReportController::class, 'show'])
            ->name('monthly.show');

        Route::get('quarterly', [QuarterlyTahfizhReportController::class, 'index'])
            ->name('quarterly.index');

        Route::get('quarterly/students/{student}', [QuarterlyTahfizhReportController::class, 'show'])
            ->name('quarterly.show');
    });
```

---

# 19. Update Navigasi Layout

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan menu:

```blade
@if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher', 'principal']))
    <a href="{{ route('reports.tahfizh.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Dashboard Tahfizh
    </a>

    <a href="{{ route('reports.tahfizh.monthly.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Laporan Bulanan
    </a>

    <a href="{{ route('reports.tahfizh.quarterly.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Laporan Triwulan
    </a>
@endif
```

---

# 20. Buat Folder View

Jalankan:

```powershell
mkdir resources\views\reports
mkdir resources\views\reports\tahfizh
mkdir resources\views\reports\tahfizh\dashboard
mkdir resources\views\reports\tahfizh\monthly
mkdir resources\views\reports\tahfizh\quarterly
```

---

# 21. View Dashboard Tahfizh

Buat file:

```text
resources/views/reports/tahfizh/dashboard/index.blade.php
```

Isi minimal yang wajib ada:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Dashboard Tahfizh</h2>
        <p class="text-sm text-slate-500">
            Ringkasan setoran, target, dan hutang hafalan.
        </p>
    </div>

    <form method="GET" action="{{ route('reports.tahfizh.dashboard') }}"
          class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-6">
        <div>
            <label class="mb-1 block text-sm font-semibold">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from', $dateFrom->format('Y-m-d')) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Sampai</label>
            <input type="date" name="date_until" value="{{ request('date_until', $dateUntil->format('Y-m-d')) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Kelas</label>
            <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                        {{ $classRoom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Santri</label>
            <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                        {{ $student->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Guru</label>
            <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Terapkan
            </button>
        </div>
    </form>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Total Santri</div>
            <div class="mt-2 text-3xl font-bold">{{ $summary['total_students'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Total Setoran</div>
            <div class="mt-2 text-3xl font-bold">{{ $summary['total_records'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Total Baris</div>
            <div class="mt-2 text-3xl font-bold">{{ $summary['total_lines'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Akumulasi Hutang</div>
            <div class="mt-2 text-3xl font-bold">{{ $summary['total_cumulative_debt_lines'] }}</div>
        </div>
    </div>

    <div class="mt-6 grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Tercapai</div>
            <div class="mt-2 text-2xl font-bold">{{ $summary['met_count'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Kurang</div>
            <div class="mt-2 text-2xl font-bold">{{ $summary['behind_count'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Lebih</div>
            <div class="mt-2 text-2xl font-bold">{{ $summary['ahead_count'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Belum Ada Target</div>
            <div class="mt-2 text-2xl font-bold">{{ $summary['no_target_count'] }}</div>
        </div>
    </div>

    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Aktivitas Guru</h3>

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Guru</th>
                        <th class="py-2">Setoran</th>
                        <th class="py-2">Baris</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($summary['teacher_activity'] as $activity)
                        <tr class="border-b">
                            <td class="py-2">{{ $activity->teacher?->name ?? '-' }}</td>
                            <td class="py-2">{{ $activity->total_records }}</td>
                            <td class="py-2">{{ $activity->total_lines }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-500">
                                Belum ada aktivitas guru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Santri Perlu Perhatian</h3>

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Santri</th>
                        <th class="py-2">Kelas</th>
                        <th class="py-2">Hutang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($summary['at_risk_students'] as $debt)
                        <tr class="border-b">
                            <td class="py-2">{{ $debt->student?->full_name ?? '-' }}</td>
                            <td class="py-2">{{ $debt->student?->classRoom?->name ?? '-' }}</td>
                            <td class="py-2">{{ $debt->cumulative_debt_lines }} baris</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-500">
                                Belum ada santri tertinggal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
```

---

# 22. View Laporan Bulanan

Buat file:

```text
resources/views/reports/tahfizh/monthly/index.blade.php
```

Isi minimal yang wajib ada:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Laporan Bulanan Tahfizh</h2>
        <p class="text-sm text-slate-500">
            Periode {{ $periodStart->format('d/m/Y') }} sampai {{ $periodEnd->format('d/m/Y') }}
        </p>
    </div>

    <form method="GET" action="{{ route('reports.tahfizh.monthly.index') }}"
          class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-6">
        <div>
            <label class="mb-1 block text-sm font-semibold">Bulan</label>
            <input type="month" name="month" value="{{ request('month', $month) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Kelas</label>
            <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($classRooms as $classRoom)
                    <option value="{{ $classRoom->id }}" @selected(request('class_room_id') == $classRoom->id)>
                        {{ $classRoom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Santri</label>
            <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>
                        {{ $student->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Guru</label>
            <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected(request('teacher_id') == $teacher->id)>
                        {{ $teacher->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                <option value="">Semua</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ strtoupper(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Tampilkan
            </button>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Santri</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Setoran</th>
                    <th class="px-4 py-3">Target</th>
                    <th class="px-4 py-3">Capaian</th>
                    <th class="px-4 py-3">Hutang</th>
                    <th class="px-4 py-3">Lebih</th>
                    <th class="px-4 py-3">Akumulasi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr class="border-t">
                        <td class="px-4 py-3 font-semibold">{{ $row['student']->full_name }}</td>
                        <td class="px-4 py-3">{{ $row['class_room']?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $row['record_count'] }}</td>
                        <td class="px-4 py-3">{{ $row['target_lines'] }}</td>
                        <td class="px-4 py-3">{{ $row['actual_lines'] }}</td>
                        <td class="px-4 py-3">{{ $row['debt_lines'] }}</td>
                        <td class="px-4 py-3">{{ $row['surplus_lines'] }}</td>
                        <td class="px-4 py-3">{{ $row['cumulative_debt_lines'] }}</td>
                        <td class="px-4 py-3">{{ strtoupper(str_replace('_', ' ', $row['status'])) }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('reports.tahfizh.monthly.show', ['student' => $row['student']->id, 'month' => $month]) }}"
                               class="text-blue-700 hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-6 text-center text-slate-500">
                            Belum ada data laporan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
```

Buat file:

```text
resources/views/reports/tahfizh/monthly/show.blade.php
```

Isi minimal:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Laporan Bulanan</h2>
            <p class="text-sm text-slate-500">
                {{ $student->full_name }} — {{ $periodStart->format('d/m/Y') }} sampai {{ $periodEnd->format('d/m/Y') }}
            </p>
        </div>

        <a href="{{ route('reports.tahfizh.monthly.index', ['month' => $month]) }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Guru</th>
                    <th class="px-4 py-3">Rentang</th>
                    <th class="px-4 py-3">Total Baris</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $record->record_date?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $record->teacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            Hlm {{ $record->start_page }}:{{ $record->start_line }}
                            —
                            Hlm {{ $record->end_page }}:{{ $record->end_line }}
                        </td>
                        <td class="px-4 py-3">{{ $record->total_lines }}</td>
                        <td class="px-4 py-3">{{ strtoupper(str_replace('_', ' ', $record->status)) }}</td>
                        <td class="px-4 py-3">{{ $record->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-slate-500">
                            Belum ada setoran pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
```

---

# 23. View Laporan Triwulan

Buat file:

```text
resources/views/reports/tahfizh/quarterly/index.blade.php
```

Gunakan struktur yang sama dengan laporan bulanan, tetapi filter memakai:

```blade
<input type="number" name="year" value="{{ request('year', $year) }}">
<select name="quarter">
    <option value="1">Triwulan 1</option>
    <option value="2">Triwulan 2</option>
    <option value="3">Triwulan 3</option>
    <option value="4">Triwulan 4</option>
</select>
```

Kolom tabel wajib:

```text
Santri
Kelas
Setoran
Target
Capaian
Hutang
Lebih
Akumulasi
Status
Detail
```

Route detail:

```blade
{{ route('reports.tahfizh.quarterly.show', [
    'student' => $row['student']->id,
    'year' => $year,
    'quarter' => $quarter,
]) }}
```

Buat file:

```text
resources/views/reports/tahfizh/quarterly/show.blade.php
```

Gunakan struktur mirip `monthly/show.blade.php`, tetapi tampilkan label:

```blade
{{ $label }}
```

---

# 24. Update Dashboard Role

Buka file:

```text
resources/views/dashboards/admin.blade.php
resources/views/dashboards/kepala-sekolah.blade.php
resources/views/dashboards/teacher.blade.php
resources/views/dashboards/super-admin.blade.php
```

Tambahkan link:

```blade
<a href="{{ route('reports.tahfizh.dashboard') }}"
   class="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
    Dashboard Tahfizh
</a>

<a href="{{ route('reports.tahfizh.monthly.index') }}"
   class="mt-4 inline-block rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
    Laporan Bulanan
</a>

<a href="{{ route('reports.tahfizh.quarterly.index') }}"
   class="mt-4 inline-block rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
    Laporan Triwulan
</a>
```

---

# 25. Validasi Route

Jalankan:

```powershell
php artisan route:list
```

Pastikan route berikut ada:

```text
reports.tahfizh.dashboard
reports.tahfizh.monthly.index
reports.tahfizh.monthly.show
reports.tahfizh.quarterly.index
reports.tahfizh.quarterly.show
```

---

# 26. Test Manual Role

## 26.1 Super Admin

Login:

```text
superadmin@hafizplus.test
password
```

Harus bisa akses:

```text
/reports/tahfizh/dashboard
/reports/tahfizh/monthly
/reports/tahfizh/quarterly
```

---

## 26.2 Admin Sekolah

Login:

```text
admin@hafizplus.test
password
```

Harus bisa akses semua halaman report.

---

## 26.3 Kepala Sekolah

Login:

```text
kepalasekolah@hafizplus.test
password
```

Harus bisa akses:

```text
/reports/tahfizh/dashboard
/reports/tahfizh/monthly
/reports/tahfizh/quarterly
```

Tidak boleh mengubah target, hitung ulang hutang, atau input setoran.

---

## 26.4 Guru Tahfidz

Login:

```text
guru@hafizplus.test
password
```

Harus bisa akses dashboard dan laporan.

Catatan:

Untuk Phase 6, data guru minimal difilter berdasarkan `teacher_id` pada `hafalan_records`.

---

## 26.5 Orang Tua

Login:

```text
ortu@hafizplus.test
password
```

Tidak boleh akses:

```text
/reports/tahfizh/dashboard
/reports/tahfizh/monthly
/reports/tahfizh/quarterly
```

Target:

```text
403 Forbidden
```

---

## 26.6 Santri

Login:

```text
santri@hafizplus.test
password
```

Tidak boleh akses halaman report internal.

Target:

```text
403 Forbidden
```

---

# 27. Test Manual Dashboard

Pastikan sudah ada data:

1. Santri aktif.
2. Setoran hafalan.
3. Target tahfizh.
4. Hutang hafalan yang sudah dihitung dari Phase 5.

Buka:

```text
/reports/tahfizh/dashboard
```

Target tampil:

1. Total santri.
2. Total setoran.
3. Total baris.
4. Akumulasi hutang.
5. Jumlah tercapai.
6. Jumlah kurang.
7. Jumlah lebih.
8. Jumlah belum ada target.
9. Aktivitas guru.
10. Santri perlu perhatian.

---

# 28. Test Manual Laporan Bulanan

Buka:

```text
/reports/tahfizh/monthly
```

Target tampil:

1. Daftar santri.
2. Kelas.
3. Jumlah setoran bulan tersebut.
4. Target bulanan.
5. Capaian baris.
6. Hutang.
7. Lebih.
8. Akumulasi hutang.
9. Status.
10. Tombol detail.

Klik detail.

Target:

1. Riwayat setoran santri dalam bulan tersebut.
2. Tanggal.
3. Guru.
4. Rentang halaman/baris.
5. Total baris.
6. Status.
7. Catatan.

---

# 29. Test Manual Laporan Triwulan

Buka:

```text
/reports/tahfizh/quarterly
```

Target tampil:

1. Filter tahun.
2. Filter triwulan.
3. Daftar santri.
4. Capaian selama 3 bulan.
5. Hutang akumulatif.
6. Status.

Klik detail.

Target:

1. Riwayat setoran santri pada periode triwulan.
2. Data sesuai tanggal awal dan akhir triwulan.

---

# 30. Dokumentasi Phase 6

Buat file:

```text
docs/phase-6-dashboard-and-reports.md
```

Isi lengkap:

````md
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
````

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

````

---

# 31. Update Project Progress

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
| 7 | Parent and Student Progress Portal | Pending |
| 8 | Notification Center | Pending |
| 9 | Export PDF/Excel | Pending |
| 10 | Production Hardening | Pending |
```

---

# 32. Build Frontend

Jalankan:

```powershell
npm run build
```

---

# 33. Validasi Akhir

Jalankan:

```powershell
php artisan route:list
php artisan migrate:status
npm run build
```

Jalankan server:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/reports/tahfizh/dashboard
http://127.0.0.1:8000/reports/tahfizh/monthly
http://127.0.0.1:8000/reports/tahfizh/quarterly
```

---

# 34. Commit Phase 6

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add tahfizh dashboard and reports"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-6-dashboard-and-reports
```

---

# 35. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 6 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Dashboard Tahfizh
- Laporan Bulanan Tahfizh
- Laporan Triwulan Tahfizh
- Filter kelas
- Filter santri
- Filter guru
- Filter status
- Filter periode
- Ringkasan aktivitas guru
- Ringkasan santri tertinggal
- Detail setoran per santri

Route dibuat:
- reports.tahfizh.dashboard
- reports.tahfizh.monthly.index
- reports.tahfizh.monthly.show
- reports.tahfizh.quarterly.index
- reports.tahfizh.quarterly.show

Belum dibuat:
- Export PDF
- Export Excel
- Grafik interaktif
- Parent portal detail
- Student portal detail
- Notification center
- API mobile

Status:
- Siap lanjut Phase 7 setelah validasi manual.
```

---

# 36. Larangan Setelah Phase 6

Agent harus berhenti setelah Phase 6 selesai.

Jangan lanjut membuat:

1. Parent portal detail.
2. Student portal detail.
3. Notification center.
4. Export PDF.
5. Export Excel.
6. API mobile.
7. WhatsApp gateway.
8. Attendance.
9. Mutabaah.
10. Tahsin.
11. Finance.
12. Cashless.

Semua itu masuk fase berikutnya.
