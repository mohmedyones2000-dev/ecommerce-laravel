<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $inactive = Page::where('is_active', false)->count();

        return $inactive > 0 ? (string) $inactive : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('معلومات الصفحة')
                ->description('العنوان، الرابط، والعنوان الفرعي')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('عنوان الصفحة')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-document')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                            $operation === 'create'
                                ? $set('slug', Str::slug($state))
                                : null),

                    Forms\Components\TextInput::make('slug')
                        ->label('المعرّف (Slug)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-link')
                        ->helperText('مثال: about, terms, privacy'),

                    Forms\Components\TextInput::make('subtitle')
                        ->label('العنوان الفرعي')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-tag')
                        ->columnSpanFull()
                        ->placeholder('عنوان فرعي يظهر أسفل العنوان الرئيسي (اختياري)'),

                    Forms\Components\Textarea::make('meta_description')
                        ->label('وصف SEO')
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull()
                        ->placeholder('وصف مختصر للصفحة يظهر في نتائج البحث (150-160 حرف)')
                        ->helperText(fn ($state): string =>
                            strlen($state ?? '') . ' / 500 حرف'),
                ])->columns(2),

            Forms\Components\Section::make('المحتوى')
                ->description('محتوى الصفحة الكامل')
                ->icon('heroicon-o-pencil-square')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->label('محتوى الصفحة')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h1',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ]),
                ]),

            Forms\Components\Section::make('الإعدادات')
                ->description('حالة الصفحة وموقع ظهورها')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('نشطة')
                        ->helperText('الصفحات غير النشطة لا تظهر في المتجر')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger'),

                    Forms\Components\Toggle::make('show_in_footer')
                        ->label('إظهار في الفوتر')
                        ->helperText('عرض رابط الصفحة في تذييل الموقع')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('gray'),
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
                    ->sortable()
                    ->weight('semibold')
                    ->icon('heroicon-m-document-text')
                    ->iconColor('gray')
                    ->description(fn (Page $record): ?string => $record->subtitle),

                Tables\Columns\TextColumn::make('slug')
                    ->label('المعرّف')
                    ->badge()
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('تم نسخ المعرّف')
                    ->alignCenter()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('show_in_footer')
                    ->label('في الفوتر')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (Page $record): string =>
                        $record->updated_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشطة')
                    ->placeholder('الكل')
                    ->trueLabel('النشطة فقط')
                    ->falseLabel('غير النشطة'),

                Tables\Filters\TernaryFilter::make('show_in_footer')
                    ->label('في الفوتر')
                    ->placeholder('الكل')
                    ->trueLabel('تظهر في الفوتر')
                    ->falseLabel('مخفية من الفوتر'),

                Tables\Filters\Filter::make('missing_seo')
                    ->label('بدون وصف SEO')
                    ->query(fn (Builder $query) => $query
                        ->where(fn ($q) => $q
                            ->whereNull('meta_description')
                            ->orWhere('meta_description', '')))
                    ->toggle(),

                Tables\Filters\Filter::make('recently_updated')
                    ->label('حُدّثت آخر 30 يوم')
                    ->query(fn (Builder $query) =>
                        $query->where('updated_at', '>=', now()->subDays(30)))
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
                    ->modalHeading('حذف الصفحة')
                    ->modalDescription(fn (Page $record): string =>
                        "هل أنت متأكد من حذف الصفحة \"{$record->title}\"؟ لا يمكن التراجع.")
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

                    Tables\Actions\BulkAction::make('show_in_footer')
                        ->label('إظهار في الفوتر')
                        ->icon('heroicon-m-arrow-down-on-square')
                        ->color('info')
                        ->action(fn ($records) => $records->each->update(['show_in_footer' => true]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('hide_from_footer')
                        ->label('إخفاء من الفوتر')
                        ->icon('heroicon-m-eye-slash')
                        ->color('gray')
                        ->action(fn ($records) => $records->each->update(['show_in_footer' => false]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف الصفحات المحددة')
                        ->modalDescription('سيتم حذف جميع الصفحات المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد صفحات ثابتة')
            ->emptyStateDescription('ابدأ بإضافة صفحات مثل: عن الشركة، الشروط، سياسة الخصوصية')
            ->emptyStateIcon('heroicon-o-document-text')
            ->defaultSort('updated_at', 'desc')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}