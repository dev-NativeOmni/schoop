<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsCourse;
use App\Models\LmsCourseModule;
use App\Models\LmsLesson;
use App\Models\LmsLessonResource;
use App\Models\School;
use App\Models\User;
use App\Models\Role;
use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsResourceService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class LmsResourceServiceTest extends TestCase
{
    use RefreshDatabase;

    private LmsResourceService $service;
    private School $school;
    private User $user;
    private LmsLesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::create(['name' => 'School A', 'code' => 'SCHA', 'is_active' => true]);

        $role = Role::create(['name' => 'admin', 'label' => 'Admin', 'guard_name' => 'web']);

        $this->user = User::create([
            'school_id' => $this->school->id,
            'role_id' => $role->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $course = LmsCourse::create([
            'school_id' => $this->school->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'course_code' => 'TC-1',
        ]);

        $module = LmsCourseModule::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'title' => 'Test Module',
        ]);

        $this->lesson = LmsLesson::create([
            'school_id' => $this->school->id,
            'course_id' => $course->id,
            'module_id' => $module->id,
            'title' => 'Test Lesson',
            'slug' => 'test-lesson',
            'lesson_type' => 'text',
        ]);

        $tenantContext = $this->mock(TenantContextService::class, function (MockInterface $mock) {
            $mock->shouldReceive('activeSchoolId')->andReturn($this->school->id);
        });

        $logger = $this->mock(LmsActivityLogger::class, function (MockInterface $mock) {
            $mock->shouldReceive('log')->andReturnNull();
        });

        $this->service = new LmsResourceService($tenantContext, $logger);
    }

    public function test_create_resource_without_file(): void
    {
        $data = [
            'lesson_id' => $this->lesson->id,
            'title' => 'Test Link Resource',
            'resource_type' => 'link',
            'external_url' => 'https://example.com',
        ];

        $resource = $this->service->createResource($data, null, $this->user->id);

        $this->assertInstanceOf(LmsLessonResource::class, $resource);
        $this->assertEquals($this->school->id, $resource->school_id);
        $this->assertEquals($this->user->id, $resource->uploaded_by);
        $this->assertEquals('Test Link Resource', $resource->title);
        $this->assertEquals('link', $resource->resource_type);
        $this->assertEquals(1, $resource->sort_order);

        $this->assertDatabaseHas('lms_lesson_resources', [
            'id' => $resource->id,
            'title' => 'Test Link Resource',
            'school_id' => $this->school->id,
            'uploaded_by' => $this->user->id,
        ]);
    }
    public function test_create_resource_with_file(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $data = [
            'lesson_id' => $this->lesson->id,
            'title' => 'Test File Resource',
            'resource_type' => 'file',
        ];

        $resource = $this->service->createResource($data, $file, $this->user->id);

        $this->assertInstanceOf(LmsLessonResource::class, $resource);
        $this->assertEquals($this->school->id, $resource->school_id);
        $this->assertEquals($this->user->id, $resource->uploaded_by);
        $this->assertEquals('Test File Resource', $resource->title);
        $this->assertEquals('file', $resource->resource_type);
        $this->assertStringStartsWith("private/lms/{$this->school->id}/", $resource->file_path);
        $this->assertEquals('application/pdf', $resource->mime_type);
        $this->assertEquals(102400, $resource->file_size);

        Storage::disk('local')->assertExists($resource->file_path);

        $this->assertDatabaseHas('lms_lesson_resources', [
            'id' => $resource->id,
            'title' => 'Test File Resource',
            'school_id' => $this->school->id,
            'uploaded_by' => $this->user->id,
            'file_path' => $resource->file_path,
        ]);
    }
    public function test_create_resource_with_invalid_file_extension(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('malicious.exe', 100, 'application/x-msdownload');

        $data = [
            'lesson_id' => $this->lesson->id,
            'title' => 'Test Invalid File Resource',
            'resource_type' => 'file',
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Format berkas tidak diizinkan demi alasan keamanan sistem.');

        $this->service->createResource($data, $file, $this->user->id);
    }
    public function test_create_resource_with_invalid_mime_type(): void
    {
        Storage::fake('local');

        // Has valid extension but invalid mime type
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/x-msdownload');

        $data = [
            'lesson_id' => $this->lesson->id,
            'title' => 'Test Invalid Mime Type',
            'resource_type' => 'file',
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('MIME type berkas tidak sesuai dengan tipe dokumen yang diperbolehkan.');

        $this->service->createResource($data, $file, $this->user->id);
    }
    public function test_create_resource_increments_sort_order(): void
    {
        $data1 = [
            'lesson_id' => $this->lesson->id,
            'title' => 'Resource 1',
            'resource_type' => 'link',
            'external_url' => 'https://example.com/1',
        ];

        $data2 = [
            'lesson_id' => $this->lesson->id,
            'title' => 'Resource 2',
            'resource_type' => 'link',
            'external_url' => 'https://example.com/2',
        ];

        $data3 = [
            'lesson_id' => $this->lesson->id,
            'title' => 'Resource 3',
            'resource_type' => 'link',
            'external_url' => 'https://example.com/3',
        ];

        $resource1 = $this->service->createResource($data1, null, $this->user->id);
        $resource2 = $this->service->createResource($data2, null, $this->user->id);
        $resource3 = $this->service->createResource($data3, null, $this->user->id);

        $this->assertEquals(1, $resource1->sort_order);
        $this->assertEquals(2, $resource2->sort_order);
        $this->assertEquals(3, $resource3->sort_order);
    }
}
