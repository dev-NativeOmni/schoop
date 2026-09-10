<?php

namespace Tests\Unit\Services\Lms;

use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsResourceService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use Tests\TestCase;
use Mockery;

class LmsResourceServiceTest extends TestCase
{
    private LmsResourceService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $tenantContextMock = Mockery::mock(TenantContextService::class);
        $loggerMock = Mockery::mock(LmsActivityLogger::class);

        $this->service = new LmsResourceService($tenantContextMock, $loggerMock);
    }

    public function test_validate_file_passes_for_valid_file()
    {
        // 1MB file
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        // Should not throw exception
        $this->service->validateFile($file);
        $this->assertTrue(true);
    }

    public function test_validate_file_fails_for_invalid_extension()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format berkas tidak diizinkan demi alasan keamanan sistem.');

        $file = UploadedFile::fake()->create('malware.exe', 1024, 'application/x-msdownload');
        $this->service->validateFile($file);
    }

    public function test_validate_file_fails_for_invalid_mime_type()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('MIME type berkas tidak sesuai dengan tipe dokumen yang diperbolehkan.');

        // Has valid extension but invalid mime type
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'text/html');
        $this->service->validateFile($file);
    }

    public function test_validate_file_fails_when_file_too_large()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Ukuran file tidak boleh melebihi 10 MB.');

        // > 10MB file
        $file = UploadedFile::fake()->create('large_video.mp4', 11 * 1024, 'video/mp4');
        $this->service->validateFile($file);
    }
}
