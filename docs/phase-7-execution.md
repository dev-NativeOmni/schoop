# Phase 7 Execution Guide — Parent and Student Progress Portal

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

# 1. Tujuan Phase 7

Phase 7 bertujuan membuat portal progres untuk:

1. Orang tua.
2. Santri.

Portal ini bersifat **read-only**.

Orang tua dapat melihat:

1. Daftar anak yang terhubung dengan akun orang tua.
2. Ringkasan progres tahfizh anak.
3. Riwayat setoran anak.
4. Target aktif anak.
5. Hutang hafalan anak.
6. Laporan bulanan anak.
7. Catatan guru dari setoran.

Santri dapat melihat:

1. Ringkasan progres pribadi.
2. Riwayat setoran pribadi.
3. Target aktif pribadi.
4. Hutang hafalan pribadi.
5. Laporan bulanan pribadi.
6. Catatan guru untuk dirinya.

Phase 7 tidak membuat input baru, tidak mengubah data setoran, dan tidak membuat notifikasi real.

---

# 2. Batasan Phase 7

AI agent tidak boleh membuat fitur berikut pada Phase 7:

1. Input setoran.
2. Edit setoran.
3. Delete setoran.
4. Hitung ulang hutang dari portal parent/student.
5. Export PDF.
6. Export Excel.
7. Notification center.
8. WhatsApp gateway.
9. Push notification.
10. API mobile.
11. Native Android.
12. Native iOS.
13. Chat parent-guru.
14. Payment.
15. Attendance.
16. Mutabaah.
17. Tahsin.
18. Finance.
19. Cashless.
20. Multi-tenant kompleks.

Phase 7 hanya membaca data yang sudah ada dari Phase 3–6.

---

# 3. Target Output Phase 7

Setelah Phase 7 selesai, aplikasi harus punya:

1. Menu **Portal Orang Tua** untuk role `parent`.
2. Menu **Portal Santri** untuk role `student`.
3. Controller:

   * `ParentProgressPortalController`
   * `StudentProgressPortalController`
4. Request:

   * `ParentProgressFilterRequest`
   * `StudentProgressFilterRequest`
5. Service:

   * `StudentProgressSnapshotService`
   * `ParentStudentAccessService`
6. View:

   * parent dashboard
   * parent child progress
   * parent child records
   * parent child monthly report
   * student dashboard
   * student records
   * student monthly report
7. Route parent portal.
8. Route student portal.
9. Role-based access.
10. Ownership-based access.
11. Dokumentasi Phase 7.

---

# 4. Prinsip Keamanan Phase 7

Phase 7 menyentuh data anak. Agent wajib mematuhi aturan berikut.

## 4.1 Orang Tua Hanya Boleh Melihat Anak Sendiri

Orang tua hanya boleh melihat santri yang terhubung lewat tabel:

```text
parent_student
```

Relasi:

```text
parent_profiles.id → parent_student.parent_profile_id
students.id → parent_student.student_id
```

Jika orang tua mencoba membuka data anak lain, sistem harus memberi:

```text
403 Forbidden
```

---

## 4.2 Santri Hanya Boleh Melihat Data Sendiri

Santri hanya boleh melihat data `students` yang terhubung ke:

```text
students.user_id = auth()->id()
```

Jika akun santri belum punya `student` profile, tampilkan halaman kosong dengan pesan:

```text
Profil santri belum terhubung. Hubungi admin sekolah.
```

Jangan crash.

---

## 4.3 Portal Ini Read-Only

Role `parent` dan `student` tidak boleh:

1. Membuat setoran.
2. Mengubah setoran.
3. Menghapus setoran.
4. Mengubah target.
5. Menghitung hutang.
6. Mengakses report internal.
7. Mengakses dashboard guru/admin/kepala sekolah.
8. Mengakses data santri lain.

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
8. Tabel berikut sudah ada:

   * `users`
   * `roles`
   * `parent_profiles`
   * `students`
   * `parent_student`
   * `hafalan_records`
   * `tahfizh_targets`
   * `tahfizh_debts`
9. Route report internal Phase 6 sudah tersedia.
10. Working tree bersih atau perubahan sudah diketahui.

Jika Phase 6 belum selesai, hentikan eksekusi.

---

# 6. Buat Branch Git Phase 7

Jalankan:

```powershell
git checkout -b phase-7-parent-student-progress-portal
```

Jika branch sudah ada:

```powershell
git checkout phase-7-parent-student-progress-portal
```

---

# 7. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Portal/
│   │       ├── ParentProgressPortalController.php
│   │       └── StudentProgressPortalController.php
│   └── Requests/
│       └── Portal/
│           ├── ParentProgressFilterRequest.php
│           └── StudentProgressFilterRequest.php
├── Services/
│   └── Portal/
│       ├── ParentStudentAccessService.php
│       └── StudentProgressSnapshotService.php

resources/
└── views/
    └── portal/
        ├── parent/
        │   ├── dashboard.blade.php
        │   ├── progress.blade.php
        │   ├── records.blade.php
        │   └── monthly.blade.php
        └── student/
            ├── dashboard.blade.php
            ├── records.blade.php
            └── monthly.blade.php

routes/
└── web.php

