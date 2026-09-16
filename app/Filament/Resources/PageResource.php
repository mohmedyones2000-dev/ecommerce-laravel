<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'pages';

    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?string $navigationLabel = 'الصفحات الثابتة';

    protected static ?string $modelLabel = 'صفحة';

    protected static ?string $pluralModelLabel = 'الصفحات الثابتة';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الصفحة')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الصفحة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('المعرّف (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('مثال: about, terms, privacy'),

                Forms\Components\TextInput::make('subtitle')
                    ->label('العنوان الفرعي')
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('meta_description')
                    ->label('وصف SEO')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('المحتوى')->schema([
                Forms\Components\RichEditor::make('content')
                    ->label('محتوى الصفحة')
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'blockquote', 'bold', 'bulletList', 'h2', 'h3',
                        'italic', 'link', 'orderedList', 'redo', 'strike', 'underline', 'undo',
                    ]),
            ]),

            Forms\Components\Section::make('الإعدادات')->schema([
                Forms\Components\Toggle::make('is_active')
                    ->label('نشطة')
                    ->default(true),

                Forms\Components\Toggle::make('show_in_footer')
                    ->label('إظهار في الفوتر')
                    ->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('المعرّف')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean(),

                Tables\Columns\IconColumn::make('show_in_footer')
                    ->label('في الفوتر')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}