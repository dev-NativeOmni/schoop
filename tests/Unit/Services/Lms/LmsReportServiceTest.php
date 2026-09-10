<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsAssignment;
use App\Models\LmsAssignmentSubmission;
use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsLessonProgress;
use App\Models\LmsQuiz;
use App\Models\LmsQuizAttempt;
use App\Models\School;
use App\Models\Student;
use App\Services\Lms\LmsReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_student_course_summary_returns_empty_array_if_not_enrolled(): void
    {
        $service = new LmsReportService;
        $result = $service->getStudentCourseSummary(1, 1);
        $this->assertEmpty($result);
    }

    public function test_get_student_course_summary_returns_summary(): void
    {
        $school = School::create(['name' => 'Test School', 'code' => 'TEST1']);

        // Use session to set active school id
        session(['active_school_id' => $school->id]);
        app()->instance('resolved_domain_school_id', $school->id);

        $student = Student::create([
            'school_id' => $school->id,
            'student_number' => 'STU001',
            'full_name' => 'Student One',
            'is_active' => true,
        ]);

        $course = LmsCourse::create([
            'school_id' => $school->id,
            'title' => 'Course 1',
            'slug' => 'course-1',
            'course_code' => 'C001',
            'visibility' => 'published',
        ]);

        $module = LmsCourseModule::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'title' => 'Module 1',
        ]);

        $enrollment = LmsCourseEnrollment::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'student_id' => $student->id,
            'status' => 'active',
            'progress_percentage' => 10.0,
        ]);

        $lesson1 = LmsLesson::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Lesson 1',
            'slug' => 'lesson-1',
            'lesson_type' => 'text',
            'sort_order' => 1,
        ]);

        $lesson2 = LmsLesson::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Lesson 2',
            'slug' => 'lesson-2',
            'lesson_type' => 'text',
            'sort_order' => 2,
        ]);

        LmsLessonProgress::create([
            'school_id' => $school->id,
            'student_id' => $student->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
        ]);

        $assignment1 = LmsAssignment::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson1->id,
            'title' => 'Assignment 1',
        ]);

        LmsAssignmentSubmission::create([
            'school_id' => $school->id,
            'assignment_id' => $assignment1->id,
            'student_id' => $student->id,
            'status' => 'graded',
            'score' => 85.0,
        ]);

        $quiz1 = LmsQuiz::create([
            'school_id' => $school->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson2->id,
            'title' => 'Quiz 1',
            'passing_score' => 70,
        ]);

        LmsQuizAttempt::create([
            'school_id' => $school->id,
            'quiz_id' => $quiz1->id,
            'student_id' => $student->id,
            'status' => 'completed',
            'score' => 90.0,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $service = new LmsReportService;
        $result = $service->getStudentCourseSummary($student->id, $course->id);

        $this->assertEquals(10.0, $result['progress_percentage']);
        $this->assertEquals('active', $result['status']);
        $this->assertEquals(2, $result['total_lessons']);
        $this->assertEquals(1, $result['completed_lessons']);
        $this->assertEquals(1, $result['assignments_submitted']);
        $this->assertEquals(85.0, $result['assignments_average_score']);
        $this->assertEquals(1, $result['quizzes_attempted']);
        $this->assertEquals(90.0, $result['quizzes_average_score']);
    }
}
