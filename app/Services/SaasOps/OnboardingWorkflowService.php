<?php

namespace App\Services\SaasOps;

use App\Models\ImplementationProject;
use App\Models\OnboardingChecklistItem;
use App\Models\OnboardingChecklistRecord;
use App\Models\User;
use InvalidArgumentException;

class OnboardingWorkflowService
{
    public function __construct(private readonly SaasOpsNumberGenerator $numbers, private readonly SaasOpsAuditService $audit) {}

    public function createProject(array $data, User $actor): ImplementationProject
    {
        $project = ImplementationProject::query()->create(array_merge($data, [
            'project_number' => $this->numbers->make('IMP'),
            'status' => $data['status'] ?? 'open',
            'stage' => $data['stage'] ?? 'lead',
        ]));

        $this->createChecklistRecords($project);
        $this->audit->log((int) $project->school_id, $actor, 'saas.implementation.created', $project);

        return $project;
    }

    public function createChecklistRecords(ImplementationProject $project): void
    {
        OnboardingChecklistItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->each(function (OnboardingChecklistItem $item) use ($project): void {
                OnboardingChecklistRecord::query()->firstOrCreate(
                    [
                        'implementation_project_id' => $project->id,
                        'onboarding_checklist_item_id' => $item->id,
                    ],
                    [
                        'school_id' => $project->school_id,
                        'status' => 'pending',
                    ]
                );
            });

        $this->refreshProgress($project);
    }

    public function changeStage(ImplementationProject $project, string $stage, User $actor): ImplementationProject
    {
        if ($stage === 'go_live' && ! $this->requiredChecklistComplete($project)) {
            throw new InvalidArgumentException('Go-live ditolak karena checklist required belum selesai.');
        }

        $project->update([
            'stage' => $stage,
            'actual_go_live_date' => $stage === 'go_live' ? now()->toDateString() : $project->actual_go_live_date,
        ]);
        $this->audit->log((int) $project->school_id, $actor, 'saas.implementation.stage_changed', $project, ['stage' => $stage]);

        return $project;
    }

    public function completeRecord(OnboardingChecklistRecord $record, User $actor, ?string $note = null): OnboardingChecklistRecord
    {
        $record->update([
            'status' => 'done',
            'completed_at' => now(),
            'completed_by' => $actor->id,
            'note' => $note,
            'blocked_reason' => null,
        ]);

        $this->refreshProgress($record->project);
        $this->audit->log((int) $record->school_id, $actor, 'saas.onboarding.completed', $record);

        return $record;
    }

    public function refreshProgress(ImplementationProject $project): int
    {
        $total = $project->records()->count();
        $done = $project->records()->where('status', 'done')->count();
        $progress = $total > 0 ? (int) round(($done / $total) * 100) : 0;
        $project->update(['progress_percent' => $progress]);

        return $progress;
    }

    public function requiredChecklistComplete(ImplementationProject $project): bool
    {
        $requiredIds = OnboardingChecklistItem::query()->where('is_required', true)->pluck('id');

        return ! OnboardingChecklistRecord::query()
            ->where('implementation_project_id', $project->id)
            ->whereIn('onboarding_checklist_item_id', $requiredIds)
            ->where('status', '!=', 'done')
            ->exists();
    }
}