docs/
└── phase-7-parent-student-progress-portal.md
```

---

# 8. Buat Controller, Request, dan Service

Jalankan:

```powershell
php artisan make:controller Portal/ParentProgressPortalController
php artisan make:controller Portal/StudentProgressPortalController

php artisan make:request Portal/ParentProgressFilterRequest
php artisan make:request Portal/StudentProgressFilterRequest
```

Buat folder service:

```powershell
mkdir app\Services\Portal
```

Buat file service:

```powershell
New-Item app\Services\Portal\ParentStudentAccessService.php
New-Item app\Services\Portal\StudentProgressSnapshotService.php
```

Buat folder view:

```powershell
mkdir resources\views\portal
mkdir resources\views\portal\parent
mkdir resources\views\portal\student
```

---

# 9. Update Relasi Model `ParentProfile`

Buka:

```text
app/Models/ParentProfile.php
```

Pastikan ada import:

```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
```

Pastikan method berikut ada:

```php
public function students(): BelongsToMany
{
    return $this->belongsToMany(Student::class, 'parent_student')
        ->withPivot(['relationship', 'is_primary'])
        ->withTimestamps();
}
```

---

# 10. Update Relasi Model `Student`

Buka:

```text
app/Models/Student.php
```

Pastikan ada import:

```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
```

Pastikan method berikut ada:

```php
public function parents(): BelongsToMany
{
    return $this->belongsToMany(ParentProfile::class, 'parent_student')
        ->withPivot(['relationship', 'is_primary'])
        ->withTimestamps();
}

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function school(): BelongsTo
{
    return $this->belongsTo(School::class);
}

public function classRoom(): BelongsTo
{
    return $this->belongsTo(ClassRoom::class);
}

public function hafalanRecords(): HasMany
{
    return $this->hasMany(HafalanRecord::class);
}

public function tahfizhTargets(): HasMany
{
    return $this->hasMany(TahfizhTarget::class);
}

public function tahfizhDebts(): HasMany
{
    return $this->hasMany(TahfizhDebt::class);
}
```

Jika beberapa method sudah ada dari phase sebelumnya, jangan duplikasi.

---

# 11. Request `ParentProgressFilterRequest`

Buka:

```text
app/Http/Requests/Portal/ParentProgressFilterRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class ParentProgressFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('parent') ?? false;
    }

    public function rules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],
            'date_from' => ['nullable', 'date'],
            'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }

    public function attributes(): array
    {
        return [
            'month' => 'bulan',
            'date_from' => 'tanggal mulai',
            'date_until' => 'tanggal akhir',
        ];
    }
}
```

---

# 12. Request `StudentProgressFilterRequest`

Buka:

```text
app/Http/Requests/Portal/StudentProgressFilterRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Portal;

use Illuminate\Foundation\Http\FormRequest;

class StudentProgressFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('student') ?? false;
    }

    public function rules(): array
    {
        return [
            'month' => ['nullable', 'date_format:Y-m'],
            'date_from' => ['nullable', 'date'],
            'date_until' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }

    public function attributes(): array
    {
        return [
            'month' => 'bulan',
            'date_from' => 'tanggal mulai',
            'date_until' => 'tanggal akhir',
        ];
    }
}
```

---

# 13. Service `ParentStudentAccessService`

Buka:

```text
app/Services/Portal/ParentStudentAccessService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Portal;

use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\User;

class ParentStudentAccessService
{
    public function parentProfile(User $user): ?ParentProfile
    {
        return $user->parentProfile()
            ->with(['students.classRoom', 'students.school'])
            ->first();
    }

    public function children(User $user)
    {
        $parentProfile = $this->parentProfile($user);

        if (! $parentProfile) {
            return collect();
        }

        return $parentProfile->students()
            ->with(['classRoom', 'school'])
            ->where('students.is_active', true)
            ->orderBy('students.full_name')
            ->get();
    }

    public function canAccessStudent(User $user, Student $student): bool
    {
        $parentProfile = $user->parentProfile;

        if (! $parentProfile) {
            return false;
        }

        return $parentProfile->students()
            ->where('students.id', $student->id)
            ->exists();
    }

    public function abortIfCannotAccess(User $user, Student $student): void
    {
        if (! $this->canAccessStudent($user, $student)) {
            abort(403, 'Orang tua hanya boleh melihat data anak sendiri.');
        }
    }
}
```

---

# 14. Service `StudentProgressSnapshotService`

Buka:

```text
app/Services/Portal/StudentProgressSnapshotService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Portal;

