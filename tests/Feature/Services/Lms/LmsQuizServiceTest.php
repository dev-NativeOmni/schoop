<?php

namespace Tests\Feature\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsQuiz;
use App\Models\LmsQuizAttempt;
use App\Models\LmsQuizQuestion;
use App\Models\School;
use App\Models\Student;
use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsNotificationService;
use App\Services\Lms\LmsProgressService;
use App\Services\Lms\LmsQuizService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class LmsQuizServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LmsQuizService $quizService;
    protected $progressServiceMock;
    protected $notificationServiceMock;
    protected $loggerMock;
    protected $tenantContextMock;

    protected School $school;
    protected Student $student;
    protected LmsCourse $course;
    protected LmsCourseModule $module;
    protected LmsLesson $lesson;
    protected LmsQuiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        // Create base models needed for the tests
        $this->school = School::create([
            'code' => 'SCH001',
            'name' => 'Test School',
            'subdomain' => 'test-school',
            'database_name' => 'test',
        ]);

        $this->student = Student::create([
            'school_id' => $this->school->id,
            'student_number' => 'STU001',
            'full_name' => 'Test Student',
            'is_active' => true,
        ]);

        $this->course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'TC01',
            'visibility' => 'published',
        ]);

        $this->module = LmsCourseModule::create([
            'school_id' => $this->school->id,
            'course_id' => $this->course->id,
            'title' => 'Module 1',
        ]);

        $this->lesson = LmsLesson::create([
            'school_id' => $this->school->id,
            'course_id' => $this->course->id,
            'module_id' => $this->module->id,
            'title' => 'Quiz Lesson',
            'slug' => 'quiz-lesson',
            'lesson_type' => 'quiz',
            'visibility' => 'published',
        ]);

        $this->quiz = LmsQuiz::create([
            'school_id' => $this->school->id,
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title' => 'Test Quiz',
            'passing_score' => 70,
        ]);

        // Mock dependencies
        $this->tenantContextMock = Mockery::mock(TenantContextService::class);
        $this->tenantContextMock->shouldReceive('activeSchoolId')->andReturn($this->school->id);

        $this->progressServiceMock = Mockery::mock(LmsProgressService::class);
        $this->notificationServiceMock = Mockery::mock(LmsNotificationService::class);
        $this->loggerMock = Mockery::mock(LmsActivityLogger::class);

        // Bind mock to service container
        $this->app->instance(TenantContextService::class, $this->tenantContextMock);
        $this->app->instance(LmsProgressService::class, $this->progressServiceMock);
        $this->app->instance(LmsNotificationService::class, $this->notificationServiceMock);
        $this->app->instance(LmsActivityLogger::class, $this->loggerMock);

        // Instantiate the service
        $this->quizService = app(LmsQuizService::class);
    }

    public function test_submit_attempt_calculates_scores_correctly(): void
    {
        // Add a short answer question
        $shortAnswerQuestion = LmsQuizQuestion::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'question_text' => 'What is 2+2?',
            'question_type' => 'short_answer',
            'correct_answer' => 'Four',
            'score_weight' => 10,
        ]);

        // Add a multiple choice question
        $multipleChoiceQuestion = LmsQuizQuestion::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Capital of France?',
            'question_type' => 'multiple_choice',
            'options' => ['A' => 'Paris', 'B' => 'London'],
            'correct_answer' => 'A',
            'score_weight' => 20,
        ]);

        // Create an attempt
        $attempt = LmsQuizAttempt::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'started_at' => now(),
            'status' => 'started',
        ]);

        // Mock expectations for the service calls made during submitAttempt
        $this->progressServiceMock->shouldReceive('markAsComplete')
            ->once()
            ->with($this->student->id, $this->lesson->id);

        $this->notificationServiceMock->shouldReceive('notifyQuizAttemptCompleted')
            ->once();

        $this->loggerMock->shouldReceive('log')
            ->once();

        // Submit answers
        // short answer: "  four " (case insensitive, trimmed matches "Four") => 10/10
        // multiple choice: "B" (exact match needed, actual is "A") => 0/20
        $submittedAnswers = [
            $shortAnswerQuestion->id => '  four ',
            $multipleChoiceQuestion->id => 'B',
        ];

        $completedAttempt = $this->quizService->submitAttempt($attempt->id, $submittedAnswers);

        // Assertions
        $this->assertEquals('completed', $completedAttempt->status);
        $this->assertNotNull($completedAttempt->completed_at);

        // Total weight = 30, earned = 10, score = (10/30) * 100 = 33.33
        $this->assertEquals(33.33, $completedAttempt->score);

        // Passing score is 70
        $this->assertFalse($completedAttempt->is_passed);

        // Verify individual answers recorded in database
        $this->assertDatabaseHas('lms_quiz_answers', [
            'quiz_attempt_id' => $completedAttempt->id,
            'quiz_question_id' => $shortAnswerQuestion->id,
            'is_correct' => true,
            'score_obtained' => 10,
        ]);

        $this->assertDatabaseHas('lms_quiz_answers', [
            'quiz_attempt_id' => $completedAttempt->id,
            'quiz_question_id' => $multipleChoiceQuestion->id,
            'is_correct' => false,
            'score_obtained' => 0,
        ]);
    }

    public function test_submit_attempt_throws_exception_if_not_started(): void
    {
        $attempt = LmsQuizAttempt::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'started_at' => now()->subMinutes(30),
            'completed_at' => now(),
            'status' => 'completed',
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Percobaan kuis ini sudah selesai atau tidak valid.');

        $this->quizService->submitAttempt($attempt->id, []);
    }

    public function test_submit_attempt_handles_zero_weight(): void
    {
        // Add a question with 0 weight
        LmsQuizQuestion::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Zero weight question?',
            'question_type' => 'short_answer',
            'correct_answer' => 'Yes',
            'score_weight' => 0,
        ]);

        $attempt = LmsQuizAttempt::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'started_at' => now(),
            'status' => 'started',
        ]);

        $this->progressServiceMock->shouldReceive('markAsComplete')->once();
        $this->notificationServiceMock->shouldReceive('notifyQuizAttemptCompleted')->once();
        $this->loggerMock->shouldReceive('log')->once();

        $completedAttempt = $this->quizService->submitAttempt($attempt->id, []);

        $this->assertEquals(0, $completedAttempt->score);
        $this->assertEquals('completed', $completedAttempt->status);
        // Passing score is 70, so 0 should fail
        $this->assertFalse($completedAttempt->is_passed);
    }

    public function test_submit_attempt_calls_services(): void
    {
        $attempt = LmsQuizAttempt::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
            'attempt_number' => 1,
            'started_at' => now(),
            'status' => 'started',
        ]);

        $this->progressServiceMock->shouldReceive('markAsComplete')
            ->once()
            ->with($this->student->id, $this->lesson->id);

        $this->notificationServiceMock->shouldReceive('notifyQuizAttemptCompleted')
            ->once()
            ->with(Mockery::on(function ($arg) use ($attempt) {
                return $arg->id === $attempt->id;
            }));

        $this->loggerMock->shouldReceive('log')
            ->once()
            ->with(
                'quiz_attempt_submit',
                Mockery::type('string'),
                Mockery::on(function ($arg) use ($attempt) {
                    return $arg->id === $attempt->id;
                })
            );

        $this->quizService->submitAttempt($attempt->id, []);
    }
}
