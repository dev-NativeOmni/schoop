<?php

namespace Tests\Feature\Services\Lms;

use App\Models\LmsActivityLog;
use App\Models\LmsCourse;
use App\Models\LmsCourseInstructor;
use App\Models\School;
use App\Models\TeacherProfile;
use App\Models\User;
use App\Services\Lms\LmsCourseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LmsCourseServiceTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private User $admin;
    private User $teacher1;
    private User $teacher2;
    private TeacherProfile $teacherProfile1;
    private TeacherProfile $teacherProfile2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create(['name' => 'Test School', 'code' => 'TST', 'is_active' => true]);

        // Mock tenant context to provide the school id
        app()->instance('resolved_domain_school_id', $this->school->id);

        $this->admin = User::create([
            'school_id' => $this->school->id,
            'name' => 'Admin User',
            'username' => 'admin_test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->teacher1 = User::create([
            'school_id' => $this->school->id,
            'name' => 'Teacher 1',
            'username' => 'teacher1_test',
            'email' => 'teacher1@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->teacherProfile1 = TeacherProfile::create([
            'user_id' => $this->teacher1->id,
            'school_id' => $this->school->id,
            'employee_number' => 'T001',
            'is_active' => true,
        ]);

        $this->teacher2 = User::create([
            'school_id' => $this->school->id,
            'name' => 'Teacher 2',
            'username' => 'teacher2_test',
            'email' => 'teacher2@test.com',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);

        $this->teacherProfile2 = TeacherProfile::create([
            'user_id' => $this->teacher2->id,
            'school_id' => $this->school->id,
            'employee_number' => 'T002',
            'is_active' => true,
        ]);

        Auth::login($this->admin);
    }

    public function test_update_course_basic(): void
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Original Title',
            'slug' => 'original-title',
            'course_code' => 'ORIG-1',
            'type' => 'general',
            'created_by' => $this->admin->id,
        ]);

        $service = app(LmsCourseService::class);

        $data = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
        ];

        $updatedCourse = $service->updateCourse($course, $data, $this->admin->id);

        $this->assertEquals('Updated Title', $updatedCourse->title);
        $this->assertEquals('updated-title', $updatedCourse->slug);
        $this->assertEquals('Updated Description', $updatedCourse->description);
        $this->assertEquals($this->admin->id, $updatedCourse->updated_by);

        $this->assertDatabaseHas('lms_courses', [
            'id' => $course->id,
            'title' => 'Updated Title',
            'slug' => 'updated-title',
            'description' => 'Updated Description',
            'updated_by' => $this->admin->id,
        ]);

        $this->assertDatabaseHas('lms_activity_logs', [
            'school_id' => $this->school->id,
            'user_id' => $this->admin->id,
            'activity_type' => 'course_update',
            'subject_type' => LmsCourse::class,
            'subject_id' => $course->id,
        ]);
    }

    public function test_update_course_slug_collision(): void
    {
        LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Existing Course',
            'slug' => 'existing-course',
            'course_code' => 'EXT-1',
            'type' => 'general',
            'created_by' => $this->admin->id,
        ]);

        LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Existing Course 1',
            'slug' => 'existing-course-1',
            'course_code' => 'EXT-2',
            'type' => 'general',
            'created_by' => $this->admin->id,
        ]);

        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'New Course',
            'slug' => 'new-course',
            'course_code' => 'NEW-1',
            'type' => 'general',
            'created_by' => $this->admin->id,
        ]);

        $service = app(LmsCourseService::class);

        $data = [
            'title' => 'Existing Course', // Title will cause slug 'existing-course'
        ];

        $updatedCourse = $service->updateCourse($course, $data, $this->admin->id);

        $this->assertEquals('Existing Course', $updatedCourse->title);
        $this->assertEquals('existing-course-2', $updatedCourse->slug);
    }

    public function test_update_course_does_not_change_slug_if_provided(): void
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Original Title',
            'slug' => 'original-title',
            'course_code' => 'ORIG-1',
            'type' => 'general',
            'created_by' => $this->admin->id,
        ]);

        $service = app(LmsCourseService::class);

        $data = [
            'title' => 'Updated Title',
            'slug' => 'custom-slug',
        ];

        $updatedCourse = $service->updateCourse($course, $data, $this->admin->id);

        $this->assertEquals('Updated Title', $updatedCourse->title);
        $this->assertEquals('custom-slug', $updatedCourse->slug);
    }

    public function test_update_course_instructor_assignments(): void
    {
        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Original Title',
            'slug' => 'original-title',
            'course_code' => 'ORIG-1',
            'type' => 'general',
            'created_by' => $this->admin->id,
        ]);

        LmsCourseInstructor::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'teacher_profile_id' => $this->teacherProfile1->id,
            'is_primary' => true,
        ]);

        $service = app(LmsCourseService::class);

        $data = [
            'instructor_ids' => [$this->teacherProfile2->id],
            'primary_instructor_id' => $this->teacherProfile2->id,
        ];

        $service->updateCourse($course, $data, $this->admin->id);

        $this->assertDatabaseMissing('lms_course_instructors', [
            'course_id' => $course->id,
            'teacher_profile_id' => $this->teacherProfile1->id,
        ]);

        $this->assertDatabaseHas('lms_course_instructors', [
            'course_id' => $course->id,
            'teacher_profile_id' => $this->teacherProfile2->id,
            'is_primary' => true,
        ]);
    }
}
