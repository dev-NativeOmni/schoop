<?php

namespace Tests\Feature;

use App\Models\LmsCourse;
use App\Models\LmsCourseInstructor;
use App\Models\School;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Lms\LmsCourseService;
use App\Services\Tenancy\TenantContextService;
use App\Services\Lms\LmsActivityLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LmsCourseServiceTest extends TestCase
{
    use RefreshDatabase;

    private LmsCourseService $courseService;

    protected function setUp(): void
    {
        parent::setUp();

        $tenantContext = $this->app->make(TenantContextService::class);
        $logger = $this->app->make(LmsActivityLogger::class);
        $this->courseService = new LmsCourseService($tenantContext, $logger);
    }

    public function test_assign_instructors()
    {
        $school = School::create(['name' => 'Test School', 'code' => 'TEST', 'is_active' => true]);
        $user = User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => 'password']);
        $course = LmsCourse::create([
            'school_id' => $school->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'TEST-001',
        ]);

        $teacherUser1 = User::create(['name' => 'Teacher 1', 'email' => 't1@test.com', 'password' => 'password']);
        $teacherUser2 = User::create(['name' => 'Teacher 2', 'email' => 't2@test.com', 'password' => 'password']);

        $teacher1 = TeacherProfile::create(['user_id' => $teacherUser1->id, 'school_id' => $school->id, 'is_active' => true]);
        $teacher2 = TeacherProfile::create(['user_id' => $teacherUser2->id, 'school_id' => $school->id, 'is_active' => true]);

        // Initial Assignment
        $this->courseService->assignInstructors($course, [$teacher1->id, $teacher2->id], $teacher1->id);

        $this->assertDatabaseCount('lms_course_instructors', 2);

        $this->assertDatabaseHas('lms_course_instructors', [
            'course_id' => $course->id,
            'teacher_profile_id' => $teacher1->id,
            'is_primary' => true,
        ]);

        $this->assertDatabaseHas('lms_course_instructors', [
            'course_id' => $course->id,
            'teacher_profile_id' => $teacher2->id,
            'is_primary' => false,
        ]);

        // Test re-assignment deletes old ones and sets the new array correctly
        $this->courseService->assignInstructors($course, [$teacher2->id], $teacher2->id);

        $this->assertDatabaseCount('lms_course_instructors', 1);

        $this->assertDatabaseHas('lms_course_instructors', [
            'course_id' => $course->id,
            'teacher_profile_id' => $teacher2->id,
            'is_primary' => true,
        ]);
    }
}
