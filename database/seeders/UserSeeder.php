<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. المشرف العام
        User::updateOrCreate(
            ['email' => 'admin@matjari.com'],
            [
                'name'        => 'المشرف العام',
                'phone'       => '0599000001',
                'password'    => Hash::make('password'),
                'role'        => 'admin',
                'permissions' => null,
            ]
        );

        // 2. المديرون
        $managers = [
            [
                'name'        => 'أحمد المدير',
                'email'       => 'ahmed.manager@matjari.com',
                'phone'       => '0599000002',
                'permissions' => ['products', 'categories', 'sub_categories', 'brands', 'colors'],
            ],
            [
                'name'        => 'سارة المديرة',
                'email'       => 'sara.manager@matjari.com',
                'phone'       => '0599000003',
                'permissions' => ['orders', 'coupons', 'reviews', 'addresses', 'cities'],
            ],
            [
                'name'        => 'خالد المدير',
                'email'       => 'khaled.manager@matjari.com',
                'phone'       => '0599000004',
                'permissions' => ['products', 'orders', 'reviews', 'settings'],
            ],
        ];

        foreach ($managers as $manager) {
            User::updateOrCreate(
                ['email' => $manager['email']],
                [
                    'name'        => $manager['name'],
                    'phone'       => $manager['phone'],
                    'password'    => Hash::make('password'),
                    'role'        => 'manager',
                    'permissions' => $manager['permissions'],
                ]
            );
        }

        // 3. الزبائن الأساسيون
        $customers = [
            ['name' => 'محمد العميل',   'email' => 'mohamed@test.com', 'phone' => '0599111111'],
            ['name' => 'فاطمة الزهراء', 'email' => 'fatima@test.com',  'phone' => '0599222222'],
            ['name' => 'علي الحسن',     'email' => 'ali@test.com',     'phone' => '0599333333'],
            ['name' => 'نور الهدى',     'email' => 'noor@test.com',    'phone' => '0599444444'],
            ['name' => 'يوسف الكريم',   'email' => 'yousef@test.com',  'phone' => '0599555555'],
        ];

        foreach ($customers as $customer) {
            User::updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name'        => $customer['name'],
                    'phone'       => $customer['phone'],
                    'password'    => Hash::make('password'),
                    'role'        => 'customer',
                    'permissions' => null,
                ]
            );
        }

        $this->command->info('Users seeded: 1 admin + 3 managers + 5 customers');
    }
}