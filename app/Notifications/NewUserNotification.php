<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public User $newUser) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
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

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'         => $this->id,
            'title'      => 'مستخدم جديد',
            'message'    => 'تم تسجيل مستخدم جديد: ' . $this->newUser->name,
            'icon'       => 'user-plus',
            'color'      => 'info',
            'url'        => '/admin/users/' . $this->newUser->id . '/edit',
            'created_at' => now()->toISOString(),
        ]);
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('admin.notifications')];
    }

    public function broadcastAs(): string
    {
        return 'new.user';
    }
}