<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'تعديل المستخدم: ' . ($this->record->name ?? '');
    }

    public function getSubheading(): ?string
    {
        $role = UserResource::roleOptions()[$this->record->role] ?? $this->record->role;
        $email = $this->record->email ?? '—';

        return "{$email} • {$role}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المستخدمون' => UserResource::getUrl('index'),
            'تعديل المستخدم',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم حفظ التعديلات بنجاح';
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم حفظ التعديلات')
            ->body("تم تحديث بيانات المستخدم \"{$this->record->name}\" بنجاح.")
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('حفظ التعديلات')
                ->icon('heroicon-o-check'),

            $this->getCancelFormAction()
                ->label('إلغاء')
                ->icon('heroicon-o-x-mark'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('change_password')
                ->label('تغيير كلمة المرور')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->modalHeading('تغيير كلمة المرور')
                ->modalDescription(fn (): string =>
                    "أدخل كلمة مرور جديدة للمستخدم \"{$this->record->name}\".")
                ->modalIcon('heroicon-o-key')
                ->modalSubmitActionLabel('حفظ كلمة المرور')
                ->modalCancelActionLabel('إلغاء')
                ->form([
                    \Filament\Forms\Components\TextInput::make('password')
                        ->label('كلمة المرور الجديدة')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->revealable(),

                    \Filament\Forms\Components\TextInput::make('password_confirmation')
                        ->label('تأكيد كلمة المرور')
                        ->password()
                        ->required()
                        ->same('password')
                        ->dehydrated(false)
                        ->revealable(),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                    ]);

                    Notification::make()
                        ->title('تم تغيير كلمة المرور')
                        ->body("تم تحديث كلمة مرور \"{$this->record->name}\" بنجاح.")
                        ->success()
                        ->icon('heroicon-o-check-circle')
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف المستخدم')
                ->icon('heroicon-o-trash')
                ->visible(fn (): bool => $this->record->id !== auth()->id())
                ->requiresConfirmation()
                ->modalHeading('حذف المستخدم')
                ->modalDescription(fn (): string =>
                    "هل أنت متأكد من حذف المستخدم \"{$this->record->name}\"؟ لا يمكن التراجع عن هذا الإجراء.")
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $permissions = $data['permissions'] ?? [];

        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?? [];
        }

        if (! is_array($permissions)) {
            $permissions = [];
        }

        $data['permissions'] = array_values($permissions);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $role = $data['role'] ?? 'customer';

        $validPermissions = array_keys(User::PERMISSIONS);

        $permissions = $data['permissions'] ?? [];

        if (! is_array($permissions)) {
            $permissions = [];
        }

        $permissions = array_values(array_intersect($permissions, $validPermissions));

        if ($role !== 'manager') {
            $permissions = [];
        }

        $data['permissions'] = $permissions;

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}