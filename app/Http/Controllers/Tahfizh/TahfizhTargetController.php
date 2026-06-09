<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahfizh\StoreTahfizhTargetRequest;
use App\Http\Requests\Tahfizh\UpdateTahfizhTargetRequest;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Student;
use App\Models\TahfizhTarget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahfizhTargetController extends Controller
{
    public function index(Request $request): View
    {
        $targets = TahfizhTarget::query()
            ->with(['school', 'classRoom', 'student', 'creator'])
            ->when($request->filled('school_id'), function ($query) use ($request): void {
                $query->where('school_id', $request->integer('school_id'));
            })
            ->when($request->filled('class_room_id'), function ($query) use ($request): void {
                $query->where('class_room_id', $request->integer('class_room_id'));
            })
            ->when($request->filled('student_id'), function ($query) use ($request): void {
                $query->where('student_id', $request->integer('student_id'));
            })
            ->when($request->filled('program_type'), function ($query) use ($request): void {
                $query->where('program_type', $request->input('program_type'));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('tahfizh.targets.index', [
            'targets' => $targets,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('tahfizh.targets.create', [
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
        ]);
    }

    public function store(StoreTahfizhTargetRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['created_by'] = $request->user()->id;
        $data['is_active'] = $request->boolean('is_active');

        TahfizhTarget::query()->create($data);

        return redirect()
            ->route('tahfizh.targets.index')
            ->with('success', 'Target tahfizh berhasil dibuat.');
    }

    public function show(TahfizhTarget $target): View
    {
        $target->load(['school', 'classRoom', 'student', 'creator']);

        return view('tahfizh.targets.show', compact('target'));
    }

    public function edit(TahfizhTarget $target): View
    {
        return view('tahfizh.targets.edit', [
            'target' => $target,
            'schools' => School::query()->where('is_active', true)->orderBy('name')->get(),
            'classRooms' => ClassRoom::query()->where('is_active', true)->orderBy('name')->get(),
            'students' => Student::query()->where('is_active', true)->orderBy('full_name')->get(),
        ]);
    }

    public function update(UpdateTahfizhTargetRequest $request, TahfizhTarget $target): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $target->update($data);

        return redirect()
            ->route('tahfizh.targets.index')
            ->with('success', 'Target tahfizh berhasil diperbarui.');
    }

    public function destroy(TahfizhTarget $target): RedirectResponse
    {
        $target->delete();

        return redirect()
            ->route('tahfizh.targets.index')
            ->with('success', 'Target tahfizh berhasil dihapus.');
    }
}
