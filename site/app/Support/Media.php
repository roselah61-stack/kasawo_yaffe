<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class Media
{
    /** Seeded images live in public/images; uploads live on the public disk. */
    public static function url(?string $path): string
    {
        if (! $path) {
            return asset('images/market.jpg');
        }
        if (str_starts_with($path, 'http') || str_starts_with($path, 'images/')) {
            return str_starts_with($path, 'http') ? $path : asset($path);
        }

        return Storage::disk('public')->url($path);
    }

    public static function ugx(?int $amount): string
    {
        return 'UGX '.number_format((int) $amount);
    }
}
