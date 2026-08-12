import type { Metadata } from "next";
import { getTranslations } from "next-intl/server";
import { AdvisorSection } from "@/components/features/home/AdvisorSection";
import { AnnouncementBanner } from "@/components/features/home/AnnouncementBanner";
import { CtaBand } from "@/components/features/home/CtaBand";
import { FiguresBand } from "@/components/features/home/FiguresBand";
import { GoalsGrid } from "@/components/features/home/GoalsGrid";
import { Hero } from "@/components/features/home/Hero";
import { PricesSection } from "@/components/features/home/PricesSection";
import { ServicesGrid } from "@/components/features/home/ServicesGrid";
import { StepsSection } from "@/components/features/home/StepsSection";
import { Ticker } from "@/components/features/home/Ticker";
import { TrustStrip } from "@/components/features/home/TrustStrip";
import { WhySection } from "@/components/features/home/WhySection";
import { JsonLd } from "@/components/misc/JsonLd";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { assertLocale } from "@/lib/utils/locale";
import { toSectionMap } from "@/lib/utils/sections";
import { absoluteUrl, languageAlternates, siteUrl } from "@/lib/utils/urls";
import type { Fund, Section, Service } from "@/types/api";

type Props = { params: Promise<{ locale: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("home.title"),
    description: t("home.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/"),
      languages: languageAlternates("/"),
    },
    openGraph: {
      title: t("home.title"),
      description: t("home.description"),
      url: absoluteUrl(locale, "/"),
      siteName: "AFIM",
      type: "website",
      locale: locale === "ar" ? "ar_EG" : "en_US",
    },
  };
}

export default async function HomePage({ params }: Props) {
  const locale = assertLocale((await params).locale);

  const [sections, featuredFunds, services] = await Promise.all([
    fetchData<Section[]>(locale, endpoints.sections),
    fetchData<Fund[]>(locale, endpoints.funds, { "filters[is_featured]": 1 }),
    fetchData<Service[]>(locale, endpoints.services),
  ]);
  const map = toSectionMap(sections);

  const announcement = map["announcement"];
  const t = await getTranslations({ locale, namespace: "Home" });

  return (
    <>
      <JsonLd
        data={{
          "@context": "https://schema.org",
          "@graph": [
            {
              "@type": "Organization",
              "@id": `${siteUrl()}/#organization`,
              name: "Al Ahly Financial Investments Management",
              alternateName: "AFIM",
              url: siteUrl(),
              foundingDate: "1994",
              address: {
                "@type": "PostalAddress",
                addressLocality: "Giza",
                addressCountry: "EG",
              },
            },
            {
              "@type": "WebSite",
              "@id": `${siteUrl()}/#website`,
              url: siteUrl(),
              name: "AFIM",
              publisher: { "@id": `${siteUrl()}/#organization` },
              inLanguage: ["ar", "en"],
            },
          ],
        }}
      />

      {announcement?.body ? (
        <AnnouncementBanner
          text={announcement.body}
          ctaLabel={announcement.cta?.label ?? null}
          ctaHref={announcement.cta?.href ?? null}
        />
      ) : null}

      {map["ticker"]?.items ? (
        <Ticker items={map["ticker"].items} ariaLabel={t("tickerAria")} />
      ) : null}

      <Hero locale={locale} section={map["hero"] ?? null} />
      <AdvisorSection locale={locale} section={map["advisor"] ?? null} />
      <TrustStrip section={map["trust"] ?? null} />
      <GoalsGrid section={map["goals"] ?? null} />
      <PricesSection
        locale={locale}
        section={map["prices_intro"] ?? null}
        funds={featuredFunds}
      />
      <ServicesGrid
        locale={locale}
        section={map["services_intro"] ?? null}
        services={services}
      />
      <WhySection section={map["why"] ?? null} />
      <FiguresBand section={map["figures"] ?? null} />
      <StepsSection locale={locale} section={map["steps"] ?? null} />
      <CtaBand section={map["cta"] ?? null} />
    </>
  );
}
