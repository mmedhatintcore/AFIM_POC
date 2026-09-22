import { getTranslations } from "next-intl/server";
import { CtaLink } from "@/components/misc/CtaLink";
import { Reveal } from "@/components/misc/Reveal";
import { buttonVariants } from "@/components/ui/Button";
import { SectionHeader } from "@/components/ui/SectionHeader";
import type { Locale } from "@/i18n/routing";
import type { Section } from "@/types/api";

/** "How to start" — 3 numbered steps with connectors. */
export async function StepsSection({
  locale,
  section,
}: {
  locale: Locale;
  section: Section | null;
}) {
  if (!section || (section.items ?? []).length === 0) return null;
  const t = await getTranslations({ locale, namespace: "Home" });

  return (
    <section className="px-[6vw] py-14" data-testid="home-steps">
      <Reveal>
        <SectionHeader title={section.title} center logo />
      </Reveal>
      <Reveal>
        <div className="mx-auto grid max-w-4xl gap-8 md:grid-cols-3 md:gap-6">
          {(section.items ?? []).map((step, index, all) => (
            <div key={index} className="relative px-3 text-center">
              <span
                className="tnum relative z-1 mb-5 inline-grid size-16 place-items-center rounded-full border border-border bg-surface text-2xl font-extrabold text-accent shadow-elev-1"
                aria-hidden="true"
              >
                {index + 1}
              </span>
              {index < all.length - 1 ? (
                <span
                  className="absolute start-[calc(50%+40px)] top-8 hidden h-0.5 w-[calc(100%-40px)] from-accent to-transparent opacity-45 md:block ltr:bg-linear-to-r rtl:bg-linear-to-l"
                  aria-hidden="true"
                />
              ) : null}
              <h3 className="mb-2 text-[1.1rem] font-bold">{step.title}</h3>
              <p className="mx-auto max-w-72 text-[0.88rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                {step.text}
              </p>
            </div>
          ))}
        </div>
        <div className="mt-11 text-center">
          <CtaLink
            href={section.cta?.href}
            testId="steps-cta"
            className={buttonVariants({ variant: "cta", size: "lg" })}
          >
            {section.cta?.label ?? t("stepsCta")}
          </CtaLink>
        </div>
      </Reveal>
    </section>
  );
}
