import { CheckCheck } from "lucide-react";
import { getTranslations } from "next-intl/server";
import { IconKey } from "@/components/icons/IconKey";
import { CtaLink } from "@/components/misc/CtaLink";
import { Reveal } from "@/components/misc/Reveal";
import { buttonVariants } from "@/components/ui/Button";
import type { Locale } from "@/i18n/routing";
import { extraString } from "@/lib/utils/sections";
import type { Section } from "@/types/api";

/** "Need help in investment?" panel with the floating survey-preview card. */
export async function AdvisorSection({
  locale,
  section,
}: {
  locale: Locale;
  section: Section | null;
}) {
  if (!section) return null;
  const t = await getTranslations({ locale, namespace: "Home" });
  const tp = await getTranslations({
    locale,
    namespace: "Home.advisorPreview",
  });

  const note = extraString(section, "note");
  const steps = Array.isArray(section.extra?.steps)
    ? (section.extra.steps as unknown[]).filter(
        (step): step is string => typeof step === "string",
      )
    : [tp("rail1"), tp("rail2"), tp("rail3")];

  return (
    <section className="px-[6vw] pt-10" data-testid="home-advisor">
      <Reveal>
        <div className="relative mx-auto grid max-w-6xl items-center gap-10 overflow-hidden rounded-card-xl border border-border bg-linear-135 from-surface-2 to-background-2 p-8 shadow-elev-1 sm:p-12 lg:grid-cols-[1.08fr_0.92fr]">
          <div className="relative">
            {section.title ? (
              <h2
                className="text-3xl font-extrabold leading-snug sm:text-4xl"
                dangerouslySetInnerHTML={{ __html: section.title }}
              />
            ) : null}
            {section.body ? (
              <p className="mt-4 max-w-lg text-[0.99rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                {section.body}
              </p>
            ) : null}

            {(section.items ?? []).length > 0 ? (
              <div className="my-6 flex flex-wrap gap-2">
                {(section.items ?? []).map((chip, index) => (
                  <span
                    key={index}
                    className="inline-flex items-center gap-2 rounded-full border border-border bg-surface px-3.5 py-1.5 text-[0.78rem] text-soft"
                  >
                    <IconKey name={chip.icon} className="size-3.5 text-accent" />
                    {chip.text}
                  </span>
                ))}
              </div>
            ) : null}

            <div className="flex flex-wrap gap-4">
              <CtaLink
                href={section.cta?.href ?? "/survey"}
                testId="advisor-cta-survey"
                className={buttonVariants({ variant: "cta", size: "lg" })}
              >
                {section.cta?.label ?? t("advisorPrimaryCta")}
              </CtaLink>
              <CtaLink
                href={section.cta?.secondary_href ?? "/funds"}
                testId="advisor-cta-funds"
                className={buttonVariants({ variant: "ghost", size: "lg" })}
              >
                {section.cta?.secondary_label ?? t("advisorSecondaryCta")}
              </CtaLink>
            </div>
            {note ? <p className="mt-4 text-xs text-muted">{note}</p> : null}
          </div>

          {/* Decorative survey preview */}
          <div
            className="pointer-events-none relative mx-auto w-full max-w-[340px] select-none"
            aria-hidden="true"
          >
            <div className="adv-float relative rounded-card-lg border border-border bg-surface p-5 pb-6 shadow-elev-2 dark:bg-surface-2">
              <span className="text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-accent rtl:tracking-normal">
                {tp("step")}
              </span>
              <h4 className="mb-4 mt-2 text-[1.02rem] font-bold">
                {tp("question")}
              </h4>
              {[
                { label: tp("opt1"), icon: "shield", selected: true, dim: false },
                { label: tp("opt2"), icon: "sprout", selected: false, dim: false },
                { label: tp("opt3"), icon: "payout", selected: false, dim: true },
              ].map((option, index) => (
                <div
                  key={index}
                  className={`mb-2 flex items-center gap-2.5 rounded-[11px] border bg-background-2 px-3 py-2.5 text-[0.8rem] ${
                    option.selected
                      ? "border-accent text-foreground shadow-[0_0_0_3px_rgba(246,125,48,0.14)]"
                      : "border-border text-soft"
                  } ${option.dim ? "opacity-55" : ""}`}
                >
                  <IconKey name={option.icon} className="size-4 text-accent" />
                  <span>{option.label}</span>
                  {option.selected ? (
                    <span className="ms-auto font-bold text-accent">✓</span>
                  ) : null}
                </div>
              ))}
              <div className="adv-float-2 absolute -bottom-6 -end-3 flex items-center gap-2 rounded-[14px] bg-linear-120 from-navy to-navy-2 px-3.5 py-2.5 text-[0.78rem] text-white shadow-elev-2">
                <CheckCheck className="size-4 text-accent" aria-hidden="true" />
                <span>
                  {tp("match")} <b className="text-accent">{tp("matchName")}</b>
                </span>
              </div>
            </div>
            <div className="mt-11 flex flex-wrap items-center justify-center gap-2 text-[0.74rem] text-muted">
              {steps.map((step, index) => (
                <span key={index} className="inline-flex items-center gap-1.5">
                  <b
                    className={`grid size-5 place-items-center rounded-full border text-[0.66rem] ${
                      index === 0
                        ? "border-accent bg-accent text-accent-on"
                        : "border-border bg-surface-2 text-foreground"
                    }`}
                  >
                    {index + 1}
                  </b>
                  <span>{step}</span>
                  {index < steps.length - 1 ? (
                    <span className="mx-1 text-border">──</span>
                  ) : null}
                </span>
              ))}
            </div>
          </div>
        </div>
      </Reveal>
    </section>
  );
}
