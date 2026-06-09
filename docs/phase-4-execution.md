# Phase 4 Execution Guide — Tahfizh Input Foundation

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

# 1. Tujuan Phase 4

Phase 4 bertujuan membuat fondasi input setoran tahfizh.

Fokus Phase 4:

1. Halaman daftar setoran hafalan.
2. Halaman tambah setoran.
3. Halaman detail setoran.
4. Halaman edit setoran.
5. Hapus setoran dengan soft delete.
6. Validasi halaman dan baris mushaf.
7. Kalkulasi otomatis total baris.
8. Sequential guard awal agar setoran tidak lompat.
9. Hak akses berdasarkan role.
10. Dokumentasi Phase 4.

Phase 4 belum membuat hutang hafalan otomatis, dashboard statistik, report bulanan, report triwulan, parent portal detail, dan notifikasi real.

---

# 2. Batasan Phase 4

AI agent tidak boleh membuat fitur berikut pada Phase 4:

1. Hutang hafalan otomatis.
2. Report bulanan.
3. Report triwulan.
4. Dashboard statistik lengkap.
5. Grafik progres.
6. Parent progress detail.
7. Student progress detail.
8. Notification center.
9. Export PDF.
10. Export Excel.
11. API mobile.
12. WhatsApp gateway.
13. Payment.
14. Attendance.
15. Mutabaah.
16. Tahsin.

Phase 4 hanya membuat input dan manajemen awal `hafalan_records`.

---

# 3. Target Output Phase 4

Setelah Phase 4 selesai, aplikasi harus punya:

1. Menu **Setoran Tahfizh**.
2. Route tahfizh records.
3. Controller `HafalanRecordController`.
4. Form Request:

   * `StoreHafalanRecordRequest`
   * `UpdateHafalanRecordRequest`
5. Service:

   * `LineRangeCalculator` sudah ada dari Phase 3.
   * `HafalanSequenceGuard` baru.
6. View:

   * index
   * create
   * show
   * edit
7. Validasi:

   * halaman 1–604
   * baris 1–15
   * halaman akhir tidak boleh lebih kecil dari halaman awal
   * baris akhir tidak boleh lebih kecil pada halaman yang sama
   * total baris dihitung otomatis
   * setoran tidak boleh lompat dari posisi terakhir
8. Role access:

   * Super Admin: lihat, tambah, edit, hapus
   * Admin Sekolah: lihat, tambah, edit, hapus
   * Guru Tahfidz: lihat, tambah, edit setoran miliknya
   * Kepala Sekolah: lihat saja
   * Orang Tua: tidak masuk Phase 4
   * Santri: tidak masuk Phase 4

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
5. Tabel berikut sudah ada:

   * `students`
   * `users`
   * `roles`
   * `schools`
   * `class_rooms`
   * `quran_juzs`
   * `quran_surahs`
   * `mushaf_pages`
   * `tahfizh_targets`
   * `hafalan_records`
6. Seeder Qur’an sudah jalan:

   * 30 juz
   * 114 surah
   * 604 halaman mushaf
7. Working tree bersih atau perubahan sudah diketahui.

Jika Phase 3 belum selesai, hentikan eksekusi.

---

# 5. Buat Branch Git Phase 4

Jalankan:

```powershell
git checkout -b phase-4-tahfizh-input-foundation
```

Jika branch sudah ada:

```powershell
git checkout phase-4-tahfizh-input-foundation
```

---

# 6. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Tahfizh/
│   │       └── HafalanRecordController.php
│   └── Requests/
│       └── Tahfizh/
│           ├── StoreHafalanRecordRequest.php
│           └── UpdateHafalanRecordRequest.php
├── Services/
│   └── Tahfizh/
│       ├── LineRangeCalculator.php
│       └── HafalanSequenceGuard.php

resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php
│   └── tahfizh/
│       └── hafalan-records/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── show.blade.php
│           └── edit.blade.php

routes/
└── web.php

docs/
└── phase-4-tahfizh-input-foundation.md
```

---

# 7. Buat Controller dan Request

Jalankan:

```powershell
php artisan make:controller Tahfizh/HafalanRecordController
php artisan make:request Tahfizh/StoreHafalanRecordRequest
php artisan make:request Tahfizh/UpdateHafalanRecordRequest
```

Buat service:

```powershell
New-Item app\Services\Tahfizh\HafalanSequenceGuard.php
```

Jika folder belum ada:

```powershell
mkdir app\Services
mkdir app\Services\Tahfizh
```

---

# 8. Service `HafalanSequenceGuard`

Buka:

```text
app/Services/Tahfizh/HafalanSequenceGuard.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Tahfizh;

use App\Models\HafalanRecord;
use Carbon\CarbonInterface;

class HafalanSequenceGuard
{
    public function validate(
        int $studentId,
        CarbonInterface|string $recordDate,
        int $startPage,
        int $startLine,
        ?int $ignoreRecordId = null
    ): array {
        $previousRecord = HafalanRecord::query()
            ->where('student_id', $studentId)
            ->when($ignoreRecordId, function ($query) use ($ignoreRecordId): void {
                $query->where('id', '!=', $ignoreRecordId);
            })
            ->whereDate('record_date', '<=', $recordDate)
            ->orderByDesc('record_date')
            ->orderByDesc('id')
            ->first();

        if (! $previousRecord) {
            return [
                'valid' => true,
                'note' => 'Setoran pertama santri. Sequential guard mengizinkan titik awal ini.',
                'expected_page' => null,
                'expected_line' => null,
            ];
        }

        [$expectedPage, $expectedLine] = $this->nextPosition(
            $previousRecord->end_page,
            $previousRecord->end_line
        );

        $isValid = $startPage === $expectedPage && $startLine === $expectedLine;

        return [
            'valid' => $isValid,
            'note' => $isValid
                ? 'Urutan setoran valid.'
                : "Setoran tidak urut. Posisi berikutnya seharusnya halaman {$expectedPage} baris {$expectedLine}.",
            'expected_page' => $expectedPage,
            'expected_line' => $expectedLine,
            'previous_record_id' => $previousRecord->id,
        ];
    }

