<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AiPracticePlanItem;
use App\Services\Ai\AiAuditLogger;
use App\Services\Ai\AiFeatureFlagService;
use App\Services\Ai\StudentLearningAssistantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentAiLearningPortalController extends Controller
{
    protected StudentLearningAssistantService $assistantService;

    protected AiFeatureFlagService $flagService;

    protected AiAuditLogger $auditLogger;

    public function __construct(
        StudentLearningAssistantService $assistantService,
        AiFeatureFlagService $flagService,
        AiAuditLogger $auditLogger
    ) {
        $this->assistantService = $assistantService;
        $this->flagService = $flagService;
        $this->auditLogger = $auditLogger;
    }

    public function index(Request $request): View
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        // Check feature flag
        if (! $this->flagService->isEnabled($schoolId, 'student_learning_assistant')) {
            abort(403, 'Fitur asisten belajar AI dinonaktifkan oleh sekolah.');
        }

        $student = $user->studentProfile;
        if (! $student) {
            abort(404, 'Profil santri tidak ditemukan.');
        }

        $assistantData = $this->assistantService->getAssistanceData($student);

        $this->auditLogger->log(
            'student_view_learning_assistant',
            $user,
            $student
        );

        return view('portal.student.ai-learning', $assistantData);
    }

    public function updateStatus(Request $request, int $itemId)
    {
        $user = Auth::user();
        $student = $user->studentProfile;
        if (! $student) {
            abort(403, 'Unauthorized.');
        }

        // Verify the item belongs to the student
        $item = AiPracticePlanItem::find($itemId);
        if (! $item || ! $item->practicePlan || $item->practicePlan->student_id !== $student->id) {
            abort(403, 'Unauthorized access to practice plan item.');
        }

        $status = $request->input('status');
        $success = $this->assistantService->updateItemStatus($itemId, $status);

        if ($request->wantsJson()) {
            return response()->json(['success' => $success]);
        }

        return redirect()->back()->with('success', 'Status latihan berhasil diperbarui.');
    }
}
