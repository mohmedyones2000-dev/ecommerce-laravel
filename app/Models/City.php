<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'shipping_cost',
        'is_free_shipping',
    ];

    protected $casts = [
        'shipping_cost'    => 'decimal:2',
        'is_free_shipping' => 'boolean',
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function hasFreeShipping(): bool
    {
        return $this->is_free_shipping || $this->shipping_cost <= 0;
    }
}