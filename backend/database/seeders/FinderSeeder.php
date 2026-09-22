<?php

namespace Database\Seeders;

use App\Models\FinderQuestion;
use Illuminate\Database\Seeder;

class FinderSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            ['sort' => 1, 'key' => 'q1',
                'question' => ['en' => 'What best describes you?', 'ar' => 'أيٌّ مما يلي يصفك؟'],
                'options' => [
                    ['tag' => 'ind', 'icon' => 'user', 'label' => ['en' => 'An individual investor', 'ar' => 'مستثمر فرد'], 'description' => ['en' => 'Saving or growing personal wealth', 'ar' => 'ادخار أو تنمية ثروة شخصية']],
                    ['tag' => 'inst', 'icon' => 'corp', 'label' => ['en' => 'A company / institution', 'ar' => 'شركة / مؤسسة'], 'description' => ['en' => 'Managing corporate funds', 'ar' => 'إدارة أموال مؤسسية']],
                    ['tag' => 'raise', 'icon' => 'trend', 'label' => ['en' => 'A growing business seeking capital', 'ar' => 'شركة نامية تبحث عن تمويل'], 'description' => ['en' => 'Raising funds or going to market', 'ar' => 'جمع تمويل أو الطرح بالسوق']],
                ]],
            ['sort' => 2, 'key' => 'q2',
                'question' => ['en' => "What's your main goal?", 'ar' => 'ما هدفك الأساسي؟'],
                'options' => [
                    ['tag' => 'preserve', 'icon' => 'shield', 'label' => ['en' => 'Preserve capital & stay liquid', 'ar' => 'الحفاظ على رأس المال والسيولة'], 'description' => ['en' => 'Low risk, easy access', 'ar' => 'مخاطر منخفضة ووصول سهل']],
                    ['tag' => 'grow', 'icon' => 'sprout', 'label' => ['en' => 'Grow wealth over time', 'ar' => 'تنمية الثروة مع الوقت'], 'description' => ['en' => 'Accept some risk for return', 'ar' => 'قبول بعض المخاطر مقابل العائد']],
                    ['tag' => 'islamic', 'icon' => 'crescent', 'label' => ['en' => 'Sharia-compliant investing', 'ar' => 'استثمار متوافق مع الشريعة'], 'description' => ['en' => 'Faith-aligned options', 'ar' => 'خيارات متوافقة مع الشريعة']],
                    ['tag' => 'capital', 'icon' => 'coins', 'label' => ['en' => 'Raise capital for my company', 'ar' => 'جمع تمويل لشركتي'], 'description' => ['en' => "We'll match you with the closest fit", 'ar' => 'سنرشّح لك الخيار الأقرب لاحتياجك']],
                ]],
            ['sort' => 3, 'key' => 'q3',
                'question' => ['en' => 'How hands-on do you want to be?', 'ar' => 'ما مدى رغبتك في إدارة استثمارك بنفسك؟'],
                'options' => [
                    ['tag' => 'managed', 'icon' => 'briefcase', 'label' => ['en' => 'Let AFIM manage it fully', 'ar' => 'أن تدير الشركة كل شيء'], 'description' => ['en' => 'Discretionary management', 'ar' => 'إدارة كاملة بالتفويض']],
                    ['tag' => 'pooled', 'icon' => 'pie', 'label' => ['en' => 'Pooled fund with others', 'ar' => 'صندوق مشترك مع آخرين'], 'description' => ['en' => 'Diversified, accessible', 'ar' => 'متنوع وسهل الوصول']],
                    ['tag' => 'exec', 'icon' => 'swap', 'label' => ['en' => 'Just need transactions executed', 'ar' => 'أحتاج تنفيذ المعاملات فقط'], 'description' => ['en' => 'Subscribe / redeem orders', 'ar' => 'تنفيذ طلبات الاكتتاب والاسترداد']],
                ]],
        ];

        foreach ($questions as $question) {
            FinderQuestion::updateOrCreate(['key' => $question['key']], $question);
        }
    }
}
