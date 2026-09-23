<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?string $navigationLabel = 'إعدادات الموقع';

    protected static ?string $title = 'إعدادات الموقع';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSetting::current()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('settings')
                    ->tabs([
                        Tabs\Tab::make('عام')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('اسم الموقع')
                                    ->required()
                                    ->maxLength(100),

                                FileUpload::make('logo')
                                    ->label('شعار الموقع')
                                    ->image()
                                    ->directory('settings')
                                    ->imageEditor()
                                    ->maxSize(2048),

                                FileUpload::make('favicon')
                                    ->label('أيقونة الموقع (Favicon)')
                                    ->image()
                                    ->directory('settings')
                                    ->imageEditor()
                                    ->maxSize(512),
                            ])->columns(2),

                        Tabs\Tab::make('التواصل')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make('email')
                                    ->label('البريد الإلكتروني')
                                    ->email()
                                    ->maxLength(255),

                                TextInput::make('phone')
                                    ->label('رقم الهاتف')
                                    ->tel()
                                    ->maxLength(30),

                                TextInput::make('address')
                                    ->label('العنوان')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                TextInput::make('whatsapp')
                                    ->label('رقم WhatsApp')
                                    ->tel()
                                    ->helperText('مثال: 970599123456 بدون رموز'),
                            ])->columns(2),

                        Tabs\Tab::make('السوشيال ميديا')
                            ->icon('heroicon-o-share')
                            ->schema([
                                TextInput::make('facebook')
                                    ->label('رابط فيسبوك')
                                    ->url()
                                    ->maxLength(255),

                                TextInput::make('instagram')
                                    ->label('رابط إنستغرام')
                                    ->url()
                                    ->maxLength(255),

                                TextInput::make('twitter')
                                    ->label('رابط تويتر (X)')
                                    ->url()
                                    ->maxLength(255),
                            ])->columns(2),

                        Tabs\Tab::make('أوقات العمل')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                TextInput::make('working_hours_weekday')
                                    ->label('أوقات العمل (أيام الأسبوع)')
                                    ->maxLength(100),

                                TextInput::make('working_hours_weekend')
                                    ->label('أوقات العمل (نهاية الأسبوع)')
                                    ->maxLength(100),
                            ])->columns(2),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::current()->update($data);

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }
}