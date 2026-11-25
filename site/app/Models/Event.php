<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'starts_at', 'location', 'description'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime'];
    }
}
