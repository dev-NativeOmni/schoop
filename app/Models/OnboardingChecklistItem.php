<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingChecklistItem extends Model
{
    protected $fillable = ['title', 'category', 'is_required', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_required' => 'boolean', 'is_active' => 'boolean'];
    }
}