    public function nextPosition(int $endPage, int $endLine): array
    {
        if ($endLine < LineRangeCalculator::MAX_LINE) {
            return [$endPage, $endLine + 1];
        }

        return [$endPage + 1, LineRangeCalculator::MIN_LINE];
    }
}
```

Catatan:

1. Jika santri belum punya setoran, titik awal bebas.
2. Jika sudah punya setoran, input berikutnya wajib mulai dari halaman/baris setelah setoran terakhir.
3. Phase 4 belum menangani backdated correction secara kompleks.
4. Phase 4 belum menangani reset target per semester.
5. Refinement sequential guard bisa dilakukan pada Phase 5.

---

# 9. Form Request `StoreHafalanRecordRequest`

Buka:

```text
app/Http/Requests/Tahfizh/StoreHafalanRecordRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahfizh;

use App\Models\HafalanRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHafalanRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'student_id' => ['required', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'tahfizh_target_id' => ['nullable', 'exists:tahfizh_targets,id'],
            'record_date' => ['required', 'date'],

            'start_surah_id' => ['nullable', 'exists:quran_surahs,id'],
            'start_ayah' => ['nullable', 'integer', 'min:1'],
            'end_surah_id' => ['nullable', 'exists:quran_surahs,id'],
            'end_ayah' => ['nullable', 'integer', 'min:1'],

            'start_page' => ['required', 'integer', 'min:1', 'max:604'],
            'start_line' => ['required', 'integer', 'min:1', 'max:15'],
            'end_page' => ['required', 'integer', 'min:1', 'max:604'],
            'end_line' => ['required', 'integer', 'min:1', 'max:15'],

            'status' => [
                'required',
                'string',
                Rule::in([
                    HafalanRecord::STATUS_LUNAS,
                    HafalanRecord::STATUS_KURANG,
                    HafalanRecord::STATUS_LEBIH,
                ]),
            ],

            'quality_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'school_id' => 'sekolah',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'tahfizh_target_id' => 'target tahfizh',
            'record_date' => 'tanggal setoran',
            'start_page' => 'halaman awal',
            'start_line' => 'baris awal',
            'end_page' => 'halaman akhir',
            'end_line' => 'baris akhir',
            'status' => 'status setoran',
            'quality_score' => 'nilai kualitas',
            'notes' => 'catatan',
        ];
    }
}
```

Catatan:

Status Phase 4 dibatasi pada:

```text
lunas
kurang
lebih
```

Status seperti `izin`, `sakit`, dan `tidak_hadir` belum dibuat UI-nya pada Phase 4 karena kolom halaman/baris masih wajib terisi. Absensi tahfizh bisa ditangani pada fase khusus.

---

# 10. Form Request `UpdateHafalanRecordRequest`

Buka:

```text
app/Http/Requests/Tahfizh/UpdateHafalanRecordRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Tahfizh;

use App\Models\HafalanRecord;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHafalanRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $record = $this->route('hafalan_record');

        if (! $user || ! $record) {
            return false;
        }

        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        if ($user->hasRole('teacher')) {
            return (int) $record->teacher_id === (int) $user->id;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['required', 'exists:schools,id'],
            'student_id' => ['required', 'exists:students,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'tahfizh_target_id' => ['nullable', 'exists:tahfizh_targets,id'],
            'record_date' => ['required', 'date'],

            'start_surah_id' => ['nullable', 'exists:quran_surahs,id'],
            'start_ayah' => ['nullable', 'integer', 'min:1'],
            'end_surah_id' => ['nullable', 'exists:quran_surahs,id'],
            'end_ayah' => ['nullable', 'integer', 'min:1'],

            'start_page' => ['required', 'integer', 'min:1', 'max:604'],
            'start_line' => ['required', 'integer', 'min:1', 'max:15'],
            'end_page' => ['required', 'integer', 'min:1', 'max:604'],
            'end_line' => ['required', 'integer', 'min:1', 'max:15'],

            'status' => [
                'required',
                'string',
                Rule::in([
                    HafalanRecord::STATUS_LUNAS,
                    HafalanRecord::STATUS_KURANG,
                    HafalanRecord::STATUS_LEBIH,
                ]),
            ],

            'quality_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'school_id' => 'sekolah',
            'student_id' => 'santri',
            'teacher_id' => 'guru',
            'tahfizh_target_id' => 'target tahfizh',
            'record_date' => 'tanggal setoran',
            'start_page' => 'halaman awal',
            'start_line' => 'baris awal',
            'end_page' => 'halaman akhir',
            'end_line' => 'baris akhir',
            'status' => 'status setoran',
            'quality_score' => 'nilai kualitas',
            'notes' => 'catatan',
        ];
    }
}
```

---

# 11. Controller `HafalanRecordController`

Buka:

```text
app/Http/Controllers/Tahfizh/HafalanRecordController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahfizh\StoreHafalanRecordRequest;
use App\Http\Requests\Tahfizh\UpdateHafalanRecordRequest;
use App\Models\ClassRoom;
use App\Models\HafalanRecord;
use App\Models\QuranSurah;
use App\Models\School;
use App\Models\Student;
use App\Models\TahfizhTarget;
use App\Models\User;
use App\Services\Tahfizh\HafalanSequenceGuard;
use App\Services\Tahfizh\LineRangeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use InvalidArgumentException;

