<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'faqs';

    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?string $navigationLabel = 'الأسئلة الشائعة';

    protected static ?string $modelLabel = 'سؤال';

    protected static ?string $pluralModelLabel = 'الأسئلة الشائعة';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('السؤال والجواب')->schema([
                Forms\Components\TextInput::make('question')
                    ->label('السؤال')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('answer')
                    ->label('الجواب')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('sort_order')
                    ->label('ترتيب العرض')
                    ->numeric()
                    ->default(0)
                    ->helperText('الأرقام الأصغر تظهر أولاً'),

                Forms\Components\Toggle::make('is_active')
                    ->label('نشط')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('question')
                    ->label('السؤال')
                    ->searchable()
                    ->weight('semibold')
                    ->limit(60),

                Tables\Columns\TextColumn::make('answer')
                    ->label('الجواب')
                    ->limit(60)
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}