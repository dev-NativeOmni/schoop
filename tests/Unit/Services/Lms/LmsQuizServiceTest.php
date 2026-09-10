<?php

namespace Tests\Unit\Services\Lms;

use App\Models\LmsQuiz;
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
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_update_quiz_successfully_updates_and_logs(): void
    {
        // 1. Arrange
        $tenantContext = Mockery::mock(TenantContextService::class);
        $progressService = Mockery::mock(LmsProgressService::class);
        $notificationService = Mockery::mock(LmsNotificationService::class);
        $logger = Mockery::mock(LmsActivityLogger::class);

        $service = new LmsQuizService(
            $tenantContext,
            $progressService,
            $notificationService,
            $logger
        );

        $quizMock = Mockery::mock(LmsQuiz::class)->makePartial();

        DB::shouldReceive('transaction')
            ->once()
            ->andReturnUsing(function ($callback) {
                return $callback();
            });

        $updateData = ['title' => 'New Title', 'passing_score' => 80];

        $quizMock->shouldReceive('update')
            ->once()
            ->with($updateData)
            ->andReturn(true);

        $quizMock->title = 'New Title'; // Alternatively set the attribute

        $logger->shouldReceive('log')
            ->once()
            ->with('quiz_update', "Quiz 'New Title' updated.", $quizMock);

        // 2. Act
        $result = $service->updateQuiz($quizMock, $updateData);

        // 3. Assert
        $this->assertSame($quizMock, $result);
    }
}
