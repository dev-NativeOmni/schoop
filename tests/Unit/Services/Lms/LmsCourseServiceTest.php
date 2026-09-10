<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsCourse;
use App\Models\School;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsCourseService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class LmsCourseServiceTest extends TestCase
{
    use RefreshDatabase;

    private LmsCourseService $service;
    private $tenantContextMock;
    private $activityLoggerMock;
    private School $school;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create database dependencies
        $this->school = School::create([
            'name' => 'Test School',
            'code' => 'TEST01',
            'is_active' => true,
        ]);

        $this->user = User::factory()->create([
            'school_id' => $this->school->id,
        ]);

        // Mock TenantContextService
        $this->tenantContextMock = Mockery::mock(TenantContextService::class);
        $this->tenantContextMock->shouldReceive('activeSchoolId')
            ->andReturn($this->school->id);

        // Mock LmsActivityLogger
        $this->activityLoggerMock = Mockery::mock(LmsActivityLogger::class);
        $this->activityLoggerMock->shouldReceive('log')
            ->andReturnNull();

        $this->service = new LmsCourseService(
            $this->tenantContextMock,
            $this->activityLoggerMock
        );
    }

    public function test_create_course_with_automatic_slug(): void
    {
        $data = [
            'title' => 'Introduction to Laravel',
            'course_code' => 'LAR-101',
            'type' => 'general',
            'description' => 'A basic course.',
            'level' => 'beginner',
            'visibility' => 'published',
            'enrollment_mode' => 'manual',
            'is_required' => true,
        ];

        $course = $this->service->createCourse($data, $this->user->id);

        $this->assertInstanceOf(LmsCourse::class, $course);
        $this->assertEquals('introduction-to-laravel', $course->slug);
        $this->assertEquals($this->school->id, $course->school_id);
        $this->assertEquals($this->user->id, $course->created_by);
        $this->assertEquals($this->user->id, $course->updated_by);

        // Verify the logger was called
        $this->activityLoggerMock->shouldHaveReceived('log')
            ->with('course_create', "Course '{$course->title}' created.", Mockery::on(function ($arg) use ($course) {
                return $arg->id === $course->id;
            }))
            ->once();
    }

    public function test_create_course_with_existing_slug_generates_unique(): void
    {
        $data = [
            'title' => 'Duplicate Course',
            'course_code' => 'DUP-01',
        ];

        // Create first course
        $course1 = $this->service->createCourse($data, $this->user->id);
        $this->assertEquals('duplicate-course', $course1->slug);

        // Create second course with same title
        $data2 = [
            'title' => 'Duplicate Course',
            'course_code' => 'DUP-02',
        ];
        $course2 = $this->service->createCourse($data2, $this->user->id);

        $this->assertEquals('duplicate-course-1', $course2->slug);
    }

    public function test_create_course_with_provided_slug(): void
    {
        $data = [
            'title' => 'Custom Slug Course',
            'slug' => 'my-custom-slug',
            'course_code' => 'CUS-01',
        ];

        $course = $this->service->createCourse($data, $this->user->id);

        $this->assertEquals('my-custom-slug', $course->slug);
    }

    public function test_create_course_assigns_instructors(): void
    {
        // Create teacher profile
        $teacher = TeacherProfile::create([
            'user_id' => $this->user->id,
            'school_id' => $this->school->id,
            'employee_number' => 'EMP-001',
            'specialization' => 'IT',
            'is_active' => true,
        ]);

        $teacher2 = TeacherProfile::create([
            'user_id' => User::factory()->create(['school_id' => $this->school->id])->id,
            'school_id' => $this->school->id,
            'employee_number' => 'EMP-002',
            'specialization' => 'IT',
            'is_active' => true,
        ]);

        $data = [
            'title' => 'Instructor Course',
            'course_code' => 'INS-01',
            'instructor_ids' => [$teacher->id, $teacher2->id],
            'primary_instructor_id' => $teacher->id,
        ];

        $course = $this->service->createCourse($data, $this->user->id);

        $this->assertDatabaseHas('lms_course_instructors', [
            'course_id' => $course->id,
            'teacher_profile_id' => $teacher->id,
            'is_primary' => true,
        ]);

        $this->assertDatabaseHas('lms_course_instructors', [
            'course_id' => $course->id,
            'teacher_profile_id' => $teacher2->id,
            'is_primary' => false,
        ]);
    }
}
