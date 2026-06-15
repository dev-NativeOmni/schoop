<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolBrandProfile extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'display_name',
        'short_name',
        'tagline',
        'logo_path',
        'favicon_path',
        'login_background_path',
        'public_contact_email',
        'public_contact_phone',
        'public_address',
        'public_website_url',
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
