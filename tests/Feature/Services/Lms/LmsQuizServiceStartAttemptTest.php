<?php

namespace Tests\Feature\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsQuiz;
use App\Models\LmsQuizAttempt;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Services\Lms\LmsQuizService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class LmsQuizServiceStartAttemptTest extends TestCase
{
    use RefreshDatabase;

    private LmsQuizService $quizService;

    private School $school;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'SCH001',
            'domain' => 'test.example.com',
        ]);

        Session::put(TenantContextService::SESSION_KEY, $this->school->id);

        $this->quizService = app(LmsQuizService::class);
    }

    public function test_start_attempt_successfully(): void
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'C001',
            'status' => 'published',
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
        ]);

        $quiz = LmsQuiz::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => 'Test Quiz',
            'max_attempts' => 2,
            'passing_score' => 80,
        ]);

        $user = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'school_id' => $this->school->id,
        ]);

        $student = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'full_name' => 'Student User',
            'student_number' => '12345',
            'is_active' => true,
        ]);

        $attempt = $this->quizService->startAttempt($quiz->id, $student->id);

        $this->assertInstanceOf(LmsQuizAttempt::class, $attempt);
        $this->assertEquals($quiz->id, $attempt->quiz_id);
        $this->assertEquals($student->id, $attempt->student_id);
        $this->assertEquals($this->school->id, $attempt->school_id);
        $this->assertEquals(1, $attempt->attempt_number);
        $this->assertEquals('started', $attempt->status);
        $this->assertNotNull($attempt->started_at);
        $this->assertDatabaseHas('lms_quiz_attempts', [
            'id' => $attempt->id,
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'attempt_number' => 1,
            'status' => 'started',
        ]);
    }

    public function test_start_attempt_throws_exception_if_max_attempts_reached(): void
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course-2',
            'course_code' => 'C002',
            'status' => 'published',
        ]);

        $module = LmsCourseModule::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'title' => 'Test Module 2',
        ]);

        $lesson = LmsLesson::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Test Lesson',
            'slug' => 'test-lesson-2',
            'lesson_type' => 'quiz',
        ]);

        $quiz = LmsQuiz::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => 'Test Quiz',
            'max_attempts' => 1,
            'passing_score' => 80,
        ]);

        $user = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'school_id' => $this->school->id,
        ]);

        $student = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'full_name' => 'Student User',
            'student_number' => '12345',
            'is_active' => true,
        ]);

        $this->quizService->startAttempt($quiz->id, $student->id);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Anda telah mencapai batas maksimum percobaan (1) untuk kuis ini.');

        $this->quizService->startAttempt($quiz->id, $student->id);
    }

    public function test_start_attempt_unlimited_attempts(): void
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course-3',
            'course_code' => 'C003',
            'status' => 'published',
        ]);

        $module = LmsCourseModule::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'title' => 'Test Module 3',
        ]);

        $lesson = LmsLesson::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Test Lesson',
            'slug' => 'test-lesson-3',
            'lesson_type' => 'quiz',
        ]);

        $quiz = LmsQuiz::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'title' => 'Test Quiz',
            'max_attempts' => 0, // unlimited
            'passing_score' => 80,
        ]);

        $user = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'school_id' => $this->school->id,
        ]);

        $student = Student::create([
            'school_id' => $this->school->id,
            'user_id' => $user->id,
            'full_name' => 'Student User',
            'student_number' => '12345',
            'is_active' => true,
        ]);

        $attempt1 = $this->quizService->startAttempt($quiz->id, $student->id);
        $this->assertEquals(1, $attempt1->attempt_number);

        $attempt2 = $this->quizService->startAttempt($quiz->id, $student->id);
        $this->assertEquals(2, $attempt2->attempt_number);

        $attempt3 = $this->quizService->startAttempt($quiz->id, $student->id);
        $this->assertEquals(3, $attempt3->attempt_number);
    }
}
