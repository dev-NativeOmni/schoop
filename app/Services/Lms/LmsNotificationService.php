<?php

namespace App\Services\Lms;

use App\Models\LmsAssignmentSubmission;
use App\Models\LmsCourse;
use App\Models\LmsQuizAttempt;
use App\Notifications\AnnouncementNotification;
use App\Services\Notifications\NotificationDispatchService;

class LmsNotificationService
{
    public function __construct(
        private readonly NotificationDispatchService $dispatchService,
    ) {
        //
    }

    public function notifyAssignmentGraded(LmsAssignmentSubmission $submission): void
    {
        $submission->loadMissing(['student.user', 'student.parents.user', 'assignment']);
        $student = $submission->student;
        $assignment = $submission->assignment;

        $recipients = collect();
        if ($student?->user) {
            $recipients->push($student->user);
        }

        foreach ($student->parents ?? [] as $parent) {
            if ($parent->user) {
                $recipients->push($parent->user);
            }
        }

        $recipients = $recipients->filter()->unique('id');

        $notification = new AnnouncementNotification(
            title: 'Tugas Selesai Dinilai',
            body: "Tugas '{$assignment->title}' untuk santri {$student->full_name} telah dinilai dengan skor {$submission->score}.",
            type: 'success',
            actionUrl: "/portal/student/lms/course/{$assignment->course_id}",
            senderName: 'Sistem Akademik'
        );

        $this->dispatchService->sendToUsers($recipients, $notification);
    }

    public function notifyQuizAttemptCompleted(LmsQuizAttempt $attempt): void
    {
        $attempt->loadMissing(['student.user', 'student.parents.user', 'quiz']);
        $student = $attempt->student;
        $quiz = $attempt->quiz;

        $recipients = collect();
        if ($student?->user) {
            $recipients->push($student->user);
        }

        foreach ($student->parents ?? [] as $parent) {
            if ($parent->user) {
                $recipients->push($parent->user);
            }
        }

        $recipients = $recipients->filter()->unique('id');

        $statusText = $attempt->is_passed ? 'LULUS' : 'TIDAK LULUS';
        $notification = new AnnouncementNotification(
            title: 'Kuis Selesai Dikerjakan',
            body: "Kuis '{$quiz->title}' untuk santri {$student->full_name} telah selesai dikerjakan dengan skor {$attempt->score} ({$statusText}).",
            type: 'info',
            actionUrl: "/portal/student/lms/course/{$quiz->course_id}",
            senderName: 'Sistem Akademik'
        );

        $this->dispatchService->sendToUsers($recipients, $notification);
    }

    public function notifyNewCourse(LmsCourse $course, iterable $students): void
    {
        $recipients = collect();
        foreach ($students as $student) {
            if ($student->user) {
                $recipients->push($student->user);
            }
        }

        $recipients = $recipients->filter()->unique('id');

        $notification = new AnnouncementNotification(
            title: 'Kelas Baru Tersedia',
            body: "Kelas baru '{$course->title}' telah dipublikasikan. Silakan cek materi pembelajaran Anda.",
            type: 'info',
            actionUrl: "/portal/student/lms/course/{$course->id}",
            senderName: 'LMS System'
        );

        $this->dispatchService->sendToUsers($recipients, $notification);
    }
}
