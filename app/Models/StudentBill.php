<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentBill extends Model
{
    use BelongsToTenant;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_POSTED = 'posted';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_PAID = 'paid';
    public const STATUS_OVERDUE = 'overdue';
    public const STATUS_VOID = 'void';

    protected $fillable = [
        'school_id',
        'student_id',
        'class_room_id',
        'invoice_number',
        'title',
        'description',
        'issued_date',
        'due_date',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'status',
        'created_by',
        'posted_by',
        'posted_at',
        'voided_by',
        'voided_at',
        'void_reason',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'integer',
        'paid_amount' => 'integer',
        'outstanding_amount' => 'integer',
        'posted_at' => 'datetime',
        'voided_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StudentBillItem::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(StudentPaymentAllocation::class);
    }
}
