<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExecutiveReportSection extends Model
{
    protected $fillable = [
        'executive_report_run_id',
        'section_key',
        'title',
        'content',
        'metrics',
        'charts',
        'sort_order',
    ];

    protected $casts = [
        'metrics' => 'array',
        'charts' => 'array',
        'sort_order' => 'integer',
    ];

    public function run(): BelongsTo
    {
        return $this->belongsTo(ExecutiveReportRun::class, 'executive_report_run_id');
    }
}
