<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'slug', 'topic', 'excerpt', 'body', 'image', 'author', 'read_minutes', 'is_featured', 'published_at'];

    protected function casts(): array
    {
        return ['is_featured' => 'boolean', 'published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function imageUrl(): string
    {
        return Media::url($this->image);
    }

    public function topicClass(): string
    {
        return in_array($this->topic, ['Farming', 'Health']) ? 'green' : '';
    }
}
