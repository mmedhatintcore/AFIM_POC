import { CountUp } from "@/components/misc/CountUp";
import { Reveal } from "@/components/misc/Reveal";
import { extraString } from "@/lib/utils/sections";
import type { Section } from "@/types/api";

/** "By the numbers" — editorial stat band with animated count-up on reveal. */
export function FiguresBand({ section }: { section: Section | null }) {
  const items = section?.items ?? [];
  if (!section || items.length === 0) return null;

  const hero = items.find((item) => item.hero) ?? items[0];
  const rows = items.filter((item) => item !== hero);
  const kicker = extraString(section, "kicker");

  return (
    <section className="px-[6vw] py-16 lg:py-20" data-testid="home-figures">
      <Reveal>
        <div className="mx-auto grid max-w-5xl items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-20">
          <div>
            {kicker ? (
              <span className="mb-5 inline-block text-[0.78rem] font-semibold uppercase tracking-[0.18em] text-accent">
                {kicker}
              </span>
            ) : null}
            <div className="flex flex-wrap items-baseline gap-2.5">
              <span className="text-[clamp(3.6rem,9vw,7rem)] font-extrabold leading-[0.88]">
                <CountUp
                  value={Number.parseFloat(hero.value ?? "0")}
                  decimals={hero.decimals ?? 0}
                  suffix={hero.suffix ?? ""}
                />
              </span>
              {hero.unit ? (
                <span className="text-xl font-semibold text-accent sm:text-2xl">
                  {hero.unit}
                </span>
              ) : null}
            </div>
            {hero.label ? (
              <p className="mt-4 max-w-sm text-[0.96rem] leading-relaxed text-soft">
                {hero.label}
              </p>
            ) : null}
          </div>
          <div className="flex flex-col">
            {rows.map((row, index) => (
              <div
                key={index}
                className="flex items-baseline gap-5 border-b border-hairline py-5 first:border-t first:border-t-hairline"
              >
                <span className="min-w-[3.4ch] text-end text-3xl font-extrabold text-accent sm:text-4xl">
                  <CountUp
                    value={Number.parseFloat(row.value ?? "0")}
                    decimals={row.decimals ?? 0}
                    suffix={row.suffix ?? ""}
                  />
                </span>
                <span className="text-[0.94rem] leading-normal text-soft">
                  {row.label}
                </span>
              </div>
            ))}
          </div>
        </div>
      </Reveal>
    </section>
  );
}
