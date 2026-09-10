<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\GeneratePracticePlanRequest;
use App\Models\AiPracticePlan;
use App\Models\Student;
use App\Services\Ai\AiAccessService;
use App\Services\Ai\AiAuditLogger;
use App\Services\Ai\PracticePlanGenerator;
use App\Services\Tenancy\TenantContextService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiPracticePlanController extends Controller
{
    protected PracticePlanGenerator $planGenerator;

    protected AiAccessService $accessService;

    protected AiAuditLogger $auditLogger;

    protected TenantContextService $tenantContext;

    public function __construct(
        PracticePlanGenerator $planGenerator,
        AiAccessService $accessService,
        AiAuditLogger $auditLogger,
        TenantContextService $tenantContext
    ) {
        $this->planGenerator = $planGenerator;
        $this->accessService = $accessService;
        $this->auditLogger = $auditLogger;
        $this->tenantContext = $tenantContext;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        $practicePlans = AiPracticePlan::query()
            ->where('school_id', $schoolId)
            ->with(['student.user'])
            ->latest()
            ->paginate(15);

        return view('ai.practice-plans.index', compact('practicePlans'));
    }

    public function show(AiPracticePlan $practicePlan): View
    {
        if (! $this->accessService->canViewStudentAiData(Auth::user(), $practicePlan->student)) {
            abort(403, 'Unauthorized access to practice plan.');
        }

        $practicePlan->load(['student.user', 'items']);

        return view('ai.practice-plans.show', compact('practicePlan'));
    }

    public function create(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        $students = Student::query()->where('school_id', $schoolId)->with('user')->get();

        return view('ai.practice-plans.create', compact('students'));
    }

    public function store(GeneratePracticePlanRequest $request): RedirectResponse
    {
        $studentId = $request->input('student_id');
        $student = Student::findOrFail($studentId);

        if (! $this->accessService->canReviewAiOutput(Auth::user(), $student)) {
            abort(403, 'Unauthorized to generate practice plans.');
        }

        $days = (int) $request->input('days', 7);
        $plan = $this->planGenerator->generate($student, $days);

        $this->auditLogger->log(
            'generate_practice_plan_manual',
            Auth::user(),
            $student,
            get_class($plan),
            $plan->id
        );

        return redirect()
            ->route('ai-learning.practice-plans.show', $plan)
            ->with('success', 'Rencana latihan mandiri berhasil dibuat sebagai draf.');
    }

    public function publish(AiPracticePlan $practicePlan): RedirectResponse
    {
        $student = $practicePlan->student;
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $student)) {
            abort(403, 'Unauthorized to publish practice plan.');
        }

        $practicePlan->status = 'published';
        $practicePlan->published_at = Carbon::now();
        $practicePlan->save();

        $this->auditLogger->log(
            'publish_practice_plan',
            Auth::user(),
            $student,
            get_class($practicePlan),
            $practicePlan->id,
            ['status' => $practicePlan->getOriginal('status')],
            ['status' => 'published']
        );

        return redirect()->back()->with('success', 'Rencana latihan mandiri berhasil dipublikasikan.');
    }
}
