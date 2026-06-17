<?php

namespace App\Services\Ai;

use App\Models\Student;
use App\Models\AiFeedbackTemplate;
use App\Models\AiLearningProfile;
use App\Models\AiAssistanceRequest;
use App\Models\AiAssistanceOutput;
use App\Models\AiTeacherReviewQueue;
use App\Services\Ai\AiSafetyGuardService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeacherFeedbackDraftService
{
    protected AiSafetyGuardService $safetyGuard;

    public function __construct(AiSafetyGuardService $safetyGuard)
    {
        $this->safetyGuard = $safetyGuard;
    }

    public function draftFeedback(Student $student, string $templateKey): string
    {
        $schoolId = $student->school_id;

        // Fetch latest learning profile to get context
        $profile = AiLearningProfile::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->latest('profile_date')
            ->first();

        // Fetch template
        $template = AiFeedbackTemplate::query()
            ->withoutGlobalScopes()
            ->where(function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId)->orWhereNull('school_id');
            })
            ->where('template_key', $templateKey)
            ->where('is_active', true)
            ->first();

        $bodyTemplate = $template ? $template->body_template : "Ananda {student_name} telah menunjukkan usaha belajar yang baik. Pekan ini mari fokus pada latihan makhraj dan murajaah harian.";

        // Resolve placeholders
        $studentName = $student->user?->name ?? 'Ananda';
        $tahfizhTrend = $profile?->tahfizh_trend ?? 'perlu konsistensi';
        $tahsinTrend = $profile?->tahsin_trend ?? 'perlu penguatan';
        $focusArea = $profile && !empty($profile->focus_areas) ? $profile->focus_areas[0] : 'murajaah mandiri';

        $draftText = strtr($bodyTemplate, [
            '{student_name}' => $studentName,
            '{tahfizh_trend}' => $tahfizhTrend,
            '{tahsin_trend}' => $tahsinTrend,
            '{focus_area}' => $focusArea,
        ]);

        // Run safety checks
        $safetyResult = $this->safetyGuard->validateOutput($draftText);
        if (!$safetyResult['safe']) {
            // Log safety event inside guard and redact negative words
            $draftText = $this->safetyGuard->redactNegativeWording($draftText, $student);
        }

        // Create Assistance Request record
        $request = AiAssistanceRequest::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $schoolId,
                'requested_by' => Auth::id(),
                'student_id' => $student->id,
                'request_type' => 'draft_teacher_feedback',
                'status' => 'completed',
                'purpose' => "Membuat draf umpan balik guru menggunakan template {$templateKey}.",
                'processed_at' => Carbon::now(),
            ]);

        // Create Assistance Output record
        $output = AiAssistanceOutput::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $schoolId,
                'ai_assistance_request_id' => $request->id,
                'student_id' => $student->id,
                'output_type' => 'draft_teacher_feedback',
                'status' => 'draft',
                'title' => "Draf Catatan Umpan Balik Guru - " . $studentName,
                'body' => $draftText,
                'structured_output' => [
                    'template_key' => $templateKey,
                    'student_name' => $studentName,
                ],
            ]);

        // Create Review Queue item
        AiTeacherReviewQueue::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'ai_assistance_output_id' => $output->id,
                'review_type' => 'teacher_feedback',
                'status' => 'pending',
                'assigned_to' => Auth::id(),
            ]);

        return $draftText;
    }
}
