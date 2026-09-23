<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BrandResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'brands';

    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'العلامات التجارية';

    protected static ?string $modelLabel = 'علامة تجارية';

    protected static ?string $pluralModelLabel = 'العلامات التجارية';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 11;

    public static function getNavigationBadge(): ?string
    {
        $empty = Brand::whereDoesntHave('products')->count();

        return $empty > 0 ? (string) $empty : null;
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
            Forms\Components\Section::make('معلومات العلامة التجارية')
                ->description('اسم العلامة والشعار')
                ->icon('heroicon-o-building-storefront')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم العلامة التجارية')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-tag')
                        ->placeholder('مثال: Nike، Adidas'),

                    Forms\Components\FileUpload::make('logo')
                        ->label('شعار العلامة التجارية')
                        ->image()
                        ->directory('brands')
                        ->maxSize(2048)
                        ->imageEditor()
                        ->imageEditorAspectRatios(['1:1'])
                        ->imagePreviewHeight('100')
                        ->openable()
                        ->downloadable()
                        ->helperText('ارفع صورة الشعار بخلفية شفافة PNG إن أمكن. الأبعاد المثالية: مربعة'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('الشعار')
                    ->circular()
                    ->size(45)
                    ->alignCenter()
                    ->defaultImageUrl(fn (Brand $record): string =>
                        'https://ui-avatars.com/api/?name=' . urlencode($record->name)
                        . '&background=C9A961&color=fff&size=90'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-building-storefront')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('products_count')
                    ->label('المنتجات')
                    ->counts('products')
                    ->badge()
                    ->color(fn ($state): string => $state > 0 ? 'success' : 'gray')
                    ->alignCenter()
                    ->suffix(' منتج')
                    ->icon('heroicon-m-shopping-bag'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (Brand $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_products')
                    ->label('تحتوي على منتجات')
                    ->query(fn (Builder $query) => $query->has('products'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_products')
                    ->label('علامات فارغة')
                    ->query(fn (Builder $query) => $query->doesntHave('products'))
                    ->toggle(),

                Tables\Filters\Filter::make('has_logo')
                    ->label('لديها شعار')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('logo')
                        ->where('logo', '!=', ''))
                    ->toggle(),

                Tables\Filters\Filter::make('no_logo')
                    ->label('بدون شعار')
                    ->query(fn (Builder $query) => $query
                        ->where(fn ($q) => $q
                            ->whereNull('logo')
                            ->orWhere('logo', '')))
                    ->toggle(),

                Tables\Filters\Filter::make('recent')
                    ->label('أُضيفت حديثاً')
                    ->query(fn (Builder $query) =>
                        $query->where('created_at', '>=', now()->subDays(30)))
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
                    ->modalHeading('حذف العلامة التجارية')
                    ->modalDescription(fn (Brand $record): string =>
                        $record->products()->count() > 0
                            ? "تحذير: هذه العلامة مرتبطة بـ " . $record->products()->count() . " منتج. سيتم فصلها عنهم."
                            : "هل أنت متأكد من حذف العلامة \"{$record->name}\"؟ لا يمكن التراجع.")
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف العلامات المحددة')
                        ->modalDescription('سيتم حذف جميع العلامات المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد علامات تجارية')
            ->emptyStateDescription('ابدأ بإضافة أول علامة تجارية لمتجرك')
            ->emptyStateIcon('heroicon-o-building-storefront')
            ->defaultSort('name', 'asc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit'   => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}