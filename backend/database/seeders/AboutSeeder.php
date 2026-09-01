<?php

namespace Database\Seeders;

use App\Models\Committee;
use App\Models\TeamMember;
use App\Models\TimelineMilestone;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTimeline();
        $this->seedTeam();
        $this->seedCommittees();
    }

    private function seedTimeline(): void
    {
        $milestones = [
            ['sort' => 1, 'year' => '1994',
                'title' => ['en' => 'First in Egypt', 'ar' => 'الأولى في مصر'],
                'body' => ['en' => 'Established as the first asset-management company in Egypt under Capital Market Law 95/1992.', 'ar' => 'تأسست كأول شركة لإدارة الأصول في مصر وفقاً لقانون سوق رأس المال ٩٥ لسنة ١٩٩٢.']],
            ['sort' => 2, 'year' => '2000s',
                'title' => ['en' => 'Fund family expands', 'ar' => 'توسّع باقة الصناديق'],
                'body' => ['en' => 'Launch of successive NBE mutual funds across money-market and equity strategies.', 'ar' => 'إطلاق صناديق البنك الأهلي المتتالية عبر استراتيجيات أسواق النقد والأسهم.']],
            ['sort' => 3, 'year' => '2021',
                'title' => ['en' => 'Al Ahly Hayah', 'ar' => 'الأهلي حياة'],
                'body' => ['en' => 'Balanced fund launched with Misr Life Insurance, widening retail access via NBE branches.', 'ar' => 'إطلاق صندوق متوازن مع مصر لتأمينات الحياة، لتوسيع وصول الأفراد عبر فروع البنك الأهلي.']],
            ['sort' => 4, 'year' => '2024',
                'title' => ['en' => 'Dahab gold fund', 'ar' => 'صندوق دهب للذهب'],
                'body' => ['en' => "Egypt's third licensed gold fund launched with Evolve — oversubscribed 3× at IPO.", 'ar' => 'إطلاق ثالث صناديق الذهب المرخصة في مصر مع إيفولف — وتمت تغطية الاكتتاب ٣ أضعاف.']],
            ['sort' => 5, 'year' => '2025',
                'title' => ['en' => 'EGP 93bn AUM', 'ar' => '٩٣ مليار جنيه أصول'],
                'body' => ['en' => 'Assets under management surpass EGP 93 billion with a 21.3% market share.', 'ar' => 'تجاوز الأصول المُدارة ٩٣ مليار جنيه بحصة سوقية ٢١٫٣٪.']],
        ];

        foreach ($milestones as $milestone) {
            TimelineMilestone::updateOrCreate(['sort' => $milestone['sort']], $milestone);
        }
    }

    private function seedTeam(): void
    {
        $members = [
            ['group' => 'board', 'sort' => 1, 'name' => ['en' => 'Karim Abou El Naga', 'ar' => 'كريم أبو النجا'], 'role' => ['en' => 'Chairman (Non-Executive)', 'ar' => 'رئيس مجلس الإدارة (غير تنفيذي)']],
            ['group' => 'board', 'sort' => 2, 'name' => ['en' => 'Adel Kamel El-Waly', 'ar' => 'عادل كامل الوالي'], 'role' => ['en' => 'Managing Director & CIO', 'ar' => 'العضو المنتدب ورئيس الاستثمار']],
            ['group' => 'board', 'sort' => 3, 'name' => ['en' => 'Board Member', 'ar' => 'عضو مجلس الإدارة'], 'role' => ['en' => 'Non-Executive Member', 'ar' => 'عضو غير تنفيذي']],
            ['group' => 'board', 'sort' => 4, 'name' => ['en' => 'Board Member', 'ar' => 'عضو مجلس الإدارة'], 'role' => ['en' => 'Non-Executive Member', 'ar' => 'عضو غير تنفيذي']],
            ['group' => 'board', 'sort' => 5, 'name' => ['en' => 'Board Member', 'ar' => 'عضو مجلس الإدارة'], 'role' => ['en' => 'Non-Executive Member', 'ar' => 'عضو غير تنفيذي']],
            ['group' => 'board', 'sort' => 6, 'name' => ['en' => 'Board Member', 'ar' => 'عضو مجلس الإدارة'], 'role' => ['en' => 'Independent Member', 'ar' => 'عضو مستقل']],
            ['group' => 'board', 'sort' => 7, 'name' => ['en' => 'Board Member', 'ar' => 'عضو مجلس الإدارة'], 'role' => ['en' => 'Independent Member', 'ar' => 'عضو مستقل']],
            ['group' => 'leadership', 'sort' => 1, 'name' => ['en' => 'Adel Kamel El-Waly', 'ar' => 'عادل كامل الوالي'], 'role' => ['en' => 'Managing Director & CIO', 'ar' => 'العضو المنتدب ورئيس الاستثمار']],
            ['group' => 'leadership', 'sort' => 2, 'name' => ['en' => 'Head of Asset Management', 'ar' => 'رئيس إدارة الأصول'], 'role' => ['en' => 'Mutual Funds & Portfolios', 'ar' => 'الصناديق والمحافظ']],
            ['group' => 'leadership', 'sort' => 3, 'name' => ['en' => 'Head of Risk', 'ar' => 'رئيس إدارة المخاطر'], 'role' => ['en' => 'Risk & Compliance', 'ar' => 'المخاطر والالتزام']],
            ['group' => 'leadership', 'sort' => 4, 'name' => ['en' => 'Head of Operations', 'ar' => 'رئيس العمليات'], 'role' => ['en' => 'Fund Operations & Settlement', 'ar' => 'عمليات الصناديق والتسوية']],
            ['group' => 'leadership', 'sort' => 5, 'name' => ['en' => 'Head of Research', 'ar' => 'رئيس البحوث'], 'role' => ['en' => 'Investment Research', 'ar' => 'بحوث الاستثمار']],
            ['group' => 'leadership', 'sort' => 6, 'name' => ['en' => 'Head of Client Relations', 'ar' => 'رئيس علاقات العملاء'], 'role' => ['en' => 'Institutional & Retail', 'ar' => 'المؤسسات والأفراد']],
        ];

        foreach ($members as $member) {
            TeamMember::updateOrCreate(['group' => $member['group'], 'sort' => $member['sort']], $member);
        }
    }

    private function seedCommittees(): void
    {
        $committees = [
            ['sort' => 1, 'name' => ['en' => 'Audit Committee', 'ar' => 'لجنة المراجعة'],
                'mission' => ['en' => 'Oversees the integrity of financial reporting, internal controls, and the internal and external audit functions.', 'ar' => 'الإشراف على سلامة التقارير المالية والرقابة الداخلية ووظيفتي المراجعة الداخلية والخارجية.'],
                'members' => [
                    ['name' => ['en' => 'Independent Director', 'ar' => 'عضو مستقل'], 'role' => ['en' => 'Chair', 'ar' => 'رئيس اللجنة']],
                    ['name' => ['en' => 'Non-Executive Director', 'ar' => 'عضو غير تنفيذي'], 'role' => ['en' => 'Member', 'ar' => 'عضو']],
                    ['name' => ['en' => 'Independent Director', 'ar' => 'عضو مستقل'], 'role' => ['en' => 'Member', 'ar' => 'عضو']],
                ],
                'responsibilities' => [
                    ['en' => 'Reviews financial reporting integrity', 'ar' => 'مراجعة سلامة التقارير المالية'],
                    ['en' => 'Oversees internal & external audit', 'ar' => 'الإشراف على المراجعة الداخلية والخارجية'],
                    ['en' => 'Monitors internal-control effectiveness', 'ar' => 'متابعة فاعلية الرقابة الداخلية'],
                ]],
            ['sort' => 2, 'name' => ['en' => 'Risk Committee', 'ar' => 'لجنة المخاطر'],
                'mission' => ['en' => 'Defines the risk appetite framework and monitors market, credit, liquidity, and operational risk across the firm.', 'ar' => 'وضع إطار قابلية تحمّل المخاطر ومتابعة مخاطر السوق والائتمان والسيولة والمخاطر التشغيلية على مستوى الشركة.'],
                'members' => [
                    ['name' => ['en' => 'Non-Executive Director', 'ar' => 'عضو غير تنفيذي'], 'role' => ['en' => 'Chair', 'ar' => 'رئيس اللجنة']],
                    ['name' => ['en' => 'Independent Director', 'ar' => 'عضو مستقل'], 'role' => ['en' => 'Member', 'ar' => 'عضو']],
                    ['name' => ['en' => 'Head of Risk', 'ar' => 'رئيس إدارة المخاطر'], 'role' => ['en' => 'Member (Executive)', 'ar' => 'عضو (تنفيذي)']],
                ],
                'responsibilities' => [
                    ['en' => 'Sets the risk appetite framework', 'ar' => 'وضع إطار قابلية تحمّل المخاطر'],
                    ['en' => 'Monitors market, credit & liquidity risk', 'ar' => 'متابعة مخاطر السوق والائتمان والسيولة'],
                    ['en' => 'Reviews risk policies and limits', 'ar' => 'مراجعة سياسات وحدود المخاطر'],
                ]],
            ['sort' => 3, 'name' => ['en' => 'Investment Committee', 'ar' => 'لجنة الاستثمار'],
                'mission' => ['en' => 'Sets asset-allocation strategy and oversees fund and portfolio investment decisions and performance.', 'ar' => 'اعتماد استراتيجية توزيع الأصول والإشراف على قرارات وأداء الصناديق والمحافظ الاستثمارية.'],
                'members' => [
                    ['name' => ['en' => 'Managing Director & CIO', 'ar' => 'العضو المنتدب ورئيس الاستثمار'], 'role' => ['en' => 'Chair', 'ar' => 'رئيس اللجنة']],
                    ['name' => ['en' => 'Head of Asset Management', 'ar' => 'رئيس إدارة الأصول'], 'role' => ['en' => 'Member', 'ar' => 'عضو']],
                    ['name' => ['en' => 'Head of Research', 'ar' => 'رئيس البحوث'], 'role' => ['en' => 'Member', 'ar' => 'عضو']],
                ],
                'responsibilities' => [
                    ['en' => 'Approves asset-allocation strategy', 'ar' => 'اعتماد استراتيجية توزيع الأصول'],
                    ['en' => 'Reviews fund & portfolio performance', 'ar' => 'مراجعة أداء الصناديق والمحافظ'],
                    ['en' => 'Oversees the investment process', 'ar' => 'الإشراف على العملية الاستثمارية'],
                ]],
            ['sort' => 4, 'name' => ['en' => 'Governance Committee', 'ar' => 'لجنة الحوكمة'],
                'mission' => ['en' => 'Oversees corporate governance practices, board effectiveness, nominations, and compliance with regulatory governance requirements.', 'ar' => 'الإشراف على ممارسات الحوكمة وفاعلية مجلس الإدارة والترشيحات والالتزام بمتطلبات الحوكمة التنظيمية.'],
                'members' => [
                    ['name' => ['en' => 'Non-Executive Director', 'ar' => 'عضو غير تنفيذي'], 'role' => ['en' => 'Chair', 'ar' => 'رئيس اللجنة']],
                    ['name' => ['en' => 'Independent Director', 'ar' => 'عضو مستقل'], 'role' => ['en' => 'Member', 'ar' => 'عضو']],
                    ['name' => ['en' => 'Independent Director', 'ar' => 'عضو مستقل'], 'role' => ['en' => 'Member', 'ar' => 'عضو']],
                ],
                'responsibilities' => [
                    ['en' => 'Oversees board composition & nominations', 'ar' => 'الإشراف على تشكيل المجلس والترشيحات'],
                    ['en' => 'Reviews corporate governance policies', 'ar' => 'مراجعة سياسات الحوكمة المؤسسية'],
                    ['en' => 'Monitors regulatory compliance on governance', 'ar' => 'متابعة الالتزام التنظيمي بمتطلبات الحوكمة'],
                ]],
        ];

        foreach ($committees as $committee) {
            Committee::updateOrCreate(['sort' => $committee['sort']], $committee);
        }
    }
}
