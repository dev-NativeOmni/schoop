<?php

namespace Tests\Feature\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsQuiz;
use App\Models\School;
use App\Models\User;
use App\Services\Lms\LmsQuizService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsQuizServiceTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private LmsCourse $course;
    private LmsCourseModule $module;
    private LmsLesson $lesson;
    private LmsQuizService $quizService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create dependencies
        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'TEST' . rand(1000, 9999),
            'is_active' => true,
        ]);

        $this->course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course-' . rand(1000, 9999),
            'course_code' => 'TC' . rand(100, 999),
            'visibility' => 'published',
        ]);

        $this->module = LmsCourseModule::create([
            'school_id' => $this->school->id,
            'course_id' => $this->course->id,
            'title' => 'Test Module',
        ]);

        $this->lesson = LmsLesson::create([
            'school_id' => $this->school->id,
            'course_id' => $this->course->id,
            'module_id' => $this->module->id,
            'title' => 'Test Lesson',
            'slug' => 'test-lesson-' . rand(1000, 9999),
            'lesson_type' => 'quiz',
            'visibility' => 'published',
        ]);

        $this->user = User::factory()->create(['school_id' => $this->school->id]);

        // Set the active school for the tenant context
        $tenantContext = $this->app->make(TenantContextService::class);

        session(['active_school_id' => $this->school->id]);
        app()->instance('resolved_domain_school_id', $this->school->id);

        $this->quizService = $this->app->make(LmsQuizService::class);
    }

    public function test_create_quiz_successfully(): void
    {
        $this->actingAs($this->user);

        $quizData = [
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title' => 'My New Quiz',
            'description' => 'Quiz Description',
            'time_limit_minutes' => 60,
            'max_attempts' => 3,
            'passing_score' => 75.50,
            'is_randomized' => true,
        ];

        $quiz = $this->quizService->createQuiz($quizData);

        $this->assertInstanceOf(LmsQuiz::class, $quiz);
        $this->assertEquals('My New Quiz', $quiz->title);
        $this->assertEquals($this->school->id, $quiz->school_id);

        $this->assertDatabaseHas('lms_quizzes', [
            'id' => $quiz->id,
            'school_id' => $this->school->id,
            'course_id' => $this->course->id,
            'lesson_id' => $this->lesson->id,
            'title' => 'My New Quiz',
            'time_limit_minutes' => 60,
            'max_attempts' => 3,
            'passing_score' => 75.50,
            'is_randomized' => 1,
        ]);

        $this->assertDatabaseHas('lms_activity_logs', [
            'school_id' => $this->school->id,
            'activity_type' => 'quiz_create',
            'subject_type' => LmsQuiz::class,
            'subject_id' => $quiz->id,
            'user_id' => $this->user->id,
        ]);
    }
}
