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
            'شارع عمر المختار',
            'شارع الوحدة',
            'شارع الرمال',
            'شارع النصر',
            'شارع الجلاء',
            'شارع الجمهورية',
            'شارع صلاح الدين',
        ];

        foreach ($customers as $customer) {
            // ✅ 1-2 عنوان لكل زبون
            $addressCount = rand(1, 2);

            for ($i = 0; $i < $addressCount; $i++) {
                $city = $cities->random();

                Address::create([
                    'user_id'        => $customer->id,
                    'city_id'        => $city->id,
                    'street_address' => $streets[array_rand($streets)] . ' - بناء رقم ' . rand(1, 100),
                    'phone'          => '059' . rand(1000000, 9999999),
                    'notes'          => rand(0, 1) ? 'الطابق الثاني - شقة 5' : null,
                ]);
            }
        }

        $this->command->info('✅ Addresses seeded: ' . Address::count() . ' addresses');
    }
}