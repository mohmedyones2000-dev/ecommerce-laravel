<?php

namespace App\Filament\Resources\NewsletterSubscriberResource\Pages;

use App\Filament\Resources\NewsletterSubscriberResource;
use App\Models\NewsletterSubscriber;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListNewsletterSubscribers extends ListRecords
{
    protected static string $resource = NewsletterSubscriberResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'المشتركون في النشرة';
    }

    public function getSubheading(): ?string
    {
        $total = NewsletterSubscriber::count();
        $active = NewsletterSubscriber::where('is_active', true)->count();
        $unsubscribed = NewsletterSubscriber::whereNotNull('unsubscribed_at')->count();

        if ($total === 0) {
            return null;
        }

        $parts = ["إجمالي المشتركين: {$total}", "نشط: {$active}"];

        if ($unsubscribed > 0) {
            $parts[] = "ألغوا الاشتراك: {$unsubscribed}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المشتركون في النشرة',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('refresh')
                ->label('تحديث')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->resetTable()),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('الكل')
                ->badge(NewsletterSubscriber::count()),

            'active' => Tab::make('النشطون')
                ->badge(NewsletterSubscriber::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', true)),

            'inactive' => Tab::make('غير النشطين')
                ->badge(NewsletterSubscriber::where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', false)),

            'unsubscribed' => Tab::make('ألغوا الاشتراك')
                ->badge(NewsletterSubscriber::whereNotNull('unsubscribed_at')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereNotNull('unsubscribed_at')),

            'this_month' => Tab::make('هذا الشهر')
                ->badge(NewsletterSubscriber::whereMonth('subscribed_at', now()->month)
                    ->whereYear('subscribed_at', now()->year)
                    ->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereMonth('subscribed_at', now()->month)
                        ->whereYear('subscribed_at', now()->year)),

            'with_name' => Tab::make('يحتوي على اسم')
                ->badge(NewsletterSubscriber::whereNotNull('name')
                    ->where('name', '!=', '')
                    ->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereNotNull('name')->where('name', '!=', '')),
        ];
    }
}