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
use Filament\Support\Colors\Color as SupportColor;
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
            ->colors([
                'primary' => $this->getPrimaryColor(),
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
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->userMenuItems([
                'back-to-site' => MenuItem::make()
                    ->label('العودة إلى المتجر')
                    ->url('/')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->openUrlInNewTab(),
            ])
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

    protected function getPrimaryColor(): array|string
    {
        try {
            $hex = SiteSetting::current()->primary_color ?? '#C9A961';

            return $this->generateColorPalette($hex);
        } catch (\Throwable $e) {
            return SupportColor::Amber;
        }
    }

    protected function generateColorPalette(string $hex): array
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $shades = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
        $palette = [];

        foreach ($shades as $shade) {
            if ($shade === 500) {
                $palette[$shade] = "rgb({$r}, {$g}, {$b})";
                continue;
            }

            if ($shade < 500) {
                $ratio = (500 - $shade) / 500 * 0.9;
                $nr = (int) round($r + (255 - $r) * $ratio);
                $ng = (int) round($g + (255 - $g) * $ratio);
                $nb = (int) round($b + (255 - $b) * $ratio);
            } else {
                $ratio = ($shade - 500) / 500 * 0.7;
                $nr = (int) round($r * (1 - $ratio));
                $ng = (int) round($g * (1 - $ratio));
                $nb = (int) round($b * (1 - $ratio));
            }

            $palette[$shade] = "rgb({$nr}, {$ng}, {$nb})";
        }

        return $palette;
    }
}