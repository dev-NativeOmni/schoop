<?php

namespace App\Http\Controllers\SchoolOs;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolOs\StoreAcademicYearRequest;
use App\Http\Requests\SchoolOs\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use App\Services\SchoolOs\SchoolOsAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(Request $request, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        $academicYears = AcademicYear::query()
            ->with('terms')
            ->orderByDesc('start_date')
            ->paginate(20);

        return view('schoolos.academic-years.index', compact('academicYears'));
    }

    public function create(Request $request, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        return view('schoolos.academic-years.create');
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = $request->validated();

            if ($request->boolean('is_active')) {
                AcademicYear::query()->update(['is_active' => false]);
            }

            AcademicYear::query()->create([
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('schoolos.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dibuat.');
    }

    public function edit(Request $request, AcademicYear $academicYear, SchoolOsAccessService $accessService): View
    {
        abort_unless($accessService->canManageSettings($request->user()), 403);

        return view('schoolos.academic-years.edit', compact('academicYear'));
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        DB::transaction(function () use ($request, $academicYear): void {
            $data = $request->validated();

            if ($request->boolean('is_active')) {
                AcademicYear::query()
                    ->whereKeyNot($academicYear->id)
                    ->update(['is_active' => false]);
            }

            $academicYear->update([
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $request->boolean('is_active'),
            ]);
        });

        return redirect()
            ->route('schoolos.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }
}