class HafalanRecordController extends Controller
{
    public function __construct(
        private readonly LineRangeCalculator $lineRangeCalculator,
        private readonly HafalanSequenceGuard $sequenceGuard,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $records = HafalanRecord::query()
            ->with([
                'school',
                'student.classRoom',
                'teacher',
                'tahfizhTarget',
                'startSurah',
                'endSurah',
            ])
            ->when($user->hasRole('teacher'), function ($query) use ($user): void {
                $query->where('teacher_id', $user->id);
            })
            ->when($request->filled('class_room_id'), function ($query) use ($request): void {
                $query->whereHas('student', function ($studentQuery) use ($request): void {
                    $studentQuery->where('class_room_id', $request->integer('class_room_id'));
                });
            })
            ->when($request->filled('student_id'), function ($query) use ($request): void {
                $query->where('student_id', $request->integer('student_id'));
            })
            ->when($request->filled('teacher_id'), function ($query) use ($request): void {
                $query->where('teacher_id', $request->integer('teacher_id'));
            })
            ->when($request->filled('status'), function ($query) use ($request): void {
                $query->where('status', $request->string('status'));
            })
            ->when($request->filled('date_from'), function ($query) use ($request): void {
                $query->whereDate('record_date', '>=', $request->date('date_from'));
            })
            ->when($request->filled('date_until'), function ($query) use ($request): void {
                $query->whereDate('record_date', '<=', $request->date('date_until'));
            })
            ->latest('record_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('tahfizh.hafalan-records.index', [
            'records' => $records,
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

    public function create(Request $request): View
    {
        $user = $request->user();

        return view('tahfizh.hafalan-records.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()
                ->with('classRoom')
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
            'targets' => TahfizhTarget::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'surahs' => QuranSurah::query()
                ->orderBy('number')
                ->get(),
            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ],
            'defaultTeacherId' => $user->hasRole('teacher') ? $user->id : null,
        ]);
    }

    public function store(StoreHafalanRecordRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            $totalLines = $this->lineRangeCalculator->calculate(
                (int) $request->input('start_page'),
                (int) $request->input('start_line'),
                (int) $request->input('end_page'),
                (int) $request->input('end_line'),
            );
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'start_page' => $exception->getMessage(),
            ]);
        }

        $sequence = $this->sequenceGuard->validate(
            studentId: (int) $request->input('student_id'),
            recordDate: $request->input('record_date'),
            startPage: (int) $request->input('start_page'),
            startLine: (int) $request->input('start_line'),
        );

        if (! $sequence['valid']) {
            throw ValidationException::withMessages([
                'start_page' => $sequence['note'],
            ]);
        }

        DB::transaction(function () use ($request, $user, $totalLines, $sequence): void {
            $teacherId = $user->hasRole('teacher')
                ? $user->id
                : $request->input('teacher_id');

            HafalanRecord::query()->create([
                'school_id' => $request->integer('school_id'),
                'student_id' => $request->integer('student_id'),
                'teacher_id' => $teacherId,
                'tahfizh_target_id' => $request->input('tahfizh_target_id'),
                'record_date' => $request->input('record_date'),

                'start_surah_id' => $request->input('start_surah_id'),
                'start_ayah' => $request->input('start_ayah'),
                'end_surah_id' => $request->input('end_surah_id'),
                'end_ayah' => $request->input('end_ayah'),

                'start_page' => $request->integer('start_page'),
                'start_line' => $request->integer('start_line'),
                'end_page' => $request->integer('end_page'),
                'end_line' => $request->integer('end_line'),

                'total_lines' => $totalLines,
                'status' => $request->input('status'),
                'quality_score' => $request->input('quality_score'),
                'notes' => $request->input('notes'),

                'is_sequence_valid' => true,
                'sequence_note' => $sequence['note'],

                'created_by' => $user->id,
                'updated_by' => null,
            ]);
        });

        return redirect()
            ->route('tahfizh.hafalan-records.index')
            ->with('success', 'Setoran hafalan berhasil disimpan.');
    }

    public function show(HafalanRecord $hafalanRecord): View
    {
        $hafalanRecord->load([
            'school',
            'student.classRoom',
            'teacher',
            'tahfizhTarget',
            'startSurah',
            'endSurah',
            'creator',
            'updater',
        ]);

        return view('tahfizh.hafalan-records.show', [
            'record' => $hafalanRecord,
        ]);
    }

    public function edit(Request $request, HafalanRecord $hafalanRecord): View
    {
        $user = $request->user();

        if ($user->hasRole('teacher') && (int) $hafalanRecord->teacher_id !== (int) $user->id) {
            abort(403, 'Guru hanya boleh mengedit setoran miliknya.');
        }

        return view('tahfizh.hafalan-records.edit', [
            'record' => $hafalanRecord,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()
                ->with('classRoom')
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
            'targets' => TahfizhTarget::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'surahs' => QuranSurah::query()
                ->orderBy('number')
                ->get(),
            'statuses' => [
                HafalanRecord::STATUS_LUNAS,
                HafalanRecord::STATUS_KURANG,
                HafalanRecord::STATUS_LEBIH,
            ],
        ]);
    }

    public function update(UpdateHafalanRecordRequest $request, HafalanRecord $hafalanRecord): RedirectResponse
    {
        $user = $request->user();

        try {
            $totalLines = $this->lineRangeCalculator->calculate(
                (int) $request->input('start_page'),
                (int) $request->input('start_line'),
                (int) $request->input('end_page'),
                (int) $request->input('end_line'),
            );
        } catch (InvalidArgumentException $exception) {
            throw ValidationException::withMessages([
                'start_page' => $exception->getMessage(),
            ]);
        }

        $sequence = $this->sequenceGuard->validate(
            studentId: (int) $request->input('student_id'),
            recordDate: $request->input('record_date'),
            startPage: (int) $request->input('start_page'),
            startLine: (int) $request->input('start_line'),
            ignoreRecordId: $hafalanRecord->id,
        );

        if (! $sequence['valid']) {
            throw ValidationException::withMessages([
                'start_page' => $sequence['note'],
            ]);
        }

        DB::transaction(function () use ($request, $user, $hafalanRecord, $totalLines, $sequence): void {
            $teacherId = $user->hasRole('teacher')
                ? $user->id
                : $request->input('teacher_id');

            $hafalanRecord->update([
                'school_id' => $request->integer('school_id'),
                'student_id' => $request->integer('student_id'),
                'teacher_id' => $teacherId,
                'tahfizh_target_id' => $request->input('tahfizh_target_id'),
                'record_date' => $request->input('record_date'),

                'start_surah_id' => $request->input('start_surah_id'),
                'start_ayah' => $request->input('start_ayah'),
                'end_surah_id' => $request->input('end_surah_id'),
                'end_ayah' => $request->input('end_ayah'),

                'start_page' => $request->integer('start_page'),
                'start_line' => $request->integer('start_line'),
                'end_page' => $request->integer('end_page'),
                'end_line' => $request->integer('end_line'),

                'total_lines' => $totalLines,
                'status' => $request->input('status'),
                'quality_score' => $request->input('quality_score'),
                'notes' => $request->input('notes'),

                'is_sequence_valid' => true,
                'sequence_note' => $sequence['note'],

                'updated_by' => $user->id,
            ]);
        });

        return redirect()
            ->route('tahfizh.hafalan-records.index')
            ->with('success', 'Setoran hafalan berhasil diperbarui.');
    }

    public function destroy(Request $request, HafalanRecord $hafalanRecord): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole('teacher') && (int) $hafalanRecord->teacher_id !== (int) $user->id) {
            abort(403, 'Guru hanya boleh menghapus setoran miliknya.');
        }

        $hafalanRecord->delete();

        return redirect()
            ->route('tahfizh.hafalan-records.index')
            ->with('success', 'Setoran hafalan berhasil dihapus.');
    }
}
```

---

# 12. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Tahfizh\HafalanRecordController;
```

