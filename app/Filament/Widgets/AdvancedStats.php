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

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyPermission(['orders', 'products', 'users', 'reviews']);
    }

    protected function getStats(): array
    {
        $user = auth()->user();
        $stats = [];

        if ($user->hasPermission('orders')) {
            $stats[] = $this->revenueStat();
            $stats[] = $this->ordersStat();
        }

        if ($user->hasPermission('users')) {
            $stats[] = $this->customersStat();
        }

        if ($user->hasPermission('products')) {
            $stats[] = $this->productsStat();
        }

        if ($user->hasPermission('reviews')) {
            $stats[] = $this->ratingStat();
        }

        return $stats;
    }

    protected function revenueStat(): Stat
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        $monthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_amount');

        $percentage = $lastMonthRevenue > 0
            ? round((($monthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $description = $percentage >= 0
            ? '+' . $percentage . '% مقارنة بالشهر الماضي'
            : $percentage . '% مقارنة بالشهر الماضي';

        return Stat::make('إجمالي المبيعات', '$' . number_format($totalRevenue, 2))
            ->description($description)
            ->descriptionIcon($percentage >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
            ->color($percentage >= 0 ? 'success' : 'danger')
            ->icon('heroicon-o-banknotes')
            ->chart($this->getSalesChart());
    }

    protected function ordersStat(): Stat
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayOrders = Order::whereDate('created_at', today())->count();

        $description = $pendingOrders . ' قيد الانتظار • ' . $todayOrders . ' اليوم';

        return Stat::make('إجمالي الطلبات', number_format($totalOrders))
            ->description($description)
            ->descriptionIcon('heroicon-m-shopping-cart')
            ->color($pendingOrders > 5 ? 'warning' : 'info')
            ->icon('heroicon-o-clipboard-document-list')
            ->chart($this->getOrdersChart());
    }

    protected function customersStat(): Stat
    {
        $totalCustomers = User::where('role', 'customer')->count();

        $newCustomersThisMonth = User::where('role', 'customer')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return Stat::make('العملاء', number_format($totalCustomers))
            ->description('+' . $newCustomersThisMonth . ' هذا الشهر')
            ->descriptionIcon('heroicon-m-user-plus')
            ->color('info')
            ->icon('heroicon-o-users')
            ->chart($this->getCustomersChart());
    }

    protected function productsStat(): Stat
    {
        $totalProducts = Product::count();

        $lowStockProducts = Product::whereHas('variants', function ($q) {
            $q->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5);
        })->count();

        $outOfStockProducts = Product::whereDoesntHave('variants', function ($q) {
            $q->where('stock_quantity', '>', 0);
        })->count();

        $color = $outOfStockProducts > 0
            ? 'danger'
            : ($lowStockProducts > 0 ? 'warning' : 'success');

        return Stat::make('المنتجات', number_format($totalProducts))
            ->description($outOfStockProducts . ' نفذ • ' . $lowStockProducts . ' مخزون منخفض')
            ->descriptionIcon('heroicon-m-cube')
            ->color($color)
            ->icon('heroicon-o-squares-2x2')
            ->chart($this->getProductsChart());
    }

    protected function ratingStat(): Stat
    {
        $avgRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();

        return Stat::make('متوسط التقييم', number_format($avgRating, 1) . ' / 5')
            ->description(number_format($totalReviews) . ' مراجعة')
            ->descriptionIcon('heroicon-m-star')
            ->color('warning')
            ->icon('heroicon-o-star')
            ->chart($this->getRatingChart());
    }

    protected function getSalesChart(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = (float) Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
        }

        return $data;
    }

    protected function getOrdersChart(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = Order::whereDate('created_at', $date)->count();
        }

        return $data;
    }

    protected function getCustomersChart(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = User::where('role', 'customer')
                ->whereDate('created_at', $date)
                ->count();
        }

        return $data;
    }

    protected function getProductsChart(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = Product::whereDate('created_at', $date)->count();
        }

        return $data;
    }

    protected function getRatingChart(): array
    {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = (float) (Review::whereDate('created_at', $date)->avg('rating') ?? 0);
        }

        return $data;
    }
}