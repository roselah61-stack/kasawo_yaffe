<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    protected $fillable = [
        'reference', 'donor_type', 'name', 'company_name', 'email', 'phone', 'amount', 'currency',
        'frequency', 'fund', 'method', 'message', 'is_anonymous', 'status', 'confirmed_at',
    ];

    protected function casts(): array
    {
        return ['is_anonymous' => 'boolean', 'confirmed_at' => 'datetime', 'amount' => 'integer'];
    }

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    protected static function booted(): void
    {
        static::creating(function (Donation $d) {
            $d->reference ??= 'KY-'.strtoupper(Str::random(8));
        });
    }

    public function scopeCounted(Builder $q): Builder
    {
        return $q->whereIn('status', ['confirmed']);
    }

    public function displayName(): string
    {
        if ($this->is_anonymous) {
            return 'A friend of Kasawo';
        }

        return $this->donor_type === 'company' && $this->company_name ? $this->company_name : $this->name;
    }

    public function fundLabel(): string
    {
        return config("kasawo.donations.funds.{$this->fund}.title", 'Where it’s needed most');
    }

    public function methodLabel(): string
    {
        return config("kasawo.donations.methods.{$this->method}.label", $this->method);
    }
}
