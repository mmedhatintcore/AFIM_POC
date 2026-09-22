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
                'question' => ['en' => 'Age', 'ar' => 'العمر'],
                'options' => [
                    ['label' => ['en' => 'Under 21', 'ar' => 'أقل من ٢١']],
                    ['label' => ['en' => '30 – 45', 'ar' => '٣٠ – ٤٥']],
                    ['label' => ['en' => '45 – 60', 'ar' => '٤٥ – ٦٠']],
                    ['label' => ['en' => 'Above 60', 'ar' => 'فوق الـ٦٠']],
                ]],
            ['sort' => 3, 'key' => null, 'phase' => $aboutYou, 'layout' => 'grid',
                'question' => ['en' => 'Annual Income', 'ar' => 'الدخل السنوي'],
                'options' => [
                    ['label' => ['en' => 'Under EGP 250K', 'ar' => 'أقل من ٢٥٠ ألف جنيه']],
                    ['label' => ['en' => 'EGP 250K – 1M', 'ar' => '٢٥٠ ألف – مليون جنيه']],
                    ['label' => ['en' => 'EGP 1M – 5M', 'ar' => 'مليون – ٥ ملايين جنيه']],
                    ['label' => ['en' => 'Above EGP 5M', 'ar' => 'أكثر من ٥ ملايين جنيه']],
                ]],
            ['sort' => 4, 'key' => null, 'phase' => $aboutYou, 'layout' => 'grid',
                'question' => ['en' => 'Investment Amount', 'ar' => 'قيمة الاستثمار'],
                'options' => [
                    ['label' => ['en' => 'Under EGP 250K', 'ar' => 'أقل من ٢٥٠ ألف جنيه']],
                    ['label' => ['en' => 'EGP 100K – 500K', 'ar' => '١٠٠ – ٥٠٠ ألف جنيه']],
                    ['label' => ['en' => 'EGP 500K – 5M', 'ar' => '٥٠٠ ألف – ٥ ملايين جنيه']],
                    ['label' => ['en' => 'Above EGP 5M', 'ar' => 'أكثر من ٥ ملايين جنيه']],
                ]],
            ['sort' => 5, 'key' => 'objective', 'phase' => $goals, 'layout' => 'cards',
                'question' => ['en' => 'Investment Objective', 'ar' => 'الهدف الاستثماري'],
                'options' => [
                    ['icon' => 'shield', 'label' => ['en' => 'Saving', 'ar' => 'الادخار'], 'description' => ['en' => 'Preserve capital, high liquidity, accumulated return', 'ar' => 'حفاظ على رأس المال وسيولة عالية وعائد تراكمي'], 'votes' => ['mm_acc' => 3]],
                    ['icon' => 'payout', 'label' => ['en' => 'Distribution', 'ar' => 'التوزيعات'], 'description' => ['en' => 'Periodic income paid out to you', 'ar' => 'دخل دوري يُصرف لك'], 'votes' => ['mm_dist' => 3, 'imm' => 2]],
                    ['icon' => 'scales', 'label' => ['en' => 'Balanced Growth', 'ar' => 'نمو متوازن'], 'description' => ['en' => 'Growth with stability, across assets', 'ar' => 'نمو مع استقرار عبر فئات أصول متعددة'], 'votes' => ['mixed' => 3, 'balanced' => 3, 'metals' => 2]],
                    ['icon' => 'launch', 'label' => ['en' => 'Aggressive Growth', 'ar' => 'نمو قوي'], 'description' => ['en' => 'Higher volatility for higher long-term returns', 'ar' => 'تقلبات أعلى مقابل عوائد أكبر على المدى الطويل'], 'votes' => ['equity' => 3, 'iequity' => 3]],
                ]],
            ['sort' => 6, 'key' => 'duration', 'phase' => $goals, 'layout' => 'grid',
                'question' => ['en' => 'Investment Duration', 'ar' => 'مدة الاستثمار'],
                'options' => [
                    ['label' => ['en' => '< 1 year', 'ar' => 'أقل من سنة'], 'votes' => ['mm_acc' => 3, 'mm_dist' => 3, 'imm' => 3]],
                    ['label' => ['en' => '1 – 3 years', 'ar' => '١ – ٣ سنوات'], 'votes' => ['mixed' => 3]],
                    ['label' => ['en' => '3 – 5 years', 'ar' => '٣ – ٥ سنوات'], 'votes' => ['balanced' => 3, 'metals' => 3]],
                    ['label' => ['en' => 'More than 5 years', 'ar' => 'أكثر من ٥ سنوات'], 'votes' => ['equity' => 3, 'iequity' => 3]],
                ]],
            ['sort' => 7, 'key' => null, 'phase' => $aboutYou, 'layout' => 'grid',
                'question' => ['en' => 'Investment Experience', 'ar' => 'الخبرة الاستثمارية'],
                'options' => [
                    ['label' => ['en' => 'No experience', 'ar' => 'بدون خبرة']],
                    ['label' => ['en' => 'Limited experience', 'ar' => 'خبرة محدودة']],
                    ['label' => ['en' => 'Moderate experience', 'ar' => 'خبرة متوسطة']],
                    ['label' => ['en' => 'Extensive experience', 'ar' => 'خبرة واسعة']],
                ]],
            ['sort' => 8, 'key' => 'risk', 'phase' => $riskProfile, 'layout' => 'cards',
                'question' => ['en' => 'Risk Tolerance', 'ar' => 'تحمّل المخاطر'],
                'options' => [
                    ['icon' => 'gaugeHi', 'label' => ['en' => 'High', 'ar' => 'مرتفعة'], 'description' => ['en' => 'Significant swings for long-term capital growth', 'ar' => 'تقلبات كبيرة مقابل نمو رأس المال طويل الأجل'], 'votes' => ['equity' => 3, 'iequity' => 3]],
                    ['icon' => 'gaugeMd', 'label' => ['en' => 'Medium', 'ar' => 'متوسطة'], 'description' => ['en' => 'Moderate fluctuations in pursuit of higher returns', 'ar' => 'تقلبات معتدلة سعياً لعوائد أعلى'], 'votes' => ['mixed' => 3, 'balanced' => 3, 'metals' => 3]],
                    ['icon' => 'gaugeLo', 'label' => ['en' => 'Low', 'ar' => 'منخفضة'], 'description' => ['en' => 'Preserve capital with minimal ups and downs', 'ar' => 'الحفاظ على رأس المال بأقل تقلبات'], 'votes' => ['mm_acc' => 3, 'mm_dist' => 3, 'imm' => 3]],
                ]],
            ['sort' => 9, 'key' => 'islamic', 'phase' => $preferences, 'layout' => 'cards',
                'question' => ['en' => 'Islamic investment?', 'ar' => 'استثمار إسلامي؟'],
                'options' => [
                    ['icon' => 'crescent', 'label' => ['en' => 'Yes', 'ar' => 'نعم'], 'votes' => ['imm' => 2, 'iequity' => 2]],
                    ['icon' => 'check', 'label' => ['en' => 'No', 'ar' => 'لا']],
                ]],
            ['sort' => 10, 'key' => 'multi', 'phase' => $preferences, 'layout' => 'cards',
                'question' => ['en' => 'Multiple Assets?', 'ar' => 'أصول متعددة؟'],
                'options' => [
                    ['icon' => 'grid', 'label' => ['en' => 'Yes', 'ar' => 'نعم'], 'votes' => ['balanced' => 2, 'metals' => 2, 'mixed' => 2]],
                    ['icon' => 'target', 'label' => ['en' => 'No', 'ar' => 'لا']],
                ]],
        ];

        // This is the full, authoritative question set — prune anything left
        // over from a previous shape (e.g. old filler risk-profile questions)
        // so the admin list and the live survey never drift apart.
        $sorts = array_column($questions, 'sort');
        SurveyQuestion::whereNotIn('sort', $sorts)->delete();

        foreach ($questions as $question) {
            SurveyQuestion::updateOrCreate(['sort' => $question['sort']], $question);
        }
    }
}