Di dalam group `Route::middleware('auth')->group(...)`, tambahkan:

```php
Route::middleware('role:super_admin,admin,teacher,principal')
    ->prefix('tahfizh')
    ->name('tahfizh.')
    ->group(function (): void {
        Route::get('hafalan-records', [HafalanRecordController::class, 'index'])
            ->name('hafalan-records.index');

        Route::get('hafalan-records/{hafalan_record}', [HafalanRecordController::class, 'show'])
            ->name('hafalan-records.show');
    });

Route::middleware('role:super_admin,admin,teacher')
    ->prefix('tahfizh')
    ->name('tahfizh.')
    ->group(function (): void {
        Route::get('hafalan-records/create', [HafalanRecordController::class, 'create'])
            ->name('hafalan-records.create');

        Route::post('hafalan-records', [HafalanRecordController::class, 'store'])
            ->name('hafalan-records.store');

        Route::get('hafalan-records/{hafalan_record}/edit', [HafalanRecordController::class, 'edit'])
            ->name('hafalan-records.edit');

        Route::put('hafalan-records/{hafalan_record}', [HafalanRecordController::class, 'update'])
            ->name('hafalan-records.update');

        Route::delete('hafalan-records/{hafalan_record}', [HafalanRecordController::class, 'destroy'])
            ->name('hafalan-records.destroy');
    });
```

Catatan:

1. Kepala Sekolah hanya boleh index dan show.
2. Guru boleh create/store/edit/update/delete miliknya.
3. Admin dan Super Admin boleh manage seluruh setoran.
4. Orang Tua dan Santri belum mendapat akses Phase 4.

---

# 13. Update Navigasi Layout

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan menu di bagian navigasi `@auth`:

```blade
@if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher', 'principal']))
    <a href="{{ route('tahfizh.hafalan-records.index') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Setoran Tahfizh
    </a>
@endif
```

Pastikan menu master data tetap hanya untuk admin/super admin.

---

# 14. Buat Folder View

Jalankan:

```powershell
mkdir resources\views\tahfizh
mkdir resources\views\tahfizh\hafalan-records
```

---

# 15. View `index.blade.php`

Buat file:

