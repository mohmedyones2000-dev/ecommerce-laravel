<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
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
            ->authGuard('web')
            ->brandName('متجري')
            ->favicon(asset('favicon.svg'))
            ->font('Cairo')
            ->colors([
                'primary' => [
                    50 => '#FBF8F0',
                    100 => '#F5EFD9',
                    200 => '#EBDDB3',
                    300 => '#DEC78A',
                    400 => '#D3B673',
                    500 => '#C9A961',
                    600 => '#B4944F',
                    700 => '#967A3E',
                    800 => '#786230',
                    900 => '#5E4E26',
                    950 => '#38301A',
                ],
                'gray' => Color::Zinc,
                'danger' => Color::Red,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'info' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->userMenuItems([
                'storefront' => MenuItem::make()
                    ->label('العودة إلى الموقع')
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt'),
            ])
            ->navigationItems([
                NavigationItem::make('العودة إلى الموقع')
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->group('روابط سريعة')
                    ->sort(1),
            ])
            ->renderHook(
                PanelsRenderHook::STYLES_AFTER,
                fn () => $this->renderCustomStyles()
            )
            ->renderHook(
                PanelsRenderHook::SCRIPTS_AFTER,
                fn () => $this->renderSidebarHidingScript()
            )
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
                \App\Http\Middleware\CheckResourcePermission::class,
            ]);
    }

    protected function renderCustomStyles(): string
    {
        return '<style>
            :root {
                --gold: #C9A961;
                --gold-dark: #B4944F;
            }

            .fi-sidebar {
                border-inline-start: 1px solid rgba(0, 0, 0, 0.05);
            }

            .dark .fi-sidebar {
                border-inline-start: 1px solid rgba(255, 255, 255, 0.05);
            }

            .fi-sidebar-header {
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .dark .fi-sidebar-header {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .fi-topbar {
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .dark .fi-topbar {
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .fi-sidebar-item-label,
            .fi-sidebar-group-label {
                font-weight: 500;
            }

            .fi-sidebar-item-active {
                background-color: rgba(201, 169, 97, 0.1) !important;
            }

            .dark .fi-sidebar-item-active {
                background-color: rgba(201, 169, 97, 0.15) !important;
            }

            .fi-sidebar-item-active .fi-sidebar-item-label {
                color: var(--gold) !important;
                font-weight: 600;
            }

            .fi-sidebar-item-icon {
                width: 1.125rem;
                height: 1.125rem;
            }

            .fi-btn {
                font-weight: 600;
                border-radius: 0.5rem;
            }

            .fi-input {
                border-radius: 0.5rem;
            }

            .fi-section {
                border-radius: 0.75rem;
            }

            .fi-ta {
                border-radius: 0.75rem;
            }

            .fi-wi-stats-overview-stat {
                border-radius: 0.75rem;
                border: 1px solid rgba(0, 0, 0, 0.06);
                box-shadow: none;
            }

            .dark .fi-wi-stats-overview-stat {
                border: 1px solid rgba(255, 255, 255, 0.06);
            }

            .fi-wi-stats-overview-stat-label {
                font-size: 0.8125rem;
                font-weight: 500;
            }

            .fi-wi-stats-overview-stat-value {
                font-weight: 700;
                letter-spacing: -0.02em;
            }

            .fi-header-heading {
                font-weight: 700;
                letter-spacing: -0.02em;
            }

            .fi-logo {
                font-weight: 700;
                letter-spacing: -0.02em;
            }

            .fi-sidebar-group-label {
                font-size: 0.6875rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: rgba(0, 0, 0, 0.4);
            }

            .dark .fi-sidebar-group-label {
                color: rgba(255, 255, 255, 0.4);
            }
        </style>';
    }

    protected function renderSidebarHidingScript(): string
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'manager') {
            return '';
        }

        $mapping = [
            'products' => 'products',
            'categories' => 'categories',
            'sub_categories' => 'sub-categories',
            'brands' => 'brands',
            'colors' => 'colors',
            'size_guides' => 'size-guides',
            'orders' => 'orders',
            'coupons' => 'coupons',
            'cities' => 'cities',
            'reviews' => 'reviews',
            'addresses' => 'addresses',
            'pages' => 'pages',
            'faqs' => 'faqs',
            'users' => 'users',
            'settings' => 'manage-settings',
        ];

        $allowedSlugs = [];
        foreach ($mapping as $permission => $slug) {
            if ($user->hasPermission($permission)) {
                $allowedSlugs[] = $slug;
            }
        }

        $allowedJson = json_encode($allowedSlugs);

        return '<script>
            (function() {
                const allowedSlugs = ' . $allowedJson . ';

                function hideSidebarItems() {
                    const links = document.querySelectorAll("aside a[href*=\'/admin/\']");

                    links.forEach(function(link) {
                        const href = link.getAttribute("href") || "";
                        const match = href.match(/\/admin\/([a-z0-9\-]+)/);

                        if (!match) return;

                        const slug = match[1];

                        if (slug === "admin" || slug === "") return;
                        if (href.includes("/admin/profile")) return;

                        if (allowedSlugs.indexOf(slug) === -1) {
                            const li = link.closest("li");
                            if (li) li.style.display = "none";
                        }
                    });

                    document.querySelectorAll("aside .fi-sidebar-group").forEach(function(group) {
                        const visibleLinks = group.querySelectorAll("li:not([style*=\'display: none\']) a");
                        if (visibleLinks.length === 0) {
                            group.style.display = "none";
                        }
                    });
                }

                document.addEventListener("DOMContentLoaded", function() {
                    setTimeout(hideSidebarItems, 100);
                    setTimeout(hideSidebarItems, 500);
                    setTimeout(hideSidebarItems, 1500);
                });

                document.addEventListener("livewire:navigated", function() {
                    setTimeout(hideSidebarItems, 100);
                });
            })();
        </script>';
    }
}