use App\Models\HafalanRecord;
use App\Models\Student;
use App\Models\TahfizhDebt;
use App\Models\TahfizhTarget;
use App\Services\Tahfizh\ActiveTahfizhTargetResolver;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class StudentProgressSnapshotService
{
    public function __construct(
        private readonly ActiveTahfizhTargetResolver $targetResolver,
    ) {
        //
    }

    public function snapshot(
        Student $student,
        CarbonInterface|string|null $dateFrom = null,
        CarbonInterface|string|null $dateUntil = null
    ): array {
        $dateFrom = $dateFrom
            ? Carbon::parse($dateFrom)->startOfDay()
            : now()->startOfMonth();

        $dateUntil = $dateUntil
            ? Carbon::parse($dateUntil)->endOfDay()
            : now()->endOfDay();

        $activeTarget = $this->targetResolver->resolveForStudent($student, now());

        $recordsQuery = HafalanRecord::query()
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $dateFrom->toDateString(),
                $dateUntil->toDateString(),
            ]);

        $totalRecords = (clone $recordsQuery)->count();

        $totalLines = (int) (clone $recordsQuery)
            ->whereIn('status', [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ])
            ->sum('total_lines');

        $latestRecord = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->where('student_id', $student->id)
            ->latest('record_date')
            ->latest('id')
            ->first();

        $latestDebt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->latest('period_end')
            ->latest('id')
            ->first();

        $monthlyDebt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
            ->whereDate('period_start', now()->startOfMonth()->toDateString())
            ->whereDate('period_end', now()->endOfMonth()->toDateString())
            ->first();

        $recentRecords = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->where('student_id', $student->id)
            ->latest('record_date')
            ->latest('id')
            ->limit(10)
            ->get();

        return [
            'student' => $student->load(['school', 'classRoom']),
            'period_start' => $dateFrom,
            'period_end' => $dateUntil,
            'active_target' => $activeTarget,
            'total_records' => $totalRecords,
            'total_lines' => $totalLines,
            'latest_record' => $latestRecord,
            'latest_debt' => $latestDebt,
            'monthly_debt' => $monthlyDebt,
            'recent_records' => $recentRecords,
            'target_daily_lines' => $activeTarget?->daily_target_lines ?? 0,
            'target_weekly_lines' => $activeTarget?->weekly_target_lines ?? 0,
            'target_monthly_lines' => $activeTarget?->monthly_target_lines ?? 0,
            'current_debt_lines' => $latestDebt?->cumulative_debt_lines ?? 0,
            'monthly_status' => $monthlyDebt?->status ?? TahfizhDebt::STATUS_NO_TARGET,
        ];
    }

    public function monthlyRows(Student $student, ?string $month = null)
    {
        $date = $month
            ? Carbon::createFromFormat('Y-m', $month)->startOfMonth()
            : now()->startOfMonth();

        $periodStart = $date->copy()->startOfMonth();
        $periodEnd = $date->copy()->endOfMonth();

        $records = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah'])
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [
                $periodStart->toDateString(),
                $periodEnd->toDateString(),
            ])
            ->orderBy('record_date')
            ->orderBy('id')
            ->get();

        $debt = TahfizhDebt::query()
            ->where('student_id', $student->id)
            ->where('period_type', TahfizhDebt::PERIOD_MONTHLY)
            ->whereDate('period_start', $periodStart->toDateString())
            ->whereDate('period_end', $periodEnd->toDateString())
            ->first();

        return [
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'month' => $periodStart->format('Y-m'),
            'records' => $records,
            'debt' => $debt,
            'total_lines' => (int) $records->sum('total_lines'),
            'total_records' => $records->count(),
        ];
    }
}
```

---

# 15. Controller `ParentProgressPortalController`

Buka:

```text
app/Http/Controllers/Portal/ParentProgressPortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\ParentProgressFilterRequest;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Services\Portal\ParentStudentAccessService;
use App\Services\Portal\StudentProgressSnapshotService;
use Illuminate\View\View;

class ParentProgressPortalController extends Controller
{
    public function __construct(
        private readonly ParentStudentAccessService $accessService,
        private readonly StudentProgressSnapshotService $snapshotService,
    ) {
        //
    }

    public function dashboard(ParentProgressFilterRequest $request): View
    {
        $children = $this->accessService->children($request->user());

        $snapshots = $children->map(function (Student $student) use ($request): array {
            return $this->snapshotService->snapshot(
                student: $student,
                dateFrom: $request->input('date_from'),
                dateUntil: $request->input('date_until')
            );
        });

        return view('portal.parent.dashboard', [
            'children' => $children,
            'snapshots' => $snapshots,
        ]);
    }

    public function progress(ParentProgressFilterRequest $request, Student $student): View
    {
        $this->accessService->abortIfCannotAccess($request->user(), $student);

        $snapshot = $this->snapshotService->snapshot(
            student: $student,
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        return view('portal.parent.progress', [
            'student' => $student->load(['school', 'classRoom']),
            'snapshot' => $snapshot,
        ]);
    }

    public function records(ParentProgressFilterRequest $request, Student $student): View
    {
        $this->accessService->abortIfCannotAccess($request->user(), $student);

        $dateFrom = $request->input('date_from') ?: now()->startOfMonth()->toDateString();
        $dateUntil = $request->input('date_until') ?: now()->endOfDay()->toDateString();

        $records = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah', 'tahfizhTarget'])
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [$dateFrom, $dateUntil])
            ->latest('record_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('portal.parent.records', [
            'student' => $student->load(['school', 'classRoom']),
            'records' => $records,
            'dateFrom' => $dateFrom,
            'dateUntil' => $dateUntil,
        ]);
    }

    public function monthly(ParentProgressFilterRequest $request, Student $student): View
    {
        $this->accessService->abortIfCannotAccess($request->user(), $student);

        $report = $this->snapshotService->monthlyRows(
            student: $student,
            month: $request->input('month')
        );

        return view('portal.parent.monthly', [
            'student' => $student->load(['school', 'classRoom']),
            'report' => $report,
        ]);
    }
}
```

---

# 16. Controller `StudentProgressPortalController`

Buka:

```text
app/Http/Controllers/Portal/StudentProgressPortalController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StudentProgressFilterRequest;
use App\Models\HafalanRecord;
use App\Models\Student;
use App\Services\Portal\StudentProgressSnapshotService;
use Illuminate\View\View;

