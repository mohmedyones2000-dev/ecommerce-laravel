<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdvancedStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '30s';

    public static function canView(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return $user->hasAnyPermission(['orders', 'products', 'users', 'reviews']);
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        if ($user->hasPermission('orders')) {
            $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
            $monthRevenue = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount');

            $stats[] = Stat::make('إجمالي المبيعات', '$' . number_format($totalRevenue, 2))
                ->description('$' . number_format($monthRevenue, 2) . ' هذا الشهر')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($this->getSalesChart());

            $totalOrders = Order::count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $todayOrders = Order::whereDate('created_at', today())->count();

            $stats[] = Stat::make('إجمالي الطلبات', number_format($totalOrders))
                ->description($pendingOrders . ' قيد الانتظار • ' . $todayOrders . ' اليوم')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color($pendingOrders > 0 ? 'warning' : 'info');
        }

        if ($user->hasPermission('users')) {
            $totalCustomers = User::where('role', 'customer')->count();
            $newCustomersThisMonth = User::where('role', 'customer')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $stats[] = Stat::make('العملاء', number_format($totalCustomers))
                ->description('+' . $newCustomersThisMonth . ' هذا الشهر')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info');
        }

        if ($user->hasPermission('products')) {
            $totalProducts = Product::count();
            $lowStockProducts = Product::whereHas('variants', function ($q) {
                $q->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5);
            })->count();
            $outOfStockProducts = Product::whereDoesntHave('variants', function ($q) {
                $q->where('stock_quantity', '>', 0);
            })->count();

            $stats[] = Stat::make('المنتجات', number_format($totalProducts))
                ->description($outOfStockProducts . ' نفذ • ' . $lowStockProducts . ' مخزون منخفض')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($outOfStockProducts > 0 ? 'danger' : ($lowStockProducts > 0 ? 'warning' : 'success'));
        }

        if ($user->hasPermission('reviews')) {
            $avgRating = Review::avg('rating') ?? 0;
            $totalReviews = Review::count();

            $stats[] = Stat::make('متوسط التقييم', number_format($avgRating, 1) . ' / 5')
                ->description($totalReviews . ' مراجعة')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning');
        }

        return $stats;
    }

    private function getSalesChart(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        return $data;
    }
}