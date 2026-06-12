<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentBillItem extends Model
{
    protected $fillable = [
        'student_bill_id',
        'finance_fee_item_id',
        'name',
        'description',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_amount' => 'integer',
        'total_amount' => 'integer',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(StudentBill::class, 'student_bill_id');
    }

    public function feeItem(): BelongsTo
    {
        return $this->belongsTo(FinanceFeeItem::class, 'finance_fee_item_id');
    }
}
