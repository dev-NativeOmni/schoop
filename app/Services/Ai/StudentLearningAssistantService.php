<?php

namespace App\Services\Ai;

use App\Models\AiLearningRecommendation;
use App\Models\AiPracticePlan;
use App\Models\AiPracticePlanItem;
use App\Models\Student;
use Carbon\Carbon;

class StudentLearningAssistantService
{
    public function getAssistanceData(Student $student): array
    {
        $todayStr = Carbon::today()->toDateString();

        // Fetch published practice plans for this student
        $activePlans = AiPracticePlan::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->whereIn('status', ['published', 'completed'])
            ->with(['items'])
            ->get();

        // Gather all items for today from active plans
        $todayItems = collect();
        foreach ($activePlans as $plan) {
            $planTodayItems = $plan->items->where('practice_date', $todayStr);
            foreach ($planTodayItems as $item) {
                $todayItems->push([
                    'id' => $item->id,
                    'plan_id' => $plan->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'item_type' => $item->item_type,
                    'estimated_minutes' => $item->estimated_minutes,
                    'linked_content' => $item->linked_content,
                    'completion_status' => $item->completion_status,
                ]);
            }
        }

        // Fetch published learning recommendations
        $recommendations = AiLearningRecommendation::query()
            ->withoutGlobalScopes()
            ->where('student_id', $student->id)
            ->where('status', 'published')
            ->get();

        // Determine general positive encouragement
        $encouragement = "Semangat belajar Qur'an hari ini! Awali setiap bacaan dengan niat yang ikhlas.";
        if ($recommendations->contains('recommendation_type', 'tahfizh_practice')) {
            $encouragement = 'Ayo jaga murajaah dan setoran hafalan ananda agar semakin kuat dan melekat di hati.';
        }

        return [
            'student_name' => $student->user?->name ?? 'Teman Hafiz',
            'today_date' => Carbon::today()->translatedFormat('l, d F Y'),
            'today_items' => $todayItems,
            'active_plans' => $activePlans,
            'encouragement' => $encouragement,
        ];
    }

    public function updateItemStatus(int $itemId, string $status): bool
    {
        $item = AiPracticePlanItem::find($itemId);
        if (! $item) {
            return false;
        }

        if (! in_array($status, ['not_started', 'in_progress', 'done', 'skipped'])) {
            return false;
        }

        $item->completion_status = $status;
        if ($status === 'done') {
            $item->completed_at = Carbon::now();
        } else {
            $item->completed_at = null;
        }

        $item->save();

        // Recalculate plan completion if needed
        $plan = $item->practicePlan;
        if ($plan) {
            $totalItems = $plan->items()->count();
            $doneItems = $plan->items()->where('completion_status', 'done')->count();
            if ($totalItems > 0 && $doneItems === $totalItems) {
                $plan->status = 'completed';
                $plan->save();
            } elseif ($plan->status === 'completed' && $doneItems < $totalItems) {
                $plan->status = 'published';
                $plan->save();
            }
        }

        return true;
    }
}
