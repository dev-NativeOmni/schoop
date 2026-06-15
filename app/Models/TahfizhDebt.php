<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TahfizhDebt extends Model
{
    use BelongsToTenant;

    public const PERIOD_DAILY = 'daily';
    public const PERIOD_WEEKLY = 'weekly';
    public const PERIOD_MONTHLY = 'monthly';

    public const STATUS_NO_TARGET = 'no_target';
    public const STATUS_MET = 'met';
    public const STATUS_BEHIND = 'behind';
    public const STATUS_AHEAD = 'ahead';

    protected $fillable = [
        'school_id',
        'class_room_id',
        'student_id',
        'tahfizh_target_id',
        'period_type',
        'calculation_date',
        'period_start',
        'period_end',
        'target_lines',
        'actual_lines',
        'debt_lines',
        'surplus_lines',
        'cumulative_debt_lines',
        'status',
        'calculated_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'calculation_date' => 'date',
            'period_start' => 'date',
            'period_end' => 'date',
            'target_lines' => 'integer',
            'actual_lines' => 'integer',
            'debt_lines' => 'integer',
            'surplus_lines' => 'integer',
            'cumulative_debt_lines' => 'integer',
        ];
    }

    public static function periodTypes(): array
    {
        return [
            self::PERIOD_DAILY,
            self::PERIOD_WEEKLY,
            self::PERIOD_MONTHLY,
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_NO_TARGET,
            self::STATUS_MET,
            self::STATUS_BEHIND,
            self::STATUS_AHEAD,
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function tahfizhTarget(): BelongsTo
    {
        return $this->belongsTo(TahfizhTarget::class);
    }

    public function calculator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }
}
