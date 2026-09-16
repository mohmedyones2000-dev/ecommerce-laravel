<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['street_address', 'notes', 'phone', 'city_id', 'user_id'];

    public function user() { return $this->belongsTo(User::class); }
    public function city() { return $this->belongsTo(City::class); }
    public function orders() { return $this->hasMany(Order::class); }
}