<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiDocumentationPage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug',
        'title',
        'category',
        'content',
        'visibility',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];
}
