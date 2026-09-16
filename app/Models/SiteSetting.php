<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected static ?self $instance = null;

    protected $fillable = [
        'site_name',
        'favicon',
        'logo',
        'email',
        'phone',
        'address',
        'whatsapp',
        'facebook',
        'instagram',
        'twitter',
        'working_hours_weekday',
        'working_hours_weekend',
    ];

    public static function current(): self
    {
        if (static::$instance === null) {
            static::$instance = static::firstOrCreate([], [
                'site_name'             => 'متجري',
                'email'                 => 'info@matjari.com',
                'phone'                 => '+970 599 123 456',
                'address'               => 'فلسطين - غزة',
                'working_hours_weekday' => 'السبت - الخميس: 9ص - 6م',
                'working_hours_weekend' => 'الجمعة: مغلق',
            ]);
        }

        return static::$instance;
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::$instance = null);
        static::deleted(fn () => static::$instance = null);
    }
}