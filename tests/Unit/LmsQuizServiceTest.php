<?php

namespace Tests\Unit;

use App\Models\LmsQuizQuestion;
use App\Services\Lms\LmsActivityLogger;
use App\Services\Lms\LmsNotificationService;
use App\Services\Lms\LmsProgressService;
use App\Services\Lms\LmsQuizService;
use App\Services\Tenancy\TenantContextService;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class LmsQuizServiceTest extends TestCase
{
    public function test_delete_question_deletes_inside_transaction(): void
    {
        // Arrange
        $tenantContextMock = Mockery::mock(TenantContextService::class);
        $progressServiceMock = Mockery::mock(LmsProgressService::class);
        $notificationServiceMock = Mockery::mock(LmsNotificationService::class);
        $loggerMock = Mockery::mock(LmsActivityLogger::class);

        $service = new LmsQuizService(
            $tenantContextMock,
            $progressServiceMock,
            $notificationServiceMock,
            $loggerMock
        );

        $questionMock = Mockery::mock(LmsQuizQuestion::class);
        $questionMock->shouldReceive('delete')->once();

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        // Act
        $service->deleteQuestion($questionMock);

        // Assert
        // Mockery expectations are verified automatically by Laravel's TestCase
        $this->assertTrue(true); // Ensure at least one assertion exists to avoid risky test warnings
    }
}
