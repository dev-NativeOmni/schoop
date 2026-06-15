<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingRollCallSessionRequest;
use App\Http\Requests\Boarding\StoreBoardingRollCallRecordRequest;
use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Models\BoardingRollCallSession;
use App\Models\BoardingRollCallRecord;
use App\Services\Boarding\BoardingAccessService;
use App\Services\Boarding\BoardingRollCallService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Exception;

class BoardingRollCallController extends Controller
{
    protected BoardingAccessService $accessService;
    protected BoardingRollCallService $rollCallService;

    public function __construct(
        BoardingAccessService $accessService,
        BoardingRollCallService $rollCallService
    ) {
        $this->accessService = $accessService;
        $this->rollCallService = $rollCallService;
    }

    public function index(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $query = BoardingRollCallSession::query()
            ->whereHas('dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['dormitory', 'room', 'creator'])
            ->withCount('records')
            ->orderByDesc('session_date')
            ->orderByDesc('created_at');

        // Scope filter for boarding supervisor
        if ($request->user()->isBoardingSupervisor()) {
            $profile = $request->user()->boardingSupervisorProfile;
            if ($profile && $profile->status === 'active') {
                $query->where(function ($q) use ($profile) {
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

        $sessions = $query->paginate(15);

        return view('boarding.roll-calls.index', compact('sessions'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

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

        return view('boarding.roll-calls.create', compact('dormitories', 'rooms'));
    }

    public function store(StoreBoardingRollCallSessionRequest $request): RedirectResponse
    {
        abort_unless($this->accessService->canAccessDashboard($request->user()), 403);

        try {
            $session = $this->rollCallService->createSession(
                $request->boarding_dormitory_id,
                $request->boarding_room_id,
                $request->session_date,
                $request->session_type,
                $request->user()->id
            );

            return redirect()
                ->route('boarding.roll-calls.show', $session->id)
                ->with('success', 'Sesi absen berhasil dibuat dan santri berhasil dimuat.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Request $request, BoardingRollCallSession $rollCall): View
    {
        abort_unless($this->accessService->canManageRollCall($request->user(), $rollCall), 403);

        $rollCall->load(['dormitory', 'room', 'records.student.classRoom', 'creator']);
        
        $records = $rollCall->records()->orderByHasAssignment()->get(); // Order by student name
        // Let's just sort by student name
        $records = $rollCall->records()->get()->sortBy(function($record) {
            return $record->student->full_name;
        });

        return view('boarding.roll-calls.show', compact('rollCall', 'records'));
    }

    /**
     * Save/update the roll call records.
     */
    public function storeRecords(StoreBoardingRollCallRecordRequest $request, BoardingRollCallSession $rollCall): RedirectResponse
    {
        abort_unless($this->accessService->canManageRollCall($request->user(), $rollCall), 403);

        $isAdmin = $request->user()->isSuperAdmin() || $request->user()->isAdmin();

        try {
            foreach ($request->records as $data) {
                $this->rollCallService->recordAttendance(
                    $rollCall->id,
                    $data['student_id'],
                    $data['status'],
                    $data['note'] ?? null,
                    $request->user()->id,
                    $isAdmin // Admins can bypass closed checks
                );
            }

            return redirect()
                ->route('boarding.roll-calls.show', $rollCall->id)
                ->with('success', 'Data absen berhasil disimpan.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Close the roll call session.
     */
    public function close(Request $request, BoardingRollCallSession $rollCall): RedirectResponse
    {
        abort_unless($this->accessService->canManageRollCall($request->user(), $rollCall), 403);

        try {
            $this->rollCallService->closeSession($rollCall->id);
            return redirect()
                ->route('boarding.roll-calls.show', $rollCall->id)
                ->with('success', 'Sesi absen berhasil ditutup dan dikunci.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
