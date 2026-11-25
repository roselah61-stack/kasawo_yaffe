<?php

namespace App\Models;

use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'tagline', 'description', 'type', 'owner_name',
        'phone', 'whatsapp', 'email', 'location', 'image', 'gallery', 'opening_hours',
        'payment_methods', 'delivery', 'rating', 'reviews_count', 'is_verified', 'is_featured', 'status', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'opening_hours' => 'array',
            'is_verified' => 'boolean',
            'is_featured' => 'boolean',
            'rating' => 'float',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', 'approved');
    }

    public function imageUrl(): string
    {
        return Media::url($this->image);
    }

    public function initial(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }

    /** Digits-only WhatsApp link, or null. */
    public function whatsappUrl(): ?string
    {
        $n = preg_replace('/\D+/', '', (string) ($this->whatsapp ?: $this->phone));

        return $n ? 'https://wa.me/'.$n.'?text='.rawurlencode("Hello {$this->name}, I found you on Kasawo Yaffe.") : null;
    }

    public function telUrl(): ?string
    {
        return $this->phone ? 'tel:'.preg_replace('/[^\d+]/', '', $this->phone) : null;
    }
}
