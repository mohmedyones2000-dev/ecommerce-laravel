<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeGuide extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function items()
    {
        return $this->hasMany(SizeGuideItem::class)->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}