<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreClassRoomRequest;
use App\Http\Requests\MasterData\UpdateClassRoomRequest;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClassRoomController extends Controller
{
    public function index(): View
    {
        $classRooms = ClassRoom::query()
            ->with(['school', 'homeroomTeacher'])
            ->latest()
            ->paginate(10);

        return view('master-data.class-rooms.index', compact('classRooms'));
    }

    public function create(): View
    {
        return view('master-data.class-rooms.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(StoreClassRoomRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        ClassRoom::query()->create($data);

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil dibuat.');
    }

    public function show(ClassRoom $classRoom): View
    {
        $classRoom->load(['school', 'homeroomTeacher', 'students']);

        return view('master-data.class-rooms.show', compact('classRoom'));
    }

    public function edit(ClassRoom $classRoom): View
    {
        return view('master-data.class-rooms.edit', [
            'classRoom' => $classRoom,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'teachers' => User::query()
                ->whereHas('role', fn ($query) => $query->where('name', 'teacher'))
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(UpdateClassRoomRequest $request, ClassRoom $classRoom): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $classRoom->update($data);

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $classRoom): RedirectResponse
    {
        $classRoom->delete();

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}
