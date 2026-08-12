<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'announcement',
                'body' => [
                    'en' => "AFIM's assets under management surpass EGP 93 billion — December 2025.",
                    'ar' => 'أصول الشركة المُدارة تتجاوز ٩٣ مليار جنيه — ديسمبر ٢٠٢٥.',
                ],
                'cta' => ['label' => ['en' => 'Read more', 'ar' => 'اقرأ المزيد'], 'href' => '/news'],
            ],
            [
                'key' => 'ticker',
                'items' => [
                    ['text' => ['en' => '31+ years', 'ar' => '٣١+ عاماً']],
                    ['text' => ['en' => 'EGP 93bn+ AUM', 'ar' => '٩٣+ مليار جنيه أصول']],
                    ['text' => ['en' => '21.3% market share', 'ar' => '٢١٫٣٪ حصة سوقية']],
                    ['text' => ['en' => 'First asset manager in Egypt', 'ar' => 'أول مدير أصول في مصر']],
                    ['text' => ['en' => '5 licensed services', 'ar' => '٥ خدمات مرخصة']],
                ],
            ],
            [
                'key' => 'hero',
                'title' => ['en' => "Egypt's first asset manager.", 'ar' => 'أول شركة إدارة أصول في مصر.'],
                'subtitle' => ['en' => 'Three decades of trust.', 'ar' => 'ثلاثة عقود من الثقة.'],
                'body' => [
                    'en' => 'AFIM manages institutional and individual wealth across a full range of investment solutions — from money-market and fixed-income strategies to equities, Sharia-compliant and gold — with assets under management exceeding EGP 93 billion.',
                    'ar' => 'تدير شركة الأهلي لإدارة الاستثمارات المالية ثروات المؤسسات والأفراد عبر مجموعة كاملة من الحلول الاستثمارية — من أدوات أسواق النقد والدخل الثابت إلى الأسهم والحلول المتوافقة مع الشريعة والذهب — بأصول مُدارة تتجاوز ٩٣ مليار جنيه.',
                ],
                'items' => [
                    ['title' => ['en' => 'Dahab Gold', 'ar' => 'دهب للذهب'], 'value' => '+0.88%', 'trend' => 'up'],
                    ['title' => ['en' => 'Money Market', 'ar' => 'النقدي'], 'value' => '142.83', 'trend' => null],
                    ['title' => ['en' => 'NBE Equity', 'ar' => 'الأهلي للأسهم'], 'value' => '-0.34%', 'trend' => 'down'],
                ],
                'cta' => [
                    'label' => ['en' => 'Find your service', 'ar' => 'اعثر على خدمتك'],
                    'href' => '#finder',
                    'secondary_label' => ['en' => 'Our services', 'ar' => 'خدماتنا'],
                    'secondary_href' => '/services',
                ],
                'extra' => [
                    'eyebrow' => ['en' => 'Asset Management • Egypt • Since 1994', 'ar' => 'إدارة الأصول • مصر • منذ ١٩٩٤'],
                    'live_label' => ['en' => 'Live NAV', 'ar' => 'صافي القيمة مباشر'],
                ],
            ],
            [
                'key' => 'advisor',
                'title' => ['en' => 'Need help in <span class="accent">investment</span>?', 'ar' => 'هل تحتاج <span class="accent">مساعدة</span> في الاستثمار؟'],
                'body' => [
                    'en' => "Not sure which fund fits you? Answer a few quick questions about your goals, horizon and comfort with risk — we'll match you with the right AFIM fund and show you exactly how and where to subscribe.",
                    'ar' => 'غير متأكد أي صندوق يناسبك؟ أجب عن أسئلة سريعة حول أهدافك ومدتك الاستثمارية ومستوى المخاطرة المريح لك — وسنرشّح لك الصندوق الأنسب ونوضح لك كيف وأين تكتتب بالضبط.',
                ],
                'items' => [
                    ['icon' => 'clock', 'text' => ['en' => 'Takes about 2 minutes', 'ar' => 'يستغرق نحو دقيقتين']],
                    ['icon' => 'target', 'text' => ['en' => 'Personalised fund match', 'ar' => 'ترشيح مخصّص لك']],
                    ['icon' => 'compass', 'text' => ['en' => 'Clear next steps', 'ar' => 'خطوات واضحة للاكتتاب']],
                ],
                'cta' => [
                    'label' => ['en' => 'Find my fund →', 'ar' => 'اعثر على صندوقي ←'],
                    'href' => '/survey',
                    'secondary_label' => ['en' => 'Browse all funds', 'ar' => 'استعرض كل الصناديق'],
                    'secondary_href' => '/funds',
                ],
                'extra' => [
                    'note' => ['en' => 'Free, no account needed. Indicative guidance — not investment advice.', 'ar' => 'مجاناً وبدون حساب. إرشاد استرشادي — وليس نصيحة استثمارية.'],
                    'steps' => [
                        ['en' => 'Answer', 'ar' => 'أجب'],
                        ['en' => 'Get matched', 'ar' => 'احصل على ترشيحك'],
                        ['en' => 'Subscribe', 'ar' => 'اكتتب'],
                    ],
                ],
            ],
            [
                'key' => 'trust',
                'title' => [
                    'en' => 'In partnership with leading institutions · Regulated by the FRA',
                    'ar' => 'بالشراكة مع مؤسسات رائدة · مرخّصة من الهيئة العامة للرقابة المالية',
                ],
                'items' => [
                    ['text' => ['en' => 'National Bank of Egypt', 'ar' => 'البنك الأهلي المصري']],
                    ['text' => ['en' => 'Misr Life Insurance', 'ar' => 'مصر لتأمينات الحياة']],
                    ['text' => ['en' => 'Evolve', 'ar' => 'إيفولف']],
                    ['text' => ['en' => 'FRA', 'ar' => 'الرقابة المالية']],
                ],
            ],
            [
                'key' => 'goals',
                'title' => ['en' => 'Invest by goal', 'ar' => 'استثمر حسب هدفك'],
                'subtitle' => ['en' => "Tell us where you're headed and we'll point you to the right path.", 'ar' => 'أخبرنا بوجهتك وسنوجّهك إلى المسار المناسب.'],
                'items' => [
                    [
                        'icon' => 'shield',
                        'title' => ['en' => 'Preserve capital', 'ar' => 'الحفاظ على رأس المال'],
                        'text' => ['en' => 'Low-risk money-market and fixed-income funds that stay liquid.', 'ar' => 'صناديق نقدية ودخل ثابت منخفضة المخاطر مع سهولة الوصول.'],
                        'link_label' => ['en' => 'Explore funds →', 'ar' => 'استعرض الصناديق ←'],
                        'href' => '/funds',
                    ],
                    [
                        'icon' => 'sprout',
                        'title' => ['en' => 'Grow over time', 'ar' => 'نمِّ ثروتك'],
                        'text' => ['en' => 'Balanced and equity funds for long-term growth.', 'ar' => 'صناديق متوازنة وأسهم لنمو طويل الأجل.'],
                        'link_label' => ['en' => 'Explore funds →', 'ar' => 'استعرض الصناديق ←'],
                        'href' => '/funds',
                    ],
                    [
                        'icon' => 'crescent',
                        'title' => ['en' => 'Sharia-compliant', 'ar' => 'متوافق مع الشريعة'],
                        'text' => ['en' => 'A Sharia-compliant fund and the Dahab gold fund.', 'ar' => 'صندوق متوافق مع الشريعة وصندوق دهب للذهب.'],
                        'link_label' => ['en' => 'Explore funds →', 'ar' => 'استعرض الصناديق ←'],
                        'href' => '/funds',
                    ],
                    [
                        'icon' => 'bank',
                        'title' => ['en' => 'For institutions', 'ar' => 'للمؤسسات'],
                        'text' => ['en' => 'Discretionary portfolios and tailored institutional solutions.', 'ar' => 'محافظ مُدارة بالتفويض وحلول مخصّصة للمؤسسات.'],
                        'link_label' => ['en' => 'See services →', 'ar' => 'استعرض الخدمات ←'],
                        'href' => '/services',
                    ],
                ],
            ],
            [
                'key' => 'prices_intro',
                'title' => ['en' => 'Prices & yields', 'ar' => 'الأسعار والعوائد'],
                'subtitle' => [
                    'en' => "Compare AFIM's fund products at a glance — price, daily move and one-year yield. Swipe or let it scroll.",
                    'ar' => 'قارن منتجات صناديق الشركة في لمحة — السعر والتغير اليومي والعائد السنوي. مرّر أو دعه ينساب.',
                ],
                'extra' => [
                    'live_label' => ['en' => 'Live demo', 'ar' => 'عرض مباشر'],
                    'disclaimer' => [
                        'en' => 'Indicative NAV per certificate (EGP) — as of Jun 2026. Illustrative figures.',
                        'ar' => 'صافي قيمة الأصول الاسترشادي لكل وثيقة (بالجنيه) — حتى يونيو ٢٠٢٦. أرقام توضيحية.',
                    ],
                ],
            ],
            [
                'key' => 'services_intro',
                'title' => ['en' => 'Our services', 'ar' => 'خدماتنا'],
                'subtitle' => [
                    'en' => 'Our licensed lines of business, built on integrated risk management and a collaborative investment team. Funds and their live prices are in the Prices section above.',
                    'ar' => 'أنشطتنا المرخّصة، مبنية على إدارة متكاملة للمخاطر وفريق استثمار متعاون. الصناديق وأسعارها المباشرة في قسم الأسعار بالأعلى.',
                ],
            ],
            [
                'key' => 'why',
                'title' => ['en' => 'Three decades of disciplined stewardship.', 'ar' => 'ثلاثة عقود من الإدارة المنضبطة للثروات.'],
                'body' => [
                    'en' => "Egypt's first asset manager, trusted with institutional and individual wealth across every market cycle since 1994.",
                    'ar' => 'أول شركة إدارة أصول في مصر، مؤتمنة على ثروات المؤسسات والأفراد عبر كل دورات السوق منذ ١٩٩٤.',
                ],
                'items' => [
                    ['title' => ['en' => 'Fully integrated risk management', 'ar' => 'إدارة متكاملة للمخاطر'], 'text' => ['en' => 'Risk is managed at every step, never bolted on at the end.', 'ar' => 'تُدار المخاطر في كل خطوة، لا كإضافة لاحقة.']],
                    ['title' => ['en' => 'Research driven investing', 'ar' => 'استثمار قائم على البحث'], 'text' => ['en' => 'Diligent analysis behind every allocation decision.', 'ar' => 'تحليل دقيق وراء كل قرار لتوزيع الأصول.']],
                    ['title' => ['en' => 'A collaborative team', 'ar' => 'فريق متعاون'], 'text' => ['en' => 'One investment team working for your best interest.', 'ar' => 'فريق استثمار واحد يعمل لمصلحتك.']],
                    ['title' => ['en' => 'Market leadership', 'ar' => 'ريادة سوقية'], 'text' => ['en' => "A 21.3% share of Egypt's asset-management market.", 'ar' => 'حصة ٢١٫٣٪ من سوق إدارة الأصول في مصر.']],
                ],
                'extra' => ['kicker' => ['en' => 'Why AFIM', 'ar' => 'لماذا الأهلي']],
            ],
            [
                'key' => 'figures',
                'items' => [
                    ['value' => '93', 'unit' => ['en' => 'EGP bn', 'ar' => 'مليار جنيه'], 'label' => ['en' => 'Assets under management, December 2025.', 'ar' => 'إجمالي الأصول المُدارة، ديسمبر ٢٠٢٥.'], 'hero' => true],
                    ['value' => '31', 'suffix' => '+', 'label' => ['en' => 'Years managing assets', 'ar' => 'عاماً في إدارة الأصول']],
                    ['value' => '21.3', 'suffix' => '%', 'decimals' => 1, 'label' => ['en' => 'Market share in asset management', 'ar' => 'الحصة السوقية في إدارة الأصول']],
                    ['value' => '7', 'suffix' => '+', 'label' => ['en' => 'Funds & institutional portfolios', 'ar' => 'صناديق ومحافظ مؤسسية']],
                ],
                'extra' => ['kicker' => ['en' => 'AFIM by the numbers', 'ar' => 'الشركة في أرقام']],
            ],
            [
                'key' => 'steps',
                'title' => ['en' => 'How to start', 'ar' => 'كيف تبدأ'],
                'items' => [
                    ['title' => ['en' => 'Find your fit', 'ar' => 'اعرف ما يناسبك'], 'text' => ['en' => 'Answer three quick questions, or browse the funds by goal.', 'ar' => 'أجب عن ثلاثة أسئلة سريعة، أو تصفّح الصناديق حسب هدفك.']],
                    ['title' => ['en' => 'Subscribe via NBE', 'ar' => 'اكتتب عبر البنك الأهلي'], 'text' => ['en' => 'Subscribe to your chosen fund through NBE branches nationwide.', 'ar' => 'اكتتب في الصندوق الذي تختاره عبر فروع البنك الأهلي في كل مكان.']],
                    ['title' => ['en' => 'Track your NAV', 'ar' => 'تابع صافي قيمة أصولك'], 'text' => ['en' => 'Follow daily net asset value and yields, redeem on your cycle.', 'ar' => 'تابع صافي القيمة والعوائد يومياً، واسترد وفق دورتك.']],
                ],
                'cta' => ['label' => ['en' => 'Find your service', 'ar' => 'اعثر على خدمتك'], 'href' => '#finder'],
            ],
            [
                'key' => 'cta',
                'title' => ['en' => 'Ready to put your capital to work?', 'ar' => 'هل أنت مستعد لتنمية ثروتك؟'],
                'body' => [
                    'en' => 'Find the AFIM product that fits your goals, or talk to our team. We reply within 24 hours.',
                    'ar' => 'اعثر على منتج الأهلي المناسب لأهدافك، أو تحدّث مع فريقنا. نرد خلال ٢٤ ساعة.',
                ],
                'cta' => [
                    'label' => ['en' => 'Find your service', 'ar' => 'اعثر على خدمتك'],
                    'href' => '#finder',
                    'secondary_label' => ['en' => 'Talk to our team', 'ar' => 'تحدّث مع فريقنا'],
                    'secondary_href' => '/contact',
                ],
            ],
            [
                'key' => 'about_brief',
                'title' => ['en' => 'About AFIM', 'ar' => 'عن الشركة'],
                'body' => [
                    'en' => "Al Ahly Financial Investments Management (AFIM) was the first asset-management company established in Egypt, founded in 1994 under Capital Market Law 95/1992, license no. 21.\n\nThe firm manages a diversified family of mutual funds — money market, fixed income, balanced, equity, Sharia-compliant and gold — alongside tailored portfolios for major institutions covering a range of asset classes and strategies.",
                    'ar' => "تُعد شركة الأهلي لإدارة الاستثمارات المالية أول شركة إدارة أصول تأسست في مصر، عام ١٩٩٤ وفقاً لقانون سوق رأس المال ٩٥ لسنة ١٩٩٢، بترخيص رقم ٢١.\n\nتدير الشركة مجموعة متنوعة من صناديق الاستثمار — النقدية والدخل الثابت والمتوازنة والأسهم والمتوافقة مع الشريعة والذهب — إلى جانب محافظ مُصممة لمؤسسات كبرى تغطي فئات أصول واستراتيجيات متعددة.",
                ],
                'items' => [
                    ['icon' => 'eye', 'title' => ['en' => 'Vision', 'ar' => 'الرؤية'], 'text' => ['en' => 'To be the leading and most trusted asset-management firm in the Egyptian and regional markets.', 'ar' => 'أن نكون شركة إدارة الأصول الرائدة والأكثر ثقة في السوقين المصري والإقليمي.']],
                    ['icon' => 'target', 'title' => ['en' => 'Mission', 'ar' => 'الرسالة'], 'text' => ['en' => "To grow and protect our clients' wealth through disciplined, research-driven investment and fully integrated risk management.", 'ar' => 'تنمية وحماية ثروات عملائنا عبر استثمار منضبط قائم على البحث وإدارة متكاملة للمخاطر.']],
                    ['icon' => 'scales', 'title' => ['en' => 'Values', 'ar' => 'القيم'], 'text' => ['en' => 'Integrity, transparency, loyalty to client objectives, and prudence in volatile markets.', 'ar' => 'النزاهة والشفافية والالتزام بأهداف العملاء والحذر في الأسواق المتقلبة.']],
                ],
                'extra' => [
                    'board_note' => ['en' => 'The Board of Directors sets strategy and oversees governance.', 'ar' => 'يتولى مجلس الإدارة وضع الاستراتيجية والإشراف على الحوكمة.'],
                    'committees_note' => ['en' => "Three standing committees support the Board's oversight role.", 'ar' => 'ثلاث لجان دائمة تدعم الدور الرقابي للمجلس.'],
                    'leadership_note' => ['en' => 'The executive team running day-to-day investment and operations.', 'ar' => 'الفريق التنفيذي المسؤول عن إدارة الاستثمار والعمليات اليومية.'],
                ],
            ],
            [
                'key' => 'footer',
                'items' => [
                    ['group' => 'office', 'text' => ['en' => '25 Wezaret El Zeraa St., Al Moez Tower, 9th–10th floor, Dokki 12618, Giza, Egypt', 'ar' => '٢٥ ش وزارة الزراعة – برج المعز – الدور التاسع والعاشر – الدقي ١٢٦١٨ – الجيزة – مصر']],
                    ['group' => 'social', 'text' => ['en' => 'facebook.com/AFIM1994', 'ar' => 'facebook.com/AFIM1994'], 'href' => 'https://facebook.com/AFIM1994'],
                    ['group' => 'social', 'text' => ['en' => 'linkedin.com/company/afpm', 'ar' => 'linkedin.com/company/afpm'], 'href' => 'https://linkedin.com/company/afpm'],
                ],
                'extra' => [
                    'office_title' => ['en' => 'Head office', 'ar' => 'المركز الرئيسي'],
                    'services_title' => ['en' => 'Services', 'ar' => 'خدمات'],
                    'follow_title' => ['en' => 'Follow AFIM', 'ar' => 'تابعنا'],
                    'phone' => '(+202) 37603401 · 37603466',
                    'email' => 'info@afim.com.eg',
                    'reply_note' => ['en' => 'We reply within 24 hours', 'ar' => 'نرد خلال ٢٤ ساعة'],
                    'copyright' => [
                        'en' => 'Proof of concept prepared for AFIM — not a live website. Figures and news shown are real published data used for demonstration.',
                        'ar' => 'نموذج مبدئي مُعد للشركة — ليس موقعاً فعلياً. الأرقام والأخبار المعروضة بيانات منشورة فعلياً لأغراض العرض.',
                    ],
                ],
            ],
            [
                'key' => 'news_intro',
                'title' => ['en' => 'News Feed', 'ar' => 'الأخبار'],
                'subtitle' => [
                    'en' => "Press releases, Egyptian media coverage and the firm's social channels — one stream.",
                    'ar' => 'البيانات الصحفية وتغطية الصحافة المصرية وقنوات التواصل — في تدفق واحد.',
                ],
            ],
            [
                'key' => 'faqs_intro',
                'title' => ['en' => 'How can we help?', 'ar' => 'كيف يمكننا مساعدتك؟'],
                'subtitle' => [
                    'en' => 'Tell us what you need and we will point you to the suitable product. Take the quick finder, or open a question — each one links to the right service.',
                    'ar' => 'أخبرنا بما تحتاجه وسنوجّهك للمنتج المناسب. جرّب الدليل السريع أو افتح أي سؤال — كل سؤال يقودك للخدمة الملائمة.',
                ],
            ],
            [
                'key' => 'survey_intro',
                'title' => ['en' => 'Need help in investment?', 'ar' => 'هل تحتاج مساعدة في الاستثمار؟'],
                'subtitle' => [
                    'en' => '13 quick taps — about two minutes to your match.',
                    'ar' => '١٣ نقرة سريعة — دقيقتان تفصلانك عن ترشيحك.',
                ],
                'extra' => [
                    'note' => [
                        'en' => 'This guidance is indicative and educational — it is not investment advice. Fund terms and conditions apply.',
                        'ar' => 'هذا الإرشاد استرشادي وتثقيفي — وليس نصيحة استثمارية. تسري أحكام وشروط الصناديق.',
                    ],
                    'result_title' => ['en' => 'Your recommended path', 'ar' => 'مسارك الاستثماري المقترح'],
                    'funds_label' => ['en' => 'Matching funds & how to subscribe', 'ar' => 'الصناديق المطابقة وكيفية الاكتتاب'],
                ],
            ],
        ];

        foreach ($sections as $section) {
            Section::updateOrCreate(['key' => $section['key']], $section);
        }
    }
}
