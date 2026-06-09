<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuranJuz extends Model
{
    protected $fillable = [
        'number',
        'name',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
        ];
    }

    public function startingSurahs(): HasMany
    {
        return $this->hasMany(QuranSurah::class, 'start_juz_id');
    }

    public function endingSurahs(): HasMany
    {
        return $this->hasMany(QuranSurah::class, 'end_juz_id');
    }

    public function mushafPages(): HasMany
    {
        return $this->hasMany(MushafPage::class, 'juz_id');
    }
}
