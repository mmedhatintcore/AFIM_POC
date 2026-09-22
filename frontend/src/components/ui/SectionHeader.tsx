import type { ReactNode } from "react";
import { BrandLogo } from "@/components/icons/BrandLogo";
import { cn } from "@/lib/utils/cn";

/** Editorial section header with the AFIM 3-bar motif (or the full brand logo). */
export function SectionHeader({
  title,
  subtitle,
  center = false,
  logo = false,
  children,
  className,
}: {
  title: ReactNode;
  subtitle?: ReactNode;
  center?: boolean;
  /** Show the real AFIM logo, sized to match the header, instead of the generic 3-bar motif. */
  logo?: boolean;
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
        {logo ? (
          <BrandLogo alt="" className="mb-4 h-8 sm:h-9" />
        ) : (
          <div className="sec-bars" aria-hidden="true">
            <i />
            <i />
            <i />
          </div>
        )}
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
