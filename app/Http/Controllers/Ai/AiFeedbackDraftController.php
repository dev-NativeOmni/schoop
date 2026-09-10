<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ai\StoreTeacherFeedbackDraftRequest;
use App\Models\AiFeedbackTemplate;
use App\Models\Student;
use App\Services\Ai\AiAccessService;
use App\Services\Ai\TeacherFeedbackDraftService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiFeedbackDraftController extends Controller
{
    protected TeacherFeedbackDraftService $draftService;

    protected AiAccessService $accessService;

    protected TenantContextService $tenantContext;

    public function __construct(
        TeacherFeedbackDraftService $draftService,
        AiAccessService $accessService,
        TenantContextService $tenantContext
    ) {
        $this->draftService = $draftService;
        $this->accessService = $accessService;
        $this->tenantContext = $tenantContext;
    }

    public function create(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();
        $students = Student::query()->where('school_id', $schoolId)->with('user')->get();

        $templates = AiFeedbackTemplate::query()
            ->withoutGlobalScopes()
            ->where(function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId)->orWhereNull('school_id');
            })
            ->where('is_active', true)
            ->get();

        return view('ai.feedback-drafts.create', compact('students', 'templates'));
    }

    public function store(StoreTeacherFeedbackDraftRequest $request): RedirectResponse
    {
        $studentId = $request->input('student_id');
        $student = Student::findOrFail($studentId);

        if (! $this->accessService->canReviewAiOutput(Auth::user(), $student)) {
            abort(403, 'Unauthorized to create teacher feedback draft.');
        }

        $templateKey = $request->input('template_key');
        $this->draftService->draftFeedback($student, $templateKey);

        return redirect()
            ->route('ai-learning.review-queue.index')
            ->with('success', 'Draf umpan balik guru berhasil dibuat dan ditambahkan ke antrean verifikasi.');
    }
}
