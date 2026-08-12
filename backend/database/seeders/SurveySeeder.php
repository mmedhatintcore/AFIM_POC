<?php

namespace Database\Seeders;

use App\Models\SurveyQuestion;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        $aboutYou = ['en' => 'About you', 'ar' => 'عنك'];
        $goals = ['en' => 'Your goals', 'ar' => 'أهدافك'];
        $riskProfile = ['en' => 'Risk profile', 'ar' => 'ملف المخاطر'];
        $preferences = ['en' => 'Preferences', 'ar' => 'تفضيلاتك'];

        $questions = [
            ['sort' => 1, 'key' => 'entity', 'phase' => $aboutYou, 'layout' => 'cards',
                'question' => ['en' => 'Are you investing as an individual or a company?', 'ar' => 'هل تستثمر كفرد أم كمؤسسة؟'],
                'options' => [
                    ['icon' => 'user', 'label' => ['en' => 'Individual', 'ar' => 'فرد'], 'description' => ['en' => 'Personal savings & wealth', 'ar' => 'مدخرات وثروة شخصية']],
                    ['icon' => 'corp', 'label' => ['en' => 'Corporate', 'ar' => 'شركة / مؤسسة'], 'description' => ['en' => 'Company or institutional funds', 'ar' => 'أموال شركة أو مؤسسة']],
                ]],
            ['sort' => 2, 'key' => null, 'phase' => $aboutYou, 'layout' => 'grid',
                'question' => ['en' => 'How old are you?', 'ar' => 'كم عمرك؟'],
                'options' => [
                    ['label' => ['en' => 'Under 30', 'ar' => 'أقل من ٣٠']],
                    ['label' => ['en' => '30 – 45', 'ar' => '٣٠ – ٤٥']],
                    ['label' => ['en' => '45 – 60', 'ar' => '٤٥ – ٦٠']],
                    ['label' => ['en' => 'Above 60', 'ar' => 'فوق الـ٦٠']],
                ]],
            ['sort' => 3, 'key' => null, 'phase' => $aboutYou, 'layout' => 'grid',
                'question' => ['en' => "What's your annual income?", 'ar' => 'ما دخلك السنوي؟'],
                'options' => [
                    ['label' => ['en' => 'Under EGP 250K', 'ar' => 'أقل من ٢٥٠ ألف جنيه']],
                    ['label' => ['en' => 'EGP 250K – 1M', 'ar' => '٢٥٠ ألف – مليون جنيه']],
                    ['label' => ['en' => 'EGP 1M – 5M', 'ar' => 'مليون – ٥ ملايين جنيه']],
                    ['label' => ['en' => 'Above EGP 5M', 'ar' => 'أكثر من ٥ ملايين جنيه']],
                ]],
            ['sort' => 4, 'key' => null, 'phase' => $aboutYou, 'layout' => 'grid',
                'question' => ['en' => 'How much are you planning to invest?', 'ar' => 'كم تخطط أن تستثمر؟'],
                'options' => [
                    ['label' => ['en' => 'Under EGP 100K', 'ar' => 'أقل من ١٠٠ ألف جنيه']],
                    ['label' => ['en' => 'EGP 100K – 500K', 'ar' => '١٠٠ – ٥٠٠ ألف جنيه']],
                    ['label' => ['en' => 'EGP 500K – 5M', 'ar' => '٥٠٠ ألف – ٥ ملايين جنيه']],
                    ['label' => ['en' => 'Above EGP 5M', 'ar' => 'أكثر من ٥ ملايين جنيه']],
                ]],
            ['sort' => 5, 'key' => 'objective', 'phase' => $goals, 'layout' => 'cards',
                'question' => ['en' => "What's your primary investment objective?", 'ar' => 'ما هدفك الاستثماري الأساسي؟'],
                'options' => [
                    ['icon' => 'shield', 'label' => ['en' => 'Saving', 'ar' => 'الادخار'], 'description' => ['en' => 'Preserve capital, high liquidity, accumulated return', 'ar' => 'حفاظ على رأس المال وسيولة عالية وعائد تراكمي'], 'votes' => ['mm_acc' => 3]],
                    ['icon' => 'payout', 'label' => ['en' => 'Regular distributions', 'ar' => 'توزيعات دورية'], 'description' => ['en' => 'Periodic income paid out to you', 'ar' => 'دخل دوري يُصرف لك'], 'votes' => ['mm_dist' => 3, 'imm' => 2]],
                    ['icon' => 'scales', 'label' => ['en' => 'Balanced growth', 'ar' => 'نمو متوازن'], 'description' => ['en' => 'Growth with stability, across assets', 'ar' => 'نمو مع استقرار عبر فئات أصول متعددة'], 'votes' => ['mixed' => 3, 'balanced' => 3, 'metals' => 2]],
                    ['icon' => 'launch', 'label' => ['en' => 'Aggressive growth', 'ar' => 'نمو قوي'], 'description' => ['en' => 'Higher volatility for higher long-term returns', 'ar' => 'تقلبات أعلى مقابل عوائد أكبر على المدى الطويل'], 'votes' => ['equity' => 3, 'iequity' => 3]],
                ]],
            ['sort' => 6, 'key' => 'duration', 'phase' => $goals, 'layout' => 'grid',
                'question' => ['en' => 'How long do you plan to stay invested?', 'ar' => 'ما مدة استثمارك المتوقعة؟'],
                'options' => [
                    ['label' => ['en' => 'Under 1 year', 'ar' => 'أقل من سنة'], 'votes' => ['mm_acc' => 3, 'mm_dist' => 3, 'imm' => 3]],
                    ['label' => ['en' => '1 – 3 years', 'ar' => '١ – ٣ سنوات'], 'votes' => ['mixed' => 3]],
                    ['label' => ['en' => '3 – 5 years', 'ar' => '٣ – ٥ سنوات'], 'votes' => ['balanced' => 3, 'metals' => 3]],
                    ['label' => ['en' => 'Over 5 years', 'ar' => 'أكثر من ٥ سنوات'], 'votes' => ['equity' => 3, 'iequity' => 3]],
                ]],
            ['sort' => 7, 'key' => null, 'phase' => $aboutYou, 'layout' => 'grid',
                'question' => ['en' => 'How much investing experience do you have?', 'ar' => 'ما مستوى خبرتك الاستثمارية؟'],
                'options' => [
                    ['label' => ['en' => 'None', 'ar' => 'لا خبرة']],
                    ['label' => ['en' => 'Limited', 'ar' => 'محدودة']],
                    ['label' => ['en' => 'Moderate', 'ar' => 'متوسطة']],
                    ['label' => ['en' => 'Extensive', 'ar' => 'واسعة']],
                ]],
            ['sort' => 8, 'key' => 'risk', 'phase' => $riskProfile, 'layout' => 'cards',
                'question' => ['en' => 'How much risk are you comfortable with?', 'ar' => 'ما مستوى المخاطرة المناسب لك؟'],
                'options' => [
                    ['icon' => 'gaugeLo', 'label' => ['en' => 'Low', 'ar' => 'منخفضة'], 'description' => ['en' => 'Preserve capital with minimal ups and downs', 'ar' => 'الحفاظ على رأس المال بأقل تقلبات'], 'votes' => ['mm_acc' => 3, 'mm_dist' => 3, 'imm' => 3]],
                    ['icon' => 'gaugeMd', 'label' => ['en' => 'Medium', 'ar' => 'متوسطة'], 'description' => ['en' => 'Moderate fluctuations in pursuit of higher returns', 'ar' => 'تقلبات معتدلة سعياً لعوائد أعلى'], 'votes' => ['mixed' => 3, 'balanced' => 3, 'metals' => 3]],
                    ['icon' => 'gaugeHi', 'label' => ['en' => 'High', 'ar' => 'مرتفعة'], 'description' => ['en' => 'Significant swings for long-term capital growth', 'ar' => 'تقلبات كبيرة مقابل نمو رأس المال طويل الأجل'], 'votes' => ['equity' => 3, 'iequity' => 3]],
                ]],
            ['sort' => 9, 'key' => 'islamic', 'phase' => $preferences, 'layout' => 'cards',
                'question' => ['en' => 'Do you prefer Sharia-compliant investing?', 'ar' => 'هل تفضّل استثماراً متوافقاً مع الشريعة؟'],
                'options' => [
                    ['icon' => 'crescent', 'label' => ['en' => 'Yes', 'ar' => 'نعم'], 'description' => ['en' => 'Sharia-compliant solutions only', 'ar' => 'حلول متوافقة مع الشريعة فقط'], 'votes' => ['imm' => 2, 'iequity' => 2]],
                    ['icon' => 'check', 'label' => ['en' => 'No preference', 'ar' => 'لا تفضيل'], 'description' => ['en' => 'Conventional products are fine', 'ar' => 'لا مانع من المنتجات التقليدية']],
                ]],
            ['sort' => 10, 'key' => null, 'phase' => $riskProfile, 'layout' => 'cards',
                'question' => ['en' => 'Which statement sounds most like you?', 'ar' => 'أي عبارة تصفك أكثر؟'],
                'options' => [
                    ['icon' => 'lock', 'label' => ['en' => 'I prefer preserving my capital even if returns are low.', 'ar' => 'أفضّل الحفاظ على رأس مالي حتى لو كانت العوائد منخفضة.'], 'votes' => ['mm_acc' => 1, 'mm_dist' => 1, 'imm' => 1]],
                    ['icon' => 'waves', 'label' => ['en' => 'I can tolerate moderate fluctuations.', 'ar' => 'أستطيع تحمّل تقلبات معتدلة.'], 'votes' => ['mixed' => 1, 'balanced' => 1]],
                    ['icon' => 'chartDip', 'label' => ['en' => 'I accept temporary losses for higher long-term returns.', 'ar' => 'أتقبّل خسائر مؤقتة مقابل عوائد أعلى على المدى الطويل.'], 'votes' => ['balanced' => 1, 'equity' => 1, 'iequity' => 1]],
                    ['icon' => 'bolt', 'label' => ['en' => 'I seek maximum returns despite significant volatility.', 'ar' => 'أسعى لأقصى عائد رغم التقلبات الكبيرة.'], 'votes' => ['equity' => 1, 'iequity' => 1]],
                ]],
            ['sort' => 11, 'key' => null, 'phase' => $riskProfile, 'layout' => 'grid',
                'question' => ['en' => 'What level of annual volatility can you tolerate?', 'ar' => 'ما نسبة التقلب السنوي التي يمكنك تحمّلها؟'],
                'options' => [
                    ['label' => ['en' => 'Under 5%', 'ar' => 'أقل من ٥٪'], 'votes' => ['mm_acc' => 1, 'mm_dist' => 1, 'imm' => 1]],
                    ['label' => ['en' => '5% – 10%', 'ar' => '٥٪ – ١٠٪'], 'votes' => ['mixed' => 1, 'balanced' => 1, 'metals' => 1]],
                    ['label' => ['en' => '10% – 20%', 'ar' => '١٠٪ – ٢٠٪'], 'votes' => ['balanced' => 1, 'equity' => 1]],
                    ['label' => ['en' => 'Over 20%', 'ar' => 'أكثر من ٢٠٪'], 'votes' => ['equity' => 1, 'iequity' => 1]],
                ]],
            ['sort' => 12, 'key' => null, 'phase' => $riskProfile, 'layout' => 'grid',
                'question' => ['en' => 'How soon might you need this money?', 'ar' => 'متى قد تحتاج إلى هذه الأموال؟'],
                'options' => [
                    ['label' => ['en' => 'Within 3 months', 'ar' => 'خلال ٣ أشهر'], 'votes' => ['mm_acc' => 2, 'mm_dist' => 2, 'imm' => 2]],
                    ['label' => ['en' => 'Within 1 year', 'ar' => 'خلال سنة'], 'votes' => ['mm_acc' => 1, 'mixed' => 1]],
                    ['label' => ['en' => 'Within 3 years', 'ar' => 'خلال ٣ سنوات'], 'votes' => ['balanced' => 1, 'metals' => 1]],
                    ['label' => ['en' => 'No expected need', 'ar' => 'لا حاجة متوقعة'], 'votes' => ['equity' => 1, 'iequity' => 1]],
                ]],
            ['sort' => 13, 'key' => 'multi', 'phase' => $preferences, 'layout' => 'cards',
                'question' => ['en' => 'Do you want to diversify across multiple asset classes?', 'ar' => 'هل تفضّل التنويع عبر عدة فئات من الأصول؟'],
                'options' => [
                    ['icon' => 'grid', 'label' => ['en' => 'Yes, spread it out', 'ar' => 'نعم، وزّع استثماري'], 'description' => ['en' => 'Diversification across asset classes', 'ar' => 'تنويع عبر فئات أصول متعددة'], 'votes' => ['balanced' => 2, 'metals' => 2, 'mixed' => 2]],
                    ['icon' => 'target', 'label' => ['en' => 'No, keep it focused', 'ar' => 'لا، استراتيجية مركّزة'], 'description' => ['en' => 'A focused strategy based on your answers', 'ar' => 'استراتيجية مركّزة وفق إجاباتك']],
                ]],
        ];

        foreach ($questions as $question) {
            SurveyQuestion::updateOrCreate(['sort' => $question['sort']], $question);
        }
    }
}
