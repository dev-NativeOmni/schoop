<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiScope extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'category',
        'is_sensitive',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_sensitive' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(ApiClient::class, 'api_client_scope')
            ->withPivot(['granted_by', 'granted_at'])
            ->withTimestamps();
    }
}
