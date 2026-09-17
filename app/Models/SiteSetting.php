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
        'primary_color',
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
                'primary_color'         => '#C9A961',
                'email'                 => 'info@matjari.com',
                'phone'                 => '+970 599 123 456',
                'address'               => 'فلسطين - غزة',
                'working_hours_weekday' => 'السبت - الخميس: 9ص - 6م',
                'working_hours_weekend' => 'الجمعة: مغلق',
            ]);
        }

        return static::$instance;
    }

    public function getPrimaryColorDarkAttribute(): string
    {
        return $this->darken($this->primary_color, 18);
    }

    public function getPrimaryColorSoftAttribute(): string
    {
        return $this->tint($this->primary_color, 0.88);
    }

    public function getPrimaryColorSoftDarkAttribute(): string
    {
        return $this->darken($this->primary_color, 78);
    }

    public function getPrimaryColorGlowAttribute(): string
    {
        return $this->hexToRgba($this->primary_color, 0.12);
    }

    protected function darken(string $hex, int $percent): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, (int) round($r - ($r * $percent / 100))));
        $g = max(0, min(255, (int) round($g - ($g * $percent / 100))));
        $b = max(0, min(255, (int) round($b - ($b * $percent / 100))));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    protected function tint(string $hex, float $ratio): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = (int) round($r + (255 - $r) * $ratio);
        $g = (int) round($g + (255 - $g) * $ratio);
        $b = (int) round($b + (255 - $b) * $ratio);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    protected function hexToRgba(string $hex, float $alpha): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "rgba({$r}, {$g}, {$b}, {$alpha})";
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::$instance = null);
        static::deleted(fn () => static::$instance = null);
    }
}