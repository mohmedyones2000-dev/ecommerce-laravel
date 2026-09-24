<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AddUsersSeeder extends Seeder
{
    private array $maleNames = ['محمد', 'أحمد', 'علي', 'عمر', 'خالد', 'يوسف', 'عبدالله', 'سامي', 'كريم', 'يحيى', 'إبراهيم', 'رامي', 'زياد', 'بشار', 'أنس', 'مالك', 'نادر', 'فادي', 'جمال', 'سعيد', 'حمزة', 'مصطفى', 'أيمن', 'طارق', 'سامر'];
    private array $femaleNames = ['فاطمة', 'عائشة', 'مريم', 'خديجة', 'زينب', 'سارة', 'نور', 'ليلى', 'هبة', 'رنا', 'دانا', 'ريما', 'سمية', 'هند', 'أمل', 'لينا', 'ياسمين', 'سلمى', 'رغد', 'جنى', 'ريم', 'شذى', 'نور الهدى', 'بشرى', 'آية'];
    private array $lastNames = ['أحمد', 'محمود', 'خالد', 'حسن', 'علي', 'عمر', 'يوسف', 'إبراهيم', 'ناصر', 'زكي', 'سعيد', 'منصور', 'فؤاد', 'رشيد', 'جواد', 'كريم', 'سالم', 'حمدان', 'شاهين', 'قاسم', 'الشريف', 'النجار', 'الحسيني', 'الزهراني', 'المصري'];

    public function run(): void
    {
        $this->command->info('→ إضافة 100 عميل جديد...');

        $hasRole = Schema::hasColumn('users', 'role');
        $hasVerified = Schema::hasColumn('users', 'email_verified_at');
        $hasPhone = Schema::hasColumn('users', 'phone');
        $hasPermissions = Schema::hasColumn('users', 'permissions');

        $added = 0;

        for ($i = 1; $i <= 100; $i++) {
            $isMale = rand(0, 1) === 1;
            $firstName = $isMale
                ? $this->maleNames[array_rand($this->maleNames)]
                : $this->femaleNames[array_rand($this->femaleNames)];
            $lastName = $this->lastNames[array_rand($this->lastNames)];

            $email = "customer{$i}@matjari.test";

            if (User::where('email', $email)->exists()) {
                continue;
            }

            $data = [
                'name'     => "$firstName $lastName",
                'email'    => $email,
                'password' => Hash::make('password'),
            ];

            if ($hasPhone) $data['phone'] = '05' . rand(10000000, 99999999);
            if ($hasRole) $data['role'] = 'customer';
            if ($hasVerified) $data['email_verified_at'] = rand(0, 4) > 0 ? now()->subDays(rand(1, 180)) : null;
            if ($hasPermissions) $data['permissions'] = null;

            User::create($data);
            $added++;
        }

        $this->command->line("   ✓ {$added} عميل جديد");
        $this->command->line('   ℹ المجموع الآن: ' . User::count() . ' مستخدم');
    }
}