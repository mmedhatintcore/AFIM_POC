import type { Metadata } from "next";
import { getTranslations } from "next-intl/server";
import { NewsCard } from "@/components/features/news/NewsCard";
import { Container } from "@/components/ui/Container";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { Link } from "@/i18n/navigation";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData, fetchPaginated } from "@/lib/api/server";
import { cn } from "@/lib/utils/cn";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type { NewsItem, NewsType, Section } from "@/types/api";

const TYPES: NewsType[] = ["press", "media", "social"];

type Props = {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{ type?: string; page?: string }>;
};

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("news.title"),
    description: t("news.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/news"),
      languages: languageAlternates("/news"),
    },
    openGraph: {
      title: t("news.title"),
      description: t("news.description"),
      url: absoluteUrl(locale, "/news"),
      siteName: "AFIM",
      type: "website",
    },
  };
}

function newsHref(type: string | undefined, page: number): string {
  const search = new URLSearchParams();
  if (type) search.set("type", type);
  if (page > 1) search.set("page", String(page));
  const query = search.toString();
  return query ? `/news?${query}` : "/news";
}

export default async function NewsPage({ params, searchParams }: Props) {
  const locale = assertLocale((await params).locale);
  const query = await searchParams;
  const type = TYPES.find((entry) => entry === query.type);
  const page = Math.max(1, Number.parseInt(query.page ?? "1", 10) || 1);

  const [result, intro, t, common] = await Promise.all([
    fetchPaginated<NewsItem>(locale, endpoints.news, {
      "filters[type]": type,
      page,
    }),
    fetchData<Section>(locale, endpoints.section("news_intro")),
    getTranslations({ locale, namespace: "News" }),
    getTranslations({ locale, namespace: "Common" }),
  ]);

  const items = result?.data ?? [];
  const meta = result?.meta;

  const tabClass = (selected: boolean) =>
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

      <nav
        className="mb-9 flex flex-wrap gap-2.5"
        aria-label={t("tabsAria")}
        data-testid="news-tabs"
      >
        <Link
          href={newsHref(undefined, 1)}
          className={tabClass(!type)}
          data-testid="news-tab-all"
          aria-current={!type ? "page" : undefined}
        >
          {t("tabs.all")}
        </Link>
        {TYPES.map((entry) => (
          <Link
            key={entry}
            href={newsHref(entry, 1)}
            className={tabClass(type === entry)}
            data-testid={`news-tab-${entry}`}
            aria-current={type === entry ? "page" : undefined}
          >
            {t(`tabs.${entry}`)}
          </Link>
        ))}
      </nav>

      {items.length > 0 ? (
        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {items.map((item) => (
            <NewsCard key={item.id} item={item} locale={locale} />
          ))}
        </div>
      ) : (
        <EmptyState message={t("empty")} />
      )}

      {meta && meta.last_page > 1 ? (
        <nav
          className="mt-12 flex items-center justify-center gap-4"
          aria-label={t("paginationAria")}
          data-testid="news-pagination"
        >
          {meta.current_page > 1 ? (
            <Link
              href={newsHref(type, meta.current_page - 1)}
              className="rounded-full border border-border px-5 py-2 text-sm text-soft transition-colors hover:border-accent hover:text-accent"
              data-testid="news-prev"
            >
              {common("previous")}
            </Link>
          ) : null}
          <span className="tnum text-sm text-muted">
            {t("pageOf", { page: meta.current_page, total: meta.last_page })}
          </span>
          {meta.current_page < meta.last_page ? (
            <Link
              href={newsHref(type, meta.current_page + 1)}
              className="rounded-full border border-border px-5 py-2 text-sm text-soft transition-colors hover:border-accent hover:text-accent"
              data-testid="news-next"
            >
              {common("next")}
            </Link>
          ) : null}
        </nav>
      ) : null}
    </Container>
  );
}
