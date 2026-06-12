<?php

namespace App\Services\Finance;

use App\Models\StudentBill;
use App\Models\StudentPayment;
use Carbon\Carbon;

class FinanceReportService
{
    public function dashboard(array $filters = []): array
    {
        $startDate = Carbon::parse($filters['start_date'] ?? now()->startOfMonth()->toDateString())->toDateString();
        $endDate = Carbon::parse($filters['end_date'] ?? now()->toDateString())->toDateString();

        $billQuery = StudentBill::query()
            ->with(['student.classRoom'])
            ->whereBetween('issued_date', [$startDate, $endDate]);

        $paymentQuery = StudentPayment::query()
            ->with(['student.classRoom'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', StudentPayment::STATUS_POSTED);

        if (! empty($filters['class_room_id'])) {
            $billQuery->where('class_room_id', $filters['class_room_id']);

            $paymentQuery->whereHas('student', function ($query) use ($filters): void {
                $query->where('class_room_id', $filters['class_room_id']);
            });
        }

        if (! empty($filters['student_id'])) {
            $billQuery->where('student_id', $filters['student_id']);
            $paymentQuery->where('student_id', $filters['student_id']);
        }

        $bills = $billQuery->get();
        $payments = $paymentQuery->get();

        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'summary' => [
                'total_billed' => (int) $bills->where('status', '!=', StudentBill::STATUS_VOID)->sum('total_amount'),
                'total_paid' => (int) $payments->sum('amount'),
                'total_outstanding' => (int) $bills->where('status', '!=', StudentBill::STATUS_VOID)->sum('outstanding_amount'),
                'bill_count' => $bills->count(),
                'payment_count' => $payments->count(),
                'unpaid_count' => $bills->whereIn('status', [
                    StudentBill::STATUS_POSTED,
                    StudentBill::STATUS_PARTIAL,
                    StudentBill::STATUS_OVERDUE,
                ])->count(),
            ],
            'bills' => $bills->sortByDesc('issued_date')->values(),
            'payments' => $payments->sortByDesc('payment_date')->values(),
        ];
    }
}