class StudentProgressPortalController extends Controller
{
    public function __construct(
        private readonly StudentProgressSnapshotService $snapshotService,
    ) {
        //
    }

    public function dashboard(StudentProgressFilterRequest $request): View
    {
        $student = $this->studentFromUser($request);

        if (! $student) {
            return view('portal.student.dashboard', [
                'student' => null,
                'snapshot' => null,
            ]);
        }

        $snapshot = $this->snapshotService->snapshot(
            student: $student,
            dateFrom: $request->input('date_from'),
            dateUntil: $request->input('date_until')
        );

        return view('portal.student.dashboard', [
            'student' => $student->load(['school', 'classRoom']),
            'snapshot' => $snapshot,
        ]);
    }

    public function records(StudentProgressFilterRequest $request): View
    {
        $student = $this->studentFromUser($request);

        if (! $student) {
            return view('portal.student.records', [
                'student' => null,
                'records' => collect(),
                'dateFrom' => null,
                'dateUntil' => null,
            ]);
        }

        $dateFrom = $request->input('date_from') ?: now()->startOfMonth()->toDateString();
        $dateUntil = $request->input('date_until') ?: now()->endOfDay()->toDateString();

        $records = HafalanRecord::query()
            ->with(['teacher', 'startSurah', 'endSurah', 'tahfizhTarget'])
            ->where('student_id', $student->id)
            ->whereBetween('record_date', [$dateFrom, $dateUntil])
            ->latest('record_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('portal.student.records', [
            'student' => $student->load(['school', 'classRoom']),
            'records' => $records,
            'dateFrom' => $dateFrom,
            'dateUntil' => $dateUntil,
        ]);
    }

    public function monthly(StudentProgressFilterRequest $request): View
    {
        $student = $this->studentFromUser($request);

        if (! $student) {
            return view('portal.student.monthly', [
                'student' => null,
                'report' => null,
            ]);
        }

        $report = $this->snapshotService->monthlyRows(
            student: $student,
            month: $request->input('month')
        );

        return view('portal.student.monthly', [
            'student' => $student->load(['school', 'classRoom']),
            'report' => $report,
        ]);
    }

    private function studentFromUser(StudentProgressFilterRequest $request): ?Student
    {
        return Student::query()
            ->with(['school', 'classRoom'])
            ->where('user_id', $request->user()->id)
            ->first();
    }
}
```

---

# 17. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Portal\ParentProgressPortalController;
use App\Http\Controllers\Portal\StudentProgressPortalController;
```

Di dalam group `Route::middleware('auth')->group(...)`, tambahkan:

```php
Route::middleware('role:parent')
    ->prefix('portal/parent')
    ->name('portal.parent.')
    ->group(function (): void {
        Route::get('dashboard', [ParentProgressPortalController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('children/{student}', [ParentProgressPortalController::class, 'progress'])
            ->name('children.progress');

        Route::get('children/{student}/records', [ParentProgressPortalController::class, 'records'])
            ->name('children.records');

        Route::get('children/{student}/monthly', [ParentProgressPortalController::class, 'monthly'])
            ->name('children.monthly');
    });

Route::middleware('role:student')
    ->prefix('portal/student')
    ->name('portal.student.')
    ->group(function (): void {
        Route::get('dashboard', [StudentProgressPortalController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('records', [StudentProgressPortalController::class, 'records'])
            ->name('records');

        Route::get('monthly', [StudentProgressPortalController::class, 'monthly'])
            ->name('monthly');
    });
```

---

# 18. Update Navigasi Layout

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan di navigasi `@auth`:

```blade
@if (auth()->user()->hasRole('parent'))
    <a href="{{ route('portal.parent.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Portal Orang Tua
    </a>
@endif

@if (auth()->user()->hasRole('student'))
    <a href="{{ route('portal.student.dashboard') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Portal Santri
    </a>
@endif
```

Pastikan link ini hanya muncul untuk role masing-masing.

---

# 19. View Parent Dashboard

Buat file:

