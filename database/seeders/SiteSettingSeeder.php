<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_name'             => 'متجري',
                'favicon'               => null,
                'logo'                  => null,
                'primary_color'         => '#C9A961',
                'email'                 => 'info@matjari.com',
                'phone'                 => '+970 599 123 456',
                'address'               => 'فلسطين - غزة - شارع عمر المختار',
                'whatsapp'              => '970599123456',
                'facebook'              => 'https://facebook.com/matjari',
                'instagram'             => 'https://instagram.com/matjari',
                'twitter'               => 'https://twitter.com/matjari',
                'working_hours_weekday' => 'السبت - الخميس: 9ص - 9م',
                'working_hours_weekend' => 'الجمعة: 2م - 9م',
            ]
        );

        $this->command->info('✅ Site settings seeded');
    }
}