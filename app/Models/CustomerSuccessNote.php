<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerSuccessNote extends Model
{
    use BelongsToTenant;

    protected $fillable = ['school_id', 'user_id', 'note_type', 'content', 'next_follow_up_at', 'health_status'];

    protected function casts(): array
    {
        return ['next_follow_up_at' => 'date'];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
