import type { Metadata } from "next";
import { getTranslations } from "next-intl/server";
import { ServiceIcon } from "@/components/icons/ServiceIcon";
import { Container } from "@/components/ui/Container";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { Link } from "@/i18n/navigation";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type { Section, Service } from "@/types/api";

type Props = { params: Promise<{ locale: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("services.title"),
    description: t("services.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/services"),
      languages: languageAlternates("/services"),
    },
    openGraph: {
      title: t("services.title"),
      description: t("services.description"),
      url: absoluteUrl(locale, "/services"),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function ServicesPage({ params }: Props) {
  const locale = assertLocale((await params).locale);

  const [services, intro, t, tc] = await Promise.all([
    fetchData<Service[]>(locale, endpoints.services),
    fetchData<Section>(locale, endpoints.section("services_intro")),
    getTranslations({ locale, namespace: "Services" }),
    getTranslations({ locale, namespace: "Common" }),
  ]);

  return (
    <Container className="py-14">
      <SectionHeader
        title={intro?.title ?? t("title")}
        subtitle={intro?.subtitle ?? t("subtitle")}
      />
      {services && services.length > 0 ? (
        <div className="grid gap-6 sm:grid-cols-2">
          {services.map((service) => (
            <Link
              key={service.id}
              href={`/services/${service.slug}`}
              data-testid={`service-item-${service.key}`}
              className="group flex items-start gap-6 rounded-card-lg border border-border bg-surface p-8 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/50 hover:shadow-elev-3"
            >
              <ServiceIcon name={service.icon} className="shrink-0" />
              <span>
                <h2 className="mb-2 text-xl font-bold">{service.name}</h2>
                <p className="text-[0.92rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                  {service.description}
                </p>
                <span className="mt-4 inline-block text-[0.85rem] font-semibold text-accent transition-transform duration-300 group-hover:translate-x-1 rtl:group-hover:-translate-x-1">
                  {tc("learnMore")}
                </span>
              </span>
            </Link>
          ))}
        </div>
      ) : (
        <EmptyState message={t("empty")} />
      )}
    </Container>
  );
}
