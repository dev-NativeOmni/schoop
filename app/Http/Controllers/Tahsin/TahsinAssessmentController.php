<?php

namespace App\Http\Controllers\Tahsin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tahsin\StoreTahsinAssessmentRequest;
use App\Models\Student;
use App\Models\TahsinAssessment;
use App\Models\TahsinLevel;
use App\Models\TahsinSkill;
use App\Services\Tahsin\TahsinAccessService;
use App\Services\Tahsin\TahsinAssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahsinAssessmentController extends Controller
{
    public function index(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canViewInternalReport($request->user()), 403);

        $assessments = TahsinAssessment::query()
            ->with(['student.classRoom', 'teacher', 'level'])
            ->orderByDesc('assessment_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('tahsin.assessments.index', compact('assessments'));
    }

    public function create(Request $request, TahsinAccessService $accessService): View
    {
        abort_unless($accessService->canCreateAssessment($request->user()), 403);

        $students = $accessService
            ->applyStudentScope(Student::query()->with('classRoom'), $request->user())
            ->orderBy('full_name')
            ->limit(300)
            ->get();

        $levels = TahsinLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $skills = TahsinSkill::query()
            ->with('level')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tahsin.assessments.create', compact('students', 'levels', 'skills'));
    }

    public function store(
        StoreTahsinAssessmentRequest $request,
        TahsinAccessService $accessService,
        TahsinAssessmentService $assessmentService
    ): RedirectResponse {
        $student = Student::query()->findOrFail($request->validated('student_id'));

        abort_unless($accessService->canViewStudent($request->user(), $student), 403);

        $assessment = $assessmentService->createAssessment(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('tahsin.assessments.show', $assessment)
            ->with('success', 'Asesmen tahsin berhasil disimpan.');
    }

    public function show(
        Request $request,
        TahsinAssessment $assessment,
        TahsinAccessService $accessService
    ): View {
        $assessment->load(['student', 'teacher', 'level', 'items.skill']);

        abort_unless($accessService->canViewStudent($request->user(), $assessment->student), 403);

        return view('tahsin.assessments.show', compact('assessment'));
    }
}
