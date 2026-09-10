<?php

namespace App\Services\Ai;

use App\Models\AiAssistanceOutput;
use App\Models\AiLearningProfile;
use App\Models\AiLearningRecommendation;
use App\Models\AiPracticePlan;
use App\Models\AiTeacherReviewQueue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AiTeacherReviewService
{
    protected AiAuditLogger $auditLogger;

    public function __construct(AiAuditLogger $auditLogger)
    {
        $this->auditLogger = $auditLogger;
    }

    public function approve(AiTeacherReviewQueue $reviewItem, ?string $note = null): bool
    {
        if ($reviewItem->status !== 'pending') {
            return false;
        }

        $reviewItem->status = 'approved';
        $reviewItem->reviewed_by = Auth::id();
        $reviewItem->reviewed_at = Carbon::now();
        $reviewItem->review_note = $note;
        $reviewItem->save();

        // Update corresponding source output
        $output = $reviewItem->output;
        if ($output) {
            $output->status = 'reviewed';
            $output->reviewed_by = Auth::id();
            $output->reviewed_at = Carbon::now();
            $output->save();

            // Link to the real entity
            $this->updateLinkedEntityStatus($output, 'teacher_reviewed');
        }

        $this->auditLogger->log(
            'approve_review_queue',
            Auth::user(),
            $reviewItem->student,
            get_class($reviewItem),
            $reviewItem->id,
            ['status' => 'pending'],
            ['status' => 'approved']
        );

        return true;
    }

    public function reject(AiTeacherReviewQueue $reviewItem, ?string $note = null): bool
    {
        if ($reviewItem->status !== 'pending') {
            return false;
        }

        $reviewItem->status = 'rejected';
        $reviewItem->reviewed_by = Auth::id();
        $reviewItem->reviewed_at = Carbon::now();
        $reviewItem->review_note = $note;
        $reviewItem->save();

        $output = $reviewItem->output;
        if ($output) {
            $output->status = 'dismissed';
            $output->save();

            $this->updateLinkedEntityStatus($output, 'archived');
        }

        $this->auditLogger->log(
            'reject_review_queue',
            Auth::user(),
            $reviewItem->student,
            get_class($reviewItem),
            $reviewItem->id,
            ['status' => 'pending'],
            ['status' => 'rejected']
        );

        return true;
    }

    public function publish(AiTeacherReviewQueue $reviewItem): bool
    {
        // Approve first if pending
        if ($reviewItem->status === 'pending') {
            $this->approve($reviewItem);
        }

        if ($reviewItem->status !== 'approved' && $reviewItem->status !== 'edited') {
            return false;
        }

        $reviewItem->status = 'published';
        $reviewItem->save();

        $output = $reviewItem->output;
        if ($output) {
            $output->status = 'published';
            $output->save();

            $this->updateLinkedEntityStatus($output, 'published');
        }

        $this->auditLogger->log(
            'publish_review_queue',
            Auth::user(),
            $reviewItem->student,
            get_class($reviewItem),
            $reviewItem->id,
            ['status' => $reviewItem->status],
            ['status' => 'published']
        );

        return true;
    }

    protected function updateLinkedEntityStatus(AiAssistanceOutput $output, string $newStatus): void
    {
        $type = $output->output_type;
        $structured = $output->structured_output ?? [];

        if ($type === 'generate_practice_plan' && isset($structured['practice_plan_id'])) {
            $plan = AiPracticePlan::find($structured['practice_plan_id']);
            if ($plan) {
                $plan->status = $newStatus;
                if ($newStatus === 'published') {
                    $plan->published_at = Carbon::now();
                } elseif ($newStatus === 'teacher_reviewed') {
                    $plan->reviewed_at = Carbon::now();
                    $plan->reviewed_by = Auth::id();
                }
                $plan->save();
            }
        } elseif ($type === 'generate_learning_profile') {
            $profile = AiLearningProfile::query()
                ->withoutGlobalScopes()
                ->where('student_id', $output->student_id)
                ->latest('profile_date')
                ->first();
            if ($profile) {
                $profile->profile_status = $newStatus;
                if ($newStatus === 'published') {
                    $profile->published_at = Carbon::now();
                    $profile->published_by = Auth::id();
                } elseif ($newStatus === 'teacher_reviewed') {
                    $profile->reviewed_at = Carbon::now();
                    $profile->reviewed_by = Auth::id();
                }
                $profile->save();
            }
        } elseif ($type === 'generate_recommendation') {
            $recs = AiLearningRecommendation::query()
                ->withoutGlobalScopes()
                ->where('student_id', $output->student_id)
                ->where('status', 'draft')
                ->get();
            foreach ($recs as $rec) {
                $rec->status = $newStatus === 'teacher_reviewed' ? 'reviewed' : $newStatus;
                if ($newStatus === 'published') {
                    $rec->published_at = Carbon::now();
                } elseif ($newStatus === 'teacher_reviewed') {
                    $rec->reviewed_at = Carbon::now();
                    $rec->reviewed_by = Auth::id();
                }
                $rec->save();
            }
        }
    }
}
