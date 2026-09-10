<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HafalanRecord extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    public const STATUS_LUNAS = 'lunas';

    public const STATUS_KURANG = 'kurang';

    public const STATUS_LEBIH = 'lebih';

    public const STATUS_TIDAK_HADIR = 'tidak_hadir';

    public const STATUS_IZIN = 'izin';

    public const STATUS_SAKIT = 'sakit';

    protected $fillable = [
        'school_id',
        'student_id',
        'teacher_id',
        'tahfizh_target_id',
        'record_date',
        'start_surah_id',
        'start_ayah',
        'end_surah_id',
        'end_ayah',
        'start_page',
        'start_line',
        'end_page',
        'end_line',
        'total_lines',
        'status',
        'quality_score',
        'notes',
        'is_sequence_valid',
        'sequence_note',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'start_ayah' => 'integer',
            'end_ayah' => 'integer',
            'start_page' => 'integer',
            'start_line' => 'integer',
            'end_page' => 'integer',
            'end_line' => 'integer',
            'total_lines' => 'integer',
            'quality_score' => 'integer',
            'is_sequence_valid' => 'boolean',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_LUNAS,
            self::STATUS_KURANG,
            self::STATUS_LEBIH,
            self::STATUS_TIDAK_HADIR,
            self::STATUS_IZIN,
            self::STATUS_SAKIT,
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function tahfizhTarget(): BelongsTo
    {
        return $this->belongsTo(TahfizhTarget::class);
    }

    public function startSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'start_surah_id');
    }

    public function endSurah(): BelongsTo
    {
        return $this->belongsTo(QuranSurah::class, 'end_surah_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
