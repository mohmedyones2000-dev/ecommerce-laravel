<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasResourcePermission;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use HasResourcePermission;
    use InteractsWithForms;

    protected static string $permissionKey = 'settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'إدارة المحتوى';

    protected static ?string $navigationLabel = 'إعدادات الموقع';

    protected static ?string $title = 'إعدادات الموقع';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::current();
        $this->form->fill($settings->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('الهوية البصرية')
                    ->description('شعار الموقع والأيقونة التي تظهر في تبويب المتصفح')
                    ->schema([
                        Forms\Components\TextInput::make('site_name')
                            ->label('اسم الموقع')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('logo')
                            ->label('شعار الموقع')
                            ->image()
                            ->directory('site')
                            ->maxSize(1024)
                            ->imageEditor()
                            ->helperText('يُفضّل أن يكون الشعار بخلفية شفافة PNG، بمقاس 512×512 بكسل.'),

                        Forms\Components\FileUpload::make('favicon')
                            ->label('أيقونة الموقع (Favicon)')
                            ->image()
                            ->directory('site')
                            ->maxSize(512)
                            ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/x-icon', 'image/vnd.microsoft.icon'])
                            ->helperText('الأيقونة الصغيرة التي تظهر في تبويب المتصفح. يُفضّل 64×64 بكسل (PNG أو SVG).'),
                    ])->columns(2),

                Forms\Components\Section::make('معلومات التواصل')
                    ->description('ستظهر هذه المعلومات في صفحة "اتصل بنا" والفوتر')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label('رقم واتساب')
                            ->tel()
                            ->maxLength(255)
                            ->helperText('أدخل الرقم مع رمز الدولة بدون + مثال: 970599123456'),

                        Forms\Components\TextInput::make('address')
                            ->label('العنوان')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('أوقات العمل')
                    ->schema([
                        Forms\Components\TextInput::make('working_hours_weekday')
                            ->label('أيام الأسبوع')
                            ->maxLength(255)
                            ->placeholder('السبت - الخميس: 9ص - 6م'),

                        Forms\Components\TextInput::make('working_hours_weekend')
                            ->label('نهاية الأسبوع')
                            ->maxLength(255)
                            ->placeholder('الجمعة: مغلق'),
                    ])->columns(2),

                Forms\Components\Section::make('روابط التواصل الاجتماعي')
                    ->schema([
                        Forms\Components\TextInput::make('facebook')
                            ->label('فيسبوك')
                            ->url()
                            ->placeholder('https://facebook.com/username'),

                        Forms\Components\TextInput::make('instagram')
                            ->label('إنستقرام')
                            ->url()
                            ->placeholder('https://instagram.com/username'),

                        Forms\Components\TextInput::make('twitter')
                            ->label('تويتر / X')
                            ->url()
                            ->placeholder('https://twitter.com/username'),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = SiteSetting::current();
        $settings->update($data);

        Notification::make()
            ->title('تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }
}