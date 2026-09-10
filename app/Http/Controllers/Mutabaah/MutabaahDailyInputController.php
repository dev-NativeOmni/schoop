<?php

namespace App\Http\Controllers\Mutabaah;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mutabaah\StoreMutabaahDailyInputRequest;
use App\Models\ClassRoom;
use App\Models\MutabaahActivity;
use App\Models\MutabaahRecord;
use App\Models\Student;
use App\Services\Mutabaah\MutabaahAccessService;
use App\Services\Mutabaah\MutabaahRecordService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MutabaahDailyInputController extends Controller
{
    public function __construct(
        private readonly MutabaahAccessService $accessService,
        private readonly MutabaahRecordService $recordService,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();

        if (! $this->accessService->canInputDailyRecord($user)) {
            abort(403, 'Anda tidak memiliki akses untuk input mutabaah.');
        }

        $selectedDate = $request->input('record_date', today()->toDateString());
        $selectedStudent = null;

        // Student scope
        $studentQuery = Student::query()
            ->with(['user', 'classRoom'])
            ->where('is_active', true)
            ->orderBy('full_name');

        $this->accessService->applyStudentScope($studentQuery, $user);

        // Apply filters if any
        if ($request->filled('class_room_id')) {
            $studentQuery->where('class_room_id', $request->integer('class_room_id'));
        }

        if ($request->filled('student_id')) {
            $studentQuery->where('id', $request->integer('student_id'));
        }

        $students = $studentQuery->get();

        if ($request->filled('student_id')) {
            $selectedStudent = $students->firstWhere('id', $request->integer('student_id'));
        }

        $activities = MutabaahActivity::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Load existing records for all displayed students
        $studentIds = $students->pluck('id')->all();
        $existingRecords = collect();

        if (! empty($studentIds)) {
            $existingRecords = MutabaahRecord::query()
                ->whereIn('student_id', $studentIds)
                ->whereDate('record_date', $selectedDate)
                ->get()
                ->groupBy('student_id')
                ->map(fn ($records) => $records->keyBy('mutabaah_activity_id'));
        }

        $classRooms = ClassRoom::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('mutabaah.daily.index', compact(
            'students',
            'activities',
            'existingRecords',
            'selectedDate',
            'selectedStudent',
            'classRooms',
        ));
    }

    public function store(StoreMutabaahDailyInputRequest $request): RedirectResponse
    {
        $user = $request->user();

        $student = Student::query()
            ->where('id', $request->integer('student_id'))
            ->where('is_active', true)
            ->firstOrFail();

        if (! $this->accessService->canViewStudent($user, $student)) {
            abort(403, 'Anda tidak memiliki akses ke santri ini.');
        }

        $source = match (true) {
            $this->accessService->isAdmin($user) => 'admin',
            $this->accessService->isTeacher($user) => 'teacher',
            default => 'admin',
        };

        $this->recordService->saveDailyRecords(
            student: $student,
            recordDate: $request->input('record_date'),
            records: $request->input('records', []),
            submittedBy: $user,
            source: $source,
        );

        return redirect()
            ->route('mutabaah.daily.index', [
                'student_id' => $student->id,
                'record_date' => $request->input('record_date'),
            ])
            ->with('success', 'Mutabaah harian berhasil disimpan.');
    }
}
