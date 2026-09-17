<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'is_active',
        'is_featured',
        'category_id',
        'sub_category_id',
        'brand_id',
        'size_guide_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function sizeGuide()
    {
        return $this->belongsTo(SizeGuide::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            ProductVariant::class,
            'product_id',
            'product_variant_id',
            'id',
            'id'
        );
    }

    public function getFirstColorAttribute(): ?string
    {
        return $this->variants
            ->pluck('color')
            ->filter()
            ->first();
    }

    public function getPrimaryImagesAttribute()
    {
        $allImages = $this->images->sortBy('sort_order')->values();

        $color = $this->first_color;

        if ($color) {
            $colorImages = $this->images
                ->where('color', $color)
                ->sortBy('sort_order')
                ->values();

            if ($colorImages->isNotEmpty()) {
                return $colorImages;
            }
        }

        $coloredImages = $this->images
            ->whereNotNull('color')
            ->sortBy('sort_order')
            ->values();

        if ($coloredImages->isNotEmpty()) {
            return $coloredImages;
        }

        return $allImages;
    }
}