import { cn } from "@/lib/utils/cn";

/**
 * Flat, brand-colored fund illustrations ported from the reference page.
 * Keys match the API's `illustration` field.
 */
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
        <svg viewBox="0 0 48 48" className={cls} aria-hidden="true">
          <ellipse cx="24" cy="36" rx="15" ry="5" fill="#00053e" />
          <ellipse cx="24" cy="30" rx="15" ry="5" fill="#8e9191" />
          <ellipse cx="24" cy="24" rx="15" ry="5" fill="#f67d30" />
          <ellipse cx="24" cy="18" rx="15" ry="5" fill="#c75a14" />
        </svg>
      );
    case "fixedincome":
      return (
        <svg viewBox="0 0 48 48" className={cls} aria-hidden="true">
          <rect x="8" y="11" width="32" height="27" rx="4" fill="#00053e" />
          <rect x="13" y="17" width="22" height="3" rx="1.5" fill="#8e9191" />
          <rect x="13" y="24" width="22" height="3" rx="1.5" fill="#8e9191" />
          <rect x="13" y="31" width="13" height="3" rx="1.5" fill="#f67d30" />
          <circle cx="32" cy="32.5" r="3" fill="#f67d30" />
        </svg>
      );
    case "balanced":
      return (
        <svg viewBox="0 0 48 48" className={cls} aria-hidden="true">
          <rect x="22.5" y="13" width="3" height="25" fill="#8e9191" />
          <rect x="14" y="36" width="20" height="3" rx="1.5" fill="#00053e" />
          <path
            d="M9 16 H39"
            stroke="#00053e"
            strokeWidth="3"
            strokeLinecap="round"
          />
          <circle cx="24" cy="14" r="3" fill="#f67d30" />
          <path d="M9 16 l-4 9 h8 Z" fill="#f67d30" />
          <path d="M39 16 l-4 9 h8 Z" fill="#8e9191" />
        </svg>
      );
    case "equity":
      return (
        <svg viewBox="0 0 48 48" className={cls} aria-hidden="true">
          <path
            d="M9 31 L20 23 L31 25 L40 12"
            fill="none"
            stroke="#f67d30"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
            opacity=".55"
          />
          <line x1="13" y1="22" x2="13" y2="37" stroke="#8e9191" strokeWidth="1.5" />
          <rect x="10" y="27" width="6" height="8" rx="1" fill="#8e9191" />
          <line x1="24" y1="17" x2="24" y2="35" stroke="#00053e" strokeWidth="1.5" />
          <rect x="21" y="21" width="6" height="11" rx="1" fill="#00053e" />
          <line x1="35" y1="10" x2="35" y2="31" stroke="#f67d30" strokeWidth="1.5" />
          <rect x="32" y="15" width="6" height="12" rx="1" fill="#f67d30" />
        </svg>
      );
    case "islamic":
      return (
        <svg viewBox="0 0 48 48" className={cls} aria-hidden="true">
          <path
            d="M31 9 a15 15 0 1 0 0 30 a12 12 0 1 1 0 -30 Z"
            fill="#00053e"
          />
          <path
            d="M33 17 l2.1 4.4 l4.9 .6 l-3.6 3.3 .9 4.8 -4.3 -2.3 -4.3 2.3 .9 -4.8 -3.6 -3.3 4.9 -.6 Z"
            fill="#f67d30"
          />
        </svg>
      );
    case "gold":
      return (
        <svg viewBox="0 0 48 48" className={cls} aria-hidden="true">
          <path d="M16 19 l-1 -4 h18 l-1 4 Z" fill="#8e9191" />
          <path d="M12 31 l4 -12 h16 l4 12 Z" fill="#f67d30" />
          <path
            d="M12 31 l4 -12 h16 l4 12 Z"
            fill="none"
            stroke="#c75a14"
            strokeWidth="1.4"
            strokeLinejoin="round"
          />
          <rect x="18" y="24" width="12" height="2" rx="1" fill="#fff" opacity=".55" />
        </svg>
      );
    default:
      return (
        <svg viewBox="0 0 48 48" className={cls} aria-hidden="true">
          <rect x="7" y="26" width="7" height="14" rx="1.5" fill="#8e9191" />
          <rect x="17" y="20" width="7" height="20" rx="1.5" fill="#00053e" />
          <rect x="27" y="14" width="7" height="26" rx="1.5" fill="#f67d30" />
          <rect x="37" y="22" width="7" height="18" rx="1.5" fill="#00053e" />
          <path
            d="M8 20 L20 14 L30 9 L42 13"
            fill="none"
            stroke="#f67d30"
            strokeWidth="2.4"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <circle cx="30" cy="9" r="3" fill="#f67d30" />
        </svg>
      );
  }
}
