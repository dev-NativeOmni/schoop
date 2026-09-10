<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsAssignment;
use App\Models\LmsAssignmentSubmission;
use App\Models\LmsCourse;
use App\Models\LmsQuiz;
use App\Models\LmsQuizAttempt;
use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Services\Lms\LmsNotificationService;
use App\Services\Notifications\NotificationDispatchService;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class LmsNotificationServiceTest extends TestCase
{
    private $dispatchService;

    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatchService = Mockery::mock(NotificationDispatchService::class);
        $this->service = new LmsNotificationService($this->dispatchService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_notify_new_course_sends_notification_to_students_with_users()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        $course = new LmsCourse;
        $course->id = 1;
        $course->title = 'Test Course';

        $user1 = new User;
        $user1->id = 101;

        $student1 = new Student;
        $student1->id = 1;
        $student1->setRelation('user', $user1);

        $student2 = new Student; // no user
        $student2->id = 2;

        $user3 = new User;
        $user3->id = 103;

        $student3 = new Student;
        $student3->id = 3;
        $student3->setRelation('user', $user3);

        $students = [$student1, $student2, $student3];

        $dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function ($recipients, $notification) {
                $this->assertCount(2, $recipients);
                $this->assertTrue($recipients->contains('id', 101));
                $this->assertTrue($recipients->contains('id', 103));

                $this->assertInstanceOf(AnnouncementNotification::class, $notification);

                $toArray = $notification->toArray(new \stdClass);
                $this->assertEquals('Kelas Baru Tersedia', $toArray['title']);
                $this->assertEquals("Kelas baru 'Test Course' telah dipublikasikan. Silakan cek materi pembelajaran Anda.", $toArray['body']);
                $this->assertEquals('info', $toArray['type']);
                $this->assertEquals('/portal/student/lms/course/1', $toArray['action_url']);
                $this->assertEquals('LMS System', $toArray['sender_name']);

                return true;
            });

        $service->notifyNewCourse($course, $students);
    }

    public function test_notify_new_course_filters_duplicate_users()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        $course = new LmsCourse;
        $course->id = 1;
        $course->title = 'Test Course';

        $user1 = new User;
        $user1->id = 101;

        $student1 = new Student;
        $student1->id = 1;
        $student1->setRelation('user', $user1);

        $student2 = new Student;
        $student2->id = 2;
        $student2->setRelation('user', $user1); // Same user

        $students = [$student1, $student2];

        $dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function ($recipients, $notification) {
                $this->assertCount(1, $recipients);
                $this->assertTrue($recipients->contains('id', 101));

                return true;
            });

        $service->notifyNewCourse($course, $students);
    }

    public function test_notify_new_course_handles_empty_students_list()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        $course = new LmsCourse;
        $course->id = 1;
        $course->title = 'Test Course';

        $students = [];

        $dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function ($recipients, $notification) {
                $this->assertCount(0, $recipients);

                return true;
            });

        $service->notifyNewCourse($course, $students);
    }

    public function test_notify_assignment_graded_sends_to_student_and_parents(): void
    {
        $studentUser = new User;
        $studentUser->id = 1;

        $parentUser = new User;
        $parentUser->id = 2;

        $parent = new ParentProfile;
        $parent->user = $parentUser;

        $student = new Student;
        $student->full_name = 'John Doe';
        $student->user = $studentUser;
        $student->setRelation('parents', collect([$parent]));

        $assignment = new LmsAssignment;
        $assignment->title = 'Math Homework';
        $assignment->course_id = 10;

        $submission = Mockery::mock(LmsAssignmentSubmission::class)->makePartial();
        $submission->shouldReceive('loadMissing')->once()->with(['student.user', 'student.parents.user', 'assignment']);
        $submission->score = 95;
        $submission->student = $student;
        $submission->assignment = $assignment;

        $this->dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function (Collection $recipients, AnnouncementNotification $notification) {
                // Ensure recipients are correct
                $this->assertCount(2, $recipients);
                $this->assertTrue($recipients->contains('id', 1));
                $this->assertTrue($recipients->contains('id', 2));

                // Inspect the notification
                $toArray = $notification->toArray(new \stdClass);
                $this->assertEquals('Tugas Selesai Dinilai', $toArray['title']);
                $this->assertEquals("Tugas 'Math Homework' untuk santri John Doe telah dinilai dengan skor 95.00.", $toArray['body']);
                $this->assertEquals('success', $toArray['type']);
                $this->assertEquals('/portal/student/lms/course/10', $toArray['action_url']);
                $this->assertEquals('Sistem Akademik', $toArray['sender_name']);

                return true;
            });

        $this->service->notifyAssignmentGraded($submission);
    }

    public function test_notify_assignment_graded_handles_missing_student_user(): void
    {
        $parentUser = new User;
        $parentUser->id = 2;

        $parent = new ParentProfile;
        $parent->user = $parentUser;

        $student = new Student;
        $student->full_name = 'Jane Doe';
        $student->user = null;
        $student->setRelation('parents', collect([$parent]));

        $assignment = new LmsAssignment;
        $assignment->title = 'Science Project';
        $assignment->course_id = 11;

        $submission = Mockery::mock(LmsAssignmentSubmission::class)->makePartial();
        $submission->shouldReceive('loadMissing')->once();
        $submission->score = 88.5;
        $submission->student = $student;
        $submission->assignment = $assignment;

        $this->dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function (Collection $recipients, AnnouncementNotification $notification) {
                $this->assertCount(1, $recipients);
                $this->assertTrue($recipients->contains('id', 2));

                return true;
            });

        $this->service->notifyAssignmentGraded($submission);
    }

    public function test_notify_assignment_graded_deduplicates_recipients(): void
    {
        $sharedUser = new User;
        $sharedUser->id = 1;

        $parent1 = new ParentProfile;
        $parent1->user = $sharedUser;

        $parent2 = new ParentProfile;
        $parent2->user = $sharedUser;

        $student = new Student;
        $student->full_name = 'Duplicate Doe';
        $student->user = $sharedUser;
        $student->setRelation('parents', collect([$parent1, $parent2]));

        $assignment = new LmsAssignment;
        $assignment->title = 'History Essay';
        $assignment->course_id = 12;

        $submission = Mockery::mock(LmsAssignmentSubmission::class)->makePartial();
        $submission->shouldReceive('loadMissing')->once();
        $submission->score = 70;
        $submission->student = $student;
        $submission->assignment = $assignment;

        $this->dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function (Collection $recipients, AnnouncementNotification $notification) {
                $this->assertCount(1, $recipients);
                $this->assertTrue($recipients->contains('id', 1));

                return true;
            });

        $this->service->notifyAssignmentGraded($submission);
    }

    public function test_notify_assignment_graded_handles_no_recipients_at_all(): void
    {
        $student = new Student;
        $student->full_name = 'No Users Doe';
        $student->user = null;
        $student->setRelation('parents', collect([]));

        $assignment = new LmsAssignment;
        $assignment->title = 'Art Project';
        $assignment->course_id = 13;

        $submission = Mockery::mock(LmsAssignmentSubmission::class)->makePartial();
        $submission->shouldReceive('loadMissing')->once();
        $submission->score = 60;
        $submission->student = $student;
        $submission->assignment = $assignment;

        $this->dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function (Collection $recipients, AnnouncementNotification $notification) {
                $this->assertCount(0, $recipients);

                return true;
            });

        $this->service->notifyAssignmentGraded($submission);
    }

    public function test_notify_quiz_attempt_completed_sends_to_student_and_parents_with_passed_status()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        $studentUser = new User;
        $studentUser->id = 1;

        $parentUser = new User;
        $parentUser->id = 2;

        $parent = new ParentProfile;
        $parent->setRelation('user', $parentUser);

        $student = new Student(['full_name' => 'Budi Santoso']);
        $student->setRelation('user', $studentUser);
        $student->setRelation('parents', collect([$parent]));

        $quiz = new LmsQuiz(['title' => 'Math Final', 'course_id' => 10]);

        $attempt = Mockery::mock(LmsQuizAttempt::class)->makePartial();
        $attempt->shouldReceive('loadMissing')
            ->once()
            ->with(['student.user', 'student.parents.user', 'quiz'])
            ->andReturnSelf();

        $attempt->setRelation('student', $student);
        $attempt->setRelation('quiz', $quiz);
        $attempt->is_passed = true;
        $attempt->score = '85.50';

        $dispatchService->shouldReceive('sendToUsers')->once()->withArgs(function ($recipients, $notification) {
            $this->assertCount(2, $recipients);
            $this->assertTrue($recipients->contains('id', 1));
            $this->assertTrue($recipients->contains('id', 2));

            $this->assertInstanceOf(AnnouncementNotification::class, $notification);

            $array = $notification->toArray(new \stdClass);
            $this->assertEquals('Kuis Selesai Dikerjakan', $array['title']);
            $this->assertEquals("Kuis 'Math Final' untuk santri Budi Santoso telah selesai dikerjakan dengan skor 85.50 (LULUS).", $array['body']);
            $this->assertEquals('info', $array['type']);
            $this->assertEquals('/portal/student/lms/course/10', $array['action_url']);
            $this->assertEquals('Sistem Akademik', $array['sender_name']);

            return true;
        });

        $service->notifyQuizAttemptCompleted($attempt);
    }

    public function test_notify_quiz_attempt_completed_sends_only_to_student_if_no_parents_with_failed_status()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        $studentUser = new User;
        $studentUser->id = 1;

        $student = new Student(['full_name' => 'Andi']);
        $student->setRelation('user', $studentUser);
        $student->setRelation('parents', collect([])); // No parents

        $quiz = new LmsQuiz(['title' => 'Science Quiz', 'course_id' => 5]);

        $attempt = Mockery::mock(LmsQuizAttempt::class)->makePartial();
        $attempt->shouldReceive('loadMissing')->andReturnSelf();

        $attempt->setRelation('student', $student);
        $attempt->setRelation('quiz', $quiz);
        $attempt->is_passed = false;
        $attempt->score = '40.00';

        $dispatchService->shouldReceive('sendToUsers')->once()->withArgs(function ($recipients, $notification) {
            $this->assertCount(1, $recipients);
            $this->assertTrue($recipients->contains('id', 1));

            $this->assertInstanceOf(AnnouncementNotification::class, $notification);

            $array = $notification->toArray(new \stdClass);
            $this->assertEquals("Kuis 'Science Quiz' untuk santri Andi telah selesai dikerjakan dengan skor 40.00 (TIDAK LULUS).", $array['body']);

            return true;
        });

        $service->notifyQuizAttemptCompleted($attempt);
    }

    public function test_notify_quiz_attempt_completed_filters_unique_users()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        // Scenario: duplicate user IDs across student and parents
        $sharedUser = new User;
        $sharedUser->id = 99;

        $parent1 = new ParentProfile;
        $parent1->setRelation('user', $sharedUser);

        $parent2 = new ParentProfile;
        $parent2->setRelation('user', $sharedUser); // Same user ID

        $student = new Student(['full_name' => 'Caca']);
        $student->setRelation('user', $sharedUser); // Also same user ID for testing unique
        $student->setRelation('parents', collect([$parent1, $parent2]));

        $quiz = new LmsQuiz(['title' => 'History Quiz', 'course_id' => 12]);

        $attempt = Mockery::mock(LmsQuizAttempt::class)->makePartial();
        $attempt->shouldReceive('loadMissing')->andReturnSelf();

        $attempt->setRelation('student', $student);
        $attempt->setRelation('quiz', $quiz);
        $attempt->is_passed = true;
        $attempt->score = 100;

        $dispatchService->shouldReceive('sendToUsers')->once()->withArgs(function ($recipients, $notification) {
            // Should be filtered to 1 unique user
            $this->assertCount(1, $recipients);
            $this->assertTrue($recipients->contains('id', 99));

            return true;
        });

        $service->notifyQuizAttemptCompleted($attempt);
    }
}
