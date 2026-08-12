import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { getTranslations } from "next-intl/server";
import { ChannelBadge } from "@/components/features/funds/ChannelBadge";
import { FundCard } from "@/components/features/funds/FundCard";
import { PlatformsList } from "@/components/features/funds/PlatformsList";
import { FundIllustration } from "@/components/icons/FundIllustration";
import { JsonLd } from "@/components/misc/JsonLd";
import { Container } from "@/components/ui/Container";
import { RiskBadge } from "@/components/ui/RiskBadge";
import { Sparkline } from "@/components/ui/Sparkline";
import { Link } from "@/i18n/navigation";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { cn } from "@/lib/utils/cn";
import { signedChange } from "@/lib/utils/format";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates, siteUrl } from "@/lib/utils/urls";
import type { Fund } from "@/types/api";

type Props = { params: Promise<{ locale: string; slug: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { locale: rawLocale, slug } = await params;
  const locale = assertLocale(rawLocale);
  const fund = await fetchData<Fund>(locale, endpoints.fund(slug));
  if (!fund) notFound();
  const title = `${fund.name} — AFIM`;
  return {
    title,
    description: fund.description ?? undefined,
    alternates: {
      canonical: absoluteUrl(locale, `/funds/${slug}`),
      languages: languageAlternates(`/funds/${slug}`),
    },
    openGraph: {
      title,
      description: fund.description ?? undefined,
      url: absoluteUrl(locale, `/funds/${slug}`),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function FundDetailPage({ params }: Props) {
  const { locale: rawLocale, slug } = await params;
  const locale = assertLocale(rawLocale);

  const fund = await fetchData<Fund>(locale, endpoints.fund(slug));
  if (!fund) notFound();

  const [t, tc, related] = await Promise.all([
    getTranslations({ locale, namespace: "Funds" }),
    getTranslations({ locale, namespace: "FundCard" }),
    fetchData<Fund[]>(locale, endpoints.funds, {
      "filters[group_key]": fund.group_key,
    }),
  ]);

  const change = fund.daily_change ? signedChange(fund.daily_change) : null;
  const relatedFunds = (related ?? [])
    .filter((entry) => entry.slug !== fund.slug)
    .slice(0, 3);

  return (
    <Container className="py-14">
      <JsonLd
        data={{
          "@context": "https://schema.org",
          "@type": "FinancialProduct",
          name: fund.name,
          description: fund.description ?? undefined,
          url: absoluteUrl(locale, `/funds/${fund.slug}`),
          category: fund.category_label,
          provider: {
            "@type": "Organization",
            name: "Al Ahly Financial Investments Management",
            url: siteUrl(),
          },
        }}
      />

      <nav className="mb-8 text-sm text-muted" aria-label="Breadcrumb">
        <Link href="/funds" className="text-accent hover:text-accent-dark">
          {t("backToFunds")}
        </Link>
        <span className="mx-2" aria-hidden="true">
          /
        </span>
        <span>{fund.name}</span>
      </nav>

      <div className="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
        <div>
          <div className="mb-6 flex items-center gap-5">
            <span className="grid size-24 shrink-0 place-items-center rounded-card-lg border border-border bg-surface p-3 shadow-elev-1">
              <FundIllustration name={fund.illustration} className="size-16" />
            </span>
            <div>
              <div className="mb-2 flex flex-wrap items-center gap-2.5">
                <span className="text-[0.72rem] uppercase tracking-wide text-muted">
                  {fund.category_label}
                </span>
                <RiskBadge level={fund.risk_level} label={fund.risk_label} />
              </div>
              <h1 className="text-3xl font-extrabold leading-tight sm:text-4xl">
                {fund.name}
              </h1>
            </div>
          </div>

          {fund.description ? (
            <>
              <h2 className="mb-2 mt-8 text-sm font-bold uppercase tracking-widest text-muted">
                {t("aboutFund")}
              </h2>
              <p className="max-w-2xl leading-relaxed text-soft rtl:leading-loose">
                {fund.description}
              </p>
            </>
          ) : null}

          <div className="mt-8 rounded-card-lg border border-border bg-surface p-6 shadow-elev-1">
            <h2 className="mb-3 text-sm font-bold uppercase tracking-widest text-muted">
              {t("howToSubscribe")}
            </h2>
            <ChannelBadge
              channel={fund.order_channel}
              label={fund.order_channel_label}
            />
            <p className="mt-3 text-[0.92rem] leading-relaxed text-soft">
              {fund.how_to}
            </p>
            <PlatformsList
              platforms={fund.platforms}
              label={t("platformsToggle", { count: fund.platforms.length })}
            />
          </div>

          <p className="mt-8 max-w-2xl text-xs leading-relaxed text-muted">
            {t("disclaimer")}
          </p>
        </div>

        <aside className="rounded-card-lg border border-border bg-surface p-7 shadow-elev-1">
          {fund.nav_price ? (
            <>
              <div className="text-[0.68rem] uppercase tracking-widest text-muted">
                {t("navPerCertificate")}
              </div>
              <div className="mt-1 flex items-baseline gap-2" dir="ltr">
                <span className="tnum text-5xl font-extrabold tracking-tight">
                  {fund.nav_price}
                </span>
                <span className="text-sm text-muted">{tc("currency")}</span>
              </div>
              {change ? (
                <div
                  className={cn(
                    "tnum mt-2 text-sm font-semibold",
                    change.up ? "text-up" : "text-down",
                  )}
                >
                  <span dir="ltr">
                    {change.up ? "▲" : "▼"} {change.signed}%
                  </span>{" "}
                  <span className="font-normal text-muted">{tc("today")}</span>
                </div>
              ) : null}
              <div className="mt-6 border-t border-hairline pt-5">
                <div className="tnum text-3xl font-extrabold text-accent" dir="ltr">
                  {fund.yield_1y ? `+${fund.yield_1y}%` : "—"}
                </div>
                <div className="mt-1 text-[0.68rem] uppercase tracking-widest text-muted">
                  {tc("yield1y")}
                </div>
              </div>
              {fund.spark && fund.spark.length > 1 ? (
                <div className="mt-6">
                  <Sparkline
                    data={fund.spark}
                    up={change?.up ?? true}
                    id={`detail-${fund.slug}`}
                    width={300}
                    height={90}
                    strokeWidth={2.5}
                    className="w-full"
                  />
                </div>
              ) : null}
            </>
          ) : (
            <p className="text-sm text-muted">{tc("noPricing")}</p>
          )}
        </aside>
      </div>

      {relatedFunds.length > 0 ? (
        <section className="mt-16">
          <h2 className="mb-6 text-2xl font-extrabold">{t("related")}</h2>
          <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {relatedFunds.map((entry) => (
              <FundCard key={entry.id} fund={entry} locale={locale} />
            ))}
          </div>
        </section>
      ) : null}
    </Container>
  );
}
