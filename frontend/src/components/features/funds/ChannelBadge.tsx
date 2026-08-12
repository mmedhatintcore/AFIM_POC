import { cn } from "@/lib/utils/cn";
import type { OrderChannel } from "@/types/api";

/** Subscription channel chip — NBE (navy) vs directly via AFIM (orange). */
export function ChannelBadge({
  channel,
  label,
  className,
}: {
  channel: OrderChannel;
  label: string;
  className?: string;
}) {
  return (
    <span
      className={cn(
        "inline-block whitespace-nowrap rounded-full border px-2.5 py-1 text-[0.7rem] font-semibold",
        channel === "nbe"
          ? "border-navy/25 bg-navy/10 text-foreground dark:border-border dark:bg-white/10"
          : "border-accent/35 bg-accent/15 text-accent-dark dark:text-accent",
        className,
      )}
      data-testid={`channel-${channel}`}
    >
      {label}
    </span>
  );
}
