<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiLearningProfile;
use App\Models\Student;
use App\Services\Ai\AiAccessService;
use App\Services\Ai\AiAuditLogger;
use App\Services\Ai\QuranLearningProfileService;
use App\Services\Tenancy\TenantContextService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiLearningProfileController extends Controller
{
    protected QuranLearningProfileService $profileService;

    protected AiAccessService $accessService;

    protected AiAuditLogger $auditLogger;

    protected TenantContextService $tenantContext;

    public function __construct(
        QuranLearningProfileService $profileService,
        AiAccessService $accessService,
        AiAuditLogger $auditLogger,
        TenantContextService $tenantContext
    ) {
        $this->profileService = $profileService;
        $this->accessService = $accessService;
        $this->auditLogger = $auditLogger;
        $this->tenantContext = $tenantContext;
    }

    public function index(Request $request): View
    {
        $schoolId = $this->tenantContext->activeSchoolId();

        $profiles = AiLearningProfile::query()
            ->where('school_id', $schoolId)
            ->with(['student.user'])
            ->latest('profile_date')
            ->paginate(15);

        return view('ai.learning-profiles.index', compact('profiles'));
    }

    public function show(AiLearningProfile $learningProfile): View
    {
        $student = $learningProfile->student;
        if (! $this->accessService->canViewStudentAiData(Auth::user(), $student)) {
            abort(403, 'Unauthorized access to student AI data.');
        }

        $learningProfile->load(['student.user', 'signals', 'recommendations']);

        return view('ai.learning-profiles.show', [
            'profile' => $learningProfile,
        ]);
    }

    public function generate(Request $request, Student $student): RedirectResponse
    {
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $student)) {
            abort(403, 'Unauthorized to generate AI learning profile.');
        }

        $profile = $this->profileService->generateForStudent($student);

        $this->auditLogger->log(
            'generate_learning_profile_manual',
            Auth::user(),
            $student,
            get_class($profile),
            $profile->id
        );

        return redirect()
            ->route('ai-learning.learning-profiles.show', $profile)
            ->with('success', 'Profil pembelajaran AI berhasil dibuat sebagai draf.');
    }

    public function review(AiLearningProfile $profile): RedirectResponse
    {
        $student = $profile->student;
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $student)) {
            abort(403, 'Unauthorized to review AI output.');
        }

        $profile->profile_status = 'teacher_reviewed';
        $profile->reviewed_by = Auth::id();
        $profile->reviewed_at = Carbon::now();
        $profile->save();

        $this->auditLogger->log(
            'review_learning_profile',
            Auth::user(),
            $student,
            get_class($profile),
            $profile->id,
            ['status' => 'draft'],
            ['status' => 'teacher_reviewed']
        );

        return redirect()->back()->with('success', 'Profil pembelajaran telah disetujui oleh guru.');
    }

    public function publish(AiLearningProfile $profile): RedirectResponse
    {
        $student = $profile->student;
        if (! $this->accessService->canReviewAiOutput(Auth::user(), $student)) {
            abort(403, 'Unauthorized to publish AI output.');
        }

        $profile->profile_status = 'published';
        $profile->published_by = Auth::id();
        $profile->published_at = Carbon::now();
        $profile->save();

        $this->auditLogger->log(
            'publish_learning_profile',
            Auth::user(),
            $student,
            get_class($profile),
            $profile->id,
            ['status' => $profile->getOriginal('profile_status')],
            ['status' => 'published']
        );

        return redirect()->back()->with('success', 'Profil pembelajaran telah dipublikasikan ke orang tua dan siswa.');
    }
}
