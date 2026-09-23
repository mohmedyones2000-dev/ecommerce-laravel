<?php

namespace App\Filament\Resources\SizeGuideResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'جدول المقاسات';

    protected static ?string $modelLabel = 'مقاس';

    protected static ?string $pluralModelLabel = 'المقاسات';

    protected static ?string $recordTitleAttribute = 'size';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('المعلومات الأساسية')
                ->description('اسم المقاس وترتيب ظهوره في الجدول')
                ->icon('heroicon-o-tag')
                ->schema([
                    Forms\Components\TextInput::make('size')
                        ->label('المقاس')
                        ->required()
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-tag')
                        ->placeholder('S / M / L / XL'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('الترتيب')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->prefixIcon('heroicon-o-bars-arrow-up')
                        ->helperText('الأرقام الأصغر تظهر أولاً'),
                ])->columns(2),

            Forms\Components\Section::make('القياسات (سم)')
                ->description('اترك الحقول فارغة إذا لم تكن مطلوبة')
                ->icon('heroicon-o-ruler-square')
                ->schema([
                    Forms\Components\TextInput::make('chest')
                        ->label('الصدر')
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-arrows-up-down')
                        ->suffix('سم')
                        ->placeholder('86-91'),

                    Forms\Components\TextInput::make('waist')
                        ->label('الخصر')
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-arrows-up-down')
                        ->suffix('سم')
                        ->placeholder('71-76'),

                    Forms\Components\TextInput::make('hips')
                        ->label('الأرداف')
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-arrows-up-down')
                        ->suffix('سم')
                        ->placeholder('86-91'),

                    Forms\Components\TextInput::make('length')
                        ->label('الطول')
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-arrows-up-down')
                        ->suffix('سم')
                        ->placeholder('68'),
                ])->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('size')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->badge()
                    ->color('gray')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('size')
                    ->label('المقاس')
                    ->weight('bold')
                    ->badge()
                    ->color('warning')
                    ->alignCenter()
                    ->searchable()
                    ->icon('heroicon-m-tag'),

                Tables\Columns\TextColumn::make('chest')
                    ->label('الصدر (سم)')
                    ->placeholder('—')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('waist')
                    ->label('الخصر (سم)')
                    ->placeholder('—')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('hips')
                    ->label('الأرداف (سم)')
                    ->placeholder('—')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('length')
                    ->label('الطول (سم)')
                    ->placeholder('—')
                    ->alignCenter()
                    ->toggleable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('إضافة مقاس')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->after(fn () => $this->notify('تم إضافة المقاس بنجاح')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->size('sm')
                    ->after(fn () => $this->notify('تم تحديث المقاس')),

                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->icon('heroicon-o-trash')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('حذف المقاس')
                    ->modalDescription('هل أنت متأكد من حذف هذا المقاس؟ لا يمكن التراجع.')
                    ->modalSubmitActionLabel('نعم، احذف')
                    ->after(fn () => $this->notify('تم حذف المقاس')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف المحدد')
                        ->requiresConfirmation()
                        ->modalHeading('حذف المقاسات المحددة')
                        ->modalDescription('سيتم حذف جميع المقاسات المحددة. لا يمكن التراجع.')
                        ->modalSubmitActionLabel('نعم، احذف الكل')
                        ->after(fn () => $this->notify('تم حذف المقاسات المحددة')),
                ]),
            ])
            ->emptyStateHeading('لا توجد مقاسات')
            ->emptyStateDescription('ابدأ بإضافة أول مقاس لهذا الدليل')
            ->emptyStateIcon('heroicon-o-table-cells')
            ->striped();
    }

    protected function notify(string $message): void
    {
        Notification::make()
            ->title($message)
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }
}