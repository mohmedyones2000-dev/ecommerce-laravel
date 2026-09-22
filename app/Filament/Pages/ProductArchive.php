<?php

namespace App\Filament\Pages;

use App\Models\Product;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;

class ProductArchive extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'إدارة المتجر';
    protected static ?string $navigationLabel = 'الأرشيف';
    protected static ?string $title = 'أرشيف المنتجات';
    protected static ?int $navigationSort = 99;
    protected static string $view = 'filament.pages.product-archive';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::onlyTrashed()->latest('deleted_at'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('USD'),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('تاريخ الحذف')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('restore')
                    ->label('استعادة')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Product $record) {
                        $record->restore();
                        Notification::make()
                            ->title('تم استعادة المنتج')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('forceDelete')
                    ->label('حذف نهائي')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('⚠️ هذا الإجراء لا يمكن التراجع عنه!')
                    ->action(function (Product $record) {
                        $record->forceDelete();
                        Notification::make()
                            ->title('تم الحذف النهائي')
                            ->danger()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('restore_bulk')
                    ->label('استعادة المحدد')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each->restore();
                        Notification::make()->title('تم استعادة المنتجات')->success()->send();
                    }),

                Tables\Actions\BulkAction::make('force_delete_bulk')
                    ->label('حذف نهائي')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        $records->each->forceDelete();
                        Notification::make()->title('تم الحذف النهائي')->danger()->send();
                    }),
            ])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('لا توجد منتجات محذوفة')
            ->emptyStateDescription('جميع المنتجات موجودة في الواجهة الرئيسية')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}