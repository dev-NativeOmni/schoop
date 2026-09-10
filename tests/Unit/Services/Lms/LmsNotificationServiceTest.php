<?php

namespace Tests\Unit\Services\Lms;

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
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_notify_quiz_attempt_completed_sends_to_student_and_parents_with_passed_status()
    {
        $dispatchService = Mockery::mock(NotificationDispatchService::class);
        $service = new LmsNotificationService($dispatchService);

        $studentUser = new User();
        $studentUser->id = 1;

        $parentUser = new User();
        $parentUser->id = 2;

        $parent = new ParentProfile();
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

            $array = $notification->toArray(new \stdClass());
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

        $studentUser = new User();
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

            $array = $notification->toArray(new \stdClass());
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
        $sharedUser = new User();
        $sharedUser->id = 99;

        $parent1 = new ParentProfile();
        $parent1->setRelation('user', $sharedUser);

        $parent2 = new ParentProfile();
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
