<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MushafPage extends Model
{
    public const DEFAULT_TOTAL_LINES = 15;

    protected $fillable = [
        'page_number',
        'total_lines',
        'start_surah_id',
        'start_ayah',
        'end_surah_id',
        'end_ayah',
        'juz_id',
    ];

    protected function casts(): array
    {
        return [
            'page_number' => 'integer',
            'total_lines' => 'integer',
            'start_ayah' => 'integer',
            'end_ayah' => 'integer',
        ];
    }

    public function startSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'start_surah_id');
    }

    public function endSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'end_surah_id');
    }

    public function juz(): BelongsTo
    {
        return $this->belongsTo(QuranJuz::class, 'juz_id');
    }
}
