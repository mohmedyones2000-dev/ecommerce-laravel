<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'categories';

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'التصنيفات الرئيسية';

    protected static ?string $modelLabel = 'تصنيف رئيسي';

    protected static ?string $pluralModelLabel = 'التصنيفات الرئيسية';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $empty = Category::whereDoesntHave('products')->count();

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
            Forms\Components\Section::make('معلومات التصنيف')
                ->description('اسم التصنيف، الرابط، والتصنيفات الفرعية المرتبطة به')
                ->icon('heroicon-o-tag')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('اسم التصنيف')
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

                    Forms\Components\Select::make('subCategories')
                        ->label('التصنيفات الفرعية')
                        ->relationship('subCategories', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->prefixIcon('heroicon-o-folder-arrow-down')
                        ->columnSpanFull()
                        ->helperText('اختر التصنيفات الفرعية التي تنتمي لهذا التصنيف الرئيسي'),
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

                Tables\Columns\TextColumn::make('sub_categories_count')
                    ->label('التصنيفات الفرعية')
                    ->counts('subCategories')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->suffix(' فرعي')
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
                    ->tooltip(fn (Category $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_products')
                    ->label('يحتوي على منتجات')
                    ->query(fn (Builder $query) => $query->has('products'))
                    ->toggle(),

                Tables\Filters\Filter::make('no_products')
                    ->label('تصنيفات فارغة')
                    ->query(fn (Builder $query) => $query->doesntHave('products'))
                    ->toggle(),

                Tables\Filters\Filter::make('has_subcategories')
                    ->label('يحتوي على تصنيفات فرعية')
                    ->query(fn (Builder $query) => $query->has('subCategories'))
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
                    ->modalHeading('حذف التصنيف')
                    ->modalDescription(fn (Category $record): string =>
                        $record->products_count > 0
                            ? "تحذير: هذا التصنيف يحتوي على {$record->products_count} منتج. سيتم فصلها عن التصنيف."
                            : 'هل أنت متأكد من حذف هذا التصنيف؟')
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف التصنيفات المحددة')
                        ->modalDescription('سيتم حذف جميع التصنيفات المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد تصنيفات')
            ->emptyStateDescription('ابدأ بإضافة أول تصنيف رئيسي للمتجر')
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
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}