```text
resources/views/tahfizh/hafalan-records/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Setoran Tahfizh</h2>
            <p class="text-sm text-slate-500">Riwayat setoran hafalan santri.</p>
        </div>

        @if (auth()->user()->hasRole(['super_admin', 'admin', 'teacher']))
            <a href="{{ route('tahfizh.hafalan-records.create') }}"
               class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Tambah Setoran
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('tahfizh.hafalan-records.index') }}"
          class="mb-6 grid gap-4 rounded-2xl bg-white p-4 shadow-sm md:grid-cols-6">
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

        <div>
            <label class="mb-1 block text-sm font-semibold">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold">Sampai</label>
            <input type="date" name="date_until" value="{{ request('date_until') }}"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2">
        </div>

        <div class="md:col-span-6">
            <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Filter
            </button>

            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="ml-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Reset
            </a>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Santri</th>
                    <th class="px-4 py-3">Guru</th>
                    <th class="px-4 py-3">Rentang</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $record)
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            {{ $record->record_date?->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $record->student?->full_name }}</div>
                            <div class="text-xs text-slate-500">{{ $record->student?->classRoom?->name ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $record->teacher?->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            Hlm {{ $record->start_page }}:{{ $record->start_line }}
                            —
                            Hlm {{ $record->end_page }}:{{ $record->end_line }}
                        </td>
                        <td class="px-4 py-3">{{ $record->total_lines }} baris</td>
                        <td class="px-4 py-3">
                            {{ strtoupper(str_replace('_', ' ', $record->status)) }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('tahfizh.hafalan-records.show', $record) }}"
                                   class="text-slate-700 hover:underline">
                                    Lihat
                                </a>

                                @if (auth()->user()->hasRole(['super_admin', 'admin']) || (auth()->user()->hasRole('teacher') && $record->teacher_id === auth()->id()))
                                    <a href="{{ route('tahfizh.hafalan-records.edit', $record) }}"
                                       class="text-blue-700 hover:underline">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('tahfizh.hafalan-records.destroy', $record) }}"
                                          onsubmit="return confirm('Hapus setoran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-700 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                            Belum ada setoran.
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

# 16. View `create.blade.php`

Buat file:

```text
resources/views/tahfizh/hafalan-records/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Tambah Setoran Tahfizh</h2>
        <p class="text-sm text-slate-500">Input setoran hafalan santri.</p>
    </div>

    <form method="POST" action="{{ route('tahfizh.hafalan-records.store') }}"
          class="space-y-6 rounded-2xl bg-white p-6 shadow-sm">
        @csrf

        @include('tahfizh.hafalan-records._form', [
            'record' => null,
            'schools' => $schools,
            'students' => $students,
            'teachers' => $teachers,
            'targets' => $targets,
            'surahs' => $surahs,
            'statuses' => $statuses,
            'defaultTeacherId' => $defaultTeacherId,
        ])

        <div class="flex gap-3">
            <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Simpan
            </button>

            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
@endsection
```

---

# 17. View `edit.blade.php`

Buat file:

```text
resources/views/tahfizh/hafalan-records/edit.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Edit Setoran Tahfizh</h2>
        <p class="text-sm text-slate-500">Perbarui data setoran hafalan.</p>
    </div>

    <form method="POST" action="{{ route('tahfizh.hafalan-records.update', $record) }}"
          class="space-y-6 rounded-2xl bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        @include('tahfizh.hafalan-records._form', [
            'record' => $record,
            'schools' => $schools,
            'students' => $students,
            'teachers' => $teachers,
            'targets' => $targets,
            'surahs' => $surahs,
            'statuses' => $statuses,
            'defaultTeacherId' => $record->teacher_id,
        ])

        <div class="flex gap-3">
            <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                Simpan Perubahan
            </button>

            <a href="{{ route('tahfizh.hafalan-records.index') }}"
               class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
