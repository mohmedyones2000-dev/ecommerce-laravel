<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\SubCategoryResource\Pages;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SubCategoryResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'sub_categories';

    protected static ?string $model = SubCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'التصنيفات الفرعية';

    protected static ?string $modelLabel = 'تصنيف فرعي';

    protected static ?string $pluralModelLabel = 'التصنيفات الفرعية';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        $empty = SubCategory::whereDoesntHave('products')->count();

        return $empty > 0 ? (string) $empty : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات التصنيف الفرعي')
                ->description('اسم التصنيف الفرعي، الرابط، والتصنيفات الرئيسية المرتبطة به')
                ->icon('heroicon-o-tag')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم التصنيف الفرعي')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-tag')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                            $operation === 'create'
                                ? $set('slug', Str::slug($state))
                                : null),

                    Forms\Components\TextInput::make('slug')
                        ->label('المعرّف الفريد (Slug)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-link')
                        ->helperText('يُولَّد تلقائياً من الاسم، ويمكنك تعديله يدوياً'),

                    Forms\Components\Select::make('categories')
                        ->label('التصنيفات الرئيسية')
                        ->relationship('categories', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->prefixIcon('heroicon-o-folder')
                        ->columnSpanFull()
                        ->helperText('اختر التصنيفات الرئيسية التي ينتمي إليها هذا التصنيف الفرعي'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-tag')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('المعرّف الفريد')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ المعرّف')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('categories.name')
                    ->label('التصنيفات الرئيسية')
                    ->badge()
                    ->color('info')
                    ->separator(',')
                    ->limit(50)
                    ->alignCenter()
                    ->icon('heroicon-m-folder'),

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
                    ->tooltip(fn (SubCategory $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('categories')
                    ->label('التصنيف الرئيسي')
                    ->relationship('categories', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('has_products')
                    ->label('يحتوي على منتجات')
                    ->query(fn (Builder $query) => $query->has('products'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_products')
                    ->label('تصنيفات فارغة')
                    ->query(fn (Builder $query) => $query->doesntHave('products'))
                    ->toggle(),

                Tables\Filters\Filter::make('unassigned')
                    ->label('بدون تصنيف رئيسي')
                    ->query(fn (Builder $query) => $query->doesntHave('categories'))
                    ->toggle(),

                Tables\Filters\Filter::make('recently_created')
                    ->label('أُضيفت آخر 30 يوم')
                    ->query(fn (Builder $query) => $query->where('created_at', '>=', now()->subDays(30)))
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
                    ->modalHeading('حذف التصنيف الفرعي')
                    ->modalDescription(fn (SubCategory $record): string =>
                        $record->products()->count() > 0
                            ? "تحذير: هذا التصنيف يحتوي على " . $record->products()->count() . " منتج. سيتم فصلها عن التصنيف."
                            : 'هل أنت متأكد من حذف هذا التصنيف الفرعي؟')
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف التصنيفات الفرعية المحددة')
                        ->modalDescription('سيتم حذف جميع التصنيفات الفرعية المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد تصنيفات فرعية')
            ->emptyStateDescription('ابدأ بإضافة أول تصنيف فرعي للمتجر')
            ->emptyStateIcon('heroicon-o-tag')
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
            'index'  => Pages\ListSubCategories::route('/'),
            'create' => Pages\CreateSubCategory::route('/create'),
            'edit'   => Pages\EditSubCategory::route('/{record}/edit'),
        ];
    }
}