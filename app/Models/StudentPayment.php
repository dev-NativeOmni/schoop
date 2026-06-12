<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentPayment extends Model
{
    public const METHOD_CASH = 'cash';
    public const METHOD_BANK_TRANSFER = 'bank_transfer';
    public const METHOD_QRIS_EXTERNAL = 'qris_external';
    public const METHOD_ADJUSTMENT = 'adjustment';

    public const STATUS_POSTED = 'posted';
    public const STATUS_VOID = 'void';

    protected $fillable = [
        'school_id',
        'student_id',
        'receipt_number',
        'payment_date',
        'payment_method',
        'reference_number',
        'amount',
        'note',
        'status',
        'received_by',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'integer',
        'voided_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StudentPaymentAllocation::class);
    }
}
