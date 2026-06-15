<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MutabaahActivity extends Model
{
    use BelongsToTenant;

    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'mutabaah_category_id',
        'name',
        'slug',
        'description',
        'input_type',
        'target_score',
        'target_count',
        'target_unit',
        'is_required',
        'is_active',
        'allow_teacher_input',
        'allow_parent_input',
        'allow_student_input',
        'sort_order',
    ];

    protected $casts = [
        'target_score' => 'integer',
        'target_count' => 'integer',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'allow_teacher_input' => 'boolean',
        'allow_parent_input' => 'boolean',
        'allow_student_input' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MutabaahCategory::class, 'mutabaah_category_id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(MutabaahRecord::class, 'mutabaah_activity_id');
    }
}
