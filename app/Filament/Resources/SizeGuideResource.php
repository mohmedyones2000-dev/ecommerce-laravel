<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\SizeGuideResource\Pages;
use App\Filament\Resources\SizeGuideResource\RelationManagers\ItemsRelationManager;
use App\Models\SizeGuide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SizeGuideResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'size_guides';

    protected static ?string $model = SizeGuide::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'أدلة المقاسات';

    protected static ?string $modelLabel = 'دليل مقاسات';

    protected static ?string $pluralModelLabel = 'أدلة المقاسات';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 5;

    public static function getNavigationBadge(): ?string
    {
        $inactive = SizeGuide::where('is_active', false)->count();

        return $inactive > 0 ? (string) $inactive : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الدليل')
                ->description('اسم الدليل، الوصف، وحالة التفعيل')
                ->icon('heroicon-o-table-cells')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم الدليل')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-document-text')
                        ->placeholder('مثال: مقاسات الرجالي'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->helperText('الأدلة غير النشطة لا تظهر في المتجر')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger'),

                    Forms\Components\Textarea::make('description')
                        ->label('ملاحظات / وصف')
                        ->rows(3)
                        ->columnSpanFull()
                        ->placeholder('أضف ملاحظات حول هذا الدليل (اختياري)'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم الدليل')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('gray')
                    ->tooltip(fn (SizeGuide $record): ?string => $record->description),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('المقاسات')
                    ->counts('items')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'info' : 'gray')
                    ->alignCenter()
                    ->suffix(' مقاس')
                    ->icon('heroicon-m-list-bullet'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('المنتجات المرتبطة')
                    ->counts('products')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'gray')
                    ->alignCenter()
                    ->suffix(' منتج')
                    ->icon('heroicon-m-shopping-bag'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (SizeGuide $record): string =>
                        $record->updated_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('النشطة فقط')
                    ->falseLabel('غير النشطة'),

                Tables\Filters\Filter::make('has_items')
                    ->label('يحتوي على مقاسات')
                    ->query(fn (Builder $query) => $query->has('items'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_items')
                    ->label('أدلة فارغة')
                    ->query(fn (Builder $query) => $query->doesntHave('items'))
                    ->toggle(),

                Tables\Filters\Filter::make('has_products')
                    ->label('مرتبط بمنتجات')
                    ->query(fn (Builder $query) => $query->has('products'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_products')
                    ->label('غير مرتبط بأي منتج')
                    ->query(fn (Builder $query) => $query->doesntHave('products'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->size('sm'),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف دليل المقاسات')
                    ->modalDescription(fn (SizeGuide $record): string =>
                        $record->products()->count() > 0
                            ? "تحذير: هذا الدليل مرتبط بـ " . $record->products()->count() . " منتج. سيتم فصلها عنه."
                            : 'هل أنت متأكد من حذف هذا الدليل؟ لا يمكن التراجع.')
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('تفعيل')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('تعطيل')
                        ->icon('heroicon-m-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف أدلة المقاسات المحددة')
                        ->modalDescription('سيتم حذف جميع الأدلة المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد أدلة مقاسات')
            ->emptyStateDescription('ابدأ بإضافة أول دليل مقاسات للمتجر')
            ->emptyStateIcon('heroicon-o-table-cells')
            ->defaultSort('name', 'asc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSizeGuides::route('/'),
            'create' => Pages\CreateSizeGuide::route('/create'),
            'edit'   => Pages\EditSizeGuide::route('/{record}/edit'),
        ];
    }
}