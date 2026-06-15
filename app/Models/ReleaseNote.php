<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReleaseNote extends Model
{
    protected $fillable = ['version', 'title', 'summary', 'body', 'status', 'published_at', 'created_by'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }
}
