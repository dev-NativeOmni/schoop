<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsQuiz;
use App\Models\LmsQuizQuestion;
use App\Models\School;
use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsNotificationService;
use App\Services\Lms\LmsProgressService;
use App\Services\Lms\LmsQuizService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class LmsQuizServiceTest extends TestCase
{
    use RefreshDatabase;

    private LmsQuizService $quizService;

    private School $school;

    private LmsQuiz $quiz;

    private $tenantContextMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'SCH001',
            'slug' => 'test-school',
        ]);

        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'TC001',
            'visibility' => 'published',
        ]);

        $module = LmsCourseModule::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'title' => 'Test Module',
        ]);

        $lesson = LmsLesson::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Test Lesson',
            'slug' => 'test-lesson',
            'lesson_type' => 'quiz',
            'visibility' => 'published',
        ]);

        $this->quiz = LmsQuiz::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => 'Test Quiz',
            'passing_score' => 70,
        ]);

        $this->tenantContextMock = Mockery::mock(TenantContextService::class);
        $this->tenantContextMock->shouldReceive('activeSchoolId')->andReturn($this->school->id);

        $progressServiceMock = Mockery::mock(LmsProgressService::class);
        $notificationServiceMock = Mockery::mock(LmsNotificationService::class);
        $activityLoggerMock = Mockery::mock(LmsActivityLogger::class);

        $this->quizService = new LmsQuizService(
            $this->tenantContextMock,
            $progressServiceMock,
            $notificationServiceMock,
            $activityLoggerMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_update_quiz_successfully_updates_and_logs(): void
    {
        $tenantContext = Mockery::mock(TenantContextService::class);
        $progressService = Mockery::mock(LmsProgressService::class);
        $notificationService = Mockery::mock(LmsNotificationService::class);
        $logger = Mockery::mock(LmsActivityLogger::class);

        $service = new LmsQuizService(
            $tenantContext,
            $progressService,
            $notificationService,
            $logger
        );

        $quizMock = Mockery::mock(LmsQuiz::class)->makePartial();

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $updateData = ['title' => 'New Title', 'passing_score' => 80];

        $quizMock->shouldReceive('update')
            ->once()
            ->with($updateData)
            ->andReturn(true);

        $quizMock->title = 'New Title';

        $logger->shouldReceive('log')
            ->once()
            ->with('quiz_update', "Quiz 'New Title' updated.", $quizMock);

        $result = $service->updateQuiz($quizMock, $updateData);

        $this->assertSame($quizMock, $result);
    }

    public function test_add_question_with_explicit_sort_order(): void
    {
        $questionData = [
            'question_text' => 'What is 2 + 2?',
            'question_type' => 'multiple_choice',
            'options' => ['A' => '3', 'B' => '4'],
            'correct_answer' => 'B',
            'score_weight' => 10,
            'sort_order' => 5,
        ];

        $question = $this->quizService->addQuestion($this->quiz->id, $questionData);

        $this->assertInstanceOf(LmsQuizQuestion::class, $question);
        $this->assertEquals($this->school->id, $question->school_id);
        $this->assertEquals($this->quiz->id, $question->quiz_id);
        $this->assertEquals('What is 2 + 2?', $question->question_text);
        $this->assertEquals(5, $question->sort_order);

        $this->assertDatabaseHas('lms_quiz_questions', [
            'id' => $question->id,
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'sort_order' => 5,
        ]);
    }

    public function test_add_question_auto_assigns_sort_order_when_first(): void
    {
        $questionData = [
            'question_text' => 'First question?',
            'question_type' => 'short_answer',
            'correct_answer' => 'yes',
            'score_weight' => 10,
        ];

        $question = $this->quizService->addQuestion($this->quiz->id, $questionData);

        $this->assertInstanceOf(LmsQuizQuestion::class, $question);
        $this->assertEquals(1, $question->sort_order);
    }

    public function test_add_question_auto_assigns_sort_order_based_on_max(): void
    {
        LmsQuizQuestion::create([
            'school_id' => $this->school->id,
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Existing question',
            'question_type' => 'short_answer',
            'correct_answer' => 'ok',
            'sort_order' => 10,
        ]);

        $questionData = [
            'question_text' => 'Next question?',
            'question_type' => 'short_answer',
            'correct_answer' => 'yes',
            'score_weight' => 10,
        ];

        $question = $this->quizService->addQuestion($this->quiz->id, $questionData);

        $this->assertInstanceOf(LmsQuizQuestion::class, $question);
        $this->assertEquals(11, $question->sort_order);
    }
}
