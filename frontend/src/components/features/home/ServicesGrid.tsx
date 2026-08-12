import { getTranslations } from "next-intl/server";
import { ServiceIcon } from "@/components/icons/ServiceIcon";
import { Reveal } from "@/components/misc/Reveal";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { Link } from "@/i18n/navigation";
import type { Locale } from "@/i18n/routing";
import type { Section, Service } from "@/types/api";

export async function ServicesGrid({
  locale,
  section,
  services,
}: {
  locale: Locale;
  section: Section | null;
  services: Service[] | null;
}) {
  const t = await getTranslations({ locale, namespace: "Home" });
  const ts = await getTranslations({ locale, namespace: "Services" });

  return (
    <section
      className="bg-surface-2 px-[6vw] py-14"
      id="services"
      data-testid="home-services"
    >
      <Reveal>
        <SectionHeader
          title={section?.title ?? ts("title")}
          subtitle={section?.subtitle ?? ts("subtitle")}
        />
      </Reveal>
      {services && services.length > 0 ? (
        <Reveal>
          <div className="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            {services.map((service) => (
              <Link
                key={service.id}
                href={`/services/${service.slug}`}
                data-testid={`service-card-${service.key}`}
                className="group relative overflow-hidden rounded-card-lg border border-border bg-surface p-7 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/50 hover:shadow-elev-3"
              >
                <ServiceIcon name={service.icon} className="mb-5" />
                <h3 className="mb-2.5 text-[1.08rem] font-bold">
                  {service.name}
                </h3>
                <p className="text-[0.88rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                  {service.description}
                </p>
                <span className="mt-5 inline-block text-[0.82rem] font-semibold text-accent opacity-0 transition-all duration-300 group-hover:translate-x-0 group-hover:opacity-100 ltr:-translate-x-2 rtl:translate-x-2">
                  {t("isThisForMe")}
                </span>
              </Link>
            ))}
          </div>
        </Reveal>
      ) : (
        <EmptyState message={t("servicesEmpty")} />
      )}
    </section>
  );
}
