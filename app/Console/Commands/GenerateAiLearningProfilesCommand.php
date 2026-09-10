<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Services\Ai\QuranLearningProfileService;
use App\Services\Ai\RuleBasedRecommendationEngine;
use App\Services\Ai\AiAuditLogger;
use App\Models\AiLearningSignal;
use Carbon\Carbon;

class GenerateAiLearningProfilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ai-generate-learning-profiles {--school_id=} {--student_id=} {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI-assisted learning profiles and recommendations for students';

    /**
     * Execute the console command.
     */
    public function handle(
        QuranLearningProfileService $profileService,
        RuleBasedRecommendationEngine $recEngine,
        AiAuditLogger $auditLogger
    ): int {
        $schoolId = $this->option('school_id');
        $studentId = $this->option('student_id');
        $dateStr = $this->option('date');
        $date = $dateStr ? Carbon::parse($dateStr) : Carbon::today();

        $query = Student::query()->withoutGlobalScopes();
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }
        if ($studentId) {
            $query->where('id', $studentId);
        }

        $students = $query->get();

        if ($students->isEmpty()) {
            $this->info('No students found matching the criteria.');
            return 0;
        }

        $this->info("Generating profiles for {$students->count()} students...");

        $profiles = [];
        foreach ($students as $student) {
            // 1. Generate learning profile (also aggregates signals)
            $profiles[$student->id] = $profileService->generateForStudent($student, $date);
        }

        // 2. Pre-fetch all signals generated for these students in a single query
        $allSignals = AiLearningSignal::query()
            ->withoutGlobalScopes()
            ->whereIn('student_id', $students->pluck('id'))
            ->where('signal_date', $date->toDateString())
            ->get()
            ->groupBy('student_id');

        foreach ($students as $student) {
            $profile = $profiles[$student->id];
            $signals = $allSignals->get($student->id, collect());

            // 3. Generate recommendations
            $recEngine->generate($student, $signals, $profile);

            $auditLogger->log(
                'generate_learning_profile_batch',
                null,
                $student,
                get_class($profile),
                $profile->id
            );
        }

        $this->info('AI learning profiles generated successfully.');
        return 0;
    }
}
