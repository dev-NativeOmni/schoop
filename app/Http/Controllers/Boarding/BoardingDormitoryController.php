<?php

namespace App\Http\Controllers\Boarding;

use App\Http\Controllers\Controller;
use App\Http\Requests\Boarding\StoreBoardingDormitoryRequest;
use App\Http\Requests\Boarding\UpdateBoardingDormitoryRequest;
use App\Models\BoardingDormitory;
use App\Services\Boarding\BoardingAccessService;
use App\Services\Boarding\BoardingOccupancyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BoardingDormitoryController extends Controller
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
        $dormitories = BoardingDormitory::query()
            ->where('school_id', $schoolId)
            ->withCount('rooms')
            ->paginate(15);

        foreach ($dormitories as $dormitory) {
            $dormitory->stats = $this->occupancyService->getDormitoryStats($dormitory);
        }

        return view('boarding.dormitories.index', compact('dormitories'));
    }

    public function create(Request $request): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        return view('boarding.dormitories.create');
    }

    public function store(StoreBoardingDormitoryRequest $request): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);

        BoardingDormitory::create([
            'school_id' => $request->user()->school_id,
            'name' => $request->name,
            'gender' => $request->gender,
            'capacity' => $request->capacity,
            'is_active' => $request->boolean('is_active', true),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('boarding.dormitories.index')
            ->with('success', 'Asrama berhasil ditambahkan.');
    }

    public function show(Request $request, BoardingDormitory $dormitory): View
    {
        abort_unless($this->accessService->canViewMasterData($request->user()), 403);
        abort_unless($dormitory->school_id === $request->user()->school_id, 403);

        $dormitory->load(['rooms' => function ($q) {
            $q->withCount('beds');
        }]);

        $stats = $this->occupancyService->getDormitoryStats($dormitory);

        foreach ($dormitory->rooms as $room) {
            $room->stats = $this->occupancyService->getRoomStats($room);
        }

        return view('boarding.dormitories.show', compact('dormitory', 'stats'));
    }

    public function edit(Request $request, BoardingDormitory $dormitory): View
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        abort_unless($dormitory->school_id === $request->user()->school_id, 403);

        return view('boarding.dormitories.edit', compact('dormitory'));
    }

    public function update(UpdateBoardingDormitoryRequest $request, BoardingDormitory $dormitory): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        abort_unless($dormitory->school_id === $request->user()->school_id, 403);

        $dormitory->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'capacity' => $request->capacity,
            'is_active' => $request->boolean('is_active', true),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('boarding.dormitories.index')
            ->with('success', 'Asrama berhasil diperbarui.');
    }

    public function destroy(Request $request, BoardingDormitory $dormitory): RedirectResponse
    {
        abort_unless($this->accessService->canManageMasterData($request->user()), 403);
        abort_unless($dormitory->school_id === $request->user()->school_id, 403);

        $dormitory->delete();

        return redirect()
            ->route('boarding.dormitories.index')
            ->with('success', 'Asrama berhasil dihapus.');
    }
}
