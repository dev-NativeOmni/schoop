<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuranSurah extends Model
{
    protected $fillable = [
        'number',
        'name_latin',
        'name_arabic',
        'meaning',
        'total_ayah',
        'revelation_place',
        'start_juz_id',
        'end_juz_id',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'total_ayah' => 'integer',
        ];
    }

    public function startJuz(): BelongsTo
    {
        return $this->belongsTo(QuranJuz::class, 'start_juz_id');
    }

    public function endJuz(): BelongsTo
    {
        return $this->belongsTo(QuranJuz::class, 'end_juz_id');
    }

    public function startingPages(): HasMany
    {
        return $this->hasMany(MushafPage::class, 'start_surah_id');
    }

    public function endingPages(): HasMany
    {
        return $this->hasMany(MushafPage::class, 'end_surah_id');
    }

    public function hafalanRecordsStartingHere(): HasMany
    {
        return $this->hasMany(HafalanRecord::class, 'start_surah_id');
    }

    public function hafalanRecordsEndingHere(): HasMany
    {
        return $this->hasMany(HafalanRecord::class, 'end_surah_id');
    }
}
