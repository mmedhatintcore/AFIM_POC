import type { Metadata } from "next";
import { getTranslations } from "next-intl/server";
import { FundCard } from "@/components/features/funds/FundCard";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { Container } from "@/components/ui/Container";
import { Link } from "@/i18n/navigation";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { cn } from "@/lib/utils/cn";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type { Fund, Section } from "@/types/api";

type Props = {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{ group?: string }>;
};

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("funds.title"),
    description: t("funds.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/funds"),
      languages: languageAlternates("/funds"),
    },
    openGraph: {
      title: t("funds.title"),
      description: t("funds.description"),
      url: absoluteUrl(locale, "/funds"),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function FundsPage({ params, searchParams }: Props) {
  const locale = assertLocale((await params).locale);
  const { group } = await searchParams;

  const [funds, intro] = await Promise.all([
    fetchData<Fund[]>(locale, endpoints.funds),
    fetchData<Section>(locale, endpoints.section("prices_intro")),
  ]);
  const t = await getTranslations({ locale, namespace: "Funds" });

  const categories: { key: string; label: string }[] = [];
  for (const fund of funds ?? []) {
    if (!categories.some((category) => category.key === fund.group_key)) {
      categories.push({ key: fund.group_key, label: fund.category_label });
    }
  }
  const active = categories.some((category) => category.key === group)
    ? group
    : undefined;
  const visible = (funds ?? []).filter(
    (fund) => !active || fund.group_key === active,
  );

  const chipClass = (selected: boolean) =>
    cn(
      "rounded-full border px-4 py-2 text-[0.85rem] transition-all duration-300 ease-out-soft hover:-translate-y-0.5",
      selected
        ? "rounded-[10px] border-accent bg-accent font-bold text-accent-on"
        : "border-border text-soft hover:border-accent hover:text-accent",
    );

  return (
    <Container className="py-14">
      <SectionHeader
        title={intro?.title ?? t("title")}
        subtitle={intro?.subtitle ?? t("subtitle")}
      />

      {categories.length > 0 ? (
        <nav
          className="mb-9 flex flex-wrap gap-2.5"
          aria-label={t("filterAria")}
          data-testid="fund-filters"
        >
          <Link
            href="/funds"
            className={chipClass(!active)}
            data-testid="fund-filter-all"
            aria-current={!active ? "page" : undefined}
          >
            {t("all")}
          </Link>
          {categories.map((category) => (
            <Link
              key={category.key}
              href={`/funds?group=${category.key}`}
              className={chipClass(active === category.key)}
              data-testid={`fund-filter-${category.key}`}
              aria-current={active === category.key ? "page" : undefined}
            >
              {category.label}
            </Link>
          ))}
        </nav>
      ) : null}

      {visible.length > 0 ? (
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {visible.map((fund) => (
            <FundCard key={fund.id} fund={fund} locale={locale} detailed />
          ))}
        </div>
      ) : (
        <EmptyState message={t("empty")} />
      )}

      <p className="mt-10 max-w-3xl text-xs leading-relaxed text-muted">
        {t("disclaimer")}
      </p>
    </Container>
  );
}
