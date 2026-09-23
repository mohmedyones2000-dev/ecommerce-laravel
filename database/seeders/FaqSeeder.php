<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question'   => 'كيف يمكنني تتبع طلبي؟',
                'answer'     => 'يمكنك تتبع طلبك من خلال صفحة "طلباتي" في حسابك، حيث ستجد حالة الطلب محدثة باستمرار مع مراحل التوصيل.',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'question'   => 'ما هي طرق الدفع المتاحة؟',
                'answer'     => 'نوفر حالياً الدفع عند الاستلام، وسنضيف قريباً بطاقات الائتمان والمحافظ الإلكترونية.',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'question'   => 'كم يستغرق الشحن؟',
                'answer'     => 'يستغرق الشحن من 2 إلى 5 أيام عمل حسب المدينة. يمكنك معرفة تكلفة الشحن من صفحة إتمام الطلب.',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'question'   => 'هل يمكنني إرجاع المنتج؟',
                'answer'     => 'نعم، يمكنك إرجاع المنتج خلال 14 يوماً من الاستلام بشرط أن يكون بحالته الأصلية.',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'question'   => 'كيف أستخدم كود الخصم؟',
                'answer'     => 'أضف المنتجات إلى السلة، ثم في صفحة السلة أدخل كود الخصم في الحقل المخصص واضغط "تطبيق".',
                'sort_order' => 5,
                'is_active'  => true,
            ],
            [
                'question'   => 'هل الشحن مجاني؟',
                'answer'     => 'الشحن مجاني لبعض المدن المختارة. يمكنك معرفة تكلفة الشحن حسب مدينتك من صفحة إتمام الطلب.',
                'sort_order' => 6,
                'is_active'  => true,
            ],
            [
                'question'   => 'كيف أتواصل مع خدمة العملاء؟',
                'answer'     => 'يمكنك التواصل معنا عبر صفحة "اتصل بنا" أو عبر البريد الإلكتروني info@matjari.com.',
                'sort_order' => 7,
                'is_active'  => true,
            ],
            [
                'question'   => 'هل يمكنني تعديل طلبي بعد إرساله؟',
                'answer'     => 'يمكنك تعديل الطلب فقط إذا كان بحالة "قيد المراجعة". بعد ذلك لا يمكن التعديل.',
                'sort_order' => 8,
                'is_active'  => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }

        $this->command->info('✅ FAQs seeded: ' . Faq::count() . ' FAQs');
    }
}