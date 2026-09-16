<?php

namespace App\Filament\Resources\SizeGuideResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'جدول المقاسات';

    protected static ?string $modelLabel = 'مقاس';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('size')
                ->label('المقاس')
                ->required()
                ->maxLength(50)
                ->placeholder('S, M, L, XL'),

            Forms\Components\TextInput::make('chest')
                ->label('الصدر (سم)')
                ->maxLength(50)
                ->placeholder('86-91'),

            Forms\Components\TextInput::make('waist')
                ->label('الخصر (سم)')
                ->maxLength(50)
                ->placeholder('71-76'),

            Forms\Components\TextInput::make('hips')
                ->label('الأرداف (سم)')
                ->maxLength(50)
                ->placeholder('86-91'),

            Forms\Components\TextInput::make('length')
                ->label('الطول (سم)')
                ->maxLength(50)
                ->placeholder('68'),

            Forms\Components\TextInput::make('sort_order')
                ->label('الترتيب')
                ->numeric()
                ->default(0)
                ->helperText('الأرقام الأصغر تظهر أولاً'),
        ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('size')
                    ->label('المقاس')
                    ->weight('bold')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('chest')
                    ->label('الصدر')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('waist')
                    ->label('الخصر')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('hips')
                    ->label('الأرداف')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('length')
                    ->label('الطول')
                    ->placeholder('—'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة مقاس'),
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