import type { SectionItem } from "@/types/api";

/** Marquee of highlight chips (CSS-driven, direction-aware, reduced-motion safe). */
export function Ticker({
  items,
  ariaLabel,
}: {
  items: SectionItem[];
  ariaLabel: string;
}) {
  const texts = items.map((item) => item.text).filter(Boolean) as string[];
  if (texts.length === 0) return null;

  const run = (hidden: boolean) => (
    <span aria-hidden={hidden || undefined}>
      {texts.map((text, index) => (
        <span key={index} className="mx-6">
          <b className="font-semibold text-foreground">{text}</b>
          <span className="ms-6 text-accent" aria-hidden="true">
            •
          </span>
        </span>
      ))}
    </span>
  );

  return (
    <div
      className="overflow-hidden whitespace-nowrap border-b border-hairline bg-background-2 py-2 text-[0.78rem] text-muted"
      role="marquee"
      aria-label={ariaLabel}
      data-testid="home-ticker"
    >
      <div className="ticker-track">
        {run(false)}
        {run(true)}
      </div>
    </div>
  );
}
