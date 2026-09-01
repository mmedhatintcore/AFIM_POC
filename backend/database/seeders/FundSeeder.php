<?php

namespace Database\Seeders;

use App\Models\Fund;
use App\Models\FundCategory;
use Illuminate\Database\Seeder;

class FundSeeder extends Seeder
{
    private const PLATFORMS = [
        'Ahly Pharos' => 'الأهلي فاروس',
        'Mubasher' => 'مباشر',
        'Banque Du Caire' => 'بنك القاهرة',
        'Thndr' => 'ثاندر',
        'PFI' => 'بي إف آي',
        'Naeem' => 'نعيم',
        '3 Way' => 'ثري واي',
        'AF Securities' => 'إيه إف للأوراق المالية',
        'Arabeya Online' => 'عربية أون لاين',
    ];

    public function run(): void
    {
        $this->seedCategories();
        $this->seedFunds();
    }

    private function seedCategories(): void
    {
        $categories = [
            ['key' => 'mm_acc', 'fund_group' => 'mm', 'risk_level' => 0, 'illustration' => 'moneymarket', 'sort' => 1,
                'name' => ['en' => 'Money Market Fund — Daily Accumulated Return', 'ar' => 'الصندوق النقدي — عائد يومي تراكمي'],
                'description' => ['en' => 'Daily accumulated return with high liquidity — subscribe and redeem daily with no fees, ideal for keeping savings safe and accessible.', 'ar' => 'عائد يومي تراكمي مع سيولة عالية — اكتتاب واسترداد يومي دون رسوم، مثالي لإبقاء مدخراتك آمنة ومتاحة.']],
            ['key' => 'mm_dist', 'fund_group' => 'mm', 'risk_level' => 0, 'illustration' => 'moneymarket', 'sort' => 2,
                'name' => ['en' => 'Money Market Fund — Monthly Distribution', 'ar' => 'الصندوق النقدي — توزيع شهري'],
                'description' => ['en' => 'Daily return with a monthly cash distribution — steady income while your capital stays liquid and low-risk.', 'ar' => 'عائد يومي مع توزيع نقدي شهري — دخل منتظم بينما يظل رأس مالك سائلاً ومنخفض المخاطر.']],
            ['key' => 'imm', 'fund_group' => 'imm', 'risk_level' => 0, 'illustration' => 'islamic', 'sort' => 3,
                'name' => ['en' => 'Islamic Money Market Fund', 'ar' => 'الصندوق النقدي الإسلامي'],
                'description' => ['en' => 'Sharia-compliant daily return with monthly distribution — capital preservation aligned with your values.', 'ar' => 'عائد يومي متوافق مع الشريعة مع توزيع شهري — حفاظ على رأس المال بما يتوافق مع قيمك.']],
            ['key' => 'mixed', 'fund_group' => 'mixed', 'risk_level' => 1, 'illustration' => 'fixedincome', 'sort' => 4,
                'name' => ['en' => 'Mixed Funds', 'ar' => 'الصناديق المختلطة'],
                'description' => ['en' => 'Invests across money-market and fixed-income instruments — a steady step up for medium-term goals.', 'ar' => 'تستثمر في أدوات النقد والدخل الثابت — خطوة متوازنة لأهدافك متوسطة المدى.']],
            ['key' => 'balanced', 'fund_group' => 'balanced', 'risk_level' => 1, 'illustration' => 'balanced', 'sort' => 5,
                'name' => ['en' => 'Balanced Funds', 'ar' => 'الصناديق المتوازنة'],
                'description' => ['en' => 'Diversified across multiple asset classes — growth and stability working together.', 'ar' => 'تنويع عبر فئات أصول متعددة — نمو واستقرار معاً.']],
            ['key' => 'metals', 'fund_group' => 'metals', 'risk_level' => 1, 'illustration' => 'gold', 'sort' => 6,
                'name' => ['en' => 'Precious Metals Funds', 'ar' => 'صناديق المعادن الثمينة'],
                'description' => ['en' => 'Investment in precious metals such as gold — a classic diversifier and store of value.', 'ar' => 'استثمار في المعادن الثمينة مثل الذهب — أداة تنويع كلاسيكية ومخزن للقيمة.']],
            ['key' => 'equity', 'fund_group' => 'equity', 'risk_level' => 2, 'illustration' => 'equity', 'sort' => 7,
                'name' => ['en' => 'Equity Funds', 'ar' => 'صناديق الأسهم'],
                'description' => ['en' => 'Invests in listed equities for long-term capital growth — for investors comfortable with market swings.', 'ar' => 'تستثمر في الأسهم المقيدة لتحقيق نمو رأسمالي طويل الأجل — لمن يتقبّلون تقلبات السوق.']],
            ['key' => 'iequity', 'fund_group' => 'iequity', 'risk_level' => 2, 'illustration' => 'islamic', 'sort' => 8,
                'name' => ['en' => 'Islamic Equity Funds', 'ar' => 'صناديق الأسهم الإسلامية'],
                'description' => ['en' => 'Sharia-compliant equity investments for long-term growth aligned with your values.', 'ar' => 'استثمارات أسهم متوافقة مع الشريعة لنموٍ طويل الأجل يتوافق مع قيمك.']],
        ];

        foreach ($categories as $category) {
            FundCategory::updateOrCreate(['key' => $category['key']], $category);
        }
    }

