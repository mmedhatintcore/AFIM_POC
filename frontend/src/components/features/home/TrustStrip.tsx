import { Reveal } from "@/components/misc/Reveal";
import type { Section } from "@/types/api";

export function TrustStrip({ section }: { section: Section | null }) {
  const partners = (section?.items ?? [])
    .map((item) => item.text)
    .filter(Boolean) as string[];
  if (!section || partners.length === 0) return null;

  return (
    <section
      className="border-y border-hairline bg-background-2 px-[6vw] py-10 text-center"
      data-testid="home-trust"
    >
      <Reveal>
        {section.title ? (
          <span className="mb-6 block text-[0.74rem] uppercase tracking-[0.1em] text-muted">
            {section.title}
          </span>
        ) : null}
        <div className="flex flex-wrap items-center justify-center gap-x-10 gap-y-4 sm:gap-x-14">
          {partners.map((name, index) => (
            <span
              key={index}
              className="text-lg font-bold text-soft opacity-70 transition-opacity hover:opacity-100 sm:text-xl"
            >
              {name}
            </span>
          ))}
        </div>
      </Reveal>
    </section>
  );
}
