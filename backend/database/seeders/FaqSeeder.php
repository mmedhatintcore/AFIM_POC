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
                'sort' => 1,
                'question' => ['en' => "I'm new — how do I start, and what suits me?", 'ar' => 'جديد — كيف أبدأ وما الذي يناسبني؟'],
                'answer' => [
                    'en' => "Answer three quick questions and we'll point you to the right product based on your profile, goal and how hands-on you want to be.",
                    'ar' => 'أجب عن ثلاثة أسئلة سريعة وسنوجّهك للمنتج المناسب وفق ملفك وهدفك ومدى رغبتك في الإدارة بنفسك.',
                ],
                'action_type' => 'finder',
                'action_label' => ['en' => 'Find your service', 'ar' => 'اعثر على خدمتك'],
            ],
            [
                'sort' => 2,
                'question' => ['en' => 'I want low risk and easy access to my cash', 'ar' => 'أريد مخاطر منخفضة وسهولة الوصول لأموالي'],
                'answer' => [
                    'en' => 'Money-market and fixed-income funds aim to preserve capital and stay liquid. For corporate cash, our Liquidity Management service is tailored to your cash-flow cycle.',
                    'ar' => 'تستهدف صناديق أسواق النقد والدخل الثابت الحفاظ على رأس المال والسيولة. وللشركات، تُصمَّم خدمة إدارة السيولة وفق دورة التدفق النقدي لديك.',
                ],
                'action_type' => 'prices',
                'action_label' => ['en' => 'See low-risk funds & prices', 'ar' => 'استعرض الصناديق منخفضة المخاطر'],
            ],
            [
                'sort' => 3,
                'question' => ['en' => 'Do you offer Sharia-compliant or gold options?', 'ar' => 'هل تقدمون خيارات متوافقة مع الشريعة أو الذهب؟'],
                'answer' => [
                    'en' => 'Yes — AFIM manages a Sharia-compliant fund and the Dahab gold fund alongside conventional strategies.',
                    'ar' => 'نعم — تدير الشركة صندوقاً متوافقاً مع الشريعة وصندوق دهب للذهب إلى جانب الاستراتيجيات التقليدية.',
                ],
                'action_type' => 'prices',
                'action_label' => ['en' => 'View Sharia & gold funds', 'ar' => 'استعرض صناديق الشريعة والذهب'],
            ],
            [
                'sort' => 4,
                'question' => ['en' => "I'm an institution / want a tailored mandate", 'ar' => 'أنا مؤسسة / أريد محفظة مخصّصة'],
                'answer' => [
                    'en' => 'Our discretionary Portfolio Management builds a bespoke strategy across asset classes for institutions and high-net-worth clients.',
                    'ar' => 'تبني خدمة إدارة المحافظ بالتفويض استراتيجية مخصّصة عبر فئات الأصول للمؤسسات وكبار العملاء.',
                ],
                'action_type' => 'services',
                'action_label' => ['en' => 'See our services', 'ar' => 'استعرض خدماتنا'],
            ],
            [
                'sort' => 5,
                'question' => ['en' => 'How are prices updated, and can I redeem any time?', 'ar' => 'كيف تُحدَّث الأسعار وهل أسترد في أي وقت؟'],
                'answer' => [
                    'en' => 'Net asset values are published on a regular cycle (figures here are illustrative). Subscription and redemption run on a regular cycle via our Subscription & Redemption service; terms depend on the fund.',
                    'ar' => 'تُنشر صافي قيمة الأصول بصفة دورية (الأرقام هنا توضيحية). ويتم الاكتتاب والاسترداد بصفة دورية عبر خدمة تلقي الاكتتاب والاسترداد، وتختلف الشروط حسب الصندوق.',
                ],
                'action_type' => 'prices',
                'action_label' => ['en' => 'Check prices & yields', 'ar' => 'تابع الأسعار والعوائد'],
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['sort' => $faq['sort']], $faq);
        }
    }
}
