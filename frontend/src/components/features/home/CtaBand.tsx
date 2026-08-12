import { CtaLink } from "@/components/misc/CtaLink";
import { Reveal } from "@/components/misc/Reveal";
import { buttonVariants } from "@/components/ui/Button";
import type { Section } from "@/types/api";

/** Closing CTA band on navy. */
export function CtaBand({ section }: { section: Section | null }) {
  if (!section) return null;

  return (
    <section
      className="cta-band-glow relative overflow-hidden bg-linear-135 from-navy to-navy-2 px-[6vw] py-16 text-center lg:py-24"
      data-testid="home-cta-band"
    >
      <Reveal className="relative mx-auto max-w-2xl">
        <h2 className="mb-4 text-3xl font-extrabold leading-tight text-white sm:text-4xl rtl:leading-snug">
          {section.title}
        </h2>
        {section.body ? (
          <p className="mb-8 text-[1.02rem] font-light leading-relaxed text-white/85 rtl:font-normal rtl:leading-loose">
            {section.body}
          </p>
        ) : null}
        <div className="flex flex-wrap justify-center gap-4">
          {section.cta?.label ? (
            <CtaLink
              href={section.cta.href}
              testId="cta-band-primary"
              className={buttonVariants({ variant: "cta", size: "lg" })}
            >
              {section.cta.label}
            </CtaLink>
          ) : null}
          {section.cta?.secondary_label ? (
            <CtaLink
              href={section.cta.secondary_href}
              testId="cta-band-secondary"
              className={buttonVariants({ variant: "ghostOnNavy", size: "lg" })}
            >
              {section.cta.secondary_label}
            </CtaLink>
          ) : null}
        </div>
      </Reveal>
    </section>
  );
}
