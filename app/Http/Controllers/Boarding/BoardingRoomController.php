<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingRoomRequest;
use App\Http\Requests\Boarding\UpdateBoardingRoomRequest;
use App\Models\BoardingDormitory;
use App\Models\BoardingRoom;
use App\Services\Boarding\BoardingAccessService;
use App\Services\Boarding\BoardingOccupancyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingRoomController extends Controller
{
    protected BoardingAccessService $accessService;

    protected BoardingOccupancyService $occupancyService;

    public function __construct(
        BoardingAccessService $accessService,
        BoardingOccupancyService $occupancyService
    ) {
        $this->accessService = $accessService;
        $this->occupancyService = $occupancyService;
    }

    public function index(Request $request): View
    {
        abort_unless($this->accessService->canViewMasterData($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $rooms = BoardingRoom::query()
            ->whereHas('dormitory', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['dormitory'])
            ->withCount('beds')
            ->paginate(15);

        foreach ($rooms as $room) {
            $room->stats = $this->occupancyService->getRoomStats($room);
        }

        return view('boarding.rooms.index', compact('rooms'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $schoolId = $request->user()->school_id;
        $dormitories = BoardingDormitory::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        return view('boarding.rooms.create', compact('dormitories'));
    }

    public function store(StoreBoardingRoomRequest $request): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $dormitory = BoardingDormitory::findOrFail($request->boarding_dormitory_id);
        abort_unless($dormitory->school_id === $request->user()->school_id, 403);

        BoardingRoom::create([
            'boarding_dormitory_id' => $request->boarding_dormitory_id,
            'name' => $request->name,
            'floor' => $request->floor,
            'capacity' => $request->capacity,
            'is_active' => $request->boolean('is_active', true),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('boarding.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(Request $request, BoardingRoom $room): View
    {
        abort_unless($this->accessService->canViewMasterData($request->user()), 403);
        $room->load(['dormitory', 'beds']);
        abort_unless($room->dormitory->school_id === $request->user()->school_id, 403);

        $stats = $this->occupancyService->getRoomStats($room);

        return view('boarding.rooms.show', compact('room', 'stats'));
    }

    public function edit(Request $request, BoardingRoom $room): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        $room->load('dormitory');
        abort_unless($room->dormitory->school_id === $request->user()->school_id, 403);

        $schoolId = $request->user()->school_id;
        $dormitories = BoardingDormitory::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get();

        return view('boarding.rooms.edit', compact('room', 'dormitories'));
    }

    public function update(UpdateBoardingRoomRequest $request, BoardingRoom $room): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        $dormitory = BoardingDormitory::findOrFail($request->boarding_dormitory_id);
        abort_unless($dormitory->school_id === $request->user()->school_id, 403);
        abort_unless($room->dormitory->school_id === $request->user()->school_id, 403);

        $room->update([
            'boarding_dormitory_id' => $request->boarding_dormitory_id,
            'name' => $request->name,
            'floor' => $request->floor,
            'capacity' => $request->capacity,
            'is_active' => $request->boolean('is_active', true),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('boarding.rooms.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Request $request, BoardingRoom $room): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        abort_unless($room->dormitory->school_id === $request->user()->school_id, 403);

        $room->delete();

        return redirect()
            ->route('boarding.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }
}
