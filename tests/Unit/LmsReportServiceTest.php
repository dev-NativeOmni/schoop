<?php

namespace Tests\Unit;

use App\Models\LmsCourse;
use App\Models\LmsCourseEnrollment;
use App\Models\School;
use App\Models\Student;
use App\Services\Lms\LmsReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsReportServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $school;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'TS01',
            'tenant_code' => 'ts01',
        ]);

        // Ensure tenant scope is applied properly by setting active school
        session(['active_school_id' => $this->school->id]);
        app()->instance('resolved_domain_school_id', $this->school->id);
    }

    public function test_get_course_completion_stats_calculates_correctly()
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'TC01',
        ]);

        $student1 = Student::create([
            'school_id' => $this->school->id,
            'student_number' => 'S01',
            'full_name' => 'Student 1',
        ]);

        $student2 = Student::create([
            'school_id' => $this->school->id,
            'student_number' => 'S02',
            'full_name' => 'Student 2',
        ]);

        $student3 = Student::create([
            'school_id' => $this->school->id,
            'student_number' => 'S03',
            'full_name' => 'Student 3',
        ]);

        $student4 = Student::create([
            'school_id' => $this->school->id,
            'student_number' => 'S04',
            'full_name' => 'Student 4',
        ]);

        LmsCourseEnrollment::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'student_id' => $student1->id,
            'status' => 'completed',
        ]);

        LmsCourseEnrollment::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'student_id' => $student2->id,
            'status' => 'active',
        ]);

        LmsCourseEnrollment::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'student_id' => $student3->id,
            'status' => 'completed',
        ]);

        LmsCourseEnrollment::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'student_id' => $student4->id,
            'status' => 'active',
        ]);

        $service = new LmsReportService;
        $stats = $service->getCourseCompletionStats($course->id);

        $this->assertArrayHasKey('total_enrolled', $stats);
        $this->assertArrayHasKey('completed_count', $stats);
        $this->assertArrayHasKey('completion_rate', $stats);

        $this->assertEquals(4, $stats['total_enrolled']);
        $this->assertEquals(2, $stats['completed_count']);
        $this->assertEquals(50.0, $stats['completion_rate']);
    }

    public function test_get_course_completion_stats_returns_zeros_when_no_enrollments()
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Empty Course',
            'slug' => 'empty-course',
            'course_code' => 'EC01',
        ]);

        $service = new LmsReportService;
        $stats = $service->getCourseCompletionStats($course->id);

        $this->assertArrayHasKey('total_enrolled', $stats);
        $this->assertArrayHasKey('completed_count', $stats);
        $this->assertArrayHasKey('completion_rate', $stats);

        $this->assertEquals(0, $stats['total_enrolled']);
        $this->assertEquals(0, $stats['completed_count']);
        $this->assertEquals(0.0, $stats['completion_rate']);
    }
}
