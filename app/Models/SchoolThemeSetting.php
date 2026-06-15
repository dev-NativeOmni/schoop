<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolThemeSetting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'theme_name',
        'primary_color',
        'secondary_color',
        'accent_color',
        'text_color',
        'background_color',
        'sidebar_style',
        'header_style',
        'login_layout',
        'card_radius',
        'button_radius',
        'is_active',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
