<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use App\Filament\Resources\ReviewResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    public function getTitle(): string
    {
        return 'تفاصيل المراجعة #' . ($this->record->id ?? '');
    }

    public function getSubheading(): ?string
    {
        $user = $this->record->user?->name ?? 'غير معروف';
        $product = $this->record->product?->name ?? 'منتج محذوف';
        $rating = (int) ($this->record->rating ?? 0);

        return "العميل: {$user} • المنتج: {$product} • التقييم: {$rating}/5";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المراجعات' => ReviewResource::getUrl('index'),
            'تفاصيل المراجعة',
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_product')
                ->label('عرض المنتج')
                ->icon('heroicon-o-shopping-bag')
                ->color('gray')
                ->visible(fn (): bool => $this->record->product !== null)
                ->url(fn () => url('/product/' . $this->record->product?->slug))
                ->openUrlInNewTab(),

            Actions\Action::make('view_customer')
                ->label('عرض العميل')
                ->icon('heroicon-o-user')
                ->color('gray')
                ->visible(fn (): bool =>
                    $this->record->user !== null
                    && auth()->user()?->hasPermission('users'))
                ->url(fn () => \App\Filament\Resources\UserResource::getUrl(
                    'edit',
                    ['record' => $this->record->user_id]
                )),

            Actions\DeleteAction::make()
                ->label('حذف المراجعة')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف المراجعة')
                ->modalDescription(fn (): string =>
                    'هل أنت متأكد من حذف مراجعة "' . ($this->record->user?->name ?? 'العميل')
                    . '" على "' . ($this->record->product?->name ?? 'المنتج') . '"؟ لا يمكن التراجع.')
                ->modalSubmitActionLabel('نعم، احذف')
                ->after(function () {
                    Notification::make()
                        ->title('تم حذف المراجعة')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}