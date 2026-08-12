import type { Metadata } from "next";
import { getTranslations } from "next-intl/server";
import { FaqAccordion } from "@/components/features/faqs/FaqAccordion";
import { Container } from "@/components/ui/Container";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type { Faq, Section } from "@/types/api";

type Props = { params: Promise<{ locale: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("faqs.title"),
    description: t("faqs.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/faqs"),
      languages: languageAlternates("/faqs"),
    },
    openGraph: {
      title: t("faqs.title"),
      description: t("faqs.description"),
      url: absoluteUrl(locale, "/faqs"),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function FaqsPage({ params }: Props) {
  const locale = assertLocale((await params).locale);

  const [faqs, intro, t] = await Promise.all([
    fetchData<Faq[]>(locale, endpoints.faqs),
    fetchData<Section>(locale, endpoints.section("faqs_intro")),
    getTranslations({ locale, namespace: "Faqs" }),
  ]);

  return (
    <Container className="max-w-3xl py-14">
      <SectionHeader
        title={intro?.title ?? t("title")}
        subtitle={intro?.subtitle ?? t("subtitle")}
        center
      />
      {faqs && faqs.length > 0 ? (
        <FaqAccordion faqs={faqs} />
      ) : (
        <EmptyState message={t("empty")} />
      )}
    </Container>
  );
}
