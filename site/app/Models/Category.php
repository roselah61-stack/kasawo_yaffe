<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'sort'];

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
