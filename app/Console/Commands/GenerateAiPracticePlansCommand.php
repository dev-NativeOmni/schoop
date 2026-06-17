<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Services\Ai\PracticePlanGenerator;
use App\Services\Ai\AiAuditLogger;

class GenerateAiPracticePlansCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ai-generate-practice-plans {--school_id=} {--student_id=} {--days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI-assisted practice plans for students';

    /**
     * Execute the console command.
     */
    public function handle(
        PracticePlanGenerator $planGenerator,
        AiAuditLogger $auditLogger
    ): int {
        $schoolId = $this->option('school_id');
        $studentId = $this->option('student_id');
        $days = (int) ($this->option('days') ?? 7);

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

        $this->info("Generating practice plans for {$students->count()} students...");

        foreach ($students as $student) {
            $plan = $planGenerator->generate($student, $days);

            $auditLogger->log(
                'generate_practice_plan_batch',
                null,
                $student,
                get_class($plan),
                $plan->id
            );
        }

        $this->info('AI practice plans generated successfully.');
        return 0;
    }
}
