<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\StoreSchoolRequest;
use App\Http\Requests\MasterData\UpdateSchoolRequest;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function index(): View
    {
        $schools = School::query()
            ->latest()
            ->paginate(10);

        return view('master-data.schools.index', compact('schools'));
    }

    public function create(): View
    {
        return view('master-data.schools.create');
    }

    public function store(StoreSchoolRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        School::query()->create($data);

        return redirect()
            ->route('master-data.schools.index')
            ->with('success', 'Data sekolah berhasil dibuat.');
    }

    public function show(School $school): View
    {
        return view('master-data.schools.show', compact('school'));
    }

    public function edit(School $school): View
    {
        return view('master-data.schools.edit', compact('school'));
    }

    public function update(UpdateSchoolRequest $request, School $school): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $school->update($data);

        return redirect()
            ->route('master-data.schools.index')
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(School $school): RedirectResponse
    {
        $school->delete();

        return redirect()
            ->route('master-data.schools.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }
}
