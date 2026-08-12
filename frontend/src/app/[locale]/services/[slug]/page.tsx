import type { Metadata } from "next";
import { notFound } from "next/navigation";
import { getTranslations } from "next-intl/server";
import { ServiceIcon } from "@/components/icons/ServiceIcon";
import { CtaLink } from "@/components/misc/CtaLink";
import { Container } from "@/components/ui/Container";
import { buttonVariants } from "@/components/ui/Button";
import { Link } from "@/i18n/navigation";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type { Service } from "@/types/api";

type Props = { params: Promise<{ locale: string; slug: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { locale: rawLocale, slug } = await params;
  const locale = assertLocale(rawLocale);
  const service = await fetchData<Service>(locale, endpoints.service(slug));
  if (!service) notFound();
  const title = `${service.name} — AFIM`;
  return {
    title,
    description: service.description,
    alternates: {
      canonical: absoluteUrl(locale, `/services/${slug}`),
      languages: languageAlternates(`/services/${slug}`),
    },
    openGraph: {
      title,
      description: service.description,
      url: absoluteUrl(locale, `/services/${slug}`),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function ServiceDetailPage({ params }: Props) {
  const { locale: rawLocale, slug } = await params;
  const locale = assertLocale(rawLocale);

  const service = await fetchData<Service>(locale, endpoints.service(slug));
  if (!service) notFound();

  const t = await getTranslations({ locale, namespace: "Services" });
  const paragraphs = (service.body ?? service.description)
    .split(/\n{2,}/)
    .map((paragraph) => paragraph.trim())
    .filter(Boolean);

  return (
    <Container className="py-14">
      <nav className="mb-8 text-sm text-muted" aria-label="Breadcrumb">
        <Link href="/services" className="text-accent hover:text-accent-dark">
          {t("backToServices")}
        </Link>
        <span className="mx-2" aria-hidden="true">
          /
        </span>
        <span>{service.name}</span>
      </nav>

      <div className="mx-auto max-w-3xl" data-testid="service-detail">
        <div className="mb-8 flex items-center gap-5">
          <ServiceIcon name={service.icon} />
          <h1 className="text-3xl font-extrabold leading-tight sm:text-4xl">
            {service.name}
          </h1>
        </div>
        <p className="mb-6 text-lg font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
          {service.description}
        </p>
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

        <div className="mt-12 rounded-card-lg border border-border bg-surface-2 p-8 text-center">
          <p className="mb-5 font-semibold">{t("notSure")}</p>
          <div className="flex flex-wrap justify-center gap-4">
            <CtaLink
              href="#finder"
              testId="service-finder-cta"
              className={buttonVariants({ variant: "cta", size: "lg" })}
            >
              {t("findService")}
            </CtaLink>
            <Link
              href="/contact"
              data-testid="service-contact-cta"
              className={buttonVariants({ variant: "ghost", size: "lg" })}
            >
              {t("contactUs")}
            </Link>
          </div>
        </div>
      </div>
    </Container>
  );
}
