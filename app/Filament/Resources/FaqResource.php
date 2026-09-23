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
use Illuminate\Database\Eloquent\Builder;

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

    protected static ?string $recordTitleAttribute = 'question';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $inactive = Faq::where('is_active', false)->count();

        return $inactive > 0 ? (string) $inactive : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['question', 'answer'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('السؤال والجواب')
                ->description('اكتب السؤال والجواب بشكل واضح ومختصر')
                ->icon('heroicon-o-question-mark-circle')
                ->schema([
                    Forms\Components\TextInput::make('question')
                        ->label('السؤال')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-question-mark-circle')
                        ->columnSpanFull()
                        ->placeholder('مثال: كيف يمكنني تتبع طلبي؟'),

                    Forms\Components\Textarea::make('answer')
                        ->label('الجواب')
                        ->required()
                        ->rows(5)
                        ->columnSpanFull()
                        ->placeholder('اكتب الإجابة الكاملة هنا...')
                        ->helperText(fn ($state): string => strlen($state ?? '') . ' حرف'),
                ]),

            Forms\Components\Section::make('الإعدادات')
                ->description('ترتيب العرض وحالة التفعيل')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->label('ترتيب العرض')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->prefixIcon('heroicon-o-bars-arrow-up')
                        ->helperText('الأرقام الأصغر تظهر أولاً'),

                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->helperText('الأسئلة غير النشطة لا تظهر في المتجر')
                        ->default(true)
                        ->onColor('success')
                        ->offColor('danger'),
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
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('question')
                    ->label('السؤال')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->limit(60)
                    ->tooltip(fn (Faq $record): ?string => $record->question)
                    ->icon('heroicon-m-question-mark-circle')
                    ->iconColor('info'),

                Tables\Columns\TextColumn::make('answer')
                    ->label('الجواب')
                    ->limit(60)
                    ->placeholder('—')
                    ->tooltip(fn (Faq $record): ?string => $record->answer)
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->since()
                    ->tooltip(fn (Faq $record): string =>
                        $record->updated_at?->format('Y-m-d H:i') ?? '')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('النشطة فقط')
                    ->falseLabel('غير النشطة'),

                Tables\Filters\Filter::make('recently_updated')
                    ->label('حُدّثت آخر 30 يوم')
                    ->query(fn (Builder $query) =>
                        $query->where('updated_at', '>=', now()->subDays(30)))
                    ->toggle(),

                Tables\Filters\Filter::make('long_answer')
                    ->label('جواب طويل (أكثر من 500 حرف)')
                    ->query(fn (Builder $query) =>
                        $query->whereRaw('LENGTH(answer) > 500'))
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
                    ->modalHeading('حذف السؤال')
                    ->modalDescription(fn (Faq $record): string =>
                        "هل أنت متأكد من حذف السؤال؟ لا يمكن التراجع.")
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

                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف الأسئلة المحددة')
                        ->modalDescription('سيتم حذف جميع الأسئلة المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل'),
                ]),
            ])
            ->emptyStateHeading('لا توجد أسئلة شائعة')
            ->emptyStateDescription('ابدأ بإضافة أول سؤال شائع للمتجر')
            ->emptyStateIcon('heroicon-o-question-mark-circle')
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->striped()
            ->persistSearchInSession()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit'   => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}