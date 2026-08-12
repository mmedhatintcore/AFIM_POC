<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'slug' => 'aum-surpasses-egp-93-billion',
                'type' => 'press',
                'source' => 'AFIM Press Release',
                'published_at' => '2025-12-15 09:00:00',
                'title' => ['en' => 'AUM surpasses EGP 93 billion', 'ar' => 'الأصول المُدارة تتجاوز ٩٣ مليار جنيه'],
                'excerpt' => [
                    'en' => "Assets under management exceeded EGP 93bn in December 2025, with a 21.3% share of Egypt's asset-management market — confirming AFIM's position as market leader since 1994.",
                    'ar' => 'تجاوز حجم الأصول المُدارة ٩٣ مليار جنيه في ديسمبر ٢٠٢٥، بحصة سوقية ٢١٫٣٪ من سوق إدارة الأصول في مصر — تأكيداً لريادة الشركة منذ ١٩٩٤.',
                ],
                'body' => [
                    'en' => "Assets under management exceeded EGP 93 billion in December 2025, with a 21.3% share of Egypt's asset-management market — confirming AFIM's position as market leader since 1994.\n\nThe milestone reflects sustained growth across the firm's family of mutual funds — money market, fixed income, balanced, equity, Sharia-compliant and gold — alongside discretionary portfolios for major institutions. Management attributed the result to fully integrated risk management, research-driven allocation and a collaborative investment team serving clients across every market cycle.",
                    'ar' => "تجاوز حجم الأصول المُدارة ٩٣ مليار جنيه في ديسمبر ٢٠٢٥، بحصة سوقية ٢١٫٣٪ من سوق إدارة الأصول في مصر — تأكيداً لريادة الشركة منذ ١٩٩٤.\n\nويعكس هذا الإنجاز نمواً متواصلاً عبر باقة صناديق الشركة — النقدية والدخل الثابت والمتوازنة والأسهم والمتوافقة مع الشريعة والذهب — إلى جانب المحافظ المُدارة بالتفويض لكبرى المؤسسات. وأرجعت الإدارة هذه النتيجة إلى الإدارة المتكاملة للمخاطر والتوزيع القائم على البحث وفريق الاستثمار المتعاون الذي يخدم العملاء عبر كل دورات السوق.",
                ],
            ],
            [
                'slug' => 'tharwa-hayah-partners-dahab-fund',
                'type' => 'media',
                'source' => 'Al-Dostor',
                'published_at' => '2025-12-03 09:00:00',
                'title' => ['en' => 'Tharwa Hayah partners with AFIM and Evolve on the Dahab fund', 'ar' => '«ثروة حياة» تتشارك مع «الأهلي» و«إيفولف» في صندوق دهب'],
                'excerpt' => [
                    'en' => "Contact Financial's life-insurance arm announced a strategic partnership including exclusive purchases of Dahab gold-fund certificates for unit-linked savings policies.",
                    'ar' => 'أعلنت ذراع تأمينات الحياة لمجموعة كونتكت المالية شراكة استراتيجية تتضمن شراءً حصرياً لوثائق صندوق دهب وتوظيفها في وثائق الادخار المرتبطة بوحدات الاستثمار.',
                ],
                'body' => [
                    'en' => "Contact Financial's life-insurance arm, Tharwa Hayah, announced a strategic partnership with AFIM and Evolve that includes exclusive purchases of Dahab gold-fund certificates for its unit-linked savings policies.\n\nThe agreement channels long-term insurance savings into Egypt's growing gold-fund segment, deepening the distribution of Dahab beyond brokerage platforms and giving policyholders exposure to gold as a store of value within regulated savings products.",
                    'ar' => "أعلنت «ثروة حياة»، ذراع تأمينات الحياة لمجموعة كونتكت المالية، عن شراكة استراتيجية مع شركة الأهلي لإدارة الاستثمارات المالية و«إيفولف» تتضمن شراءً حصرياً لوثائق صندوق دهب للذهب وتوظيفها في وثائق الادخار المرتبطة بوحدات الاستثمار.\n\nوتوجّه الاتفاقية مدخرات التأمين طويلة الأجل نحو قطاع صناديق الذهب المتنامي في مصر، بما يعمّق توزيع «دهب» خارج منصات الوساطة ويمنح حاملي الوثائق انكشافاً على الذهب كمخزن للقيمة ضمن منتجات ادخارية خاضعة للرقابة.",
                ],
            ],
            [
                'slug' => 'gold-funds-momentum-dahab',
                'type' => 'media',
                'source' => 'Masrawy',
                'published_at' => '2026-04-10 09:00:00',
                'title' => ['en' => "Gold funds momentum: Dahab among Egypt's leading gold vehicles", 'ar' => 'زخم صناديق الذهب: «دهب» ضمن أبرز صناديق الذهب'],
                'excerpt' => [
                    'en' => "Coverage of Egypt's growing gold-fund segment highlighted Dahab — launched June 2024 by AFIM with Evolve — noted for its daily cumulative return.",
                    'ar' => 'أبرزت تغطية قطاع صناديق الذهب المتنامي صندوق «دهب» الذي أطلقته الشركة مع إيفولف في يونيو ٢٠٢٤، والمتميز بعائد يومي تراكمي.',
                ],
                'body' => [
                    'en' => "Media coverage of Egypt's growing gold-fund segment highlighted Dahab — launched in June 2024 by AFIM with Evolve — noting its daily cumulative return and its position among the country's leading gold investment vehicles.\n\nGold funds have drawn strong retail demand as savers look for inflation hedges, and Dahab's availability across seven trading platforms has made it one of the most accessible routes to gold exposure in the market.",
                    'ar' => "أبرزت التغطية الإعلامية لقطاع صناديق الذهب المتنامي في مصر صندوق «دهب» — الذي أطلقته شركة الأهلي لإدارة الاستثمارات المالية مع «إيفولف» في يونيو ٢٠٢٤ — مشيرةً إلى عائده اليومي التراكمي وموقعه بين أبرز أدوات الاستثمار في الذهب بالبلاد.\n\nوتشهد صناديق الذهب طلباً قوياً من الأفراد بحثاً عن أدوات تحوّط ضد التضخم، فيما جعل توافر «دهب» عبر سبع منصات تداول منه أحد أسهل الطرق للانكشاف على الذهب في السوق.",
                ],
            ],
            [
                'slug' => 'afim-funds-ranked-first-2023',
                'type' => 'press',
                'source' => 'AFIM Press Release',
                'published_at' => '2024-02-15 09:00:00',
                'title' => ['en' => 'AFIM funds ranked first for 2023 performance', 'ar' => 'صناديق الشركة تحتل المراكز الأولى عن ٢٠٢٣'],
                'excerpt' => [
                    'en' => 'Mutual funds under AFIM management took top positions among Egyptian funds for 2023, across money-market and equity categories.',
                    'ar' => 'احتلت صناديق الاستثمار تحت إدارة الشركة المراكز الأولى بين الصناديق المصرية عن عام ٢٠٢٣.',
                ],
                'body' => [
                    'en' => "Mutual funds under AFIM management took top positions among Egyptian funds for 2023, across both money-market and equity categories.\n\nThe rankings underline the firm's research-driven investment process and disciplined risk management, which delivered leading returns for retail and institutional certificate holders alike across a volatile year for local markets.",
                    'ar' => "احتلت صناديق الاستثمار تحت إدارة شركة الأهلي لإدارة الاستثمارات المالية المراكز الأولى بين الصناديق المصرية عن عام ٢٠٢٣، في فئتي أسواق النقد والأسهم.\n\nوتؤكد هذه التصنيفات العملية الاستثمارية القائمة على البحث والإدارة المنضبطة للمخاطر لدى الشركة، واللتين حققتا عوائد رائدة لحملة الوثائق من الأفراد والمؤسسات على حد سواء في عام اتسم بتقلبات الأسواق المحلية.",
                ],
            ],
            [
                'slug' => 'dahab-oversubscribed-3x',
                'type' => 'media',
                'source' => 'FRA / Bloom Gate',
                'published_at' => '2024-07-20 09:00:00',
                'title' => ['en' => 'Dahab oversubscribed 3× at launch', 'ar' => 'تغطية اكتتاب «دهب» ٣ أضعاف المستهدف'],
                'excerpt' => [
                    'en' => "The Financial Regulatory Authority reported Dahab's IPO closed in July 2024 with over EGP 30 million in subscriptions — triple the EGP 10 million target.",
                    'ar' => 'أفادت الهيئة العامة للرقابة المالية بإغلاق الاكتتاب في يوليو ٢٠٢٤ بأكثر من ٣٠ مليون جنيه — ثلاثة أضعاف المستهدف البالغ ١٠ ملايين.',
                ],
                'body' => [
                    'en' => "The Financial Regulatory Authority reported that Dahab's IPO closed in July 2024 with over EGP 30 million in subscriptions — triple the EGP 10 million target.\n\nThe oversubscription made Dahab one of the strongest fund launches of the year and validated appetite for regulated gold investment products, only the third licensed gold fund in Egypt.",
                    'ar' => "أفادت الهيئة العامة للرقابة المالية بأن الاكتتاب في صندوق «دهب» أُغلق في يوليو ٢٠٢٤ بأكثر من ٣٠ مليون جنيه — ثلاثة أضعاف المستهدف البالغ ١٠ ملايين جنيه.\n\nوجعلت هذه التغطية «دهب» أحد أقوى إطلاقات الصناديق خلال العام، وأكدت شهية السوق لمنتجات الاستثمار في الذهب الخاضعة للرقابة، علماً بأنه ثالث صندوق ذهب مرخص في مصر فقط.",
                ],
            ],
            [
                'slug' => 'seven-plus-funds-under-management',
                'type' => 'social',
                'source' => 'LinkedIn · @afpm',
                'published_at' => '2026-01-15 09:00:00',
                'title' => ['en' => '7+ mutual funds and institutional portfolios under management', 'ar' => 'أكثر من ٧ صناديق ومحافظ مؤسسية تحت الإدارة'],
                'excerpt' => [
                    'en' => "Our philosophy: fully integrated risk management, diligent analysis and a collaborative team — serving clients' best interests in volatile markets. #AssetManagement #Egypt",
                    'ar' => 'فلسفتنا: إدارة متكاملة للمخاطر وتحليل دقيق وفريق متعاون — لخدمة مصالح عملائنا في الأسواق المتقلبة. #إدارة_الأصول #مصر',
                ],
                'body' => [
                    'en' => "Our philosophy: fully integrated risk management, diligent analysis and a collaborative team — serving clients' best interests in volatile markets.\n\nWith more than seven mutual funds and institutional portfolios under management, AFIM continues to widen access to professional asset management for individuals and institutions across Egypt. #AssetManagement #Egypt",
                    'ar' => "فلسفتنا: إدارة متكاملة للمخاطر وتحليل دقيق وفريق متعاون — لخدمة مصالح عملائنا في الأسواق المتقلبة.\n\nبأكثر من سبعة صناديق ومحافظ مؤسسية تحت الإدارة، تواصل شركة الأهلي لإدارة الاستثمارات المالية توسيع الوصول إلى الإدارة الاحترافية للأصول للأفراد والمؤسسات في مصر. #إدارة_الأصول #مصر",
                ],
            ],
            [
                'slug' => 'fund-4-cash-coupon',
                'type' => 'social',
                'source' => 'Facebook · AFIM1994',
                'published_at' => '2024-05-10 09:00:00',
                'title' => ['en' => 'Fund 4 cash coupon distributed', 'ar' => 'توزيع كوبون نقدي للصندوق الرابع'],
                'excerpt' => [
                    'en' => 'Cash coupon distribution announced for NBE Fund 4 (money market) holders — part of a regular cadence communicated on our page.',
                    'ar' => 'الإعلان عن توزيع كوبون نقدي لحملة وثائق صندوق البنك الأهلي الرابع النقدي — ضمن سلسلة توزيعات دورية عبر صفحتنا.',
                ],
                'body' => [
                    'en' => "A cash coupon distribution was announced for NBE Fund 4 (money market) certificate holders — part of the regular distribution cadence communicated on our page.\n\nHolders receive their coupons automatically; no action is required. Follow our channels for each cycle's record and payment dates.",
                    'ar' => "أُعلن عن توزيع كوبون نقدي لحملة وثائق صندوق البنك الأهلي الرابع (النقدي) — ضمن سلسلة التوزيعات الدورية التي نعلنها عبر صفحتنا.\n\nيتلقى حملة الوثائق كوبوناتهم تلقائياً دون أي إجراء مطلوب. تابعوا قنواتنا لمعرفة تواريخ الأحقية والصرف لكل دورة.",
                ],
            ],
            [
                'slug' => 'nbe-misr-life-launch-al-ahly-hayah',
                'type' => 'media',
                'source' => 'Capital News',
                'published_at' => '2021-12-10 09:00:00',
                'title' => ['en' => "NBE & Misr Life launch 'Al Ahly Hayah' under AFIM management", 'ar' => 'البنك الأهلي ومصر لتأمينات الحياة يطلقان «الأهلي حياة»'],
                'excerpt' => [
                    'en' => 'The National Bank of Egypt and Misr Life Insurance launched the Al Ahly Hayah fund managed by AFIM, supporting financial inclusion via NBE branches nationwide.',
                    'ar' => 'أطلق البنك الأهلي المصري ومصر لتأمينات الحياة صندوق «الأهلي حياة» تحت إدارة الشركة، دعماً للشمول المالي عبر فروع البنك.',
                ],
                'body' => [
                    'en' => "The National Bank of Egypt and Misr Life Insurance launched the Al Ahly Hayah balanced fund under AFIM management, supporting financial inclusion via NBE branches nationwide.\n\nThe fund widens retail access to diversified, professionally managed investment — subscriptions are available at any NBE branch, bringing balanced-fund investing to first-time savers across the country.",
                    'ar' => "أطلق البنك الأهلي المصري ومصر لتأمينات الحياة صندوق «الأهلي حياة» المتوازن تحت إدارة شركة الأهلي لإدارة الاستثمارات المالية، دعماً للشمول المالي عبر فروع البنك في أنحاء الجمهورية.\n\nويوسّع الصندوق وصول الأفراد إلى استثمار متنوع مُدار باحترافية — إذ يتاح الاكتتاب في أي فرع من فروع البنك الأهلي، بما يجلب الاستثمار في الصناديق المتوازنة للمدخرين الجدد في كل مكان.",
                ],
            ],
        ];

        foreach ($posts as $post) {
            NewsPost::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
