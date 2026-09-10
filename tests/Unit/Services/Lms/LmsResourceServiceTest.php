<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsLessonResource;
use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsResourceService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Mockery;

class LmsResourceServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_delete_resource_with_existing_file()
    {
        Storage::fake('local');
        Storage::disk('local')->put('path/to/existing.pdf', 'dummy content');

        $resource = Mockery::mock(LmsLessonResource::class)->makePartial();
        $resource->file_path = 'path/to/existing.pdf';
        $resource->title = 'My Resource';

        $resource->shouldReceive('delete')->once()->andReturn(true);

        $logger = Mockery::mock(LmsActivityLogger::class);
        $logger->shouldReceive('log')
            ->once()
            ->with('resource_delete', "Resource 'My Resource' deleted.", $resource);

        $tenantContext = Mockery::mock(TenantContextService::class);

        $service = new LmsResourceService($tenantContext, $logger);

        $service->deleteResource($resource);

        Storage::disk('local')->assertMissing('path/to/existing.pdf');
    }

    public function test_delete_resource_without_file()
    {
        Storage::fake('local');

        $resource = Mockery::mock(LmsLessonResource::class)->makePartial();
        $resource->file_path = null;
        $resource->title = 'My Resource';

        $resource->shouldReceive('delete')->once()->andReturn(true);

        $logger = Mockery::mock(LmsActivityLogger::class);
        $logger->shouldReceive('log')
            ->once()
            ->with('resource_delete', "Resource 'My Resource' deleted.", $resource);

        $tenantContext = Mockery::mock(TenantContextService::class);

        $service = new LmsResourceService($tenantContext, $logger);

        $service->deleteResource($resource);

        // Assert no error was thrown and it completed
        $this->assertTrue(true);
    }

    public function test_delete_resource_with_missing_file()
    {
        Storage::fake('local');

        $resource = Mockery::mock(LmsLessonResource::class)->makePartial();
        $resource->file_path = 'path/to/missing.pdf';
        $resource->title = 'My Resource';

        $resource->shouldReceive('delete')->once()->andReturn(true);

        $logger = Mockery::mock(LmsActivityLogger::class);
        $logger->shouldReceive('log')
            ->once()
            ->with('resource_delete', "Resource 'My Resource' deleted.", $resource);

        $tenantContext = Mockery::mock(TenantContextService::class);

        $service = new LmsResourceService($tenantContext, $logger);

        $service->deleteResource($resource);

        // Assert no error was thrown and it completed
        $this->assertTrue(true);
    }
}
