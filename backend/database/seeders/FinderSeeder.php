<?php

namespace Database\Seeders;

use App\Models\FinderQuestion;
use Illuminate\Database\Seeder;

class FinderSeeder extends Seeder
{
    /**
     * Point weights reproduce the finder's original hard-coded decision
     * tree as closely as a simple additive scoring model can — see
     * docs/api-contract/finder.md for the reasoning. Now that questions
     * and options are freely add/edit/deletable from the admin, exact
     * parity with every edge case of the old branchy logic isn't
     * preserved, but the obvious/common paths match.
     */
    public function run(): void
    {
        $questions = [
            ['sort' => 1, 'key' => 'persona',
                'question' => ['en' => 'What best describes you?', 'ar' => 'أيٌّ مما يلي يصفك؟'],
                'options' => [
                    ['icon' => 'user', 'label' => ['en' => 'An individual investor', 'ar' => 'مستثمر فرد'], 'description' => ['en' => 'Saving or growing personal wealth', 'ar' => 'ادخار أو تنمية ثروة شخصية'], 'votes' => ['portfolio' => 0, 'liquidity' => 0, 'subscription' => 0, 'funds' => 0]],
                    ['icon' => 'corp', 'label' => ['en' => 'A company / institution', 'ar' => 'شركة / مؤسسة'], 'description' => ['en' => 'Managing corporate funds', 'ar' => 'إدارة أموال مؤسسية'], 'votes' => ['portfolio' => 4, 'liquidity' => 1, 'subscription' => 0, 'funds' => 0]],
                    ['icon' => 'trend', 'label' => ['en' => 'A growing business seeking capital', 'ar' => 'شركة نامية تبحث عن تمويل'], 'description' => ['en' => 'Raising funds or going to market', 'ar' => 'جمع تمويل أو الطرح بالسوق'], 'votes' => ['portfolio' => 8, 'liquidity' => 0, 'subscription' => 0, 'funds' => 0]],
                ]],
            ['sort' => 2, 'key' => 'goal',
                'question' => ['en' => "What's your main goal?", 'ar' => 'ما هدفك الأساسي؟'],
                'options' => [
                    ['icon' => 'shield', 'label' => ['en' => 'Preserve capital & stay liquid', 'ar' => 'الحفاظ على رأس المال والسيولة'], 'description' => ['en' => 'Low risk, easy access', 'ar' => 'مخاطر منخفضة ووصول سهل'], 'votes' => ['portfolio' => 0, 'liquidity' => 4, 'subscription' => 0, 'funds' => 0]],
                    ['icon' => 'sprout', 'label' => ['en' => 'Grow wealth over time', 'ar' => 'تنمية الثروة مع الوقت'], 'description' => ['en' => 'Accept some risk for return', 'ar' => 'قبول بعض المخاطر مقابل العائد'], 'votes' => ['portfolio' => 0, 'liquidity' => 0, 'subscription' => 0, 'funds' => 2]],
                    ['icon' => 'crescent', 'label' => ['en' => 'Sharia-compliant investing', 'ar' => 'استثمار متوافق مع الشريعة'], 'description' => ['en' => 'Faith-aligned options', 'ar' => 'خيارات متوافقة مع الشريعة'], 'votes' => ['portfolio' => 0, 'liquidity' => 0, 'subscription' => 0, 'funds' => 2]],
                    ['icon' => 'coins', 'label' => ['en' => 'Raise capital for my company', 'ar' => 'جمع تمويل لشركتي'], 'description' => ['en' => "We'll match you with the closest fit", 'ar' => 'سنرشّح لك الخيار الأقرب لاحتياجك'], 'votes' => ['portfolio' => 8, 'liquidity' => 0, 'subscription' => 0, 'funds' => 0]],
                ]],
            ['sort' => 3, 'key' => 'handsOn',
                'question' => ['en' => 'How hands-on do you want to be?', 'ar' => 'ما مدى رغبتك في إدارة استثمارك بنفسك؟'],
                'options' => [
                    ['icon' => 'briefcase', 'label' => ['en' => 'Let AFIM manage it fully', 'ar' => 'أن تدير الشركة كل شيء'], 'description' => ['en' => 'Discretionary management', 'ar' => 'إدارة كاملة بالتفويض'], 'votes' => ['portfolio' => 3, 'liquidity' => 1, 'subscription' => 0, 'funds' => 0]],
                    ['icon' => 'pie', 'label' => ['en' => 'Pooled fund with others', 'ar' => 'صندوق مشترك مع آخرين'], 'description' => ['en' => 'Diversified, accessible', 'ar' => 'متنوع وسهل الوصول'], 'votes' => ['portfolio' => 0, 'liquidity' => 0, 'subscription' => 0, 'funds' => 1]],
                    ['icon' => 'swap', 'label' => ['en' => 'Just need transactions executed', 'ar' => 'أحتاج تنفيذ المعاملات فقط'], 'description' => ['en' => 'Subscribe / redeem orders', 'ar' => 'تنفيذ طلبات الاكتتاب والاسترداد'], 'votes' => ['portfolio' => 0, 'liquidity' => 0, 'subscription' => 6, 'funds' => 0]],
                ]],
        ];

        foreach ($questions as $question) {
            FinderQuestion::updateOrCreate(['sort' => $question['sort']], $question);
        }
    }
}