@endsection
```

---

# 18. View Partial `_form.blade.php`

Buat file:

```text
resources/views/tahfizh/hafalan-records/_form.blade.php
```

Isi lengkap:

```blade
<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold">Sekolah</label>
        <select name="school_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            <option value="">Pilih sekolah</option>
            @foreach ($schools as $school)
                <option value="{{ $school->id }}" @selected(old('school_id', $record->school_id ?? null) == $school->id)>
                    {{ $school->name }}
                </option>
            @endforeach
        </select>
        @error('school_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Tanggal Setoran</label>
        <input type="date" name="record_date"
               value="{{ old('record_date', isset($record) && $record ? $record->record_date?->format('Y-m-d') : now()->toDateString()) }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
        @error('record_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Santri</label>
        <select name="student_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            <option value="">Pilih santri</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_id', $record->student_id ?? null) == $student->id)>
                    {{ $student->full_name }} — {{ $student->classRoom?->name ?? '-' }}
                </option>
            @endforeach
        </select>
        @error('student_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Guru</label>
        <select name="teacher_id" class="w-full rounded-lg border border-slate-300 px-4 py-2" @disabled(auth()->user()->hasRole('teacher'))>
            <option value="">Pilih guru</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected(old('teacher_id', $defaultTeacherId ?? null) == $teacher->id)>
                    {{ $teacher->name }}
                </option>
            @endforeach
        </select>

        @if (auth()->user()->hasRole('teacher'))
            <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
        @endif

        @error('teacher_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Target Tahfizh</label>
        <select name="tahfizh_target_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
            <option value="">Tanpa target khusus</option>
            @foreach ($targets as $target)
                <option value="{{ $target->id }}" @selected(old('tahfizh_target_id', $record->tahfizh_target_id ?? null) == $target->id)>
                    {{ $target->name }}
                </option>
            @endforeach
        </select>
        @error('tahfizh_target_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Status</label>
        <select name="status" class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $record->status ?? 'kurang') === $status)>
                    {{ strtoupper(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8">
    <h3 class="mb-4 text-lg font-bold">Rentang Hafalan</h3>

    <div class="grid gap-6 md:grid-cols-4">
        <div>
            <label class="mb-2 block text-sm font-semibold">Halaman Awal</label>
            <input type="number" name="start_page" min="1" max="604"
                   value="{{ old('start_page', $record->start_page ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('start_page') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Baris Awal</label>
            <input type="number" name="start_line" min="1" max="15"
                   value="{{ old('start_line', $record->start_line ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('start_line') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Halaman Akhir</label>
            <input type="number" name="end_page" min="1" max="604"
                   value="{{ old('end_page', $record->end_page ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('end_page') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Baris Akhir</label>
            <input type="number" name="end_line" min="1" max="15"
                   value="{{ old('end_line', $record->end_line ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2" required>
            @error('end_line') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <p class="mt-2 text-sm text-slate-500">
        Aturan mushaf: 1 halaman = 15 baris. Total baris dihitung otomatis oleh sistem.
    </p>
</div>

<div class="mt-8">
    <h3 class="mb-4 text-lg font-bold">Informasi Surah dan Ayat</h3>

    <div class="grid gap-6 md:grid-cols-4">
        <div>
            <label class="mb-2 block text-sm font-semibold">Surah Awal</label>
            <select name="start_surah_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option value="">Opsional</option>
                @foreach ($surahs as $surah)
                    <option value="{{ $surah->id }}" @selected(old('start_surah_id', $record->start_surah_id ?? null) == $surah->id)>
                        {{ $surah->number }}. {{ $surah->name_latin }}
                    </option>
                @endforeach
            </select>
            @error('start_surah_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Ayat Awal</label>
            <input type="number" name="start_ayah" min="1"
                   value="{{ old('start_ayah', $record->start_ayah ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2">
            @error('start_ayah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Surah Akhir</label>
            <select name="end_surah_id" class="w-full rounded-lg border border-slate-300 px-4 py-2">
                <option value="">Opsional</option>
                @foreach ($surahs as $surah)
                    <option value="{{ $surah->id }}" @selected(old('end_surah_id', $record->end_surah_id ?? null) == $surah->id)>
                        {{ $surah->number }}. {{ $surah->name_latin }}
                    </option>
                @endforeach
            </select>
            @error('end_surah_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold">Ayat Akhir</label>
            <input type="number" name="end_ayah" min="1"
                   value="{{ old('end_ayah', $record->end_ayah ?? '') }}"
                   class="w-full rounded-lg border border-slate-300 px-4 py-2">
            @error('end_ayah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="mt-8 grid gap-6 md:grid-cols-2">
    <div>
        <label class="mb-2 block text-sm font-semibold">Nilai Kualitas</label>
        <input type="number" name="quality_score" min="0" max="100"
               value="{{ old('quality_score', $record->quality_score ?? '') }}"
               class="w-full rounded-lg border border-slate-300 px-4 py-2">
        @error('quality_score') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-2 block text-sm font-semibold">Catatan Guru</label>
        <textarea name="notes" rows="3"
                  class="w-full rounded-lg border border-slate-300 px-4 py-2">{{ old('notes', $record->notes ?? '') }}</textarea>
        @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
```

---

# 19. View `show.blade.php`

Buat file:

```text
resources/views/tahfizh/hafalan-records/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold">Detail Setoran Tahfizh</h2>
            <p class="text-sm text-slate-500">Informasi lengkap setoran hafalan.</p>
        </div>

        <a href="{{ route('tahfizh.hafalan-records.index') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Data Setoran</h3>

            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-semibold">Tanggal</dt>
                    <dd>{{ $record->record_date?->format('d/m/Y') }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Santri</dt>
                    <dd>{{ $record->student?->full_name }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Kelas</dt>
                    <dd>{{ $record->student?->classRoom?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Guru</dt>
                    <dd>{{ $record->teacher?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Target</dt>
                    <dd>{{ $record->tahfizhTarget?->name ?? '-' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Status</dt>
                    <dd>{{ strtoupper(str_replace('_', ' ', $record->status)) }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-lg font-bold">Rentang Hafalan</h3>

            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="font-semibold">Mulai</dt>
                    <dd>Halaman {{ $record->start_page }}, Baris {{ $record->start_line }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Selesai</dt>
                    <dd>Halaman {{ $record->end_page }}, Baris {{ $record->end_line }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Total Baris</dt>
                    <dd>{{ $record->total_lines }} baris</dd>
                </div>

                <div>
                    <dt class="font-semibold">Surah Awal</dt>
                    <dd>{{ $record->startSurah?->name_latin ?? '-' }} {{ $record->start_ayah ? 'ayat '.$record->start_ayah : '' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Surah Akhir</dt>
                    <dd>{{ $record->endSurah?->name_latin ?? '-' }} {{ $record->end_ayah ? 'ayat '.$record->end_ayah : '' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Sequential</dt>
                    <dd>{{ $record->is_sequence_valid ? 'Valid' : 'Tidak Valid' }}</dd>
                </div>

                <div>
                    <dt class="font-semibold">Catatan Sequence</dt>
                    <dd>{{ $record->sequence_note ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm">
        <h3 class="mb-4 text-lg font-bold">Catatan</h3>
        <p class="text-sm text-slate-700">{{ $record->notes ?? '-' }}</p>
    </div>
@endsection
```

---

# 20. Update Dashboard Guru

Buka:

```text
resources/views/dashboards/teacher.blade.php
```

Tambahkan link cepat:

```blade
<a href="{{ route('tahfizh.hafalan-records.create') }}"
   class="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
    Input Setoran Tahfizh
</a>
```

Buka:

```text
resources/views/dashboards/admin.blade.php
resources/views/dashboards/kepala-sekolah.blade.php
```

Tambahkan link ke riwayat setoran:

```blade
<a href="{{ route('tahfizh.hafalan-records.index') }}"
   class="mt-4 inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
    Lihat Setoran Tahfizh
</a>
```

---

# 21. Validasi Route

Jalankan:

```powershell
php artisan route:list
```

Pastikan route berikut ada:

```text
tahfizh.hafalan-records.index
tahfizh.hafalan-records.create
tahfizh.hafalan-records.store
tahfizh.hafalan-records.show
tahfizh.hafalan-records.edit
tahfizh.hafalan-records.update
tahfizh.hafalan-records.destroy
```

---

# 22. Test Manual Role

## 22.1 Super Admin

Login:

```text
superadmin@hafizplus.test
password
```

Harus bisa:

1. Buka `/tahfizh/hafalan-records`.
2. Tambah setoran.
3. Edit setoran.
4. Hapus setoran.
5. Lihat semua setoran.

---

## 22.2 Admin Sekolah

Login:

```text
admin@hafizplus.test
password
```

Harus bisa:

1. Buka `/tahfizh/hafalan-records`.
2. Tambah setoran.
3. Edit setoran.
4. Hapus setoran.
5. Lihat semua setoran.

---

## 22.3 Guru Tahfidz

Login:

```text
guru@hafizplus.test
password
```

Harus bisa:

1. Buka `/tahfizh/hafalan-records`.
2. Tambah setoran.
3. Edit setoran miliknya.
4. Hapus setoran miliknya.
5. Tidak boleh edit setoran guru lain.

---

## 22.4 Kepala Sekolah

Login:

```text
kepalasekolah@hafizplus.test
password
```

Harus bisa:

1. Buka `/tahfizh/hafalan-records`.
2. Lihat detail setoran.

Tidak boleh:

1. Buka create.
2. Store.
3. Edit.
4. Update.
5. Delete.

Target untuk akses tidak sah:

```text
403 Forbidden
```

---

## 22.5 Orang Tua

Login:

```text
ortu@hafizplus.test
password
```

Tidak boleh akses:

```text
/tahfizh/hafalan-records
```

Target:

```text
403 Forbidden
```

---

## 22.6 Santri

Login:

```text
santri@hafizplus.test
password
```

Tidak boleh akses:

```text
/tahfizh/hafalan-records
```

Target:

```text
403 Forbidden
```

---

# 23. Test Manual Validasi Baris

Coba input:

```text
start_page: 1
start_line: 1
end_page: 1
end_line: 5
```

Target:

```text
total_lines = 5
```

Coba input berikutnya untuk santri sama:

```text
start_page: 1
start_line: 6
end_page: 1
end_line: 10
```

Target:

```text
valid
total_lines = 5
```

Coba input lompat:

```text
start_page: 1
start_line: 12
end_page: 1
end_line: 15
```

Jika setoran sebelumnya berakhir di halaman 1 baris 10, target:

```text
gagal
pesan: Setoran tidak urut. Posisi berikutnya seharusnya halaman 1 baris 11.
```

Coba input lintas halaman:

```text
start_page: 1
start_line: 11
end_page: 2
end_line: 5
```

Target:

```text
total_lines = 10
```

Perhitungan:

```text
Halaman 1 baris 11–15 = 5 baris
Halaman 2 baris 1–5 = 5 baris
Total = 10 baris
```

---

# 24. Test Manual Invalid Range

Coba input:

```text
start_page: 2
start_line: 1
end_page: 1
end_line: 5
```

Target:

```text
gagal
Halaman akhir tidak boleh lebih kecil dari halaman awal.
```

Coba input:

```text
start_page: 1
start_line: 10
end_page: 1
end_line: 5
```

Target:

```text
gagal
Baris akhir tidak boleh lebih kecil dari baris awal pada halaman yang sama.
```

Coba input:

```text
start_page: 605
start_line: 1
end_page: 605
end_line: 5
```

Target:

```text
gagal
halaman maksimal 604.
```

Coba input:

```text
start_page: 1
start_line: 16
end_page: 1
end_line: 16
```

Target:

```text
gagal
baris maksimal 15.
```

---

# 25. Dokumentasi Phase 4

Buat file:

```text
docs/phase-4-tahfizh-input-foundation.md
```

Isi lengkap:

````md
# Phase 4 — Tahfizh Input Foundation

## Status

Phase 4 membangun fondasi input setoran tahfizh untuk HafizPlus School Platform.

## Output

1. Halaman daftar setoran tahfizh.
2. Halaman tambah setoran tahfizh.
3. Halaman detail setoran tahfizh.
4. Halaman edit setoran tahfizh.
5. Soft delete setoran tahfizh.
6. Validasi halaman dan baris mushaf.
7. Kalkulasi otomatis total baris.
8. Sequential guard awal.
9. Role-based access untuk setoran tahfizh.

## Route

Route yang dibuat:

1. `tahfizh.hafalan-records.index`
2. `tahfizh.hafalan-records.create`
3. `tahfizh.hafalan-records.store`
4. `tahfizh.hafalan-records.show`
5. `tahfizh.hafalan-records.edit`
6. `tahfizh.hafalan-records.update`
7. `tahfizh.hafalan-records.destroy`

## Role Access

| Role | Index | Show | Create | Store | Edit | Update | Delete |
|---|---|---|---|---|---|---|---|
| Super Admin | Ya | Ya | Ya | Ya | Ya | Ya | Ya |
| Admin Sekolah | Ya | Ya | Ya | Ya | Ya | Ya | Ya |
| Kepala Sekolah | Ya | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Guru Tahfidz | Ya | Ya | Ya | Ya | Miliknya | Miliknya | Miliknya |
| Orang Tua | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |
| Santri | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak | Tidak |

## Aturan Mushaf

Sistem memakai aturan:

```text
1 halaman = 15 baris
Total halaman = 604 halaman
````

Validasi:

1. Halaman minimal 1.
2. Halaman maksimal 604.
3. Baris minimal 1.
4. Baris maksimal 15.
5. Halaman akhir tidak boleh lebih kecil dari halaman awal.
6. Pada halaman yang sama, baris akhir tidak boleh lebih kecil dari baris awal.
7. Total baris dihitung otomatis.

## Sequential Guard

Aturan sequential guard awal:

1. Jika santri belum punya setoran, titik awal bebas.
2. Jika santri sudah punya setoran, setoran berikutnya harus mulai dari posisi setelah setoran terakhir.
3. Jika setoran terakhir halaman 1 baris 5, maka setoran berikutnya harus mulai halaman 1 baris 6.
4. Jika setoran terakhir halaman 1 baris 15, maka setoran berikutnya harus mulai halaman 2 baris 1.
5. Setoran lompat ditolak.

## Status Setoran Phase 4

Status yang aktif pada Phase 4:

1. `lunas`
2. `kurang`
3. `lebih`

Status berikut belum diaktifkan di UI Phase 4:

1. `tidak_hadir`
2. `izin`
3. `sakit`

Alasannya: struktur `hafalan_records` Phase 3 masih mewajibkan halaman dan baris. Absensi tahfizh perlu desain khusus agar tidak memaksa input halaman/baris palsu.

## Belum Dibuat

Phase 4 belum membuat:

1. Hutang hafalan otomatis.
2. Target achievement otomatis.
3. Report bulanan.
4. Report triwulan.
5. Dashboard statistik.
6. Parent progress detail.
7. Student progress detail.
8. Notification real.
9. Export PDF.
10. Export Excel.
11. API mobile.

## Definition of Done

Phase 4 selesai jika:

1. Route setoran tahfizh tersedia.
2. Menu Setoran Tahfizh muncul untuk Super Admin, Admin, Guru, dan Kepala Sekolah.
3. Super Admin bisa CRUD setoran.
4. Admin bisa CRUD setoran.
5. Guru bisa tambah setoran.
6. Guru hanya bisa edit/hapus setoran miliknya.
7. Kepala Sekolah hanya bisa melihat setoran.
8. Orang Tua tidak bisa akses setoran admin/guru.
9. Santri tidak bisa akses setoran admin/guru.
10. Total baris dihitung otomatis.
11. Input halaman/baris invalid ditolak.
12. Setoran lompat ditolak.
13. Dokumentasi Phase 4 dibuat.
14. Build frontend berhasil.

````

---

# 26. Update Project Progress

Buka atau buat:

```text
docs/project-progress.md
````

Isi atau update:

```md
# Project Progress — HafizPlus School Platform

| Phase | Nama | Status |
|---:|---|---|
| 0 | Product Foundation | Done |
| 1 | Auth, Role, and Initial Database Foundation | Done |
| 2 | Master Data Foundation | Done |
| 3 | Tahfizh Core Database Foundation | Done |
| 4 | Tahfizh Input Foundation | Done |
| 5 | Target and Debt Calculation | Pending |
| 6 | Dashboard and Reports | Pending |
| 7 | Parent and Student Progress Portal | Pending |
| 8 | Notification Center | Pending |
```

---

# 27. Build Frontend

Jalankan:

```powershell
npm run build
```

---

# 28. Validasi Akhir

Jalankan:

```powershell
php artisan route:list
php artisan migrate:status
npm run build
```

Lalu jalankan server:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

---

# 29. Commit Phase 4

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add tahfizh input foundation"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-4-tahfizh-input-foundation
```

---

# 30. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 4 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Daftar setoran tahfizh
- Tambah setoran tahfizh
- Detail setoran tahfizh
- Edit setoran tahfizh
- Hapus setoran tahfizh dengan soft delete
- Validasi halaman dan baris
- Kalkulasi total baris otomatis
- Sequential guard awal

Route dibuat:
- tahfizh.hafalan-records.index
- tahfizh.hafalan-records.create
- tahfizh.hafalan-records.store
- tahfizh.hafalan-records.show
- tahfizh.hafalan-records.edit
- tahfizh.hafalan-records.update
- tahfizh.hafalan-records.destroy

Role access:
- Super Admin: CRUD
- Admin Sekolah: CRUD
- Guru Tahfidz: CRUD miliknya
- Kepala Sekolah: read-only
- Orang Tua: belum akses
- Santri: belum akses

Belum dibuat:
- Hutang hafalan otomatis
- Target achievement otomatis
- Report bulanan
- Report triwulan
- Dashboard statistik
- Parent progress detail
- Student progress detail
- Notification center
- Export PDF/Excel

Status:
- Siap lanjut Phase 5 setelah validasi manual.
```

---

# 31. Larangan Setelah Phase 4

Agent harus berhenti setelah Phase 4 selesai.

Jangan lanjut membuat:

1. Hutang otomatis.
2. Report bulanan.
3. Report triwulan.
4. Dashboard grafik.
5. Parent portal detail.
6. Student portal detail.
7. Notification center.
8. Export PDF/Excel.
9. WhatsApp gateway.
10. API mobile.

Semua itu masuk fase berikutnya.
