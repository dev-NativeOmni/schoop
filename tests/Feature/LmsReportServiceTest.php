<?php

namespace Tests\Feature;

use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsLessonProgress;
use App\Models\Role;
use App\Models\School;
use App\Models\Student;
use App\Services\Lms\LmsReportService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsReportServiceTest extends TestCase
{
    use RefreshDatabase;

    private School $school;

    private LmsCourse $course;

    private LmsCourseModule $module;

    private LmsLesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        if (Role::count() === 0) {
            $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        }

        $this->school = School::create(['name' => 'Test School', 'code' => 'TSTSCH', 'is_active' => true]);

        session(['active_school_id' => $this->school->id]);
        app()->instance('resolved_domain_school_id', $this->school->id);

        $this->course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'TEST-C',
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
            'slug' => 'test-lesson',
            'lesson_type' => 'text',
            'is_required' => true,
            'visibility' => 'published',
            'sort_order' => 1,
        ]);
    }

    private function createStudent(string $number, string $name): Student
    {
        return Student::create([
            'school_id' => $this->school->id,
            'student_number' => $number,
            'full_name' => $name,
            'is_active' => true,
        ]);
    }

    public function test_get_lesson_progress_stats_with_no_enrollments(): void
    {
        $reportService = new LmsReportService;

        $stats = $reportService->getLessonProgressStats($this->lesson->id);

        $this->assertEquals([
            'not_started' => 0,
            'in_progress' => 0,
            'completed' => 0,
        ], $stats);
    }

    public function test_get_lesson_progress_stats_with_varied_progress(): void
    {
        // Create 3 students
        $student1 = $this->createStudent('STU001', 'Student One');
        $student2 = $this->createStudent('STU002', 'Student Two');
        $student3 = $this->createStudent('STU003', 'Student Three');

        // Enroll all 3 in the course
        LmsCourseEnrollment::create(['school_id' => $this->school->id, 'course_id' => $this->course->id, 'student_id' => $student1->id, 'status' => 'active']);
        LmsCourseEnrollment::create(['school_id' => $this->school->id, 'course_id' => $this->course->id, 'student_id' => $student2->id, 'status' => 'active']);
        LmsCourseEnrollment::create(['school_id' => $this->school->id, 'course_id' => $this->course->id, 'student_id' => $student3->id, 'status' => 'active']);

        // Student 1 completed the lesson
        LmsLessonProgress::create([
            'school_id' => $this->school->id,
            'student_id' => $student1->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'completed',
        ]);

        // Student 2 is in progress
        LmsLessonProgress::create([
            'school_id' => $this->school->id,
            'student_id' => $student2->id,
            'lesson_id' => $this->lesson->id,
            'status' => 'in_progress',
        ]);

        // Student 3 has not started (no progress record)

        $reportService = new LmsReportService;
        $stats = $reportService->getLessonProgressStats($this->lesson->id);

        $this->assertEquals([
            'not_started' => 1,
            'in_progress' => 1,
            'completed' => 1,
        ], $stats);
    }

    public function test_get_lesson_progress_stats_throws_exception_for_missing_lesson(): void
    {
        $reportService = new LmsReportService;

        $this->expectException(ModelNotFoundException::class);
        $reportService->getLessonProgressStats(99999);
    }
}
