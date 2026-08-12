import { Reveal } from "@/components/misc/Reveal";
import { extraString } from "@/lib/utils/sections";
import type { Section } from "@/types/api";

/** "Why AFIM" — numbered feature list on a navy band. */
export function WhySection({ section }: { section: Section | null }) {
  if (!section) return null;
  const kicker = extraString(section, "kicker");

  return (
    <section
      className="navy-grid relative overflow-hidden bg-linear-160 from-navy to-navy-2 px-[6vw] py-16 text-white lg:py-24"
      data-testid="home-why"
    >
      <Reveal>
        <div className="relative mx-auto grid max-w-6xl items-center gap-10 lg:grid-cols-2 lg:gap-16">
          <div>
            {kicker ? (
              <span className="text-[0.78rem] font-semibold uppercase tracking-[0.18em] text-accent">
                {kicker}
              </span>
            ) : null}
            <h2 className="mb-4 mt-5 text-3xl font-extrabold leading-tight text-white sm:text-4xl rtl:leading-snug">
              {section.title}
            </h2>
            {section.body ? (
              <p className="max-w-lg font-light leading-loose text-white/85 rtl:font-normal">
                {section.body}
              </p>
            ) : null}
          </div>
          <div className="flex flex-col">
            {(section.items ?? []).map((item, index) => (
              <div
                key={index}
                className="flex gap-5 border-b border-white/12 py-5 first:border-t first:border-t-white/12"
              >
                <span className="tnum min-w-[2.6ch] text-[1.05rem] font-bold text-accent">
                  {String(index + 1).padStart(2, "0")}
                </span>
                <div>
                  <h3 className="mb-1 text-[1.06rem] font-bold text-white">
                    {item.title}
                  </h3>
                  <p className="text-[0.86rem] font-light leading-relaxed text-white/75 rtl:font-normal">
                    {item.text}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </Reveal>
    </section>
  );
}
