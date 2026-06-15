<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceLedgerEntry extends Model
{
    use BelongsToTenant;

    public const DIRECTION_DEBIT = 'debit';
    public const DIRECTION_CREDIT = 'credit';

    protected $fillable = [
        'school_id',
        'student_id',
        'entry_date',
        'direction',
        'amount',
        'source_type',
        'source_id',
        'description',
        'created_by',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'amount' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
