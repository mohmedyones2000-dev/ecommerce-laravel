<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'إضافة مستخدم جديد';
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المستخدمون' => UserResource::getUrl('index'),
            'إضافة مستخدم',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'تم إنشاء المستخدم بنجاح';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم إنشاء المستخدم')
            ->body("المستخدم \"{$this->record->name}\" أُضيف بنجاح.")
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('حفظ المستخدم')
                ->icon('heroicon-o-check'),

            $this->getCreateAnotherFormAction()
                ->label('حفظ وإضافة آخر')
                ->icon('heroicon-o-plus-circle'),

            $this->getCancelFormAction()
                ->label('إلغاء')
                ->icon('heroicon-o-x-mark'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
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
}