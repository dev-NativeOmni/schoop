<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBaseArticle extends Model
{
    protected $fillable = ['title', 'slug', 'category', 'visibility', 'body', 'status', 'published_at', 'created_by'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }
}
