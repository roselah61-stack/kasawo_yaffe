<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['business_id', 'name', 'note', 'price', 'price_prefix', 'image'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function imageUrl(): string
    {
        return Media::url($this->image ?: $this->business?->image);
    }
}
