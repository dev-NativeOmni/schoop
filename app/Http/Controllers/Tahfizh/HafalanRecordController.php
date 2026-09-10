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
use App\Services\Notifications\NotificationDispatchService;
use App\Services\Tahfizh\HafalanRecordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use InvalidArgumentException;

class HafalanRecordController extends Controller
{
    public function __construct(
        private readonly HafalanRecordService $hafalanRecordService,
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
            ->filter([
                'class_room_id' => $request->filled('class_room_id') ? $request->integer('class_room_id') : null,
                'student_id' => $request->filled('student_id') ? $request->integer('student_id') : null,
                'teacher_id' => $request->filled('teacher_id') ? $request->integer('teacher_id') : null,
                'status' => $request->filled('status') ? $request->string('status')->toString() : null,
                'date_from' => $request->filled('date_from') ? $request->date('date_from') : null,
                'date_until' => $request->filled('date_until') ? $request->date('date_until') : null,
            ])
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
        $record = $this->hafalanRecordService->createRecord(
            $request->validated(),
            $request->user()
        );

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
        $this->hafalanRecordService->updateRecord(
            $hafalanRecord,
            $request->validated(),
            $request->user()
        );

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
