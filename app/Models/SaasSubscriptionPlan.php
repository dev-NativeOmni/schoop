<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasSubscriptionPlan extends Model
{
    protected $fillable = ['code', 'name', 'billing_cycle', 'monthly_price', 'yearly_price', 'features', 'status', 'notes'];

    protected function casts(): array
    {
        return ['features' => 'array', 'monthly_price' => 'integer', 'yearly_price' => 'integer'];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SaasSchoolSubscription::class);
    }
}
