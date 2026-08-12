import type { Locale } from "@/i18n/routing";

/**
 * Project-wide policy: numerals always render Latin (`123`), never
 * Arabic-Indic — every formatter passes `numberingSystem: 'latn'`.
 */

export function formatDate(
  iso: string,
  locale: Locale,
  options: Intl.DateTimeFormatOptions = {
    year: "numeric",
    month: "short",
    day: "numeric",
  },
): string {
  const date = new Date(iso);
  if (Number.isNaN(date.getTime())) return iso;
  return new Intl.DateTimeFormat(locale === "ar" ? "ar-EG" : "en-EG", {
    ...options,
    numberingSystem: "latn",
  }).format(date);
}

export function formatNumber(
  value: number,
  locale: Locale,
  options: Intl.NumberFormatOptions = {},
): string {
  return new Intl.NumberFormat(locale === "ar" ? "ar-EG" : "en-EG", {
    ...options,
    numberingSystem: "latn",
  }).format(value);
}

/** "0.03" → { signed: "+0.03", up: true } ; "-0.34" → { signed: "-0.34", up: false } */
export function signedChange(change: string): { signed: string; up: boolean } {
  const n = Number.parseFloat(change);
  const up = !Number.isNaN(n) ? n >= 0 : !change.trim().startsWith("-");
  const raw = change.replace(/^[+-]/, "");
  return { signed: `${up ? "+" : "-"}${raw}`, up };
}
