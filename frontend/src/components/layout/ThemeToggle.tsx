"use client";

import { Moon, Sun } from "lucide-react";
import { useTranslations } from "next-intl";
import { useCallback, useSyncExternalStore } from "react";
import { useUIStore } from "@/stores/ui";

/** Watches the <html> class list (the theme's source of truth) as an external store. */
function subscribeToThemeClass(callback: () => void): () => void {
  const observer = new MutationObserver(callback);
  observer.observe(document.documentElement, {
    attributes: true,
    attributeFilter: ["class"],
  });
  return () => observer.disconnect();
}

export function ThemeToggle() {
  const t = useTranslations("Header");
  const setTheme = useUIStore((state) => state.setTheme);

  const isDark = useSyncExternalStore(
    subscribeToThemeClass,
    () => document.documentElement.classList.contains("dark"),
    () => false,
  );

  const toggle = useCallback(() => {
    const next = !document.documentElement.classList.contains("dark");
    document.documentElement.classList.toggle("dark", next);
    setTheme(next ? "dark" : "light");
  }, [setTheme]);

  return (
    <button
      type="button"
      onClick={toggle}
      aria-label={t("toggleTheme")}
      title={t("toggleTheme")}
      data-testid="theme-toggle"
      className="grid size-10 place-items-center rounded-full border border-border text-soft transition-all duration-300 ease-out-soft hover:-translate-y-0.5 hover:border-accent hover:text-accent"
    >
      {isDark ? (
        <Sun className="size-4" aria-hidden="true" />
      ) : (
        <Moon className="size-4" aria-hidden="true" />
      )}
    </button>
  );
}
