<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceFeeItem extends Model
{
    use BelongsToTenant;

    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'finance_fee_category_id',
        'name',
        'code',
        'description',
        'default_amount',
        'billing_cycle',
        'is_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'default_amount' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceFeeCategory::class, 'finance_fee_category_id');
    }
}
