<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Services\Cashless\CashlessWalletService;
use Illuminate\Console\Command;

class CashlessCreateStudentWalletsCommand extends Command
{
    protected $signature = 'app:cashless-create-student-wallets {--school_id=}';

    protected $description = 'Create missing cashless wallets for active students.';

    public function handle(CashlessWalletService $wallets): int
    {
        $created = 0;
        $skipped = 0;
        $failed = 0;

        $students = Student::query()
            ->when($this->option('school_id'), fn ($query, $schoolId) => $query->where('school_id', $schoolId))
            ->where('is_active', true)
            ->with('cashlessWallet')
            ->get();

        foreach ($students as $student) {
            try {
                if ($student->cashlessWallet) {
                    $skipped++;

                    continue;
                }

                $wallets->createWalletForStudent($student);
                $created++;
            } catch (\Throwable $exception) {
                $failed++;
                $this->warn("Failed student {$student->id}: {$exception->getMessage()}");
            }
        }

        $this->info("Created wallets: {$created}");
        $this->info("Skipped existing wallets: {$skipped}");
        $this->info("Failed: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
