/**
 * Decorative AUM growth chart — deterministic SVG generated on the server,
 * animated purely with CSS (draw-in line, popping milestone dots, halo).
 * Ported from the reference page's `renderHeroChart`.
 */

const W = 540;
const H = 440;
const TOP = 66;
const BASE = 406;
const PAD_X = 38;
const VALUES = [0.1, 0.16, 0.24, 0.3, 0.4, 0.52, 0.64, 0.74, 0.86, 1.0];
const MILESTONES = [
  { i: 0, year: "1994" },
  { i: 3, year: "2008" },
  { i: 6, year: "2018" },
  { i: 9, year: "2025" },
];

function buildPoints(): number[][] {
  const n = VALUES.length;
  return VALUES.map((v, i) => [
    Math.round(PAD_X + (i * (W - PAD_X * 2)) / (n - 1)),
    Math.round(BASE - v * (BASE - TOP)),
  ]);
}

function smoothPath(points: number[][]): string {
  let d = `M${points[0][0]},${points[0][1]}`;
  for (let i = 0; i < points.length - 1; i++) {
    const a = points[i > 0 ? i - 1 : i];
    const b = points[i];
    const c = points[i + 1];
    const e = points[i + 2 < points.length ? i + 2 : i + 1];
    const c1x = (b[0] + (c[0] - a[0]) / 6).toFixed(1);
    const c1y = (b[1] + (c[1] - a[1]) / 6).toFixed(1);
    const c2x = (c[0] - (e[0] - b[0]) / 6).toFixed(1);
    const c2y = (c[1] - (e[1] - b[1]) / 6).toFixed(1);
    d += ` C${c1x},${c1y} ${c2x},${c2y} ${c[0]},${c[1]}`;
  }
  return d;
}

export function HeroChart({ unit }: { unit: string }) {
  const points = buildPoints();
  const n = points.length;
  const line = smoothPath(points);
  const area = `${line} L${points[n - 1][0]},${H} L${points[0][0]},${H} Z`;

  return (
    <div
      className="relative mx-auto w-full max-w-[560px]"
      style={{ aspectRatio: "540/440" }}
      aria-hidden="true"
      data-testid="hero-chart"
    >
      <svg
        viewBox={`0 0 ${W} ${H}`}
        className="block size-full overflow-visible"
        preserveAspectRatio="xMidYMid meet"
        xmlns="http://www.w3.org/2000/svg"
      >
        <defs>
          <linearGradient id="hcArea" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stopColor="#f67d30" stopOpacity="0.3" />
            <stop offset="1" stopColor="#f67d30" stopOpacity="0" />
          </linearGradient>
        </defs>
        {Array.from({ length: 4 }, (_, g) => {
          const y = (TOP + ((BASE - TOP) * g) / 3).toFixed(0);
          return (
            <line
              key={`h${g}`}
              x1={PAD_X}
              y1={y}
              x2={W - PAD_X}
              y2={y}
              className="hc-hline"
            />
          );
        })}
        {MILESTONES.map((m) => (
          <line
            key={`v${m.i}`}
            x1={points[m.i][0]}
            y1={TOP}
            x2={points[m.i][0]}
            y2={BASE}
            className="hc-vline"
          />
        ))}
        <path d={area} className="hc-area" fill="url(#hcArea)" />
        <path d={line} className="hc-line" pathLength={1} fill="none" />
        {MILESTONES.map((m) => {
          const p = points[m.i];
          const now = m.i === n - 1;
          const delay = (0.4 + (m.i / (n - 1)) * 2).toFixed(2);
          return (
            <g key={`d${m.i}`}>
              {now ? <circle cx={p[0]} cy={p[1]} r={6} className="hc-halo" /> : null}
              <circle
                cx={p[0]}
                cy={p[1]}
                r={now ? 5.5 : 4}
                className={now ? "hc-dot now" : "hc-dot"}
                style={{ animationDelay: `${delay}s` }}
              />
              <text x={p[0]} y={H - 10} textAnchor="middle" className="hc-yr">
                {m.year}
              </text>
            </g>
          );
        })}
      </svg>
      <div className="hc-chip absolute start-[4%] top-[5%] flex flex-col gap-0.5 rounded-[14px] border border-border bg-surface px-4 py-3 shadow-elev-2">
        <span className="tnum text-[1.7rem] font-extrabold leading-none text-accent">
          93
        </span>
        <span className="text-[0.7rem] font-semibold text-soft">{unit}</span>
      </div>
    </div>
  );
}
