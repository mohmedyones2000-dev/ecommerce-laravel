<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

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


    // ============================================
// ✅ Scopes للبحث والفلترة
// ============================================

/**
 * ✅ بحث نصي في حقول متعددة
 */
public function scopeSearch($query, ?string $keyword)
{
    if (empty($keyword)) {
        return $query;
    }

    return $query->where(function ($q) use ($keyword) {
        $q->where('name', 'LIKE', "%{$keyword}%")
          ->orWhere('description', 'LIKE', "%{$keyword}%")
          ->orWhereHas('brand', function ($q2) use ($keyword) {
              $q2->where('name', 'LIKE', "%{$keyword}%");
          })
          ->orWhereHas('category', function ($q2) use ($keyword) {
              $q2->where('name', 'LIKE', "%{$keyword}%");
          });
    });
}

/**
 * ✅ فلترة حسب التصنيف (رئيسي أو فرعي)
 */
public function scopeCategory($query, ?int $categoryId)
{
    if (empty($categoryId)) {
        return $query;
    }

    return $query->where(function ($q) use ($categoryId) {
        $q->where('category_id', $categoryId)
          ->orWhere('sub_category_id', $categoryId);
    });
}

/**
 * ✅ فلترة حسب الماركة
 */
public function scopeBrand($query, ?int $brandId)
{
    if (empty($brandId)) {
        return $query;
    }

    return $query->where('brand_id', $brandId);
}

/**
 * ✅ فلترة حسب الجنس (رجالي، نسائي، أطفال)
 *    (باستخدام اسم التصنيف — لأن جدول products لا يحتوي على حقل gender)
 */
public function scopeGender($query, ?string $gender)
{
    if (empty($gender)) {
        return $query;
    }

    $categoryMap = [
        'men'   => 'ملابس رجالية',
        'women' => 'ملابس نسائية',
        'kids'  => 'ملابس أطفال',
    ];

    if (!isset($categoryMap[$gender])) {
        return $query;
    }

    return $query->whereHas('category', function ($q) use ($categoryMap, $gender) {
        $q->where('name', $categoryMap[$gender]);
    });
}

/**
 * ✅ فلترة حسب نطاق السعر
 */
public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
{
    return $query
        ->when($minPrice, fn($q) => $q->where('price', '>=', (float) $minPrice))
        ->when($maxPrice, fn($q) => $q->where('price', '<=', (float) $maxPrice));
}

/**
 * ✅ فلترة حسب حالة المخزون
 */
public function scopeStockStatus($query, ?string $status)
{
    if (empty($status)) {
        return $query;
    }

    return match ($status) {
        'in_stock'     => $query->whereHas('variants', fn($q) => $q->where('stock_quantity', '>', 0)),
        'low_stock'    => $query->whereHas('variants', fn($q) => $q->whereBetween('stock_quantity', [1, 5])),
        'out_of_stock' => $query->whereDoesntHave('variants', fn($q) => $q->where('stock_quantity', '>', 0)),
        default        => $query,
    };
}

/**
 * ✅ فلترة حسب وجود خصم
 */
public function scopeHasDiscount($query, bool $hasDiscount = false)
{
    if (!$hasDiscount) {
        return $query;
    }

    return $query->whereNotNull('discount_price')
                 ->where('discount_price', '>', 0);
}

/**
 * ✅ الترتيب
 */
public function scopeSort($query, ?string $sort)
{
    return match ($sort) {
        'oldest'     => $query->oldest(),
        'price_asc'  => $query->orderByRaw('COALESCE(discount_price, price) ASC'),
        'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) DESC'),
        'name_asc'   => $query->orderBy('name', 'asc'),
        'name_desc'  => $query->orderBy('name', 'desc'),
        'rating'     => $query->orderByDesc('rating_avg'),
        default      => $query->latest(),
    };
}}
