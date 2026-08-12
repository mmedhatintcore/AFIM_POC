"use client";

import type { ReactNode } from "react";
import { Link } from "@/i18n/navigation";
import { useUIStore } from "@/stores/ui";

/**
 * Renders a CMS-provided CTA href:
 * - `#finder`  → button that opens the service-finder modal
 * - `/path`    → locale-aware internal Link
 * - `mailto:`, `http(s)://`, other → plain anchor
 */
export function CtaLink({
  href,
  className,
  children,
  testId,
}: {
  href: string | null | undefined;
  className?: string;
  children: ReactNode;
  testId?: string;
}) {
  const openFinder = useUIStore((state) => state.openFinder);

  if (!href || href === "#finder") {
    return (
      <button
        type="button"
        onClick={() => openFinder()}
        className={className}
        data-testid={testId}
      >
        {children}
      </button>
    );
  }

  if (href.startsWith("/")) {
    return (
      <Link href={href} className={className} data-testid={testId}>
        {children}
      </Link>
    );
  }

  return (
    <a
      href={href}
      className={className}
      data-testid={testId}
      {...(href.startsWith("http")
        ? { target: "_blank", rel: "noopener noreferrer" }
        : {})}
    >
      {children}
    </a>
  );
}
