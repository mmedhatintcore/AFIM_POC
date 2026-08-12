import { cn } from "@/lib/utils/cn";

/**
 * AFIM brand mark — three navy pillars with orange caps (echoes the
 * monument/bars motif of the brand page). Pure SVG; no embedded bitmap.
 */
export function LogoMark({ className }: { className?: string }) {
  return (
    <svg
      viewBox="0 0 40 40"
      className={cn("block size-10", className)}
      aria-hidden="true"
    >
      <rect x="2" y="2" width="36" height="36" rx="9" fill="var(--navy)" />
      <rect x="9" y="14" width="6" height="18" rx="2" fill="oklch(0.34 0.13 272)" />
      <rect x="9" y="12" width="6" height="3.4" rx="1.6" fill="var(--orange)" />
      <rect x="17" y="9" width="6" height="23" rx="2" fill="oklch(0.4 0.13 272)" />
      <rect x="17" y="7" width="6" height="3.4" rx="1.6" fill="var(--orange)" />
      <rect x="25" y="14" width="6" height="18" rx="2" fill="oklch(0.34 0.13 272)" />
      <rect x="25" y="12" width="6" height="3.4" rx="1.6" fill="var(--orange)" />
    </svg>
  );
}