```text
resources/views/portal/parent/dashboard.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Portal Orang Tua</h2>
        <p class="text-sm text-slate-500">Pantau progres tahfizh anak.</p>
    </div>

    @if ($children->isEmpty())
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold">Belum Ada Anak Terhubung</h3>
            <p class="mt-2 text-sm text-slate-600">
                Akun orang tua ini belum terhubung dengan data santri. Hubungi admin sekolah.
            </p>
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2">
            @foreach ($snapshots as $snapshot)
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold">{{ $snapshot['student']->full_name }}</h3>
                        <p class="text-sm text-slate-500">
                            {{ $snapshot['student']->classRoom?->name ?? '-' }}
                        </p>
                    </div>

                    <div class="grid gap-3 md:grid-cols-3">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="text-xs text-slate-500">Setoran Bulan Ini</div>
                            <div class="mt-1 text-2xl font-bold">{{ $snapshot['total_records'] }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="text-xs text-slate-500">Baris Bulan Ini</div>
                            <div class="mt-1 text-2xl font-bold">{{ $snapshot['total_lines'] }}</div>
                        </div>

                        <div class="rounded-xl bg-slate-50 p-4">
                            <div class="text-xs text-slate-500">Hutang Aktif</div>
                            <div class="mt-1 text-2xl font-bold">{{ $snapshot['current_debt_lines'] }}</div>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <a href="{{ route('portal.parent.children.progress', $snapshot['student']) }}"
                           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                            Lihat Progres
                        </a>

                        <a href="{{ route('portal.parent.children.records', $snapshot['student']) }}"
                           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Riwayat Setoran
                        </a>

                        <a href="{{ route('portal.parent.children.monthly', $snapshot['student']) }}"
                           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                            Laporan Bulanan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
```

---

# 20. View Parent Progress

Buat file:

```text
resources/views/portal/parent/progress.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Progres Tahfizh Anak</h2>
            <p class="text-sm text-slate-500">
                {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
            </p>
        </div>

        <a href="{{ route('portal.parent.dashboard') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Target Harian</div>
            <div class="mt-2 text-3xl font-bold">{{ $snapshot['target_daily_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Target Bulanan</div>
            <div class="mt-2 text-3xl font-bold">{{ $snapshot['target_monthly_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Capaian Bulan Ini</div>
            <div class="mt-2 text-3xl font-bold">{{ $snapshot['total_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Akumulasi Hutang</div>
            <div class="mt-2 text-3xl font-bold">{{ $snapshot['current_debt_lines'] }}</div>
            <div class="text-xs text-slate-500">baris</div>
        </div>
    </div>

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Target Aktif</h3>

            @if ($snapshot['active_target'])
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-semibold">Nama Target</dt>
                        <dd>{{ $snapshot['active_target']->name }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Program</dt>
                        <dd>{{ $snapshot['active_target']->program_type ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Target Mingguan</dt>
                        <dd>{{ $snapshot['active_target']->weekly_target_lines }} baris</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Target Bulanan</dt>
                        <dd>{{ $snapshot['active_target']->monthly_target_lines }} baris</dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-slate-500">Belum ada target aktif.</p>
            @endif
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Setoran Terakhir</h3>

            @if ($snapshot['latest_record'])
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-semibold">Tanggal</dt>
                        <dd>{{ $snapshot['latest_record']->record_date?->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Guru</dt>
                        <dd>{{ $snapshot['latest_record']->teacher?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Rentang</dt>
                        <dd>
                            Hlm {{ $snapshot['latest_record']->start_page }}:{{ $snapshot['latest_record']->start_line }}
                            —
                            Hlm {{ $snapshot['latest_record']->end_page }}:{{ $snapshot['latest_record']->end_line }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-semibold">Catatan</dt>
                        <dd>{{ $snapshot['latest_record']->notes ?? '-' }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-slate-500">Belum ada setoran.</p>
            @endif
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold">Riwayat Terbaru</h3>
            <a href="{{ route('portal.parent.children.records', $student) }}" class="text-sm font-semibold text-blue-700 hover:underline">
                Lihat semua
            </a>
        </div>

        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Guru</th>
                    <th class="px-4 py-3">Rentang</th>
                    <th class="px-4 py-3">Baris</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($snapshot['recent_records'] as $record)
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                            Belum ada riwayat setoran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
```

---

# 21. View Parent Records

Buat file:

```text
resources/views/portal/parent/records.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Riwayat Setoran Anak</h2>
            <p class="text-sm text-slate-500">
                {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
            </p>
        </div>

        <a href="{{ route('portal.parent.children.progress', $student) }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali ke Progres
        </a>
    </div>

    <form method="GET" action="{{ route('portal.parent.children.records', $student) }}"
          class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-3">
        <div>
            <label class="mb-1 block text-sm font-semibold">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Sampai</label>
            <input type="date" name="date_until" value="{{ request('date_until', $dateUntil) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div class="flex items-end">
            <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Filter
            </button>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Guru</th>
                    <th class="px-4 py-3">Surah</th>
                    <th class="px-4 py-3">Rentang</th>
                    <th class="px-4 py-3">Baris</th>
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
                            {{ $record->startSurah?->name_latin ?? '-' }}
                            —
                            {{ $record->endSurah?->name_latin ?? '-' }}
                        </td>
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
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                            Belum ada setoran pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $records->links() }}
    </div>
@endsection
```

---

# 22. View Parent Monthly

Buat file:

