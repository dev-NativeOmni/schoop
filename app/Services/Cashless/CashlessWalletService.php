<?php

namespace App\Services\Cashless;

use App\Models\CashlessWallet;
use App\Models\Student;
use App\Models\User;
use InvalidArgumentException;

class CashlessWalletService
{
    public function __construct(
        private readonly CashlessNumberGenerator $numbers,
        private readonly CashlessAuditService $audit,
    ) {}

    public function createWalletForStudent(Student $student): CashlessWallet
    {
        $wallet = CashlessWallet::query()->firstOrCreate(
            ['school_id' => $student->school_id, 'student_id' => $student->id],
            [
                'wallet_number' => $this->numbers->walletNumber((int) $student->school_id, (int) $student->id),
                'balance' => 0,
                'status' => 'active',
            ]
        );

        if ($wallet->wasRecentlyCreated) {
            $this->audit->log((int) $student->school_id, null, 'wallet.created', $wallet);
        }

        return $wallet;
    }

    public function getWalletForStudent(Student $student): ?CashlessWallet
    {
        return CashlessWallet::query()
            ->where('school_id', $student->school_id)
            ->where('student_id', $student->id)
            ->first();
    }

    public function freezeWallet(CashlessWallet $wallet, User $actor, string $reason): void
    {
        $wallet->update([
            'status' => 'frozen',
            'frozen_at' => now(),
            'frozen_by' => $actor->id,
            'freeze_reason' => $reason,
        ]);

        $this->audit->log((int) $wallet->school_id, $actor, 'wallet.frozen', $wallet, ['reason' => $reason]);
    }

    public function unfreezeWallet(CashlessWallet $wallet, User $actor): void
    {
        $wallet->update([
            'status' => 'active',
            'frozen_at' => null,
            'frozen_by' => null,
            'freeze_reason' => null,
        ]);

        $this->audit->log((int) $wallet->school_id, $actor, 'wallet.unfrozen', $wallet);
    }

    public function assertWalletCanTransact(CashlessWallet $wallet): void
    {
        if ($wallet->status !== 'active') {
            throw new InvalidArgumentException('Wallet tidak aktif untuk transaksi.');
        }
    }
}
