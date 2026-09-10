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
        $query = ClassRoom::query()->with(['school', 'homeroomTeacher']);

        if (! auth()->user()->isSuperAdmin()) {
            $query->where('school_id', auth()->user()->school_id);
        }

        $classRooms = $query->latest()->paginate(10);

        return view('master-data.class-rooms.index', compact('classRooms'));
    }

    public function create(): View
    {
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $schoolId = auth()->user()->school_id;

        $schools = $isSuperAdmin
            ? School::query()->where('is_active', true)->orderBy('name')->get()
            : collect([auth()->user()->school]);

        $teachersQuery = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'teacher'));
        if (! $isSuperAdmin) {
            $teachersQuery->where('school_id', $schoolId);
        }
        $teachers = $teachersQuery->orderBy('name')->get();

        return view('master-data.class-rooms.create', [
            'schools' => $schools,
            'teachers' => $teachers,
        ]);
    }

    public function store(StoreClassRoomRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if (! auth()->user()->isSuperAdmin()) {
            $data['school_id'] = auth()->user()->school_id;
        }

        ClassRoom::query()->create($data);

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil dibuat.');
    }

    public function show(ClassRoom $classRoom): View
    {
        if (! auth()->user()->isSuperAdmin() && $classRoom->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data kelas ini.');
        }

        $classRoom->load(['school', 'homeroomTeacher', 'students']);

        return view('master-data.class-rooms.show', compact('classRoom'));
    }

    public function edit(ClassRoom $classRoom): View
    {
        if (! auth()->user()->isSuperAdmin() && $classRoom->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data kelas ini.');
        }

        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $schoolId = auth()->user()->school_id;

        $schools = $isSuperAdmin
            ? School::query()->where('is_active', true)->orderBy('name')->get()
            : collect([auth()->user()->school]);

        $teachersQuery = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'teacher'));
        if (! $isSuperAdmin) {
            $teachersQuery->where('school_id', $schoolId);
        }
        $teachers = $teachersQuery->orderBy('name')->get();

        return view('master-data.class-rooms.edit', [
            'classRoom' => $classRoom,
            'schools' => $schools,
            'teachers' => $teachers,
        ]);
    }

    public function update(UpdateClassRoomRequest $request, ClassRoom $classRoom): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin() && $classRoom->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data kelas ini.');
        }

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if (! auth()->user()->isSuperAdmin()) {
            $data['school_id'] = auth()->user()->school_id;
        }

        $classRoom->update($data);

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(ClassRoom $classRoom): RedirectResponse
    {
        if (! auth()->user()->isSuperAdmin() && $classRoom->school_id !== auth()->user()->school_id) {
            abort(403, 'Anda tidak memiliki akses ke data kelas ini.');
        }

        $classRoom->delete();

        return redirect()
            ->route('master-data.class-rooms.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}
