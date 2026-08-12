import { getTranslations } from "next-intl/server";
import { FundCard } from "@/components/features/funds/FundCard";
import { Reveal } from "@/components/misc/Reveal";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import type { Locale } from "@/i18n/routing";
import { extraString } from "@/lib/utils/sections";
import type { Fund, Section } from "@/types/api";
import { PricesCarousel } from "./PricesCarousel";

/** "Prices & yields" — featured funds carousel. */
export async function PricesSection({
  locale,
  section,
  funds,
}: {
  locale: Locale;
  section: Section | null;
  funds: Fund[] | null;
}) {
  const t = await getTranslations({ locale, namespace: "Home" });
  const tf = await getTranslations({ locale, namespace: "Funds" });

  const liveLabel = extraString(section, "live_label");
  const disclaimer = extraString(section, "disclaimer");

  return (
    <section className="px-[6vw] pb-6 pt-12" id="prices" data-testid="home-prices">
      <Reveal>
        <SectionHeader
          title={section?.title ?? tf("title")}
          subtitle={section?.subtitle ?? tf("subtitle")}
          className="mb-6"
        >
          <div className="mt-3 flex flex-wrap items-center gap-4 text-xs text-muted">
            {liveLabel ? (
              <span className="inline-flex items-center gap-2 font-semibold text-accent">
                <span className="pulse-dot" aria-hidden="true" />
                {liveLabel}
              </span>
            ) : null}
            {disclaimer ? <span>{disclaimer}</span> : null}
          </div>
        </SectionHeader>
      </Reveal>

      {funds && funds.length > 0 ? (
        <PricesCarousel
          ariaLabel={t("pricesCarouselAria")}
          prevLabel={t("pricesPrev")}
          nextLabel={t("pricesNext")}
        >
          {funds.map((fund) => (
            <div
              key={fund.id}
              className="w-[84vw] flex-none snap-start sm:w-[300px]"
            >
              <FundCard fund={fund} locale={locale} className="h-full" />
            </div>
          ))}
        </PricesCarousel>
      ) : (
        <EmptyState message={t("fundsEmpty")} />
      )}
    </section>
  );
}
