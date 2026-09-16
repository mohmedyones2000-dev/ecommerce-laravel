<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckResourcePermission
{
    protected array $map = [
        'products' => 'products',
        'categories' => 'categories',
        'sub-categories' => 'sub_categories',
        'brands' => 'brands',
        'colors' => 'colors',
        'size-guides' => 'size_guides',
        'orders' => 'orders',
        'coupons' => 'coupons',
        'cities' => 'cities',
        'reviews' => 'reviews',
        'addresses' => 'addresses',
        'pages' => 'pages',
        'faqs' => 'faqs',
        'users' => 'users',
        'newsletter-subscribers' => 'users',
        'manage-settings' => 'settings',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        // المدير العام → كل الصلاحيات
        if ($user->role === 'admin') {
            return $next($request);
        }

        // غير المدير → رفض
        if ($user->role !== 'manager') {
            abort(403);
        }

        $path = $request->path();

        // الصفحات العامة (متاحة لأي مستخدم مسجل)
        if ($path === 'admin'
            || $path === 'admin/logout'
            || str_starts_with($path, 'admin/profile')
            || str_starts_with($path, 'livewire')
            || str_starts_with($path, 'admin/storage')
        ) {
            return $next($request);
        }

        // استخرج اسم المورد من المسار
        if (preg_match('#^admin/([a-z0-9\-]+)#', $path, $matches)) {
            $resourceSlug = $matches[1];

            if (isset($this->map[$resourceSlug])) {
                $requiredPermission = $this->map[$resourceSlug];

                if (!$user->hasPermission($requiredPermission)) {
                    abort(403, 'ليس لديك صلاحية الوصول إلى هذا القسم.');
                }
            }
        }

        return $next($request);
    }
}