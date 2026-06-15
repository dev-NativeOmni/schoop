<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StudentBill;
use App\Services\DeveloperPortal\ApiAccessService;
use App\Services\DeveloperPortal\ApiResponseFormatter;
use Illuminate\Http\Request;

class FinanceApiController extends Controller
{
    public function __construct(
        private readonly ApiAccessService $access,
        private readonly ApiResponseFormatter $response,
    ) {}

    public function bills(Request $request)
    {
        $schoolId = $this->access->externalSchoolId($request);

        return $this->response->ok([
            'bills' => StudentBill::query()
                ->where('school_id', $schoolId)
                ->when($request->filled('student_id'), fn ($query) => $query->where('student_id', $request->integer('student_id')))
                ->where('status', '!=', StudentBill::STATUS_VOID)
                ->latest('issued_date')
                ->limit(200)
                ->get()
                ->map(fn (StudentBill $bill): array => [
                    'student_id' => $bill->student_id,
                    'bill_number' => $bill->invoice_number,
                    'title' => $bill->title,
                    'issued_date' => $bill->issued_date?->toDateString(),
                    'due_date' => $bill->due_date?->toDateString(),
                    'total_amount' => (int) $bill->total_amount,
                    'paid_amount' => (int) $bill->paid_amount,
                    'outstanding_amount' => (int) $bill->outstanding_amount,
                    'status' => $bill->status,
                ])
                ->values(),
        ]);
    }
}
