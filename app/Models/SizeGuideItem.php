<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeGuideItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'size_guide_id',
        'size',
        'chest',
        'waist',
        'hips',
        'length',
        'sort_order',
    ];

    public function sizeGuide()
    {
        return $this->belongsTo(SizeGuide::class);
    }
}