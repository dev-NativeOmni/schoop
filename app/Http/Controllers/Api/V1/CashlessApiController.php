<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CashlessWalletTransaction;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use Illuminate\Http\Request;

class CashlessApiController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiResponseFormatter $response,
    ) {}

    public function transactions(Request $request)
    {
        $schoolId = $this->access->externalSchoolId($request);

        return $this->response->ok([
            'transactions' => CashlessWalletTransaction::query()
                ->where('school_id', $schoolId)
                ->when($request->filled('student_id'), fn ($query) => $query->where('student_id', $request->integer('student_id')))
                ->latest('posted_at')
                ->limit(200)
                ->get()
                ->map(fn (CashlessWalletTransaction $transaction): array => [
                    'id' => $transaction->id,
                    'student_id' => $transaction->student_id,
                    'transaction_number' => $transaction->transaction_number,
                    'type' => $transaction->type,
                    'direction' => $transaction->direction,
                    'amount' => (int) $transaction->amount,
                    'status' => $transaction->status,
                    'posted_at' => $transaction->posted_at?->toIso8601String(),
                ])
                ->values(),
        ]);
    }
}
