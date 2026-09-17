<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable;

    public const PERMISSIONS = [
        'products'       => 'المنتجات',
        'categories'     => 'التصنيفات الرئيسية',
        'sub_categories' => 'التصنيفات الفرعية',
        'brands'         => 'العلامات التجارية',
        'colors'         => 'الألوان',
        'size_guides'    => 'أدلة المقاسات',
        'orders'         => 'الطلبات',
        'coupons'        => 'كوبونات الخصم',
        'cities'         => 'المدن',
        'reviews'        => 'المراجعات',
        'addresses'      => 'العناوين',
        'pages'          => 'الصفحات الثابتة',
        'faqs'           => 'الأسئلة الشائعة',
        'settings'       => 'إعدادات الموقع',
        'users'          => 'المستخدمون',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'permissions'       => 'array',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'manager'], true);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        $primaryColor = ltrim(SiteSetting::current()->primary_color ?? '#C9A961', '#');

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name)
            . '&background=' . $primaryColor
            . '&color=fff'
            . '&size=128';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        if ($this->role !== 'manager') {
            return false;
        }

        return in_array($permission, $this->permissions ?? [], true);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return null;
    }

    public function getInitialAttribute(): string
    {
        return mb_substr($this->name, 0, 1, 'UTF-8');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function userNotifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadUserNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }
}