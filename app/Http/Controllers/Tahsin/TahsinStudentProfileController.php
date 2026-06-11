<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\UpdateTahsinStudentProfileRequest;
use App\Models\Student;
use App\Models\TahsinLevel;
use App\Models\TahsinStudentProfile;
use App\Models\User;
use App\Services\Tahsin\TahsinAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahsinStudentProfileController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(
                Student::query()->with(['classRoom', 'tahsinProfile.currentLevel', 'tahsinProfile.assignedTeacher']),
                $request->user()
            )
            ->orderBy('full_name')
            ->paginate(30);

        return view('tahsin.profiles.index', compact('students'));
    }

    public function show(Request $request, Student $student, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $student->load([
            'classRoom',
            'tahsinProfile.currentLevel',
            'tahsinProfile.assignedTeacher',
            'tahsinAssessments.level',
            'tahsinAssessments.teacher',
            'tahsinAssessments.items.skill',
        ]);

        return view('tahsin.profiles.show', compact('student'));
    }

    public function edit(Request $request, Student $student, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canManageProfile($request->user()), 403);

        $profile = TahsinStudentProfile::query()->firstOrNew([
            'student_id' => $student->id,
        ]);

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $teachers = User::query()
            ->orderBy('name')
            ->get();

        return view('tahsin.profiles.edit', compact('student', 'profile', 'levels', 'teachers'));
    }

    public function update(
        UpdateTahsinStudentProfileRequest $request,
        Student $student
    ): RedirectResponse {
        $data = $request->validated();

        TahsinStudentProfile::query()->updateOrCreate(
            ['student_id' => $student->id],
            [
                'school_id' => $student->school_id ?? null,
                'current_tahsin_level_id' => $data['current_tahsin_level_id'] ?? null,
                'assigned_teacher_id' => $data['assigned_teacher_id'] ?? null,
                'status' => $data['status'],
                'placement_score' => $data['placement_score'] ?? null,
                'started_at' => $data['started_at'] ?? null,
                'completed_at' => $data['completed_at'] ?? null,
                'note' => $data['note'] ?? null,
            ]
        );

        return redirect()
            ->route('tahsin.profiles.show', $student)
            ->with('success', 'Profil tahsin santri berhasil diperbarui.');
    }
}
