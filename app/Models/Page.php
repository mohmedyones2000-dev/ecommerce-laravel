<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'subtitle',
        'content',
        'meta_description',
        'is_active',
        'show_in_footer',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_footer' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true)->where('is_active', true);
    }
}