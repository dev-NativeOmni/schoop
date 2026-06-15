<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolFinanceSnapshot extends Model
{
    protected $fillable = [
        'school_id',
        'snapshot_date',
        'period_type',
        'student_bills_total',
        'student_payments_total',
        'student_outstanding_total',
        'overdue_bills_count',
        'void_bills_count',
        'void_payments_count',
        'cashless_topup_total',
        'cashless_purchase_total',
        'cashless_refund_total',
        'cashless_void_count',
        'cashless_negative_balance_anomaly_count',
        'cashless_pending_settlement_count',
        'raw_metrics',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'student_bills_total' => 'decimal:2',
        'student_payments_total' => 'decimal:2',
        'student_outstanding_total' => 'decimal:2',
        'overdue_bills_count' => 'integer',
        'void_bills_count' => 'integer',
        'void_payments_count' => 'integer',
        'cashless_topup_total' => 'decimal:2',
        'cashless_purchase_total' => 'decimal:2',
        'cashless_refund_total' => 'decimal:2',
        'cashless_void_count' => 'integer',
        'cashless_negative_balance_anomaly_count' => 'integer',
        'cashless_pending_settlement_count' => 'integer',
        'raw_metrics' => 'array',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
