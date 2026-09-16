<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'تفاصيل الطلب';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('product_variant_id')
                ->label('المنتج (اللون/المقاس)')
                ->options(
                    ProductVariant::with('product')->get()->mapWithKeys(function ($variant) {
                        return [
                            $variant->id => ($variant->product->name ?? 'منتج محذوف') .
                                ' - ' . ($variant->color ?? 'بدون لون') .
                                ' - ' . ($variant->size ?? 'بدون مقاس')
                        ];
                    })
                )
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('quantity')
                ->label('الكمية')
                ->numeric()
                ->default(1)
                ->required(),
            Forms\Components\TextInput::make('unit_price')
                ->label('سعر الوحدة')
                ->numeric()
                ->prefix('$')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variant.product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variant.color')
                    ->label('اللون'),
                Tables\Columns\TextColumn::make('variant.size')
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('الكمية'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('سعر الوحدة')
                    ->money('USD'),
                Tables\Columns\TextColumn::make('total')
                    ->label('الإجمالي')
                    ->getStateUsing(fn ($record) => $record->quantity * $record->unit_price)
                    ->money('USD'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة منتج للطلب'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ]);
    }
}