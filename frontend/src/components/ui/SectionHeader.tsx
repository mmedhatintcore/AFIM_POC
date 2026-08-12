import type { ReactNode } from "react";
import { cn } from "@/lib/utils/cn";

/** Editorial section header with the AFIM 3-bar motif. */
export function SectionHeader({
  title,
  subtitle,
  center = false,
  children,
  className,
}: {
  title: ReactNode;
  subtitle?: ReactNode;
  center?: boolean;
  children?: ReactNode;
  className?: string;
}) {
  return (
    <div
      className={cn(
        "mb-10 flex flex-wrap items-end justify-between gap-6",
        center && "flex-col items-center justify-center text-center",
        className,
      )}
    >
      <div className={cn(center && "flex flex-col items-center")}>
        <div className="sec-bars" aria-hidden="true">
          <i />
          <i />
          <i />
        </div>
        <h2 className="text-3xl font-extrabold leading-tight sm:text-4xl">
          {title}
        </h2>
        {children}
      </div>
      {subtitle ? (
        <p className="max-w-xl text-[0.96rem] font-light leading-relaxed text-soft rtl:font-normal">
          {subtitle}
        </p>
      ) : null}
    </div>
  );
}
