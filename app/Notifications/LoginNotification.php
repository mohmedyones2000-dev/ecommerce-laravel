<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $ipAddress,
        public string $userAgent
    ) {}

    /**
     * قنوات الإرسال: database + mail
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * إشعار قاعدة البيانات
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'تسجيل دخول جديد',
            'body'    => 'تم تسجيل الدخول إلى حسابك بنجاح',
            'icon'    => 'heroicon-o-shield-check',
            'color'   => 'info',
            'ip'      => $this->ipAddress,
            'url'     => '/',
        ];
    }

    /**
     * إشعار البريد الإلكتروني
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔐 تنبيه أمني: تسجيل دخول جديد')
            ->greeting('مرحباً ' . $notifiable->name)
            ->line('تم تسجيل الدخول إلى حسابك بنجاح.')
            ->line('**التفاصيل:**')
            ->line('📅 الوقت: ' . now()->format('Y-m-d H:i:s'))
            ->line('🌐 IP: ' . $this->ipAddress)
            ->line('💻 الجهاز: ' . $this->userAgent)
            ->line('إذا لم تكن أنت من قام بهذا، يرجى تغيير كلمة المرور فوراً.')
            ->action('عرض الإشعارات', url('/notifications'))
            ->salutation('مع تحيات، فريق متجري');
    }
}