<?php

namespace App\Providers\Filament;

use App\Models\SiteSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa() // (اختياري) تسريع التنقل داخل اللوحة
            ->colors([
                'primary' => Color::Teal,
                'danger'  => Color::Rose,
                'warning' => Color::Amber,
                'success' => Color::Emerald,
                'info'    => Color::Sky,
                'gray'    => Color::Slate,
            ])
            ->font('Cairo')
            ->brandName(fn () => SiteSetting::current()->site_name ?? 'متجري')
            ->brandLogo(fn () => SiteSetting::current()->logo
                ? asset('storage/' . SiteSetting::current()->logo)
                : null)
            ->brandLogoHeight('2.5rem')
            ->favicon(fn () => SiteSetting::current()->favicon
                ? asset('storage/' . SiteSetting::current()->favicon)
                : asset('favicon.svg'))
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->sidebarWidth('16rem') // (اختياري) عرض السايدبار
            ->maxContentWidth('full') // (اختياري) استغلال عرض الشاشة
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k']) // (اختياري) اختصار البحث
            ->userMenuItems([
                'back-to-site' => MenuItem::make()
                    ->label('العودة إلى المتجر')
                    ->url('/')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->openUrlInNewTab(),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): View => view('filament.hooks.theme-styles'),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): View => view('filament.hooks.topbar-actions'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\AdvancedStats::class,
                \App\Filament\Widgets\TopProducts::class,
                \App\Filament\Widgets\LatestOrders::class,
                \App\Filament\Widgets\LowStockAlert::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}