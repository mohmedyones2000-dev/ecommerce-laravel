<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $cities = City::all();

        if ($customers->isEmpty() || $cities->isEmpty()) {
            return;
        }

        $streets = [
            'شارع عمر المختار - مفترق السرايا',
            'شارع الوحدة - قرب مجمع الشفاء',
            'شارع الرمال - بالقرب من منتزه بلدية غزة',
            'شارع النصر - عمارة الأوقاف',
            'شارع الجلاء - تقاطع برج الجوهرة',
            'شارع صلاح الدين - دير البلح',
            'شارع البحر - مخيم الشاطئ',
            'شارع الشهداء - تل الهوا',
            'شارع الثلاثيني - حي الزيتون',
            'شارع المنصورة - حي الشجاعية',
            'شارع جمال عبد الناصر',
            'شارع أحمد ياسين - الشيخ رضوان',
            'شارع البحر - خان يونس',
            'شارع المطار - رفح',
        ];

        foreach ($customers as $customer) {
            $addressCount = rand(1, 2);

            for ($i = 0; $i < $addressCount; $i++) {
                $city = $cities->random();

                Address::create([
                    'user_id'        => $customer->id,
                    'city_id'        => $city->id,
                    'street_address' => $streets[array_rand($streets)] . ' - بناء رقم ' . rand(1, 150),
                    'phone'          => (rand(0, 1) ? '059' : '056') . rand(1000000, 9999999),
                    'notes'          => rand(0, 1) ? 'الطابق ' . rand(1, 6) . ' - شقة ' . rand(1, 12) : 'بجوار المعلم المعروف',
                ]);
            }
        }
    }
}