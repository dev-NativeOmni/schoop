<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingDisciplineLogRequest;
use App\Models\BoardingDisciplineLog;
use App\Models\Student;
use App\Services\Boarding\BoardingAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingDisciplineLogController extends Controller
{
    protected BoardingAccessService $accessService;

    public function __construct(BoardingAccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    public function index(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $query = BoardingDisciplineLog::query()
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['student', 'recordedBy'])
            ->orderByDesc('logged_at');

        // Scope filter for boarding supervisor
        if ($request->user()->isBoardingSupervisor()) {
            $profile = $request->user()->boardingSupervisorProfile;
            if ($profile && $profile->status === 'active') {
                $query->whereHas('student.activeBoardingAssignment', function ($q) use ($profile) {
                    if ($profile->boarding_room_id) {
                        $q->where('boarding_room_id', $profile->boarding_room_id);
                    } elseif ($profile->boarding_dormitory_id) {
                        $q->where('boarding_dormitory_id', $profile->boarding_dormitory_id);
                    }
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        $disciplineLogs = $query->paginate(15)->withQueryString();

        return view('boarding.discipline-logs.index', compact('disciplineLogs'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;

        // Query active students to log discipline for
        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereHas('boardingAssignments', function ($q) {
                $q->where('status', 'active');
            })
            ->orderBy('full_name')
            ->get();

        return view('boarding.discipline-logs.create', compact('students'));
    }

    public function store(StoreBoardingDisciplineLogRequest $request): RedirectResponse
    {
        $student = Student::findOrFail($request->student_id);
        abort_unless($this->accessService->canViewStudent($request->user(), $student), 403);

        BoardingDisciplineLog::create([
            'student_id' => $request->student_id,
            'recorded_by_user_id' => $request->user()->id,
            'type' => $request->type,
            'category' => $request->category,
            'description' => $request->description,
            'points' => $request->points,
            'action_taken' => $request->action_taken,
            'logged_at' => $request->logged_at,
        ]);

        return redirect()
            ->route('boarding.discipline-logs.index')
            ->with('success', 'Catatan kedisiplinan santri berhasil ditambahkan.');
    }

    public function show(Request $request, BoardingDisciplineLog $disciplineLog): View
    {
        $disciplineLog->load(['student.classRoom', 'recordedBy']);
        abort_unless($this->accessService->canViewStudent($request->user(), $disciplineLog->student), 403);

        return view('boarding.discipline-logs.show', compact('disciplineLog'));
    }
}
