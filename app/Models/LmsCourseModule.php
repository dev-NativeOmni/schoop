<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsCourseModule extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'course_id',
        'title',
        'description',
        'sort_order',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(LmsCourse::class, 'course_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(LmsLesson::class, 'module_id')->orderBy('sort_order');
    }
}
