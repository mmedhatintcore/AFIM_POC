import type { Locale } from "@/i18n/routing";

export function siteUrl(): string {
  return (process.env.NEXT_PUBLIC_SITE_URL ?? "http://localhost:3000").replace(
    /\/$/,
    "",
  );
}

/** Absolute canonical URL for a locale-prefixed path. `path` starts with "/". */
export function absoluteUrl(locale: Locale, path = ""): string {
  const clean = path === "/" ? "" : path;
  return `${siteUrl()}/${locale}${clean}`;
}

/** hreflang alternates map (ar, en, x-default) for a path. */
export function languageAlternates(path = ""): Record<string, string> {
  return {
    ar: absoluteUrl("ar", path),
    en: absoluteUrl("en", path),
    "x-default": absoluteUrl("ar", path),
  };
}
