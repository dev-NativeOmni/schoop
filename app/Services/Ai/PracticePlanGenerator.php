<?php

namespace App\Services\Ai;

use App\Models\Student;
use App\Models\AiPracticePlan;
use App\Models\AiPracticePlanItem;
use App\Models\AiLearningRecommendation;
use App\Models\AiAssistanceRequest;
use App\Models\AiAssistanceOutput;
use App\Models\AiTeacherReviewQueue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PracticePlanGenerator
{
    public function generate(Student $student, int $days = 7): AiPracticePlan
    {
        $schoolId = $student->school_id;
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays($days - 1);

        // Fetch active/draft recommendations for this student
        $recommendations = AiLearningRecommendation::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->whereIn('status', ['draft', 'reviewed'])
            ->get();

        // Create Practice Plan
        $plan = AiPracticePlan::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'title' => "Rencana Latihan Belajar Qur'an Mandiri ({$days} Hari)",
                'description' => "Rencana latihan terstruktur yang disesuaikan khusus untuk ananda selama {$days} hari ke depan.",
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'status' => 'draft',
                'generated_by_type' => 'system',
                'created_by' => Auth::id(),
            ]);

        // Generate items for each day
        for ($i = 0; $i < $days; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $itemsCreated = 0;

            foreach ($recommendations as $rec) {
                if ($itemsCreated >= 3) {
                    break;
                }

                $itemType = 'murajaah';
                if ($rec->recommendation_type === 'tahsin_focus') {
                    $itemType = 'tahsin_practice';
                } elseif ($rec->recommendation_type === 'lms_content') {
                    $itemType = 'lms_content';
                } elseif ($rec->recommendation_type === 'parent_support') {
                    $itemType = 'parent_home_support';
                }

                $actions = $rec->recommended_actions ?? [];
                $actionIndex = $i % max(1, count($actions));
                $actionText = $actions[$actionIndex] ?? 'Lakukan tinjauan latihan berkala.';

                AiPracticePlanItem::query()->create([
                    'ai_practice_plan_id' => $plan->id,
                    'practice_date' => $currentDate->toDateString(),
                    'item_type' => $itemType,
                    'title' => $rec->title,
                    'description' => $actionText,
                    'estimated_minutes' => $rec->recommendation_type === 'tahfizh_practice' ? 20 : 10,
                    'linked_content' => $rec->related_content,
                    'completion_status' => 'not_started',
                ]);

                $itemsCreated++;
            }

            // Default fallback item if no recommendations are found
            if ($itemsCreated === 0) {
                AiPracticePlanItem::query()->create([
                    'ai_practice_plan_id' => $plan->id,
                    'practice_date' => $currentDate->toDateString(),
                    'item_type' => 'murajaah',
                    'title' => 'Murajaah Mandiri Harian',
                    'description' => 'Membaca ulang hafalan mandiri minimal 1 halaman sebelum tidur.',
                    'estimated_minutes' => 15,
                    'completion_status' => 'not_started',
                ]);
            }
        }

        // Create Assistance Request record for audit
        $request = AiAssistanceRequest::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $schoolId,
                'requested_by' => Auth::id(),
                'student_id' => $student->id,
                'request_type' => 'generate_practice_plan',
                'status' => 'completed',
                'purpose' => 'Sistem menjadwalkan latihan mandiri santri.',
                'processed_at' => Carbon::now(),
            ]);

        // Create Assistance Output record
        $output = AiAssistanceOutput::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $schoolId,
                'ai_assistance_request_id' => $request->id,
                'student_id' => $student->id,
                'output_type' => 'generate_practice_plan',
                'status' => 'draft',
                'title' => $plan->title,
                'body' => $plan->description,
                'structured_output' => [
                    'practice_plan_id' => $plan->id,
                    'days' => $days,
                ],
            ]);

        // Create Review Queue item
        AiTeacherReviewQueue::query()
            ->withoutGlobalScopes()
            ->create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'ai_assistance_output_id' => $output->id,
                'review_type' => 'practice_plan',
                'status' => 'pending',
            ]);

        return $plan;
    }
}