```text
resources/views/portal/parent/monthly.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Laporan Bulanan Anak</h2>
            <p class="text-sm text-slate-500">
                {{ $student->full_name }} — {{ $report['period_start']->format('d/m/Y') }} sampai {{ $report['period_end']->format('d/m/Y') }}
            </p>
        </div>

        <a href="{{ route('portal.parent.children.progress', $student) }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali ke Progres
        </a>
    </div>

    <form method="GET" action="{{ route('portal.parent.children.monthly', $student) }}"
          class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-semibold">Bulan</label>
            <input type="month" name="month" value="{{ request('month', $report['month']) }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div class="flex items-end">
            <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Tampilkan
            </button>
        </div>
    </form>

    <div class="mb-6 grid gap-4 md:grid-cols-5">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Setoran</div>
            <div class="mt-2 text-3xl font-bold">{{ $report['total_records'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Capaian</div>
            <div class="mt-2 text-3xl font-bold">{{ $report['total_lines'] }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Target</div>
            <div class="mt-2 text-3xl font-bold">{{ $report['debt']?->target_lines ?? 0 }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Hutang</div>
            <div class="mt-2 text-3xl font-bold">{{ $report['debt']?->debt_lines ?? 0 }}</div>
        </div>

        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Akumulasi</div>
            <div class="mt-2 text-3xl font-bold">{{ $report['debt']?->cumulative_debt_lines ?? 0 }}</div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Guru</th>
                    <th class="px-4 py-3">Rentang</th>
                    <th class="px-4 py-3">Baris</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($report['records'] as $record)
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
                            Belum ada setoran pada bulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
```

---

# 23. View Student Dashboard

Buat file:

```text
resources/views/portal/student/dashboard.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Portal Santri</h2>
        <p class="text-sm text-slate-500">Pantau progres tahfizh pribadi.</p>
    </div>

    @if (! $student)
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold">Profil Santri Belum Terhubung</h3>
            <p class="mt-2 text-sm text-slate-600">
                Profil santri belum terhubung. Hubungi admin sekolah.
            </p>
        </div>
    @else
        <div class="mb-6">
            <h3 class="text-xl font-bold">{{ $student->full_name }}</h3>
            <p class="text-sm text-slate-500">{{ $student->classRoom?->name ?? '-' }}</p>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Target Harian</div>
                <div class="mt-2 text-3xl font-bold">{{ $snapshot['target_daily_lines'] }}</div>
                <div class="text-xs text-slate-500">baris</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Capaian Bulan Ini</div>
                <div class="mt-2 text-3xl font-bold">{{ $snapshot['total_lines'] }}</div>
                <div class="text-xs text-slate-500">baris</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Setoran Bulan Ini</div>
                <div class="mt-2 text-3xl font-bold">{{ $snapshot['total_records'] }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Akumulasi Hutang</div>
                <div class="mt-2 text-3xl font-bold">{{ $snapshot['current_debt_lines'] }}</div>
                <div class="text-xs text-slate-500">baris</div>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('portal.student.records') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Riwayat Setoran
            </a>

            <a href="{{ route('portal.student.monthly') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Laporan Bulanan
            </a>
        </div>

        <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Riwayat Terbaru</h3>

            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Rentang</th>
                        <th class="px-4 py-3">Baris</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($snapshot['recent_records'] as $record)
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                                Belum ada setoran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
```

---

# 24. View Student Records

Buat file:

```text
resources/views/portal/student/records.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Riwayat Setoran Saya</h2>
            <p class="text-sm text-slate-500">
                {{ $student?->full_name ?? 'Profil belum terhubung' }}
            </p>
        </div>

        <a href="{{ route('portal.student.dashboard') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    @if (! $student)
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-600">
                Profil santri belum terhubung. Hubungi admin sekolah.
            </p>
        </div>
    @else
        <form method="GET" action="{{ route('portal.student.records') }}"
              class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-semibold">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Sampai</label>
                <input type="date" name="date_until" value="{{ request('date_until', $dateUntil) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Filter
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Surah</th>
                        <th class="px-4 py-3">Rentang</th>
                        <th class="px-4 py-3">Baris</th>
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
                                {{ $record->startSurah?->name_latin ?? '-' }}
                                —
                                {{ $record->endSurah?->name_latin ?? '-' }}
                            </td>
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
                            <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                                Belum ada setoran pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $records->links() }}
        </div>
    @endif
@endsection
```

---

# 25. View Student Monthly

Buat file:

```text
resources/views/portal/student/monthly.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Laporan Bulanan Saya</h2>
            <p class="text-sm text-slate-500">
                {{ $student?->full_name ?? 'Profil belum terhubung' }}
            </p>
        </div>

        <a href="{{ route('portal.student.dashboard') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    @if (! $student)
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-600">
                Profil santri belum terhubung. Hubungi admin sekolah.
            </p>
        </div>
    @else
        <form method="GET" action="{{ route('portal.student.monthly') }}"
              class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-semibold">Bulan</label>
                <input type="month" name="month" value="{{ request('month', $report['month']) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Tampilkan
                </button>
            </div>
        </form>

        <div class="mb-6 grid gap-4 md:grid-cols-5">
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Setoran</div>
                <div class="mt-2 text-3xl font-bold">{{ $report['total_records'] }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Capaian</div>
                <div class="mt-2 text-3xl font-bold">{{ $report['total_lines'] }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Target</div>
                <div class="mt-2 text-3xl font-bold">{{ $report['debt']?->target_lines ?? 0 }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Hutang</div>
                <div class="mt-2 text-3xl font-bold">{{ $report['debt']?->debt_lines ?? 0 }}</div>
            </div>

            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">Akumulasi</div>
                <div class="mt-2 text-3xl font-bold">{{ $report['debt']?->cumulative_debt_lines ?? 0 }}</div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
            <table class="w-full border-collapse text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Guru</th>
                        <th class="px-4 py-3">Rentang</th>
                        <th class="px-4 py-3">Baris</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['records'] as $record)
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
                                Belum ada setoran pada bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@endsection
```

