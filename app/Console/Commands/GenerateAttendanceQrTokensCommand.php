<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Services\Attendance\AttendanceQrTokenService;
use Illuminate\Console\Command;

class GenerateAttendanceQrTokensCommand extends Command
{
    protected $signature = 'app:generate-attendance-qr-tokens {--rotate : Rotate existing active tokens}';

    protected $description = 'Generate attendance QR tokens for students.';

    public function handle(AttendanceQrTokenService $tokenService): int
    {
        $rotate = (bool) $this->option('rotate');

        $students = Student::query()->get();

        $bar = $this->output->createProgressBar($students->count());
        $bar->start();

        foreach ($students as $student) {
            if ($rotate) {
                $tokenService->rotateToken($student);
            } else {
                $tokenService->ensureActiveToken($student);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Attendance QR tokens generated successfully.');

        return self::SUCCESS;
    }
}
