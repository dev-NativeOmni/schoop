<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsAssignment;
use App\Models\LmsAssignmentSubmission;
use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Services\Lms\LmsNotificationService;
use App\Services\Notifications\NotificationDispatchService;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class LmsNotificationServiceTest extends TestCase
{
    private NotificationDispatchService|MockInterface $dispatchService;
    private LmsNotificationService $service;

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

    public function test_notify_assignment_graded_sends_to_student_and_parents(): void
    {
        $studentUser = new User();
        $studentUser->id = 1;

        $parentUser = new User();
        $parentUser->id = 2;

        $parent = new ParentProfile();
        $parent->user = $parentUser;

        $student = new Student();
        $student->full_name = 'John Doe';
        $student->user = $studentUser;
        $student->setRelation('parents', collect([$parent]));

        $assignment = new LmsAssignment();
        $assignment->title = 'Math Homework';
        $assignment->course_id = 10;

        $submission = Mockery::mock(LmsAssignmentSubmission::class)->makePartial();
        $submission->shouldReceive('loadMissing')->once()->with(['student.user', 'student.parents.user', 'assignment']);
        $submission->score = 95;
        $submission->student = $student;
        $submission->assignment = $assignment;

        $this->dispatchService->shouldReceive('sendToUsers')
            ->once()
            ->withArgs(function (Collection $recipients, AnnouncementNotification $notification) use ($studentUser, $parentUser) {
                // Ensure recipients are correct
                $this->assertCount(2, $recipients);
                $this->assertTrue($recipients->contains('id', 1));
                $this->assertTrue($recipients->contains('id', 2));

                // Inspect the notification
                $toArray = $notification->toArray(new \stdClass());
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
        $parentUser = new User();
        $parentUser->id = 2;

        $parent = new ParentProfile();
        $parent->user = $parentUser;

        $student = new Student();
        $student->full_name = 'Jane Doe';
        $student->user = null;
        $student->setRelation('parents', collect([$parent]));

        $assignment = new LmsAssignment();
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
        $sharedUser = new User();
        $sharedUser->id = 1;

        $parent1 = new ParentProfile();
        $parent1->user = $sharedUser;

        $parent2 = new ParentProfile();
        $parent2->user = $sharedUser;

        $student = new Student();
        $student->full_name = 'Duplicate Doe';
        $student->user = $sharedUser;
        $student->setRelation('parents', collect([$parent1, $parent2]));

        $assignment = new LmsAssignment();
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
        $student = new Student();
        $student->full_name = 'No Users Doe';
        $student->user = null;
        $student->setRelation('parents', collect([]));

        $assignment = new LmsAssignment();
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
}
