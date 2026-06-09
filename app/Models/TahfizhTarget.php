<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahfizhTarget extends Model
{
    protected $fillable = [
        'school_id',
        'class_room_id',
        'student_id',
        'name',
        'program_type',
        'daily_target_lines',
        'weekly_target_lines',
        'monthly_target_lines',
        'effective_from',
        'effective_until',
        'created_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'daily_target_lines' => 'integer',
            'weekly_target_lines' => 'integer',
            'monthly_target_lines' => 'integer',
            'effective_from' => 'date',
            'effective_until' => 'date',
            'is_active' => 'boolean',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function hafalanRecords(): HasMany
    {
        return $this->hasMany(HafalanRecord::class);
    }
}
