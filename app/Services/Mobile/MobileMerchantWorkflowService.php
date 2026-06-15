<?php

namespace App\Services\Mobile;

use App\Models\CashlessMerchant;
use App\Models\CashlessMerchantUser;
use App\Models\CashlessPosSession;
use App\Models\CashlessProduct;
use App\Models\CashlessSale;
use App\Models\CashlessSettlement;
use App\Models\Student;
use App\Models\User;
use App\Services\Cashless\CashlessAccessService;
use App\Services\Cashless\CashlessPosSessionService;
use App\Services\Cashless\CashlessRefundService;
use App\Services\Cashless\CashlessSaleService;
use Illuminate\Validation\ValidationException;

class MobileMerchantWorkflowService
{
    public function __construct(
        private readonly MobileAccessService $access,
        private readonly CashlessAccessService $cashlessAccess,
        private readonly CashlessPosSessionService $sessions,
        private readonly CashlessSaleService $sales,
        private readonly CashlessRefundService $refunds,
    ) {}

    public function profile(User $user): array
    {
        $schoolId = $this->access->activeSchoolId();

        $merchants = $this->merchantQuery($user, $schoolId)
            ->withCount(['products', 'sales'])
            ->orderBy('name')
            ->get();

        return [
            'role' => $user->role?->name,
            'merchants' => $merchants,
        ];
    }

    public function products(User $user, ?int $merchantId = null)
    {
        $schoolId = $this->access->activeSchoolId();
        $merchantIds = $this->merchantQuery($user, $schoolId)
            ->when($merchantId, fn ($query) => $query->whereKey($merchantId))
            ->pluck('id');

        return CashlessProduct::query()
            ->where('school_id', $schoolId)
            ->whereIn('cashless_merchant_id', $merchantIds)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function openSession(User $user, array $payload): CashlessPosSession
    {
        $merchant = CashlessMerchant::query()->findOrFail($payload['cashless_merchant_id']);
        $this->access->ensureMerchantAccess($user, $merchant);

        return $this->sessions->open(
            merchant: $merchant,
            cashier: $user,
            shiftName: $payload['shift_name'] ?? null,
            note: $payload['opening_note'] ?? null,
        );
    }

    public function closeSession(User $user, CashlessPosSession $session, ?string $note = null): CashlessPosSession
    {
        $this->cashlessAccess->assertSameSchool($user, (int) $session->school_id);
        $this->sessions->close($session, $user, $note);

        return $session->fresh(['merchant']);
    }

    public function previewSale(User $user, array $payload): array
    {
        $session = CashlessPosSession::query()->with('merchant')->findOrFail($payload['cashless_pos_session_id']);
        $this->cashlessAccess->assertMerchantAccess($user, $session->merchant);

        $student = Student::query()->findOrFail($payload['student_id']);
        $this->cashlessAccess->assertStudentInSchool($student, (int) $session->school_id);

        $items = collect($payload['items'])->map(function (array $item) use ($session): array {
            $product = CashlessProduct::query()
                ->where('school_id', $session->school_id)
                ->where('cashless_merchant_id', $session->cashless_merchant_id)
                ->where('status', 'active')
                ->findOrFail($item['cashless_product_id']);

            $quantity = (int) $item['quantity'];

            return [
                'product_id' => $product->id,
                'name' => $product->name,
                'unit_price' => (int) $product->price,
                'quantity' => $quantity,
                'subtotal' => (int) $product->price * $quantity,
            ];
        })->values();

        return [
            'student' => [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'student_number' => $student->student_number,
            ],
            'items' => $items,
            'total_amount' => (int) $items->sum('subtotal'),
            'server_authoritative' => true,
        ];
    }

    public function checkout(User $user, array $payload): CashlessSale
    {
        return $this->sales->createSale($payload, $user);
    }

    public function voidSale(User $user, CashlessSale $sale, string $reason)
    {
        if (! $user->hasRole(['super_admin', 'admin', 'finance'])) {
            $canRefund = CashlessMerchantUser::query()
                ->where('cashless_merchant_id', $sale->cashless_merchant_id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->where('can_refund', true)
                ->exists();

            if (! $canRefund) {
                throw ValidationException::withMessages([
                    'sale_id' => 'Kasir tidak memiliki izin void/refund.',
                ]);
            }
        }

        return $this->refunds->voidSale($sale, $user, $reason);
    }

    public function sales(User $user)
    {
        $schoolId = $this->access->activeSchoolId();
        $merchantIds = $this->merchantQuery($user, $schoolId)->pluck('id');

        return CashlessSale::query()
            ->with(['merchant', 'student', 'items'])
            ->where('school_id', $schoolId)
            ->whereIn('cashless_merchant_id', $merchantIds)
            ->latest('posted_at')
            ->latest('id')
            ->limit(100)
            ->get();
    }

    public function settlements(User $user)
    {
        $schoolId = $this->access->activeSchoolId();
        $merchantIds = $this->merchantQuery($user, $schoolId)->pluck('id');

        return CashlessSettlement::query()
            ->with('merchant')
            ->where('school_id', $schoolId)
            ->whereIn('cashless_merchant_id', $merchantIds)
            ->latest('period_end')
            ->limit(50)
            ->get();
    }

    private function merchantQuery(User $user, int $schoolId)
    {
        $query = CashlessMerchant::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active');

        if ($user->hasRole(['super_admin', 'admin', 'finance'])) {
            return $query;
        }

        return $query->whereHas('users', function ($merchantUser) use ($user): void {
            $merchantUser->where('user_id', $user->id)
                ->where('is_active', true);
        });
    }
}
