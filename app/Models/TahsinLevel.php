<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahsinLevel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'name',
        'slug',
        'description',
        'minimum_score',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'minimum_score' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function skills(): HasMany
    {
        return $this->hasMany(TahsinSkill::class);
    }

    public function studentProfiles(): HasMany
    {
        return $this->hasMany(TahsinStudentProfile::class, 'current_tahsin_level_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(TahsinAssessment::class);
    }
}
