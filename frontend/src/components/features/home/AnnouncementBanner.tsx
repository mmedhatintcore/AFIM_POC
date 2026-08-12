"use client";

import { X } from "lucide-react";
import { useTranslations } from "next-intl";
import { useSyncExternalStore } from "react";
import { CtaLink } from "@/components/misc/CtaLink";

const STORAGE_KEY = "afim-announce-dismissed";
const DISMISS_EVENT = "afim-announce-dismissed";

function subscribeToDismissal(callback: () => void): () => void {
  window.addEventListener(DISMISS_EVENT, callback);
  window.addEventListener("storage", callback);
  return () => {
    window.removeEventListener(DISMISS_EVENT, callback);
    window.removeEventListener("storage", callback);
  };
}

export function AnnouncementBanner({
  text,
  ctaLabel,
  ctaHref,
}: {
  text: string;
  ctaLabel: string | null;
  ctaHref: string | null;
}) {
  const t = useTranslations("Common");
  const tHome = useTranslations("Home");

  const dismissed = useSyncExternalStore(
    subscribeToDismissal,
    () => {
      try {
        return window.localStorage.getItem(STORAGE_KEY) === text;
      } catch {
        return false;
      }
    },
    () => false,
  );

  if (dismissed) return null;

  const dismiss = () => {
    try {
      window.localStorage.setItem(STORAGE_KEY, text);
    } catch {
      // storage unavailable — dismissal won't persist
    }
    window.dispatchEvent(new Event(DISMISS_EVENT));
  };

  return (
    <div
      role="region"
      aria-label={tHome("announcementAria")}
      data-testid="announcement-banner"
      className="relative flex items-center justify-center gap-4 bg-linear-100 from-navy to-navy-2 px-12 py-2.5 text-[0.83rem] text-white"
    >
      <span className="pulse-dot shrink-0" aria-hidden="true" />
      <span className="truncate">{text}</span>
      {ctaLabel ? (
        <CtaLink
          href={ctaHref}
          testId="announcement-link"
          className="shrink-0 font-semibold text-accent underline underline-offset-3"
        >
          {ctaLabel}
        </CtaLink>
      ) : null}
      <button
        type="button"
        onClick={dismiss}
        aria-label={t("dismiss")}
        data-testid="announcement-dismiss"
        className="absolute end-4 top-1/2 -translate-y-1/2 p-1 opacity-70 transition-opacity hover:opacity-100"
      >
        <X className="size-4" aria-hidden="true" />
      </button>
    </div>
  );
}
