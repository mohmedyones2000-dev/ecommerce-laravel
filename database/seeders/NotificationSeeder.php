<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('role', ['admin', 'manager', 'customer'])->get();

        if ($users->isEmpty()) {
            return;
        }

        $templates = [
            // إشعارات الطلبات
            [
                'title' => 'طلب جديد',
                'message' => 'تم استلام طلب جديد برقم ORD-XXXX',
                'type' => 'success',
                'icon' => 'order',
                'link' => '/admin/orders',
                'roles' => ['admin', 'manager'],
            ],
            [
                'title' => 'تم شحن طلبك',
                'message' => 'طلبك في الطريق إليك',
                'type' => 'info',
                'icon' => 'shipping',
                'link' => '/my-orders',
                'roles' => ['customer'],
            ],
            [
                'title' => 'تم توصيل طلبك',
                'message' => 'نتمنى أن تكون راضياً عن تجربتك',
                'type' => 'success',
                'icon' => 'delivered',
                'link' => '/my-orders',
                'roles' => ['customer'],
            ],
            // إشعارات المخزون
            [
                'title' => 'المخزون منخفض',
                'message' => 'بعض المنتجات على وشك النفاد',
                'type' => 'warning',
                'icon' => 'payment',
                'link' => '/admin/products',
                'roles' => ['admin', 'manager'],
            ],
            // إشعارات التقييمات
            [
                'title' => 'تقييم جديد',
                'message' => 'تم إضافة تقييم جديد على أحد المنتجات',
                'type' => 'info',
                'icon' => 'wishlist',
                'link' => '/admin/reviews',
                'roles' => ['admin', 'manager'],
            ],
            // عروض
            [
                'title' => 'عرض جديد متاح',
                'message' => 'خصم 20% على جميع المنتجات',
                'type' => 'success',
                'icon' => 'promo',
                'link' => '/products',
                'roles' => ['customer'],
            ],
            // ترحيب
            [
                'title' => 'مرحباً بعودتك',
                'message' => 'نتمنى لك تجربة تسوق ممتعة',
                'type' => 'success',
                'icon' => 'login',
                'link' => '/',
                'roles' => ['customer'],
            ],
            // السلة
            [
                'title' => 'تمت الإضافة إلى السلة',
                'message' => 'منتج جديد في سلتك',
                'type' => 'success',
                'icon' => 'cart',
                'link' => '/cart',
                'roles' => ['customer'],
            ],
            // الإلغاء
            [
                'title' => 'تم إلغاء الطلب',
                'message' => 'تم إلغاء طلبك بناءً على طلبك',
                'type' => 'danger',
                'icon' => 'cancelled',
                'link' => '/my-orders',
                'roles' => ['customer'],
            ],
        ];

        $totalNotifications = 0;

        foreach ($users as $user) {
            // ✅ 3-8 إشعارات لكل مستخدم
            $count = rand(3, 8);

            // فلترة القوالب حسب الدور
            $userTemplates = array_filter($templates, fn($t) => in_array($user->role, $t['roles']));

            if (empty($userTemplates)) continue;

            $userTemplates = array_values($userTemplates);

            for ($i = 0; $i < $count; $i++) {
                $template = $userTemplates[array_rand($userTemplates)];

                $isRead = rand(0, 1) === 1;

                Notification::create([
                    'user_id'   => $user->id,
                    'title'     => $template['title'],
                    'message'   => $template['message'],
                    'type'      => $template['type'],
                    'icon'      => $template['icon'],
                    'link'      => $template['link'],
                    'read_at'   => $isRead ? now()->subHours(rand(1, 72)) : null,
                    'created_at' => now()->subHours(rand(1, 168)),
                ]);

                $totalNotifications++;
            }
        }

        $this->command->info('✅ Notifications seeded: ' . $totalNotifications . ' notifications');
    }
}