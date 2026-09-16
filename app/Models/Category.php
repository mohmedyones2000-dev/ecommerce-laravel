<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];


    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'category_sub_category');
    }

    public function products() { return $this->hasMany(Product::class); }
}