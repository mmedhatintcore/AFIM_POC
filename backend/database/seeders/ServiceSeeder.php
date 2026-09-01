<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'key' => 'funds',
                'slug' => 'fund-management',
                'icon' => 'fundmanagement',
                'sort' => 1,
                'name' => ['en' => 'Fund Management', 'ar' => 'إدارة الصناديق'],
                'description' => [
                    'en' => 'A diversified family of licensed mutual funds — money market, fixed income, balanced, equity, Sharia-compliant and gold — professionally managed since 1994.',
                    'ar' => 'مجموعة متنوعة من صناديق الاستثمار المرخّصة — النقدية والدخل الثابت والمتوازنة والأسهم والمتوافقة مع الشريعة والذهب — تُدار باحترافية منذ عام ١٩٩٤.',
                ],
                'body' => [
                    'en' => "Fund Management is AFIM's core business: establishing and managing a diversified family of mutual funds across money-market, fixed-income, balanced, equity, Sharia-compliant and gold strategies. Every fund is run by one collaborative investment team with fully integrated risk management and research-driven analysis, and priced on a regular cycle with clear, published NAVs.",
                    'ar' => 'تُعد إدارة الصناديق النشاط الأساسي للشركة: إنشاء وإدارة مجموعة متنوعة من صناديق الاستثمار عبر استراتيجيات أسواق النقد والدخل الثابت والصناديق المتوازنة والأسهم والصناديق المتوافقة مع الشريعة والذهب. يُدار كل صندوق بواسطة فريق استثمار واحد متعاون مع إدارة متكاملة للمخاطر وتحليل قائم على البحث، ويُسعَّر بشكل دوري منتظم مع صافي قيمة أصول واضح ومنشور.',
                ],
            ],
            [
                'key' => 'portfolio',
                'slug' => 'portfolio-management',
                'icon' => 'portfolio',
                'sort' => 2,
                'name' => ['en' => 'Portfolio Management', 'ar' => 'إدارة المحافظ'],
                'description' => [
                    'en' => 'Tailored discretionary mandates for institutions and high net worth clients, spanning asset classes and bespoke investment strategies.',
                    'ar' => 'محافظ استثمارية مُصممة للمؤسسات وكبار العملاء، تغطي فئات أصول متعددة واستراتيجيات استثمارية مخصصة.',
                ],
                'body' => [
                    'en' => "Our discretionary Portfolio Management service builds a bespoke strategy across asset classes for institutions and high-net-worth clients. Each mandate starts from your objectives, constraints and risk appetite, and is run by one collaborative investment team with fully integrated risk management and diligent, research-driven analysis behind every allocation decision.",
                    'ar' => 'تبني خدمة إدارة المحافظ بالتفويض استراتيجية مخصّصة عبر فئات الأصول للمؤسسات وكبار العملاء. يبدأ كل تفويض من أهدافك وقيودك وقابليتك للمخاطر، ويُدار بواسطة فريق استثمار واحد متعاون مع إدارة متكاملة للمخاطر وتحليل دقيق قائم على البحث وراء كل قرار لتوزيع الأصول.',
                ],
            ],
            [
                'key' => 'liquidity',
                'slug' => 'liquidity-management',
                'icon' => 'liquidity',
                'sort' => 3,
                'name' => ['en' => 'Liquidity Management', 'ar' => 'إدارة السيولة'],
                'description' => [
                    'en' => 'Optimal deployment of corporate cash: analysing financial statements, mapping cash flow cycles, and selecting the right instruments for return and access.',
                    'ar' => 'الإدارة المثلى للموارد النقدية للمنشآت: تحليل القوائم المالية ورسم دورات التدفق النقدي واختيار الأدوات المناسبة للعائد والسيولة.',
                ],
                'body' => [
                    'en' => "Liquidity Management puts corporate cash to work without sacrificing access. We analyse your financial statements, map your cash-flow cycle, and select the right mix of money-market and fixed-income instruments so idle balances earn a return while staying available exactly when your business needs them.",
                    'ar' => 'تُوظّف خدمة إدارة السيولة النقد المؤسسي دون التضحية بسهولة الوصول إليه. نحلل قوائمك المالية ونرسم دورة تدفقاتك النقدية ونختار المزيج المناسب من أدوات أسواق النقد والدخل الثابت، لتحقق أرصدتك الخاملة عائداً مع بقائها متاحة تماماً وقت حاجة نشاطك إليها.',
                ],
            ],
            [
                'key' => 'subscription',
                'slug' => 'subscription-redemption',
                'icon' => 'subscription',
                'sort' => 4,
                'name' => ['en' => 'Subscription & Redemption', 'ar' => 'تلقي الاكتتاب والاسترداد'],
                'description' => [
                    'en' => 'Receiving and executing purchase and redemption orders for mutual fund certificates, with clear settlement and reporting.',
                    'ar' => 'تلقي وتنفيذ طلبات شراء واسترداد وثائق صناديق الاستثمار، مع تسوية وتقارير واضحة.',
                ],
                'body' => [
                    'en' => 'Subscription & Redemption receives and executes purchase and redemption orders for mutual-fund certificates on their regular cycles, with clear settlement and reporting. Whether you subscribe through NBE branches or directly via AFIM, orders are processed transparently and confirmations reach you without delay.',
                    'ar' => 'تتلقى خدمة تلقي الاكتتاب والاسترداد طلبات شراء واسترداد وثائق صناديق الاستثمار وتنفذها وفق دوراتها المنتظمة، مع تسوية وتقارير واضحة. سواء اكتتبت عبر فروع البنك الأهلي أو مباشرةً عبر الشركة، تُعالج الطلبات بشفافية وتصلك التأكيدات دون تأخير.',
                ],
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['key' => $service['key']], $service);
        }

        // Promotion & Underwriting was dropped from the services lineup.
        Service::where('key', 'underwriting')->delete();
    }
}
