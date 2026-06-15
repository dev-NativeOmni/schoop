<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaPolicy extends Model
{
    protected $fillable = ['priority', 'first_response_minutes', 'resolution_minutes', 'is_active', 'notes'];

    protected function casts(): array
    {
        return ['first_response_minutes' => 'integer', 'resolution_minutes' => 'integer', 'is_active' => 'boolean'];
    }
}
