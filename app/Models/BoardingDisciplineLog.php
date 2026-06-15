<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardingDisciplineLog extends Model
{
    use BelongsToTenant;

    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'recorded_by_user_id',
        'type',
        'category',
        'description',
        'points',
        'action_taken',
        'logged_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'logged_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
}
