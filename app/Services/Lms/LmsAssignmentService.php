<?php

namespace App\Services\Lms;

use App\Models\LmsAssignment;
use App\Models\LmsAssignmentSubmission;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class LmsAssignmentService
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
        private readonly LmsProgressService $progressService,
        private readonly LmsActivityLogger $logger,
        private readonly LmsNotificationService $notificationService,
    ) {
        //
    }

    public function createAssignment(array $data): LmsAssignment
    {
        return DB::transaction(function () use ($data) {
            $schoolId = $this->tenantContext->activeSchoolId();
            $data['school_id'] = $schoolId;

            $assignment = LmsAssignment::create($data);
            $this->logger->log('assignment_create', "Assignment '{$assignment->title}' created.", $assignment);

            return $assignment;
        });
    }

    public function updateAssignment(LmsAssignment $assignment, array $data): LmsAssignment
    {
        return DB::transaction(function () use ($assignment, $data) {
            $assignment->update($data);
            $this->logger->log('assignment_update', "Assignment '{$assignment->title}' updated.", $assignment);

            return $assignment;
        });
    }

    public function submitAssignment(int $assignmentId, int $studentId, array $data, ?UploadedFile $file): LmsAssignmentSubmission
    {
        return DB::transaction(function () use ($assignmentId, $studentId, $data, $file) {
            $schoolId = $this->tenantContext->activeSchoolId();
            $assignment = LmsAssignment::findOrFail($assignmentId);

            $submissionData = [
                'school_id' => $schoolId,
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'submitted_text' => $data['submitted_text'] ?? null,
                'status' => 'submitted',
            ];

            if ($file) {
                $this->validateFile($file);

                $filename = time().'_sub_'.preg_replace('/[^a-zA-Z0-9_.-]/', '', $file->getClientOriginalName());
                $path = $file->storeAs("private/lms_submissions/{$schoolId}", $filename, 'local');

                $submissionData['file_path'] = $path;
                $submissionData['file_name'] = $file->getClientOriginalName();
                $submissionData['file_size'] = $file->getSize();
            }

            $submission = LmsAssignmentSubmission::updateOrCreate([
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
            ], $submissionData);

            // Auto-complete the lesson progress
            $this->progressService->markAsComplete($studentId, $assignment->lesson_id);

            $this->logger->log('assignment_submit', "Assignment '{$assignment->title}' submitted.", $submission);

            return $submission;
        });
    }

    public function gradeSubmission(int $submissionId, float $score, ?string $feedback, int $teacherUserId): LmsAssignmentSubmission
    {
        return DB::transaction(function () use ($submissionId, $score, $feedback, $teacherUserId) {
            $submission = LmsAssignmentSubmission::findOrFail($submissionId);

            $submission->update([
                'score' => $score,
                'teacher_feedback' => $feedback,
                'graded_by' => $teacherUserId,
                'graded_at' => now(),
                'status' => 'graded',
            ]);

            $assignment = $submission->assignment;

            // Send internal notification to student
            $this->notificationService->notifyAssignmentGraded($submission);

            $this->logger->log('assignment_grade', "Submission for '{$assignment->title}' graded with score {$score}.", $submission);

            return $submission;
        });
    }

    private function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $blockedExtensions = ['exe', 'bat', 'cmd', 'sh', 'php', 'js', 'zip'];

        if (in_array($extension, $blockedExtensions, true)) {
            throw new \InvalidArgumentException('Format file executable atau terkompresi tidak didukung demi alasan keamanan.');
        }

        // Limit size to 10MB
        $maxSizeBytes = 10 * 1024 * 1024;
        if ($file->getSize() > $maxSizeBytes) {
            throw new \InvalidArgumentException('Ukuran file tidak boleh melebihi 10 MB.');
        }
    }
}
