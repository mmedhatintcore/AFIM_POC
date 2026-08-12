/**
 * Deterministic SVG sparkline — safe to render on the server.
 * Mirrors the reference page's `sparkSVG` implementation.
 */
export function Sparkline({
  data,
  up,
  id,
  width = 104,
  height = 40,
  strokeWidth = 2,
  className,
}: {
  data: number[];
  up: boolean;
  /** unique id for the gradient (e.g. the fund slug) */
  id: string;
  width?: number;
  height?: number;
  strokeWidth?: number;
  className?: string;
}) {
  if (data.length < 2) return null;

  const p = 4;
  const min = Math.min(...data);
  const max = Math.max(...data);
  const range = max - min || 1;
  const step = (width - p * 2) / (data.length - 1);
  const xy = data.map((v, i) => [
    +(p + i * step).toFixed(1),
    +(height - p - ((v - min) / range) * (height - p * 2)).toFixed(1),
  ]);
  const line = xy.map((c, i) => `${i ? "L" : "M"}${c[0]} ${c[1]}`).join(" ");
  const last = xy[xy.length - 1];
  const area = `${line} L${last[0]} ${height} L${xy[0][0]} ${height} Z`;
  const color = up ? "var(--pos)" : "var(--neg)";
  const gradId = `spark-${id}`;

  return (
    <svg
      viewBox={`0 0 ${width} ${height}`}
      style={{ width, height }}
      className={className}
      aria-hidden="true"
      xmlns="http://www.w3.org/2000/svg"
    >
      <defs>
        <linearGradient id={gradId} x1="0" y1="0" x2="0" y2="1">
          <stop offset="0" stopColor={color} stopOpacity="0.3" />
          <stop offset="1" stopColor={color} stopOpacity="0" />
        </linearGradient>
      </defs>
      <path d={area} fill={`url(#${gradId})`} />
      <path
        d={line}
        fill="none"
        stroke={color}
        strokeWidth={strokeWidth}
        strokeLinecap="round"
        strokeLinejoin="round"
      />
      <circle cx={last[0]} cy={last[1]} r={2.6} fill={color} />
    </svg>
  );
}
