import { getTranslations } from "next-intl/server";
import { CtaLink } from "@/components/misc/CtaLink";
import { buttonVariants } from "@/components/ui/Button";
import type { Locale } from "@/i18n/routing";
import { extraString } from "@/lib/utils/sections";
import type { Section } from "@/types/api";
import { HeroChart, type HeroChartMilestone } from "./HeroChart";

export async function Hero({
  locale,
  section,
}: {
  locale: Locale;
  section: Section | null;
}) {
  const t = await getTranslations({ locale, namespace: "Home" });

  const eyebrow = extraString(section, "eyebrow") ?? t("heroEyebrow");
  const title = section?.title ?? t("heroTitle1");
  const accentLine = section?.subtitle ?? t("heroTitle2");
  const lead = section?.body ?? t("heroLead");
  const primaryLabel = section?.cta?.label ?? t("heroPrimaryCta");
  const secondaryLabel = section?.cta?.secondary_label ?? t("heroSecondaryCta");
  const chartUnit = extraString(section, "chart_unit") ?? t("heroChartUnit");
  const chartMilestones = (
    (section?.extra?.chart_milestones as HeroChartMilestone[] | undefined) ??
    []
  ).filter((m) => m?.year && m?.value);

  return (
    <section
      className="hero-glow relative grid items-center gap-10 overflow-hidden px-[6vw] py-14 lg:min-h-[84vh] lg:grid-cols-[1.15fr_0.85fr] lg:py-20"
      data-testid="home-hero"
    >
      <div className="relative z-1">
        <span className="eyebrow-rule mb-5 inline-flex items-center gap-2.5 text-[0.72rem] font-semibold uppercase tracking-[0.22em] text-accent">
          {eyebrow}
        </span>
        <h1 className="text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl rtl:leading-[1.32] rtl:tracking-normal">
          {title}
          <br />
          <span className="text-accent">{accentLine}</span>
        </h1>
        <p className="mb-9 mt-6 max-w-xl text-[1.06rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
          {lead}
        </p>
        <div className="flex flex-wrap gap-4">
          <CtaLink
            href={section?.cta?.href}
            testId="hero-cta-primary"
            className={buttonVariants({ variant: "cta", size: "lg" })}
          >
            {primaryLabel}
          </CtaLink>
          <CtaLink
            href={section?.cta?.secondary_href ?? "/services"}
            testId="hero-cta-secondary"
            className={buttonVariants({ variant: "ghost", size: "lg" })}
          >
            {secondaryLabel}
          </CtaLink>
        </div>
      </div>

      <div className="relative z-1 hidden lg:block">
        <HeroChart unit={chartUnit} milestones={chartMilestones} />
      </div>
    </section>
  );
}
