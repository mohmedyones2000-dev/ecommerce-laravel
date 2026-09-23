<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'hex_code',
        'size',
        'stock_quantity',
        'price',           // ← أضف هذا
    'discount_price',
        'product_id',
    ];
    protected $casts = [
    'price'          => 'decimal:2',
    'discount_price' => 'decimal:2',
];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= 5;
    }

    public function getDisplayNameAttribute(): string
    {
        $parts = array_filter([$this->color, $this->size]);

        return implode(' - ', $parts) ?: 'افتراضي';
    }
}