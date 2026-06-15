<?php

namespace App\Services\Cashless;

use App\Models\CashlessMerchant;
use App\Models\CashlessPosSession;
use App\Models\User;
use InvalidArgumentException;

class CashlessPosSessionService
{
    public function __construct(
        private readonly CashlessNumberGenerator $numbers,
        private readonly CashlessAccessService $access,
        private readonly CashlessAuditService $audit,
    ) {}

    public function open(CashlessMerchant $merchant, User $cashier, ?string $shiftName = null, ?string $note = null): CashlessPosSession
    {
        $this->access->assertMerchantAccess($cashier, $merchant);

        $session = CashlessPosSession::query()->create([
            'school_id' => $merchant->school_id,
            'cashless_merchant_id' => $merchant->id,
            'cashier_id' => $cashier->id,
            'session_number' => $this->numbers->transaction('SES'),
            'shift_name' => $shiftName,
            'opened_at' => now(),
            'opening_note' => $note,
            'status' => 'open',
        ]);

        $this->audit->log((int) $merchant->school_id, $cashier, 'pos_session.opened', $session);

        return $session;
    }

    public function close(CashlessPosSession $session, User $actor, ?string $note = null): void
    {
        $this->access->assertSameSchool($actor, (int) $session->school_id);

        if ($session->status !== 'open') {
            throw new InvalidArgumentException('Session POS sudah tidak open.');
        }

        $session->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closing_note' => $note,
        ]);

        $this->audit->log((int) $session->school_id, $actor, 'pos_session.closed', $session);
    }
}
