<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUsageSnapshot extends Model
{
    use BelongsToTenant;

    protected $fillable = ['school_id', 'snapshot_date', 'metrics', 'health_score', 'health_status'];

    protected function casts(): array
    {
        return ['snapshot_date' => 'date', 'metrics' => 'array', 'health_score' => 'integer'];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
