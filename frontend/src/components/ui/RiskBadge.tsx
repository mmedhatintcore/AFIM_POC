import { cn } from "@/lib/utils/cn";
import type { RiskLevel } from "@/types/api";

const outline: Record<RiskLevel, string> = {
  0: "text-up border-up/45",
  1: "text-warn border-warn/45",
  2: "text-down border-down/45",
};

const solid: Record<RiskLevel, string> = {
  0: "bg-up text-white",
  1: "bg-warn text-accent-on",
  2: "bg-down text-white",
};

export function RiskBadge({
  level,
  label,
  variant = "outline",
  className,
}: {
  level: RiskLevel;
  label: string;
  variant?: "outline" | "solid";
  className?: string;
}) {
  return (
    <span
      className={cn(
        "inline-block whitespace-nowrap rounded-full px-2.5 py-1 text-[0.66rem] font-semibold uppercase tracking-wide",
        variant === "outline"
          ? cn("border", outline[level])
          : solid[level],
        className,
      )}
    >
      {label}
    </span>
  );
}
