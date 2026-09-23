<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use App\Models\Faq;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListFaqs extends ListRecords
{
    protected static string $resource = FaqResource::class;

    protected static ?string $pollingInterval = '60s';

    public function getTitle(): string
    {
        return 'الأسئلة الشائعة';
    }

    public function getSubheading(): ?string
    {
        $total = Faq::count();
        $active = Faq::where('is_active', true)->count();
        $inactive = $total - $active;

        if ($total === 0) {
            return null;
        }

        $parts = ["إجمالي الأسئلة: {$total}", "نشطة: {$active}"];

        if ($inactive > 0) {
            $parts[] = "غير نشطة: {$inactive}";
        }

        return implode(' • ', $parts);
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الأسئلة الشائعة',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('إضافة سؤال جديد')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->keyBindings(['ctrl+n', 'command+n']),

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
                ->badge(Faq::count()),

            'active' => Tab::make('النشطة')
                ->badge(Faq::where('is_active', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', true)),

            'inactive' => Tab::make('غير النشطة')
                ->badge(Faq::where('is_active', false)->count())
                ->badgeColor('danger')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('is_active', false)),

            'recent' => Tab::make('حُدّثت حديثاً')
                ->badge(Faq::where('updated_at', '>=', now()->subDays(30))->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('updated_at', '>=', now()->subDays(30))),

            'long_answers' => Tab::make('جواب طويل')
                ->badge(Faq::whereRaw('LENGTH(answer) > 500')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereRaw('LENGTH(answer) > 500')),
        ];
    }
}