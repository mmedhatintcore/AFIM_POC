import Image from "next/image";
import type { ReactNode } from "react";
import { cn } from "@/lib/utils/cn";

const MARK_WIDTH = 296;
const MARK_HEIGHT = 320;

/** Editorial section header with the AFIM mark. */
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
        <span className="mb-4 block h-9" aria-hidden="true">
          <Image
            src="/logo-mark.png"
            alt=""
            width={MARK_WIDTH}
            height={MARK_HEIGHT}
            className="block h-full w-auto dark:hidden"
          />
          <Image
            src="/logo-mark-dark.png"
            alt=""
            width={MARK_WIDTH}
            height={MARK_HEIGHT}
            className="hidden h-full w-auto dark:block"
          />
        </span>
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
