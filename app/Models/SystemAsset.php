<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemAsset extends Model
{
    protected $fillable = [
        'key',
        'mime_type',
        'size',
        'data',
    ];

    /**
     * Store or update asset from raw content.
     */
    public static function put(string $key, string $content, string $mimeType = 'image/png'): self
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'mime_type' => $mimeType,
                'size' => strlen($content),
                'data' => base64_encode($content),
            ]
        );
    }

    /**
     * Retrieve decoded raw content.
     */
    public function getRawContent(): ?string
    {
        return $this->data ? base64_decode($this->data) : null;
    }

    /**
     * Check if asset exists.
     */
    public static function has(string $key): bool
    {
        return static::where('key', $key)->exists();
    }

    /**
     * Generate cache-busted URL for an asset key.
     */
    public static function url(string $key): string
    {
        $asset = static::where('key', $key)->first();
        $v = $asset && $asset->updated_at ? $asset->updated_at->timestamp : time();

        return asset('storage/' . ltrim($key, '/')) . '?v=' . $v;
    }
}
