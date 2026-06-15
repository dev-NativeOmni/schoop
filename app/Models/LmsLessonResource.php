<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LmsLessonResource extends Model
{
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'lesson_id',
        'uploaded_by',
        'title',
        'resource_type',
        'file_path',
        'external_url',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(LmsLesson::class, 'lesson_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
