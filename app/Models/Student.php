<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'class_room_id',
        'student_number',
        'nisn',
        'full_name',
        'nickname',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'program_type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentProfile::class, 'parent_student')
            ->withPivot(['relationship', 'is_primary'])
            ->withTimestamps();
    }

    public function tahfizhTargets(): HasMany
    {
        return $this->hasMany(TahfizhTarget::class);
    }

    public function hafalanRecords(): HasMany
    {
        return $this->hasMany(HafalanRecord::class);
    }

    public function tahfizhDebts(): HasMany
    {
        return $this->hasMany(TahfizhDebt::class);
    }
}
