import { IconKey } from "@/components/icons/IconKey";
import { CtaLink } from "@/components/misc/CtaLink";
import { Reveal } from "@/components/misc/Reveal";
import { SectionHeader } from "@/components/ui/SectionHeader";
import type { Section } from "@/types/api";

/** "Invest by goal" — 4-card grid from the CMS `goals` section. */
export function GoalsGrid({ section }: { section: Section | null }) {
  if (!section || (section.items ?? []).length === 0) return null;

  return (
    <section className="px-[6vw] py-14" data-testid="home-goals">
      <Reveal>
        <SectionHeader
          title={section.title}
          subtitle={section.subtitle ?? undefined}
          logo
        />
      </Reveal>
      <Reveal>
        <div className="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
          {(section.items ?? []).map((goal, index) => (
            <CtaLink
              key={index}
              href={goal.href}
              testId={`goal-card-${index}`}
              className="group relative overflow-hidden rounded-card-lg border border-border bg-surface p-7 text-start shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-2 hover:border-accent/50 hover:shadow-elev-3"
            >
              <span className="mb-5 grid size-[54px] place-items-center rounded-[15px] bg-accent/12">
                <IconKey name={goal.icon} className="size-7 text-accent" />
              </span>
              <h3 className="mb-2 text-[1.12rem] font-bold">{goal.title}</h3>
              <p className="mb-4 text-[0.86rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                {goal.text}
              </p>
              {goal.link_label ? (
                <span className="inline-block text-[0.82rem] font-semibold text-accent transition-transform duration-300 group-hover:translate-x-1 rtl:group-hover:-translate-x-1">
                  {goal.link_label}
                </span>
              ) : null}
            </CtaLink>
          ))}
        </div>
      </Reveal>
    </section>
  );
}