---

# 26. Update Dashboard Role Parent

Buka:

```text
resources/views/dashboards/parent.blade.php
```

Tambahkan link:

```blade
<a href="{{ route('portal.parent.dashboard') }}"
   class="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
    Buka Portal Orang Tua
</a>
```

---

# 27. Update Dashboard Role Student

Buka:

```text
resources/views/dashboards/student.blade.php
```

Tambahkan link:

```blade
<a href="{{ route('portal.student.dashboard') }}"
   class="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
    Buka Portal Santri
</a>
```

---

# 28. Validasi Route

Jalankan:

```powershell
php artisan route:list
```

Pastikan route berikut ada:

```text
portal.parent.dashboard
portal.parent.children.progress
portal.parent.children.records
portal.parent.children.monthly

portal.student.dashboard
portal.student.records
portal.student.monthly
```

---

# 29. Test Manual Parent Portal

## 29.1 Login Orang Tua

Login:

```text
ortu@hafizplus.test
password
```

Buka:

```text
/portal/parent/dashboard
```

Target:

1. Jika parent punya anak, daftar anak tampil.
2. Jika parent belum terhubung ke santri, tampil pesan belum ada anak terhubung.
3. Tidak error.

---

## 29.2 Buka Detail Progres Anak

Klik:

```text
Lihat Progres
```

Target tampil:

1. Nama anak.
2. Kelas.
3. Target harian.
4. Target bulanan.
5. Capaian bulan ini.
6. Akumulasi hutang.
7. Target aktif.
8. Setoran terakhir.
9. Riwayat setoran terbaru.

---

## 29.3 Buka Riwayat Setoran Anak

Klik:

```text
Riwayat Setoran
```

Target tampil:

1. Filter tanggal.
2. Tabel setoran.
3. Tanggal.
4. Guru.
5. Surah.
6. Rentang halaman/baris.
7. Total baris.
8. Status.
9. Catatan guru.

---

## 29.4 Buka Laporan Bulanan Anak

Klik:

```text
Laporan Bulanan
```

Target tampil:

1. Filter bulan.
2. Total setoran.
3. Total capaian.
4. Target.
5. Hutang.
6. Akumulasi.
7. Detail setoran bulan tersebut.

---

## 29.5 Test Akses Anak Lain

Login sebagai parent A.

Coba buka URL anak lain:

```text
/portal/parent/children/{id-anak-lain}
```

Target:

```text
403 Forbidden
```

---

# 30. Test Manual Student Portal

## 30.1 Login Santri

Login:

```text
santri@hafizplus.test
password
```

Buka:

```text
/portal/student/dashboard
```

Target:

1. Jika user santri terhubung ke row `students`, dashboard tampil.
2. Jika belum terhubung, tampil pesan profil belum terhubung.
3. Tidak error.

---

## 30.2 Buka Riwayat Setoran Santri

Buka:

```text
/portal/student/records
```

Target tampil:

1. Filter tanggal.
2. Riwayat setoran milik sendiri.
3. Tidak ada data santri lain.

---

## 30.3 Buka Laporan Bulanan Santri

Buka:

```text
/portal/student/monthly
```

Target tampil:

1. Filter bulan.
2. Total setoran.
3. Total capaian.
4. Target.
5. Hutang.
6. Akumulasi.
7. Detail setoran bulan tersebut.

---

# 31. Test Manual Role Forbidden

## 31.1 Admin Tidak Perlu Akses Portal Parent

Login admin.

Coba buka:

```text
/portal/parent/dashboard
```

Target:

```text
403 Forbidden
```

Admin sudah punya dashboard/report internal, tidak perlu portal parent.

---

## 31.2 Parent Tidak Boleh Akses Report Internal

Login parent.

Coba buka:

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

## 31.3 Student Tidak Boleh Akses Report Internal

Login student.

Coba buka:

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

## 31.4 Parent Tidak Boleh Akses Input Setoran

Login parent.

Coba buka:

```text
/tahfizh/hafalan-records/create
```

Target:

```text
403 Forbidden
```

---

## 31.5 Student Tidak Boleh Akses Input Setoran

Login student.

Coba buka:

```text
/tahfizh/hafalan-records/create
```

Target:

```text
403 Forbidden
```

---

# 32. Dokumentasi Phase 7

Buat file:

```text
docs/phase-7-parent-student-progress-portal.md
```

Isi lengkap:

````md
# Phase 7 — Parent and Student Progress Portal

## Status

Phase 7 membangun portal progres tahfizh untuk orang tua dan santri.

Portal ini bersifat read-only.

## Output

1. Portal Orang Tua.
2. Portal Santri.
3. Dashboard progres anak untuk orang tua.
4. Dashboard progres pribadi untuk santri.
5. Riwayat setoran untuk orang tua.
6. Riwayat setoran untuk santri.
7. Laporan bulanan anak.
8. Laporan bulanan santri.
9. Ownership-based access.
10. Role-based access.

