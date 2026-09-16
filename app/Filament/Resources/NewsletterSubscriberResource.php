<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermission;
use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    use HasResourcePermission;

    protected static string $permissionKey = 'users';

    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?string $navigationLabel = 'المشتركون في النشرة';

    protected static ?string $modelLabel = 'مشترك';

    protected static ?string $pluralModelLabel = 'المشتركون في النشرة';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->disabled(),

            Forms\Components\TextInput::make('name')
                ->label('الاسم')
                ->disabled(),

            Forms\Components\Toggle::make('is_active')
                ->label('نشط'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->copyable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->placeholder('—')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('subscribed_at')
                    ->label('تاريخ الاشتراك')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unsubscribed_at')
                    ->label('تاريخ إلغاء الاشتراك')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('نشط')
                    ->placeholder('الكل')
                    ->trueLabel('النشطون فقط')
                    ->falseLabel('غير النشطين'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('subscribed_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
            'edit' => Pages\EditNewsletterSubscriber::route('/{record}/edit'),
        ];
    }
}