<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsCourse extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'created_by',
        'updated_by',
        'title',
        'slug',
        'course_code',
        'type',
        'description',
        'cover_image_path',
        'level',
        'visibility',
        'enrollment_mode',
        'start_date',
        'end_date',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(LmsCourseModule::class, 'course_id')->orderBy('sort_order');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(LmsLesson::class, 'course_id')->orderBy('sort_order');
    }

    public function instructors(): BelongsToMany
    {
        return $this->belongsToMany(TeacherProfile::class, 'lms_course_instructors', 'course_id', 'teacher_profile_id')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(LmsCourseEnrollment::class, 'course_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'lms_course_enrollments', 'course_id', 'student_id')
            ->withPivot('progress_percentage', 'status', 'enrolled_at', 'completed_at')
            ->withTimestamps();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(LmsAssignment::class, 'course_id');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(LmsQuiz::class, 'course_id');
    }
}
