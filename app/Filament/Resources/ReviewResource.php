<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'reviews';

    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'إدارة المتجر';

    protected static ?string $navigationLabel = 'المراجعات';

    protected static ?string $modelLabel = 'مراجعة';

    protected static ?string $pluralModelLabel = 'المراجعات';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        $lastWeek = Review::where('created_at', '>=', now()->subWeek())->count();

        return $lastWeek > 0 ? '+' . $lastWeek : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['comment'];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات المراجعة')
                ->description('بيانات المراجعة الأساسية (للقراءة فقط)')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('العميل')
                        ->relationship('user', 'name')
                        ->prefixIcon('heroicon-o-user')
                        ->disabled(),

                    Forms\Components\Select::make('product_id')
                        ->label('المنتج')
                        ->relationship('product', 'name')
                        ->prefixIcon('heroicon-o-shopping-bag')
                        ->disabled(),

                    Forms\Components\TextInput::make('rating')
                        ->label('التقييم')
                        ->prefixIcon('heroicon-o-star')
                        ->formatStateUsing(fn ($state): string =>
                            str_repeat('★', (int) $state) . str_repeat('☆', 5 - (int) $state)
                            . ' (' . (int) $state . '/5)')
                        ->disabled(),

                    Forms\Components\Textarea::make('comment')
                        ->label('التعليق')
                        ->rows(4)
                        ->columnSpanFull()
                        ->disabled()
                        ->placeholder('لا يوجد تعليق'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-user')
                    ->iconColor('gray')
                    ->description(fn (Review $record): ?string => $record->user?->email),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn (Review $record): ?string => $record->product?->name)
                    ->icon('heroicon-m-shopping-bag')
                    ->iconColor('gray'),

                Tables\Columns\TextColumn::make('rating')
                    ->label('التقييم')
                    ->badge()
                    ->alignCenter()
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string =>
                        str_repeat('★', $state) . str_repeat('☆', 5 - $state))
                    ->color(fn (int $state): string => match (true) {
                        $state >= 5 => 'success',
                        $state >= 4 => 'info',
                        $state >= 3 => 'warning',
                        default      => 'danger',
                    }),

                Tables\Columns\TextColumn::make('comment')
                    ->label('التعليق')
                    ->limit(40)
                    ->placeholder('—')
                    ->tooltip(fn (Review $record): ?string => $record->comment)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (Review $record): string =>
                        $record->created_at?->format('Y-m-d H:i') ?? '')
                    ->icon('heroicon-m-calendar')
                    ->iconColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->label('المنتج')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('rating')
                    ->label('التقييم')
                    ->options([
                        5 => '★★★★★',
                        4 => '★★★★☆',
                        3 => '★★★☆☆',
                        2 => '★★☆☆☆',
                        1 => '★☆☆☆☆',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('high_rating')
                    ->label('تقييم عالٍ (4-5 نجوم)')
                    ->query(fn (Builder $query) => $query->where('rating', '>=', 4))
                    ->toggle(),

                Tables\Filters\Filter::make('low_rating')
                    ->label('تقييم منخفض (1-2 نجوم)')
                    ->query(fn (Builder $query) => $query->where('rating', '<=', 2))
                    ->toggle(),

                Tables\Filters\Filter::make('has_comment')
                    ->label('يحتوي على تعليق')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('comment')
                        ->where('comment', '!=', ''))
                    ->toggle(),

                Tables\Filters\Filter::make('no_comment')
                    ->label('بدون تعليق')
                    ->query(fn (Builder $query) => $query
                        ->where(fn ($q) => $q
                            ->whereNull('comment')
                            ->orWhere('comment', '')))
                    ->toggle(),

                Tables\Filters\Filter::make('date_range')
                    ->label('نطاق تاريخ')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('من تاريخ')
                            ->native(false),
                        Forms\Components\DatePicker::make('to')
                            ->label('إلى تاريخ')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['to'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('من: ' . $data['from'])
                                ->removeField('from');
                        }

                        if ($data['to'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('إلى: ' . $data['to'])
                                ->removeField('to');
                        }

                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض')
                    ->icon('heroicon-o-eye')
                    ->size('sm'),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف المراجعة')
                    ->modalDescription('هل أنت متأكد من حذف هذه المراجعة؟ لا يمكن التراجع.')
                    ->modalSubmitActionLabel('نعم، احذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف المراجعات المحددة')
                        ->modalDescription('سيتم حذف جميع المراجعات المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد مراجعات')
            ->emptyStateDescription('لم يقم أي عميل بترك مراجعة بعد')
            ->emptyStateIcon('heroicon-o-star')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit'  => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}