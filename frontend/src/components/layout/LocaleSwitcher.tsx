"use client";

import { useLocale, useTranslations } from "next-intl";
import { usePathname, useRouter } from "@/i18n/navigation";

export function LocaleSwitcher() {
  const t = useTranslations("Header");
  const locale = useLocale();
  const router = useRouter();
  const pathname = usePathname();
  const next = locale === "ar" ? "en" : "ar";

  const switchLocale = () => {
    const search = typeof window !== "undefined" ? window.location.search : "";
    router.replace(`${pathname}${search}`, { locale: next });
  };

  return (
    <button
      type="button"
      onClick={switchLocale}
      aria-label={t("switchLocaleAria")}
      data-testid="locale-switcher"
      className="rounded-full border border-border px-4 py-2 text-[0.8rem] font-semibold text-foreground transition-all duration-300 ease-out-soft hover:-translate-y-0.5 hover:border-accent hover:text-accent"
    >
      {t("switchLocale")}
    </button>
  );
}
