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
use App\Services\Notifications\NotificationDispatchService;
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
        $activeSchoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();

        $students = Student::query()
            ->with('classRoom')
            ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
            ->where('is_active', true)
            ->orderBy('full_name')
            ->get();

        $latestRecords = HafalanRecord::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->orderByDesc('record_date')
            ->orderByDesc('id')
            ->get()
            ->unique('student_id')
            ->keyBy('student_id');

        $studentsData = $students->mapWithKeys(function ($student) use ($latestRecords) {
            $latest = $latestRecords->get($student->id);
            $expectedPage = 1;
            $expectedLine = 1;
            $hasPrevious = false;

            if ($latest) {
                $hasPrevious = true;
                if ($latest->end_line < 15) {
                    $expectedPage = (int) $latest->end_page;
                    $expectedLine = (int) $latest->end_line + 1;
                } else {
                    $expectedPage = min(604, (int) $latest->end_page + 1);
                    $expectedLine = 1;
                }
            }

            return [
                $student->id => [
                    'id' => $student->id,
                    'name' => $student->full_name,
                    'nis' => $student->nis ?? '-',
                    'class_room_id' => $student->class_room_id,
                    'class_room_name' => $student->classRoom?->name ?? 'Tanpa Kelas',
                    'school_id' => $student->school_id,
                    'has_previous' => $hasPrevious,
                    'last_record_date' => $latest?->record_date?->format('d/m/Y'),
                    'last_end_page' => $latest?->end_page,
                    'last_end_line' => $latest?->end_line,
                    'expected_page' => $expectedPage,
                    'expected_line' => $expectedLine,
                    'target_daily_lines' => 15,
                ]
            ];
        })->toArray();

        return view('tahfizh.hafalan-records.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => $students,
            'studentsData' => $studentsData,
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

        $record = DB::transaction(function () use ($request, $user, $totalLines, $sequence): HafalanRecord {
            $teacherId = $user->hasRole('teacher')
                ? $user->id
                : $request->input('teacher_id');

            return HafalanRecord::query()->create([
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

        app(NotificationDispatchService::class)->notifyHafalanRecordCreated($record);

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

        $activeSchoolId = app(\App\Services\Tenancy\TenantContextService::class)->activeSchoolId();

        $students = Student::query()
            ->with('classRoom')
            ->when($activeSchoolId, fn ($q) => $q->where('school_id', $activeSchoolId))
            ->where('is_active', true)
            ->orderBy('full_name')
            ->get();

        $latestRecords = HafalanRecord::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->where('id', '!=', $hafalanRecord->id)
            ->orderByDesc('record_date')
            ->orderByDesc('id')
            ->get()
            ->unique('student_id')
            ->keyBy('student_id');

        $studentsData = $students->mapWithKeys(function ($student) use ($latestRecords) {
            $latest = $latestRecords->get($student->id);
            $expectedPage = 1;
            $expectedLine = 1;
            $hasPrevious = false;

            if ($latest) {
                $hasPrevious = true;
                if ($latest->end_line < 15) {
                    $expectedPage = (int) $latest->end_page;
                    $expectedLine = (int) $latest->end_line + 1;
                } else {
                    $expectedPage = min(604, (int) $latest->end_page + 1);
                    $expectedLine = 1;
                }
            }

            return [
                $student->id => [
                    'id' => $student->id,
                    'name' => $student->full_name,
                    'nis' => $student->nis ?? '-',
                    'class_room_id' => $student->class_room_id,
                    'class_room_name' => $student->classRoom?->name ?? 'Tanpa Kelas',
                    'school_id' => $student->school_id,
                    'has_previous' => $hasPrevious,
                    'last_record_date' => $latest?->record_date?->format('d/m/Y'),
                    'last_end_page' => $latest?->end_page,
                    'last_end_line' => $latest?->end_line,
                    'expected_page' => $expectedPage,
                    'expected_line' => $expectedLine,
                    'target_daily_lines' => 15,
                ]
            ];
        })->toArray();

        return view('tahfizh.hafalan-records.edit', [
            'record' => $hafalanRecord,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => $students,
            'studentsData' => $studentsData,
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
            'defaultTeacherId' => $hafalanRecord->teacher_id,
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
