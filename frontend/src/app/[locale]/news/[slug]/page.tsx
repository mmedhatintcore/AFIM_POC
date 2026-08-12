import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { getTranslations } from "next-intl/server";
import { NewsCard } from "@/components/features/news/NewsCard";
import { JsonLd } from "@/components/misc/JsonLd";
import { Container } from "@/components/ui/Container";
import { Link } from "@/i18n/navigation";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { formatDate } from "@/lib/utils/format";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates, siteUrl } from "@/lib/utils/urls";
import type { NewsArticle } from "@/types/api";

type Props = { params: Promise<{ locale: string; slug: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { locale: rawLocale, slug } = await params;
  const locale = assertLocale(rawLocale);
  const article = await fetchData<NewsArticle>(
    locale,
    endpoints.newsArticle(slug),
  );
  if (!article) notFound();
  const title = `${article.title} — AFIM`;
  return {
    title,
    description: article.excerpt,
    alternates: {
      canonical: absoluteUrl(locale, `/news/${slug}`),
      languages: languageAlternates(`/news/${slug}`),
    },
    openGraph: {
      title,
      description: article.excerpt,
      url: absoluteUrl(locale, `/news/${slug}`),
      siteName: "AFIM",
      type: "article",
      publishedTime: article.published_at,
    },
  };
}

export default async function NewsArticlePage({ params }: Props) {
  const { locale: rawLocale, slug } = await params;
  const locale = assertLocale(rawLocale);

  const article = await fetchData<NewsArticle>(
    locale,
    endpoints.newsArticle(slug),
  );
  if (!article) notFound();

  const t = await getTranslations({ locale, namespace: "News" });
  const paragraphs = article.body
    .split(/\n{2,}/)
    .map((paragraph) => paragraph.trim())
    .filter(Boolean);

  return (
    <Container className="py-14">
      <JsonLd
        data={{
          "@context": "https://schema.org",
          "@type": "NewsArticle",
          headline: article.title,
          description: article.excerpt,
          datePublished: article.published_at,
          inLanguage: locale,
          mainEntityOfPage: absoluteUrl(locale, `/news/${article.slug}`),
          author: { "@type": "Organization", name: article.source },
          publisher: {
            "@type": "Organization",
            name: "Al Ahly Financial Investments Management",
            url: siteUrl(),
          },
        }}
      />
      <JsonLd
        data={{
          "@context": "https://schema.org",
          "@type": "BreadcrumbList",
          itemListElement: [
            {
              "@type": "ListItem",
              position: 1,
              name: t("breadcrumbHome"),
              item: absoluteUrl(locale, "/"),
            },
            {
              "@type": "ListItem",
              position: 2,
              name: t("title"),
              item: absoluteUrl(locale, "/news"),
            },
            { "@type": "ListItem", position: 3, name: article.title },
          ],
        }}
      />

      <nav
        className="mb-8 text-sm text-muted"
        aria-label="Breadcrumb"
        data-testid="news-breadcrumbs"
      >
        <Link href="/" className="hover:text-accent">
          {t("breadcrumbHome")}
        </Link>
        <span className="mx-2" aria-hidden="true">
          /
        </span>
        <Link href="/news" className="text-accent hover:text-accent-dark">
          {t("backToNews")}
        </Link>
        <span className="mx-2" aria-hidden="true">
          /
        </span>
        <span className="text-soft">{article.title}</span>
      </nav>

      <article className="mx-auto max-w-3xl" data-testid="news-article">
        <div className="mb-4 flex flex-wrap items-center gap-3 text-[0.78rem] text-muted">
          <span className="rounded-full border border-border px-3 py-1 text-[0.66rem] uppercase tracking-widest">
            {article.type_label}
          </span>
          <span>
            {article.source} · {formatDate(article.published_at, locale)}
          </span>
        </div>
        <h1 className="mb-6 text-3xl font-extrabold leading-tight sm:text-4xl rtl:leading-snug">
          {article.title}
        </h1>
        <div className="space-y-5">
          {paragraphs.map((paragraph, index) => (
            <p
              key={index}
              className="leading-relaxed text-soft rtl:leading-loose"
            >
              {paragraph}
            </p>
          ))}
        </div>
      </article>

      {article.related.length > 0 ? (
        <section className="mt-16">
          <h2 className="mb-6 text-2xl font-extrabold">{t("related")}</h2>
          <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {article.related.map((item) => (
              <NewsCard key={item.id} item={item} locale={locale} />
            ))}
          </div>
        </section>
      ) : null}
    </Container>
  );
}
