<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingLeaveRequestRequest;
use App\Http\Requests\Boarding\UpdateBoardingLeaveRequestStatusRequest;
use App\Models\BoardingLeaveRequest;
use App\Models\Student;
use App\Services\Boarding\BoardingAccessService;
use App\Services\Boarding\BoardingLeaveRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Exception;

class BoardingLeaveRequestController extends Controller
{
    protected BoardingAccessService $accessService;
    protected BoardingLeaveRequestService $leaveService;

    public function __construct(
        BoardingAccessService $accessService,
        BoardingLeaveRequestService $leaveService
    ) {
        $this->accessService = $accessService;
        $this->leaveService = $leaveService;
    }

    public function index(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $query = BoardingLeaveRequest::query()
            ->whereHas('student', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['student', 'requestedBy', 'approvedBy'])
            ->orderByRaw("CASE WHEN status = 'submitted' THEN 1 WHEN status = 'approved' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at');

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
                $query->whereRaw('1 = 0'); // No active profile = no scope access
            }
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
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

        $leaveRequests = $query->paginate(15)->withQueryString();

        return view('boarding.leave-requests.index', compact('leaveRequests'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;
        
        // Query active students to request leave for
        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->whereHas('boardingAssignments', function ($q) {
                $q->where('status', 'active');
            })
            ->orderBy('full_name')
            ->get();

        return view('boarding.leave-requests.create', compact('students'));
    }

    public function store(StoreBoardingLeaveRequestRequest $request): RedirectResponse
    {
        $student = Student::findOrFail($request->student_id);
        abort_unless($this->accessService->canViewStudent($request->user(), $student), 403);

        $this->leaveService->createRequest(
            $request->student_id,
            $request->type,
            $request->destination,
            $request->reason,
            $request->leave_start_at,
            $request->leave_end_at,
            $request->user()->id,
            'submitted'
        );

        return redirect()
            ->route('boarding.leave-requests.index')
            ->with('success', 'Pengajuan izin santri berhasil dikirim.');
    }

    public function show(Request $request, BoardingLeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['student.classRoom', 'requestedBy', 'approvedBy']);
        abort_unless($this->accessService->canViewStudent($request->user(), $leaveRequest->student), 403);

        return view('boarding.leave-requests.show', compact('leaveRequest'));
    }

    public function approve(Request $request, BoardingLeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless($this->accessService->canApproveLeave($request->user(), $leaveRequest), 403);

        try {
            $this->leaveService->approve($leaveRequest->id, $request->user()->id, $request->input('approval_note'));
            return redirect()
                ->route('boarding.leave-requests.show', $leaveRequest->id)
                ->with('success', 'Pengajuan izin disetujui.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reject(Request $request, BoardingLeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless($this->accessService->canApproveLeave($request->user(), $leaveRequest), 403);

        try {
            $this->leaveService->reject($leaveRequest->id, $request->user()->id, $request->input('approval_note'));
            return redirect()
                ->route('boarding.leave-requests.show', $leaveRequest->id)
                ->with('success', 'Pengajuan izin ditolak.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function markReturned(Request $request, BoardingLeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless($this->accessService->canApproveLeave($request->user(), $leaveRequest), 403);

        try {
            $this->leaveService->markReturned($leaveRequest->id);
            return redirect()
                ->route('boarding.leave-requests.show', $leaveRequest->id)
                ->with('success', 'Status santri berhasil ditandai sudah kembali.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function cancel(Request $request, BoardingLeaveRequest $leaveRequest): RedirectResponse
    {
        abort_unless($this->accessService->canViewStudent($request->user(), $leaveRequest->student), 403);

        try {
            $this->leaveService->cancel($leaveRequest->id);
            return redirect()
                ->route('boarding.leave-requests.index')
                ->with('success', 'Pengajuan izin dibatalkan.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
