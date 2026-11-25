<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['title', 'detail', 'icon', 'is_urgent'];

    protected function casts(): array
    {
        return ['is_urgent' => 'boolean'];
    }
}
