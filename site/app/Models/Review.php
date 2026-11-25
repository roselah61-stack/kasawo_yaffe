<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['business_id', 'author', 'rating', 'body'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->author))->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->implode('');
    }
}
