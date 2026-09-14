/**
 * API types — mirror docs/api-contract/*.md exactly.
 * Money/NAV numbers are decimal strings with 2 fraction digits ("142.83").
 * Dates are ISO 8601 UTC.
 */

/* ===== Sections (CMS singleton blocks) ===== */

export type SectionTrend = "up" | "down" | null;

export type SectionItem = {
  icon?: string | null;
  title?: string | null;
  text?: string | null;
  value?: string | null;
  href?: string | null;
  trend?: SectionTrend;
  link_label?: string | null;
  label?: string | null;
  suffix?: string | null;
  decimals?: number | null;
  unit?: string | null;
  hero?: boolean;
  group?: string | null;
  platform?: string | null;
};

export type SectionCta = {
  label?: string | null;
  href?: string | null;
  secondary_label?: string | null;
  secondary_href?: string | null;
};

export type Section = {
  key: string;
  is_enabled: boolean;
  title: string | null;
  subtitle: string | null;
  body: string | null;
  items: SectionItem[] | null;
  cta: SectionCta | null;
  extra: Record<string, unknown> | null;
};

export type SectionMap = Partial<Record<string, Section>>;

/* ===== Services ===== */

export type Service = {
  id: number;
  key: string;
  slug: string;
  name: string;
  description: string;
  body: string | null;
  icon: string;
  sort: number;
};

/* ===== Funds ===== */

export type RiskLevel = 0 | 1 | 2;
export type OrderChannel = "nbe" | "afim";

export type Fund = {
  id: number;
  slug: string;
  name: string;
  category_label: string;
  group_key: string;
  risk_level: RiskLevel;
  risk_label: string;
  nav_price: string | null;
  daily_change: string | null;
  yield_1y: string | null;
  spark: number[] | null;
  illustration: string;
  order_channel: OrderChannel;
  order_channel_label: string;
  how_to: string;
  platforms: string[];
  description: string | null;
  is_featured: boolean;
};

export type FundCategory = {
  key: string;
  name: string;
  description: string;
  risk_level: RiskLevel;
  risk_label: string;
  fund_group: string;
  illustration: string;
};

/* ===== News ===== */

export type NewsType = "press" | "media" | "social";

export type NewsItem = {
  id: number;
  slug: string;
  type: NewsType;
  type_label: string;
  source: string;
  title: string;
  excerpt: string;
  image_url: string | null;
  published_at: string;
};

export type NewsArticle = NewsItem & {
  body: string;
  related: NewsItem[];
};

/* ===== FAQs ===== */

export type FaqActionType =
  | "finder"
  | "survey"
  | "prices"
  | "services"
  | "about"
  | null;

export type Faq = {
  id: number;
  question: string;
  answer: string;
  action_type: FaqActionType;
  action_label: string | null;
};

/* ===== About ===== */

export type TimelineMilestone = {
  id: number;
  year: string;
  title: string;
  body: string;
};

export type TeamGroup = "board" | "leadership";

export type TeamMember = {
  id: number;
  group: TeamGroup;
  name: string;
  role: string;
  bio: string | null;
  photo_url: string | null;
};

export type CommitteeMember = {
  name: string;
  role: string;
};

export type Committee = {
  id: number;
  name: string;
  mission: string | null;
  members: CommitteeMember[];
  responsibilities: string[];
};

/* ===== Survey ===== */

export type SurveyLayout = "cards" | "grid";

export type SurveyOption = {
  index: number;
  icon?: string | null;
  label: string;
  description?: string | null;
};

export type SurveyQuestion = {
  id: number;
  key: string | null;
  phase: string;
  question: string;
  layout: SurveyLayout;
  options: SurveyOption[];
};

export type SurveyAnswer = {
  question_id: number;
  option_index: number;
};

export type SurveyResultCategory = {
  key: string;
  name: string;
  description: string;
  risk_level: RiskLevel;
  risk_label: string;
  illustration: string;
};

export type SurveyResultAlternative = {
  key: string;
  name: string;
  description: string;
  risk_level?: RiskLevel;
  risk_label?: string;
  fund_group?: string;
  illustration?: string;
};

export type SurveyResult = {
  category: SurveyResultCategory;
  alternative: SurveyResultAlternative | null;
  is_islamic: boolean;
  is_corporate: boolean;
  profile: string[];
  funds: Fund[];
};

/* ===== Contact ===== */

export type ContactMessagePayload = {
  name: string;
  email: string;
  phone?: string;
  subject?: string;
  message: string;
};
