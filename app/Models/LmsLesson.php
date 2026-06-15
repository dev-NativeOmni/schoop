<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsLesson extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'course_id',
        'module_id',
        'created_by',
        'title',
        'slug',
        'lesson_type',
        'content',
        'external_url',
        'embed_code',
        'estimated_minutes',
        'is_required',
        'visibility',
        'available_from',
        'available_until',
        'sort_order',
    ];

    protected $casts = [
        'estimated_minutes' => 'integer',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(LmsCourse::class, 'course_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(LmsCourseModule::class, 'module_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(LmsLessonResource::class, 'lesson_id')->orderBy('sort_order');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LmsLessonProgress::class, 'lesson_id');
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(LmsAssignment::class, 'lesson_id');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(LmsQuiz::class, 'lesson_id');
    }
}
