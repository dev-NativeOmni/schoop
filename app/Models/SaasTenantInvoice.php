<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasTenantInvoice extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'saas_school_subscription_id', 'invoice_number', 'status', 'issued_at', 'due_date',
        'subtotal_amount', 'total_amount', 'paid_amount', 'balance_amount', 'note', 'voided_at',
        'voided_by', 'void_reason',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'due_date' => 'date',
            'subtotal_amount' => 'integer',
            'total_amount' => 'integer',
            'paid_amount' => 'integer',
            'balance_amount' => 'integer',
            'voided_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(SaasSchoolSubscription::class, 'saas_school_subscription_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaasTenantInvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SaasTenantPayment::class);
    }
}
