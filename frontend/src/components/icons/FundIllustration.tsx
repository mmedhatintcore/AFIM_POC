import { cn } from "@/lib/utils/cn";

/**
 * Fund illustrations drawn only from the brand palette (navy, orange,
 * grey — read from the site's colour tokens). Keys match the API's
 * `illustration` field.
 */

const NAVY = "var(--navy)";
const ORANGE = "var(--orange)";
const ORANGE_D = "var(--orange-d)";
const GREY = "var(--grey)";

/** A flat coin seen slightly from above. */
function Coin({
  cx,
  cy,
  rx = 10,
  top = GREY,
  side = NAVY,
}: {
  cx: number;
  cy: number;
  rx?: number;
  top?: string;
  side?: string;
}) {
  const ry = rx * 0.38;
  return (
    <g>
      <rect x={cx - rx} y={cy} width={rx * 2} height={4} fill={side} />
      <ellipse cx={cx} cy={cy + 4} rx={rx} ry={ry} fill={side} />
      <ellipse cx={cx} cy={cy} rx={rx} ry={ry} fill={top} />
    </g>
  );
}

export function FundIllustration({
  name,
  className,
}: {
  name?: string | null;
  className?: string;
}) {
  const cls = cn("block size-12", className);
  switch (name) {
    case "moneymarket":
      return (
        <svg viewBox="0 0 64 64" className={cls} aria-hidden="true">
          <ellipse cx="32" cy="57" rx="27" ry="4" fill={NAVY} opacity=".12" />
          {/* left stack */}
          <Coin cx={14} cy={50} rx={9} />
          <Coin cx={14} cy={45} rx={9} />
          <Coin cx={14} cy={40} rx={9} />
          {/* right stack */}
          <Coin cx={50} cy={50} rx={9} />
          <Coin cx={50} cy={45} rx={9} />
          {/* tall centre stack topped with a rising coin */}
          <Coin cx={32} cy={50} rx={11} />
          <Coin cx={32} cy={45} rx={11} />
          <Coin cx={32} cy={40} rx={11} />
          <Coin cx={32} cy={35} rx={11} />
          <Coin cx={32} cy={26} rx={11} top={ORANGE} side={ORANGE_D} />
          <path
            d="M27.5 27 L32 22 L36.5 27"
            fill="none"
            stroke="#fff"
            strokeWidth="2.2"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      );
    case "fixedincome":
      return (
        <svg viewBox="0 0 64 64" className={cls} aria-hidden="true">
          <rect x="8" y="8" width="44" height="42" rx="6" fill={NAVY} />
          <rect x="14" y="16" width="20" height="3.5" rx="1.75" fill={ORANGE} />
          <rect x="14" y="24" width="32" height="3" rx="1.5" fill={GREY} />
          <rect x="14" y="31" width="32" height="3" rx="1.5" fill={GREY} opacity=".7" />
          <rect x="14" y="38" width="18" height="3" rx="1.5" fill={GREY} opacity=".7" />
          {/* ribbon seal */}
          <path d="M39 50 L36 62 L43 58 L50 62 L47 50 Z" fill={ORANGE_D} />
          <circle cx="43" cy="44" r="11" fill={ORANGE} />
          <circle cx="43" cy="44" r="7.5" fill="none" stroke="#fff" strokeWidth="1.6" opacity=".7" />
          <path
            d="M38.5 44.5 L41.8 47.8 L47.8 40.8"
            fill="none"
            stroke="#fff"
            strokeWidth="2.6"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      );
    case "balanced":
      return (
        <svg viewBox="0 0 64 64" className={cls} aria-hidden="true">
          <rect x="30" y="14" width="4" height="38" rx="2" fill={NAVY} />
          <rect x="18" y="51" width="28" height="6" rx="3" fill={NAVY} />
          <path d="M9 19 L55 12" stroke={NAVY} strokeWidth="3.4" strokeLinecap="round" />
          <circle cx="32" cy="15.5" r="4.4" fill={ORANGE} />
          {/* left pan (heavier) */}
          <path d="M9 19 L3 38 M9 19 L15 38" stroke={GREY} strokeWidth="1.6" strokeLinecap="round" />
          <path d="M1.5 38 H16.5 A7.5 7.5 0 0 1 1.5 38 Z" fill={ORANGE} />
          <circle cx="9" cy="33" r="4.6" fill={NAVY} />
          <circle cx="9" cy="33" r="1.8" fill="#fff" opacity=".85" />
          {/* right pan */}
          <path d="M55 12 L49 28 M55 12 L61 28" stroke={GREY} strokeWidth="1.6" strokeLinecap="round" />
          <path d="M47.5 28 H62.5 A7.5 7.5 0 0 1 47.5 28 Z" fill={GREY} />
          <rect x="51" y="21" width="3" height="7" rx="1" fill={NAVY} />
          <rect x="56" y="18" width="3" height="10" rx="1" fill={ORANGE} />
        </svg>
      );
    case "equity":
      return (
        <svg viewBox="0 0 64 64" className={cls} aria-hidden="true">
          <rect x="5" y="55" width="54" height="2.5" rx="1.25" fill={GREY} opacity=".5" />
          {/* candlesticks */}
          <path d="M14 38 V52" stroke={GREY} strokeWidth="2" strokeLinecap="round" />
          <rect x="9.5" y="42" width="9" height="9" rx="2" fill={NAVY} />
          <path d="M28 30 V52" stroke={GREY} strokeWidth="2" strokeLinecap="round" />
          <rect x="23.5" y="34" width="9" height="13" rx="2" fill={ORANGE} />
          <path d="M42 22 V50" stroke={GREY} strokeWidth="2" strokeLinecap="round" />
          <rect x="37.5" y="27" width="9" height="16" rx="2" fill={NAVY} />
          <path d="M56 8 V38" stroke={GREY} strokeWidth="2" strokeLinecap="round" />
          <rect x="51.5" y="14" width="9" height="19" rx="2" fill={ORANGE} />
          {/* trend arrow */}
          <path
            d="M5 34 L20 25 L32 28 L43 15"
            fill="none"
            stroke={ORANGE_D}
            strokeWidth="2.6"
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeDasharray="1 5.5"
          />
          <path d="M36.5 12.5 L47.5 8.5 L45.5 20 Z" fill={ORANGE_D} stroke={ORANGE_D} strokeWidth="1.6" strokeLinejoin="round" />
        </svg>
      );
    case "islamic":
      return (
        <svg viewBox="0 0 64 64" className={cls} aria-hidden="true">
          {/* eight-pointed star */}
          <rect x="10" y="10" width="44" height="44" rx="7" fill={NAVY} />
          <rect x="10" y="10" width="44" height="44" rx="7" fill={NAVY} transform="rotate(45 32 32)" />
          <circle cx="32" cy="32" r="17" fill="#fff" />
          <circle cx="32" cy="32" r="17" fill="none" stroke={ORANGE} strokeWidth="1.6" />
          {/* crescent */}
          <circle cx="29.5" cy="32.5" r="10" fill={ORANGE} />
          <circle cx="34" cy="30" r="8.6" fill="#fff" />
          {/* star */}
          <path
            d="M39.5 26 l1.3 2.9 3.1.4-2.3 2.1.6 3.1-2.7-1.6-2.7 1.6.6-3.1-2.3-2.1 3.1-.4z"
            fill={ORANGE_D}
          />
        </svg>
      );
    case "gold":
      return (
        <svg viewBox="0 0 64 64" className={cls} aria-hidden="true">
          <ellipse cx="32" cy="58" rx="27" ry="3.5" fill={NAVY} opacity=".12" />
          {/* bottom bars */}
          <path d="M4 54 L9 41 H30 L35 54 Z" fill={ORANGE} />
          <path d="M4 54 L5.4 50.5 H33.6 L35 54 Z" fill={ORANGE_D} />
          <path d="M9 41 H30 L29 44 H10 Z" fill="#fff" opacity=".4" />
          <path d="M29 54 L34 41 H55 L60 54 Z" fill={ORANGE} />
          <path d="M29 54 L30.4 50.5 H58.6 L60 54 Z" fill={ORANGE_D} />
          <path d="M34 41 H55 L54 44 H35 Z" fill="#fff" opacity=".4" />
          {/* top bar */}
          <path d="M16.5 40 L21.5 27 H42.5 L47.5 40 Z" fill={ORANGE} />
          <path d="M16.5 40 L17.9 36.5 H46.1 L47.5 40 Z" fill={ORANGE_D} />
          <path d="M21.5 27 H42.5 L41.5 30 H22.5 Z" fill="#fff" opacity=".45" />
          <rect x="26" y="31.5" width="12" height="2.2" rx="1.1" fill="#fff" opacity=".55" />
          {/* sparkles */}
          <path d="M52 6 L53.8 11 L59 13 L53.8 15 L52 20 L50.2 15 L45 13 L50.2 11 Z" fill={NAVY} />
          <path d="M12 12 L13.2 15.4 L16.6 16.6 L13.2 17.8 L12 21.2 L10.8 17.8 L7.4 16.6 L10.8 15.4 Z" fill={GREY} />
        </svg>
      );
    default:
      return (
        <svg viewBox="0 0 64 64" className={cls} aria-hidden="true">
          <rect x="9" y="34" width="10" height="20" rx="2.5" fill={GREY} />
          <rect x="23" y="26" width="10" height="28" rx="2.5" fill={NAVY} />
          <rect x="37" y="18" width="10" height="36" rx="2.5" fill={ORANGE} />
          <rect x="51" y="30" width="7" height="24" rx="2.5" fill={NAVY} />
          <path
            d="M10 26 L27 17 L42 9 L57 16"
            fill="none"
            stroke={ORANGE}
            strokeWidth="3"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <circle cx="42" cy="9" r="3.6" fill={ORANGE} />
        </svg>
      );
  }
}