## Controller Baru

```text
App\Http\Controllers\Portal\ParentProgressPortalController
App\Http\Controllers\Portal\StudentProgressPortalController
````

## Request Baru

```text
App\Http\Requests\Portal\ParentProgressFilterRequest
App\Http\Requests\Portal\StudentProgressFilterRequest
```

## Service Baru

```text
App\Services\Portal\ParentStudentAccessService
App\Services\Portal\StudentProgressSnapshotService
```

## Route Baru

```text
portal.parent.dashboard
portal.parent.children.progress
portal.parent.children.records
portal.parent.children.monthly

portal.student.dashboard
portal.student.records
portal.student.monthly
```

## Role Access

| Role           | Parent Portal    | Student Portal   |
| -------------- | ---------------- | ---------------- |
| Super Admin    | Tidak            | Tidak            |
| Admin Sekolah  | Tidak            | Tidak            |
| Kepala Sekolah | Tidak            | Tidak            |
| Guru Tahfidz   | Tidak            | Tidak            |
| Orang Tua      | Ya, anak sendiri | Tidak            |
| Santri         | Tidak            | Ya, data sendiri |

## Aturan Keamanan

Orang tua hanya boleh melihat anak sendiri berdasarkan relasi:

```text
parent_profiles → parent_student → students
```

Santri hanya boleh melihat data sendiri berdasarkan relasi:

```text
students.user_id = auth()->id()
```

Jika akses tidak sah, sistem harus mengembalikan:

```text
403 Forbidden
```

## Data yang Ditampilkan Orang Tua

1. Daftar anak.
2. Target aktif.
3. Capaian bulan ini.
4. Hutang hafalan.
5. Riwayat setoran.
6. Laporan bulanan.
7. Catatan guru.

## Data yang Ditampilkan Santri

1. Target aktif.
2. Capaian bulan ini.
3. Hutang hafalan.
4. Riwayat setoran pribadi.
5. Laporan bulanan pribadi.
6. Catatan guru.

## Belum Dibuat

Phase 7 belum membuat:

1. Notification center.
2. Push notification.
3. WhatsApp gateway.
4. Chat orang tua dan guru.
5. Export PDF.
6. Export Excel.
7. API mobile.
8. Native Android.
9. Native iOS.
10. Payment.
11. Attendance.
12. Mutabaah.
13. Tahsin.

## Definition of Done

Phase 7 selesai jika:

1. Parent bisa login dan melihat daftar anak.
2. Parent bisa melihat progres anak sendiri.
3. Parent tidak bisa melihat anak lain.
4. Parent bisa melihat riwayat setoran anak.
5. Parent bisa melihat laporan bulanan anak.
6. Student bisa login dan melihat progres pribadi.
7. Student bisa melihat riwayat setoran pribadi.
8. Student bisa melihat laporan bulanan pribadi.
9. Parent tidak bisa akses dashboard internal.
10. Student tidak bisa akses dashboard internal.
11. Parent dan student tidak bisa input setoran.
12. Semua akses ilegal menghasilkan 403.
13. Build frontend berhasil.
14. Dokumentasi Phase 7 dibuat.

````

---

# 33. Update Project Progress

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
| 8 | Notification Center | Pending |
| 9 | Export PDF/Excel | Pending |
| 10 | Production Hardening | Pending |
```

---

# 34. Build Frontend

Jalankan:

```powershell
npm run build
```

---

# 35. Validasi Akhir

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
http://127.0.0.1:8000/portal/parent/dashboard
http://127.0.0.1:8000/portal/student/dashboard
```

---

# 36. Commit Phase 7

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add parent and student progress portal"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-7-parent-student-progress-portal
```

---

# 37. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 7 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Portal Orang Tua
- Portal Santri
- Dashboard progres anak
- Dashboard progres pribadi santri
- Riwayat setoran anak
- Riwayat setoran santri
- Laporan bulanan anak
- Laporan bulanan santri
- Ownership-based access
- Read-only portal access

Route dibuat:
- portal.parent.dashboard
- portal.parent.children.progress
- portal.parent.children.records
- portal.parent.children.monthly
- portal.student.dashboard
- portal.student.records
- portal.student.monthly

Role access:
- Orang Tua: hanya anak sendiri
- Santri: hanya data sendiri
- Admin/Guru/Kepala Sekolah: tidak memakai portal ini
- Parent dan Student tidak bisa input/edit/delete setoran

Belum dibuat:
- Notification Center
- Push notification
- WhatsApp gateway
- Export PDF
- Export Excel
- API mobile
- Native Android/iOS

Status:
- Siap lanjut Phase 8 setelah validasi manual.
```

---

# 38. Larangan Setelah Phase 7

Agent harus berhenti setelah Phase 7 selesai.

Jangan lanjut membuat:

1. Notification center.
2. Push notification.
3. WhatsApp gateway.
4. Export PDF.
5. Export Excel.
6. API mobile.
7. Native mobile.
8. Attendance.
9. Mutabaah.
10. Tahsin.
11. Finance.
12. Cashless.

Semua itu masuk fase berikutnya.
