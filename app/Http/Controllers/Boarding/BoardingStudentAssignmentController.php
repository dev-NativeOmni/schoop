<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingStudentAssignmentRequest;
use App\Models\BoardingBed;
use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Models\BoardingStudentAssignment;
use App\Models\Student;
use App\Services\Boarding\BoardingAccessService;
use App\Services\Boarding\BoardingAssignmentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingStudentAssignmentController extends Controller
{
    protected BoardingAccessService $accessService;

    protected BoardingAssignmentService $assignmentService;

    public function __construct(
        BoardingAccessService $accessService,
        BoardingAssignmentService $assignmentService
    ) {
        $this->accessService = $accessService;
        $this->assignmentService = $assignmentService;
    }

    public function index(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $query = BoardingStudentAssignment::query()
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['student', 'dormitory', 'room', 'bed', 'creator'])
            ->orderByRaw("CASE WHEN status = 'active' THEN 1 ELSE 2 END")
            ->orderByDesc('start_date');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('boarding_dormitory_id')) {
            $query->where('boarding_dormitory_id', $request->boarding_dormitory_id);
        }
        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        $assignments = $query->paginate(15)->withQueryString();

        $dormitories = BoardingDormitory::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        return view('boarding.assignments.index', compact('assignments', 'dormitories'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;

        // Get students who DO NOT have an active assignment
        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereDoesntHave('boardingAssignments', function ($q) {
                $q->where('status', 'active');
            })
            ->orderBy('full_name')
            ->get();

        $dormitories = BoardingDormitory::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        $rooms = BoardingRoom::query()
            ->whereHas('dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->where('is_active', true)
            ->get();

        $beds = BoardingBed::query()
            ->whereHas('room.dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->where('status', 'available')
            ->get();

        return view('boarding.assignments.create', compact('students', 'dormitories', 'rooms', 'beds'));
    }

    public function store(StoreBoardingStudentAssignmentRequest $request): RedirectResponse
    {
        $student = Student::findOrFail($request->student_id);
        abort_unless($this->accessService->canManageStudent($request->user(), $student), 403);

        try {
            $this->assignmentService->assignStudent(
                $request->student_id,
                $request->boarding_dormitory_id,
                $request->boarding_room_id,
                $request->boarding_bed_id,
                $request->start_date,
                $request->notes,
                $request->user()->id
            );

            return redirect()
                ->route('boarding.assignments.index')
                ->with('success', 'Santri berhasil ditempatkan di asrama.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Request $request, BoardingStudentAssignment $assignment): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);
        $assignment->load(['student.classRoom', 'dormitory', 'room', 'bed', 'creator']);
        abort_unless($assignment->student->school_id === $request->user()->school_id, 403);

        $schoolId = $request->user()->school_id;
        $dormitories = BoardingDormitory::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        $rooms = BoardingRoom::query()
            ->whereHas('dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->where('is_active', true)
            ->get();

        $beds = BoardingBed::query()
            ->whereHas('room.dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->where(function ($query) use ($assignment) {
                $query->where('status', 'available')
                    ->orWhere('id', $assignment->boarding_bed_id);
            })
            ->get();

        return view('boarding.assignments.show', compact('assignment', 'dormitories', 'rooms', 'beds'));
    }

    /**
     * End the active student assignment.
     */
    public function end(Request $request, BoardingStudentAssignment $assignment): RedirectResponse
    {
        $student = $assignment->student;
        abort_unless($this->accessService->canManageStudent($request->user(), $student), 403);

        try {
            $endDate = $request->input('end_date', now()->toDateString());
            $this->assignmentService->endAssignment($assignment->id, $endDate);

            return redirect()
                ->route('boarding.assignments.index')
                ->with('success', 'Penempatan asrama santri berhasil diakhiri.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Move student to another bed / room.
     */
    public function move(Request $request, BoardingStudentAssignment $assignment): RedirectResponse
    {
        $student = $assignment->student;
        abort_unless($this->accessService->canManageStudent($request->user(), $student), 403);

        $request->validate([
            'boarding_dormitory_id' => ['required', 'exists:boarding_dormitories,id'],
            'boarding_room_id' => ['required', 'exists:boarding_rooms,id'],
            'boarding_bed_id' => ['nullable', 'exists:boarding_beds,id'],
            'move_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            $this->assignmentService->moveStudent(
                $student->id,
                $request->boarding_dormitory_id,
                $request->boarding_room_id,
                $request->boarding_bed_id,
                $request->move_date,
                $request->notes,
                $request->user()->id
            );

            return redirect()
                ->route('boarding.assignments.show', $assignment->id)
                ->with('success', 'Santri berhasil dipindahkan ke kamar/ranjang baru.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
