import { cn } from "@/lib/utils/cn";

/**
 * Service line icons (drawn on a navy disc), ported from the reference page.
 * Keys match the API's `icon` field on services.
 */
function Glyph({ name }: { name?: string | null }) {
  switch (name) {
    case "fundmanagement":
      return (
        <svg viewBox="0 0 32 32" className="size-8" aria-hidden="true">
          <rect x="5" y="9" width="22" height="16" rx="2" fill="#fff" />
          <path
            d="M5 13h22"
            stroke="var(--orange)"
            strokeWidth="2"
          />
          <circle cx="16" cy="19" r="3.4" fill="var(--orange)" />
        </svg>
      );
    case "portfolio":
      return (
        <svg viewBox="0 0 32 32" className="size-8" aria-hidden="true">
          <circle
            cx="16"
            cy="16"
            r="10"
            fill="none"
            stroke="rgba(255,255,255,.25)"
            strokeWidth="5"
          />
          <circle
            cx="16"
            cy="16"
            r="10"
            fill="none"
            stroke="var(--orange)"
            strokeWidth="5"
            strokeLinecap="round"
            strokeDasharray="22 41"
            transform="rotate(-110 16 16)"
          />
        </svg>
      );
    case "liquidity":
      return (
        <svg viewBox="0 0 32 32" className="size-8" aria-hidden="true">
          <path
            d="M16 4c4 5 8 8.5 8 13a8 8 0 0 1-16 0c0-4.5 4-8 8-13z"
            fill="#fff"
          />
          <path
            d="M16 13c2 2.4 3.6 4.2 3.6 6a3.6 3.6 0 0 1-7.2 0c0-1.8 1.6-3.6 3.6-6z"
            fill="var(--orange)"
          />
        </svg>
      );
    case "underwriting":
      return (
        <svg viewBox="0 0 32 32" className="size-8" aria-hidden="true">
          <rect x="6" y="18" width="4.6" height="8" rx="1" fill="#fff" />
          <rect x="13.7" y="14" width="4.6" height="12" rx="1" fill="#fff" />
          <rect x="21.4" y="9" width="4.6" height="17" rx="1" fill="#fff" />
          <path
            d="M6 13l7.7-3.5 4 1.5 8.3-5.5"
            fill="none"
            stroke="var(--orange)"
            strokeWidth="2.4"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      );
    case "subscription":
      return (
        <svg viewBox="0 0 32 32" className="size-8" aria-hidden="true">
          <path
            d="M11 7v14"
            fill="none"
            stroke="#fff"
            strokeWidth="2.4"
            strokeLinecap="round"
          />
          <path
            d="M7 17.5l4 4 4-4"
            fill="none"
            stroke="var(--orange)"
            strokeWidth="2.4"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
          <path
            d="M21 25V11"
            fill="none"
            stroke="#fff"
            strokeWidth="2.4"
            strokeLinecap="round"
          />
          <path
            d="M25 14.5l-4-4-4 4"
            fill="none"
            stroke="var(--orange)"
            strokeWidth="2.4"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>
      );
    default:
      return (
        <svg viewBox="0 0 32 32" className="size-8" aria-hidden="true">
          <circle
            cx="16"
            cy="16"
            r="9"
            fill="none"
            stroke="#fff"
            strokeWidth="2.2"
          />
          <circle cx="16" cy="16" r="3" fill="var(--orange)" />
        </svg>
      );
  }
}

export function ServiceIcon({
  name,
  className,
}: {
  name?: string | null;
  className?: string;
}) {
  return (
    <span
      className={cn(
        "grid size-[62px] place-items-center rounded-full bg-linear-145 from-navy-2 to-navy shadow-elev-1",
        className,
      )}
      aria-hidden="true"
    >
      <Glyph name={name} />
    </span>
  );
}
