<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsCourse;
use App\Models\Student;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Services\Lms\LmsNotificationService;
use App\Services\Notifications\NotificationDispatchService;
use Mockery;
use Tests\TestCase;

class LmsNotificationServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_notify_new_course_sends_notification_to_students_with_users()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        $course = new LmsCourse();
        $course->id = 1;
        $course->title = 'Test Course';

        $user1 = new User();
        $user1->id = 101;

        $student1 = new Student();
        $student1->id = 1;
        $student1->setRelation('user', $user1);

        $student2 = new Student(); // no user
        $student2->id = 2;

        $user3 = new User();
        $user3->id = 103;

        $student3 = new Student();
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

                $toArray = $notification->toArray(new \stdClass());
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

        $course = new LmsCourse();
        $course->id = 1;
        $course->title = 'Test Course';

        $user1 = new User();
        $user1->id = 101;

        $student1 = new Student();
        $student1->id = 1;
        $student1->setRelation('user', $user1);

        $student2 = new Student();
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

        $course = new LmsCourse();
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
}
