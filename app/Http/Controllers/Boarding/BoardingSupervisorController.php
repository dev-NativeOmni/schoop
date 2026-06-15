<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingSupervisorRequest;
use App\Http\Requests\Boarding\UpdateBoardingSupervisorRequest;
use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Models\BoardingSupervisorProfile;
use App\Models\User;
use App\Models\Role;
use App\Services\Boarding\BoardingAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingSupervisorController extends Controller
{
    protected BoardingAccessService $accessService;

    public function __construct(BoardingAccessService $accessService)
    {
        $this->accessService = $accessService;
    }

    public function index(Request $request): View
    {
        abort_unless($this->accessService->canViewMasterData($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $supervisors = BoardingSupervisorProfile::query()
            ->where('school_id', $schoolId)
            ->with(['user', 'dormitory', 'room'])
            ->paginate(15);

        return view('boarding.supervisors.index', compact('supervisors'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $schoolId = $request->user()->school_id;
        
        // Find users with boarding_supervisor role or teacher/admin roles
        $users = User::query()
            ->where('school_id', $schoolId)
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['boarding_supervisor', 'teacher', 'admin']);
            })
            ->whereDoesntHave('boardingSupervisorProfile')
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

        return view('boarding.supervisors.create', compact('users', 'dormitories', 'rooms'));
    }

    public function store(StoreBoardingSupervisorRequest $request): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $targetUser = User::findOrFail($request->user_id);
        abort_unless($targetUser->school_id === $request->user()->school_id, 403);

        BoardingSupervisorProfile::create([
            'user_id' => $request->user_id,
            'school_id' => $request->user()->school_id,
            'boarding_dormitory_id' => $request->boarding_dormitory_id,
            'boarding_room_id' => $request->boarding_room_id,
            'phone' => $request->phone,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('boarding.supervisors.index')
            ->with('success', 'Pembina asrama berhasil ditambahkan.');
    }

    public function show(Request $request, BoardingSupervisorProfile $supervisor): View
    {
        abort_unless($this->accessService->canViewMasterData($request->user()), 403);
        $supervisor->load(['user', 'dormitory', 'room']);
        abort_unless($supervisor->school_id === $request->user()->school_id, 403);

        return view('boarding.supervisors.show', compact('supervisor'));
    }

    public function edit(Request $request, BoardingSupervisorProfile $supervisor): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        $supervisor->load('user');
        abort_unless($supervisor->school_id === $request->user()->school_id, 403);

        $schoolId = $request->user()->school_id;
        
        $users = User::query()
            ->where('school_id', $schoolId)
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['boarding_supervisor', 'teacher', 'admin']);
            })
            ->where(function ($q) use ($supervisor) {
                $q->whereDoesntHave('boardingSupervisorProfile')
                  ->orWhere('id', $supervisor->user_id);
            })
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

        return view('boarding.supervisors.edit', compact('supervisor', 'users', 'dormitories', 'rooms'));
    }

    public function update(UpdateBoardingSupervisorRequest $request, BoardingSupervisorProfile $supervisor): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        abort_unless($supervisor->school_id === $request->user()->school_id, 403);

        $targetUser = User::findOrFail($request->user_id);
        abort_unless($targetUser->school_id === $request->user()->school_id, 403);

        $supervisor->update([
            'user_id' => $request->user_id,
            'boarding_dormitory_id' => $request->boarding_dormitory_id,
            'boarding_room_id' => $request->boarding_room_id,
            'phone' => $request->phone,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('boarding.supervisors.index')
            ->with('success', 'Pembina asrama berhasil diperbarui.');
    }

    public function destroy(Request $request, BoardingSupervisorProfile $supervisor): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        abort_unless($supervisor->school_id === $request->user()->school_id, 403);

        $supervisor->delete();

        return redirect()
            ->route('boarding.supervisors.index')
            ->with('success', 'Pembina asrama berhasil dihapus.');
    }
}
