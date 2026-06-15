<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolPwaSetting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'app_name',
        'short_name',
        'theme_color',
        'background_color',
        'icon_192_path',
        'icon_512_path',
        'start_url',
        'display_mode',
        'is_enabled',
        'updated_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
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
