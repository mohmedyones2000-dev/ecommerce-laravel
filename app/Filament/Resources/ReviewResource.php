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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->label('العميل')
                ->relationship('user', 'name')
                ->disabled(),

            Forms\Components\Select::make('product_id')
                ->label('المنتج')
                ->relationship('product', 'name')
                ->disabled(),

            Forms\Components\TextInput::make('rating')
                ->label('التقييم')
                ->disabled(),

            Forms\Components\Textarea::make('comment')
                ->label('التعليق')
                ->disabled()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('rating')
                    ->label('التقييم')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state) . str_repeat('☆', 5 - $state)),

                Tables\Columns\TextColumn::make('comment')
                    ->label('التعليق')
                    ->limit(50)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->label('المنتج')
                    ->relationship('product', 'name'),

                Tables\Filters\SelectFilter::make('rating')
                    ->label('التقييم')
                    ->options([
                        5 => '★★★★★',
                        4 => '★★★★☆',
                        3 => '★★★☆☆',
                        2 => '★★☆☆☆',
                        1 => '★☆☆☆☆',
                    ]),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}