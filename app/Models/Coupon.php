<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'free_shipping',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at'    => 'date',
        'is_active'     => 'boolean',
        'free_shipping' => 'boolean',
    ];

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $total): float
    {
        if ($total < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($total * $this->value) / 100;
        }

        return min($this->value, $total);
    }
}