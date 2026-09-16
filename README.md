# متجري - E-commerce Platform

متجر إلكتروني كامل مبني بـ Laravel + Filament.

## التقنيات

- Backend: Laravel 13, PHP 8.4
- Database: MySQL
- Frontend: Blade, Tailwind CSS, Alpine.js
- Admin Panel: Filament v3
- Build Tool: Vite

## الميزات

- عرض المنتجات مع فلاتر متقدمة
- بحث Live مع Autocomplete
- سلة تسوق ديناميكية
- نظام كوبونات الخصم
- إدارة الطلبات مع تتبع الحالة
- تقييمات ومراجعات
- قائمة المفضلة
- نظام إشعارات فوري
- الوضع الداكن
- تصميم متجاوب
- نظام أدوار (Admin, Manager, Customer) مع صلاحيات
- لوحة تحكم متكاملة مع إحصائيات
- إدارة الألوان والمقاسات وأدلة المقاسات
- Newsletter
- زر WhatsApp عائم
- مشاركة المنتجات على السوشيال ميديا

## متطلبات التشغيل

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 5.7+

## التثبيت

```bash
git clone https://github.com/USERNAME/REPO.git
cd REPO
composer install
cp .env.example .env
php artisan key:generate