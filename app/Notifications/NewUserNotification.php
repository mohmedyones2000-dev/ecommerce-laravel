<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    public function __construct(public User $newUser) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'مستخدم جديد',
            'body'  => 'تم تسجيل مستخدم جديد: ' . $this->newUser->name,
            'icon'  => 'heroicon-o-user-plus',
            'color' => 'info',
            'url'   => '/admin/users/' . $this->newUser->id . '/edit',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}