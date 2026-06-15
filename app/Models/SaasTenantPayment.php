<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaasTenantPayment extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id', 'saas_tenant_invoice_id', 'payment_number', 'amount', 'payment_date', 'method',
        'reference', 'status', 'received_by', 'voided_at', 'voided_by', 'void_reason', 'note',
    ];

    protected function casts(): array
    {
        return ['amount' => 'integer', 'payment_date' => 'date', 'voided_at' => 'datetime'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SaasTenantInvoice::class, 'saas_tenant_invoice_id');
    }
}
