<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaasTenantInvoiceItem extends Model
{
    protected $fillable = ['saas_tenant_invoice_id', 'description', 'quantity', 'unit_price', 'amount'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'unit_price' => 'integer', 'amount' => 'integer'];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SaasTenantInvoice::class, 'saas_tenant_invoice_id');
    }
}
