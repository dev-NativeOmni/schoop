<?php

namespace Tests\Feature\Services\Lms;

use App\Models\LmsCourse;
use App\Models\School;
use App\Models\User;
use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsCourseService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class LmsCourseServiceTest extends TestCase
{
    use RefreshDatabase;

    private School $school;
    private User $user;
    private LmsCourse $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create(['name' => 'School A', 'code' => 'SCHA', 'is_active' => true]);
        $this->user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@schoola.com',
            'password' => bcrypt('password'),
            'school_id' => $this->school->id,
            'is_active' => true,
        ]);

        $this->course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'TC-123',
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id,
        ]);
    }

    public function test_delete_course_removes_from_database_and_logs_activity(): void
    {
        // Arrange
        $tenantContextMock = Mockery::mock(TenantContextService::class);
        $tenantContextMock->shouldReceive('activeSchoolId')->andReturn($this->school->id);

        $loggerMock = Mockery::mock(LmsActivityLogger::class);
        $loggerMock->shouldReceive('log')
            ->once()
            ->with('course_delete', "Course '{$this->course->title}' deleted.", Mockery::on(function ($arg) {
                return $arg->id === $this->course->id;
            }));

        $service = new LmsCourseService($tenantContextMock, $loggerMock);

        // Act
        $service->deleteCourse($this->course);

        // Assert
        $this->assertSoftDeleted('lms_courses', [
            'id' => $this->course->id,
        ]);
    }
}
