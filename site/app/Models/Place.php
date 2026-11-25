<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'summary', 'description', 'image', 'duration', 'meta', 'is_featured', 'sort'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean'];
    }

    public function imageUrl(): string
    {
        return Media::url($this->image);
    }
}