    private function seedFunds(): void
    {
        $funds = [
            // Featured funds shown in the home prices carousel (with market data).
            ['slug' => 'al-ahly-money-market-fund', 'group_key' => 'mm', 'order_channel' => 'afim', 'risk_level' => 0,
                'illustration' => 'moneymarket', 'is_featured' => true, 'sort' => 1,
                'name' => ['en' => 'Al Ahly Money Market Fund', 'ar' => 'صندوق الأهلي النقدي'],
                'category_label' => ['en' => 'Money Market', 'ar' => 'سوق النقد'],
                'nav_price' => 142.83, 'daily_change' => 0.03, 'yield_1y' => 21.4,
                'spark' => [6, 6.4, 6.9, 7.3, 7.8, 8.3, 8.8, 9.4, 9.9, 10.5],
                'description' => ['en' => 'A low-risk money-market fund targeting capital preservation and daily liquidity with an accumulated return.', 'ar' => 'صندوق نقدي منخفض المخاطر يستهدف الحفاظ على رأس المال وسيولة يومية مع عائد تراكمي.']],
            ['slug' => 'nbe-fixed-income-fund', 'group_key' => 'mixed', 'order_channel' => 'nbe', 'risk_level' => 1,
                'illustration' => 'fixedincome', 'is_featured' => true, 'sort' => 2,
                'name' => ['en' => 'NBE Fixed-Income Fund', 'ar' => 'صندوق البنك الأهلي للدخل الثابت'],
                'category_label' => ['en' => 'Mixed', 'ar' => 'مختلط'],
                'nav_price' => 118.50, 'daily_change' => 0.06, 'yield_1y' => 19.2,
                'spark' => [5, 5.5, 5.9, 6.5, 6.8, 7.4, 7.9, 8.4, 9, 9.6],
                'description' => ['en' => 'Fixed-income exposure with low volatility — steady returns for medium-term goals.', 'ar' => 'انكشاف على أدوات الدخل الثابت بتقلبات منخفضة — عوائد مستقرة لأهداف متوسطة المدى.']],
            ['slug' => 'al-ahly-hayah-balanced-fund', 'group_key' => 'balanced', 'order_channel' => 'nbe', 'risk_level' => 1,
                'illustration' => 'balanced', 'is_featured' => true, 'sort' => 3,
                'name' => ['en' => 'Al Ahly Hayah Balanced Fund', 'ar' => 'صندوق الأهلي حياة المتوازن'],
                'category_label' => ['en' => 'Balanced', 'ar' => 'متوازن'],
                'nav_price' => 24.71, 'daily_change' => 0.21, 'yield_1y' => 17.8,
                'spark' => [4, 4.6, 4.3, 5.1, 5.5, 5.2, 6, 6.5, 6.3, 7.1],
                'description' => ['en' => 'Launched with Misr Life Insurance in 2021 — diversified growth and stability, widening retail access via NBE branches.', 'ar' => 'أُطلق مع مصر لتأمينات الحياة عام ٢٠٢١ — نمو واستقرار متنوعان، مع توسيع وصول الأفراد عبر فروع البنك الأهلي.']],
            ['slug' => 'nbe-equity-fund', 'group_key' => 'equity', 'order_channel' => 'nbe', 'risk_level' => 2,
                'illustration' => 'equity', 'is_featured' => true, 'sort' => 4,
                'name' => ['en' => 'NBE Equity Fund', 'ar' => 'صندوق البنك الأهلي للأسهم'],
                'category_label' => ['en' => 'Equity', 'ar' => 'أسهم'],
                'nav_price' => 88.14, 'daily_change' => -0.34, 'yield_1y' => 26.5,
                'spark' => [6, 6.8, 6.3, 7.4, 7, 8.1, 7.7, 8.6, 8.2, 9],
                'description' => ['en' => 'Listed-equity exposure for long-term capital growth — for investors comfortable with market swings.', 'ar' => 'انكشاف على الأسهم المقيدة لنمو رأسمالي طويل الأجل — لمن يتقبّلون تقلبات السوق.']],
            ['slug' => 'al-ahly-islamic-fund', 'group_key' => 'iequity', 'order_channel' => 'nbe', 'risk_level' => 1,
                'illustration' => 'islamic', 'is_featured' => true, 'sort' => 5,
                'name' => ['en' => 'Al Ahly Islamic Fund', 'ar' => 'صندوق الأهلي الإسلامي'],
                'category_label' => ['en' => 'Sharia-compliant', 'ar' => 'متوافق مع الشريعة'],
                'nav_price' => 36.92, 'daily_change' => 0.12, 'yield_1y' => 15.6,
                'spark' => [4, 4.4, 4.8, 5.1, 5.6, 6, 6.3, 6.8, 7.2, 7.6],
                'description' => ['en' => 'Faith-aligned investing across Sharia-compliant instruments.', 'ar' => 'استثمار متوافق مع قيمك عبر أدوات متوافقة مع الشريعة.']],
            ['slug' => 'dahab-gold-fund', 'group_key' => 'metals', 'order_channel' => 'afim', 'risk_level' => 2,
                'illustration' => 'gold', 'is_featured' => true, 'sort' => 6,
                'name' => ['en' => 'Dahab Gold Fund', 'ar' => 'صندوق دهب للذهب'],
                'category_label' => ['en' => 'Gold', 'ar' => 'ذهب'],
                'nav_price' => 13.45, 'daily_change' => 0.88, 'yield_1y' => 42.3,
                'spark' => [3, 3.6, 4.1, 4, 5, 5.8, 6.4, 7.6, 8.5, 9.8],
                'platforms' => $this->platforms(['Ahly Pharos', 'Mubasher', 'Thndr', 'Naeem', '3 Way', 'AF Securities', 'Arabeya Online']),
                'description' => ['en' => "Egypt's third licensed gold fund, launched June 2024 with Evolve — oversubscribed 3× at IPO, known for its daily cumulative return.", 'ar' => 'ثالث صناديق الذهب المرخصة في مصر، أُطلق في يونيو ٢٠٢٤ مع إيفولف — تمت تغطية اكتتابه ٣ أضعاف، ويتميز بعائد يومي تراكمي.']],

            // Funds from the Intcore workbook (survey recommendations — order channels & platforms).
            ['slug' => 'nbe-4', 'group_key' => 'mm', 'order_channel' => 'nbe', 'risk_level' => 0, 'illustration' => 'moneymarket', 'sort' => 10,
                'name' => ['en' => 'NBE 4', 'ar' => 'صندوق البنك الأهلي الرابع'],
                'category_label' => ['en' => 'Money Market', 'ar' => 'سوق النقد'],
                'description' => ['en' => 'Money-market fund with a regular cash-coupon distribution cadence.', 'ar' => 'صندوق نقدي بوتيرة منتظمة لتوزيع الكوبونات النقدية.']],
            ['slug' => 'wethaq', 'group_key' => 'mm', 'order_channel' => 'afim', 'risk_level' => 0, 'illustration' => 'moneymarket', 'is_featured' => true, 'sort' => 11,
                'name' => ['en' => 'Wethaq', 'ar' => 'وثاق'],
                'category_label' => ['en' => 'Money Market', 'ar' => 'سوق النقد'],
                'platforms' => $this->platforms(['Ahly Pharos', 'Mubasher', 'Banque Du Caire'])],
            ['slug' => 'horas', 'group_key' => 'mm', 'order_channel' => 'afim', 'risk_level' => 0, 'illustration' => 'moneymarket', 'is_featured' => true, 'sort' => 12,
                'name' => ['en' => 'Horas', 'ar' => 'حورس'],
                'category_label' => ['en' => 'Money Market', 'ar' => 'سوق النقد'],
                'platforms' => $this->platforms(['Ahly Pharos', 'Mubasher', 'Banque Du Caire'])],
            ['slug' => 'tamayouz', 'group_key' => 'mm', 'order_channel' => 'afim', 'risk_level' => 0, 'illustration' => 'moneymarket', 'is_featured' => true, 'sort' => 13,
                'name' => ['en' => 'Tamayouz', 'ar' => 'تميّز'],
                'category_label' => ['en' => 'Money Market', 'ar' => 'سوق النقد'],
                'platforms' => $this->platforms(['Ahly Pharos', 'Mubasher', 'Banque Du Caire', 'Thndr', 'PFI'])],
            ['slug' => 'islamic-money-market-fund', 'group_key' => 'imm', 'order_channel' => 'afim', 'risk_level' => 0, 'illustration' => 'islamic', 'is_featured' => true, 'sort' => 14,
                'name' => ['en' => 'Islamic Money Market Fund', 'ar' => 'الصندوق النقدي الإسلامي'],
                'category_label' => ['en' => 'Islamic Money Market', 'ar' => 'نقدي إسلامي']],
            ['slug' => 'el-waed', 'group_key' => 'mixed', 'order_channel' => 'nbe', 'risk_level' => 1, 'illustration' => 'fixedincome', 'sort' => 15,
                'name' => ['en' => 'El Waed', 'ar' => 'الواعد'],
                'category_label' => ['en' => 'Mixed', 'ar' => 'مختلط']],
            ['slug' => 'nbe-1', 'group_key' => 'balanced', 'order_channel' => 'nbe', 'risk_level' => 1, 'illustration' => 'balanced', 'sort' => 16,
                'name' => ['en' => 'NBE 1', 'ar' => 'صندوق البنك الأهلي الأول'],
                'category_label' => ['en' => 'Balanced', 'ar' => 'متوازن']],
            ['slug' => 'nbe-2', 'group_key' => 'equity', 'order_channel' => 'nbe', 'risk_level' => 2, 'illustration' => 'equity', 'sort' => 17,
                'name' => ['en' => 'NBE 2', 'ar' => 'صندوق البنك الأهلي الثاني'],
                'category_label' => ['en' => 'Equity', 'ar' => 'أسهم']],
            ['slug' => 'nbe-3', 'group_key' => 'equity', 'order_channel' => 'nbe', 'risk_level' => 2, 'illustration' => 'equity', 'sort' => 18,
                'name' => ['en' => 'NBE 3', 'ar' => 'صندوق البنك الأهلي الثالث'],
                'category_label' => ['en' => 'Equity', 'ar' => 'أسهم']],
            ['slug' => 'nbe-5', 'group_key' => 'equity', 'order_channel' => 'nbe', 'risk_level' => 2, 'illustration' => 'equity', 'sort' => 19,
                'name' => ['en' => 'NBE 5', 'ar' => 'صندوق البنك الأهلي الخامس'],
                'category_label' => ['en' => 'Equity', 'ar' => 'أسهم']],
            ['slug' => 'bashayer', 'group_key' => 'iequity', 'order_channel' => 'nbe', 'risk_level' => 2, 'illustration' => 'islamic', 'sort' => 20,
                'name' => ['en' => 'Bashayer', 'ar' => 'بشاير'],
                'category_label' => ['en' => 'Islamic Equity', 'ar' => 'أسهم إسلامية']],
        ];

        foreach ($funds as $fund) {
            Fund::updateOrCreate(['slug' => $fund['slug']], $fund);
        }
    }

    /** @param list<string> $names */
    private function platforms(array $names): array
    {
        return array_map(fn (string $name) => ['en' => $name, 'ar' => self::PLATFORMS[$name] ?? $name], $names);
    }
}
