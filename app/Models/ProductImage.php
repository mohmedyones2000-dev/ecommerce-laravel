<?php

namespace App\Models;

use App\Services\FileUploadService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'is_primary',
        'color',
        'sort_order',
        'product_id',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeForColor($query, ?string $color)
    {
        return $query->where('color', $color);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * ✅ URL كامل للصورة
     */
    public function getImageUrlAttribute(): ?string
    {
        return FileUploadService::url($this->image_path);
    }

    /**
     * ✅ حذف الملف تلقائياً عند حذف السجل
     */
    protected static function booted(): void
    {
        static::deleting(function (ProductImage $image) {
            FileUploadService::delete($image->image_path);
        });
    }
}