import { cn } from "@/lib/utils/cn";

export function Skeleton({ className }: { className?: string }) {
  return (
    <div
      className={cn(
        "animate-pulse rounded-card bg-surface-2 motion-reduce:animate-none",
        className,
      )}
      aria-hidden="true"
    />
  );
}
