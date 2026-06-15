<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutabaahRecord extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'school_id',
        'student_id',
        'mutabaah_activity_id',
        'record_date',
        'status',
        'score',
        'count_value',
        'text_value',
        'note',
        'submitted_by',
        'source',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'record_date' => 'date',
        'score' => 'integer',
        'count_value' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(MutabaahActivity::class, 'mutabaah_activity_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
