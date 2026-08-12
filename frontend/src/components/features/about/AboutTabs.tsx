"use client";

import { useTranslations } from "next-intl";
import { useState, type ReactNode } from "react";
import { cn } from "@/lib/utils/cn";

const TAB_KEYS = [
  "brief",
  "timeline",
  "board",
  "committees",
  "leadership",
] as const;

export type AboutTabKey = (typeof TAB_KEYS)[number];

/**
 * Client tab switcher over server-rendered panels (passed as ReactNodes so
 * the content itself stays server-fetched and SEO-visible).
 */
export function AboutTabs({ panels }: { panels: Record<AboutTabKey, ReactNode> }) {
  const t = useTranslations("About");
  const [active, setActive] = useState<AboutTabKey>("brief");

  return (
    <div>
      <div
        role="tablist"
        aria-label={t("tabsAria")}
        className="mb-10 flex flex-wrap justify-center gap-2"
        data-testid="about-tabs"
      >
        {TAB_KEYS.map((key) => (
          <button
            key={key}
            role="tab"
            type="button"
            id={`about-tab-${key}`}
            aria-selected={active === key}
            aria-controls={`about-panel-${key}`}
            data-testid={`about-tab-${key}`}
            onClick={() => setActive(key)}
            className={cn(
              "rounded-full border px-5 py-2 text-[0.86rem] transition-all duration-300 ease-out-soft hover:-translate-y-0.5",
              active === key
                ? "rounded-[10px] border-navy bg-navy text-white dark:border-accent dark:bg-accent dark:text-accent-on"
                : "border-border text-soft hover:border-accent hover:text-accent",
            )}
          >
            {t(`tabs.${key}`)}
          </button>
        ))}
      </div>
      {TAB_KEYS.map((key) => (
        <div
          key={key}
          role="tabpanel"
          id={`about-panel-${key}`}
          aria-labelledby={`about-tab-${key}`}
          hidden={active !== key}
          className={active === key ? "fade-view" : undefined}
          data-testid={`about-panel-${key}`}
        >
          {panels[key]}
        </div>
      ))}
    </div>
  );
}
