<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddNewsletterSubscribersSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة 20 مشترك في النشرة...');

        if (!Schema::hasTable('newsletter_subscribers')) {
            $this->command->error('   ✗ جدول newsletter_subscribers غير موجود');
            return;
        }

        $customers = User::where('role', 'customer')->inRandomOrder()->take(40)->get();

        if ($customers->isEmpty()) {
            $this->command->error('   ✗ لا يوجد عملاء');
            return;
        }

        $columns = Schema::getColumnListing('newsletter_subscribers');
        $hasName = in_array('name', $columns);
        $hasActive = in_array('is_active', $columns);
        $hasSubscribedAt = in_array('subscribed_at', $columns);
        $hasUnsubscribedAt = in_array('unsubscribed_at', $columns);

        $added = 0;
        foreach ($customers as $customer) {
            if ($added >= 20) break;

            if (DB::table('newsletter_subscribers')->where('email', $customer->email)->exists()) {
                continue;
            }

            $data = [
                'email' => $customer->email,
                'created_at' => now()->subDays(rand(1, 180)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ];

            if ($hasName) $data['name'] = $customer->name;
            if ($hasActive) $data['is_active'] = true;
            if ($hasSubscribedAt) $data['subscribed_at'] = $data['created_at'];
            if ($hasUnsubscribedAt) $data['unsubscribed_at'] = null;

            try {
                DB::table('newsletter_subscribers')->insert($data);
                $added++;
            } catch (\Exception $e) {}
        }

        $this->command->line("   ✓ {$added} مشترك جديد");
    }
}