<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use App\Models\Review;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListReviews extends ListRecords
{
    protected static string $resource = ReviewResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'المراجعات';
    }

    public function getSubheading(): ?string
    {
        $total = Review::count();

        if ($total === 0) {
            return null;
        }

        $avg = round((float) (Review::avg('rating') ?? 0), 1);
        $lowRatings = Review::where('rating', '<=', 2)->count();

        $parts = ["إجمالي المراجعات: {$total}", "متوسط التقييم: {$avg}/5"];

        if ($lowRatings > 0) {
            $parts[] = "تقييمات منخفضة: {$lowRatings}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المراجعات',
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
                ->badge(Review::count()),

            'high_rating' => Tab::make('تقييم عالٍ (4-5)')
                ->badge(Review::where('rating', '>=', 4)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('rating', '>=', 4)),

            'medium_rating' => Tab::make('تقييم متوسط (3)')
                ->badge(Review::where('rating', 3)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('rating', 3)),

            'low_rating' => Tab::make('تقييم منخفض (1-2)')
                ->badge(Review::where('rating', '<=', 2)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('rating', '<=', 2)),

            'with_comment' => Tab::make('يحتوي على تعليق')
                ->badge(Review::whereNotNull('comment')
                    ->where('comment', '!=', '')
                    ->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereNotNull('comment')->where('comment', '!=', '')),

            'this_week' => Tab::make('هذا الأسبوع')
                ->badge(Review::where('created_at', '>=', now()->subWeek())->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('created_at', '>=', now()->subWeek())),
        ];
    }
}