<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingBedRequest;
use App\Http\Requests\Boarding\UpdateBoardingBedRequest;
use App\Models\BoardingBed;
use App\Models\BoardingRoom;
use App\Services\Boarding\BoardingAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingBedController extends Controller
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
        $beds = BoardingBed::query()
            ->whereHas('room.dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['room.dormitory'])
            ->paginate(20);

        return view('boarding.beds.index', compact('beds'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $rooms = BoardingRoom::query()
            ->whereHas('dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with('dormitory')
            ->get();

        return view('boarding.beds.create', compact('rooms'));
    }

    public function store(StoreBoardingBedRequest $request): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $room = BoardingRoom::findOrFail($request->boarding_room_id);
        abort_unless($room->dormitory->school_id === $request->user()->school_id, 403);

        BoardingBed::create([
            'boarding_room_id' => $request->boarding_room_id,
            'code' => $request->code,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('boarding.beds.index')
            ->with('success', 'Ranjang berhasil ditambahkan.');
    }

    public function show(Request $request, BoardingBed $bed): View
    {
        abort_unless($this->accessService->canViewMasterData($request->user()), 403);
        $bed->load(['room.dormitory']);
        abort_unless($bed->room->dormitory->school_id === $request->user()->school_id, 403);

        return view('boarding.beds.show', compact('bed'));
    }

    public function edit(Request $request, BoardingBed $bed): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        $bed->load('room');
        abort_unless($bed->room->dormitory->school_id === $request->user()->school_id, 403);

        $schoolId = $request->user()->school_id;
        $rooms = BoardingRoom::query()
            ->whereHas('dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with('dormitory')
            ->get();

        return view('boarding.beds.edit', compact('bed', 'rooms'));
    }

    public function update(UpdateBoardingBedRequest $request, BoardingBed $bed): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $room = BoardingRoom::findOrFail($request->boarding_room_id);
        abort_unless($room->dormitory->school_id === $request->user()->school_id, 403);
        abort_unless($bed->room->dormitory->school_id === $request->user()->school_id, 403);

        $bed->update([
            'boarding_room_id' => $request->boarding_room_id,
            'code' => $request->code,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('boarding.beds.index')
            ->with('success', 'Ranjang berhasil diperbarui.');
    }

    public function destroy(Request $request, BoardingBed $bed): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        abort_unless($bed->room->dormitory->school_id === $request->user()->school_id, 403);

        $bed->delete();

        return redirect()
            ->route('boarding.beds.index')
            ->with('success', 'Ranjang berhasil dihapus.');
    }
}
