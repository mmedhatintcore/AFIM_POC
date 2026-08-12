"use client";

import { Menu, X } from "lucide-react";
import { useTranslations } from "next-intl";
import { useState } from "react";
import { BrandLogo } from "@/components/icons/BrandLogo";
import { buttonVariants } from "@/components/ui/Button";
import { Link, usePathname } from "@/i18n/navigation";
import { cn } from "@/lib/utils/cn";
import { useUIStore } from "@/stores/ui";
import { LocaleSwitcher } from "./LocaleSwitcher";
import { ThemeToggle } from "./ThemeToggle";

const NAV = [
  { key: "home", href: "/" },
  { key: "funds", href: "/funds" },
  { key: "services", href: "/services" },
  { key: "about", href: "/about" },
  { key: "news", href: "/news" },
  { key: "faqs", href: "/faqs" },
] as const;

export function Header() {
  const t = useTranslations();
  const pathname = usePathname();
  const openFinder = useUIStore((state) => state.openFinder);
  const [menuOpen, setMenuOpen] = useState(false);

  const isActive = (href: string) =>
    href === "/" ? pathname === "/" : pathname.startsWith(href);

  return (
    <header className="sticky top-0 z-50 border-b border-hairline bg-(--header-bg) backdrop-blur-lg backdrop-saturate-150">
      <div className="mx-auto flex max-w-7xl items-center gap-3 px-4 py-3 sm:px-6">
        <Link
          href="/"
          className="flex items-center gap-3"
          data-testid="nav-brand"
          onClick={() => setMenuOpen(false)}
        >
          <BrandLogo
            alt={t("Common.brandFull")}
            priority
            className="h-8 sm:h-9"
          />
        </Link>

        <nav className="ms-auto hidden items-center gap-0.5 lg:flex">
          {NAV.map((item) => (
            <Link
              key={item.key}
              href={item.href}
              data-testid={`nav-${item.key}`}
              aria-current={isActive(item.href) ? "page" : undefined}
              className={cn(
                "relative whitespace-nowrap rounded-lg px-2.5 py-2 text-[0.85rem] transition-colors",
                "after:absolute after:bottom-1 after:start-1/2 after:h-0.5 after:w-0 after:rounded-full after:bg-accent after:transition-all after:duration-300",
                "hover:text-foreground hover:after:start-[19%] hover:after:w-3/5",
                isActive(item.href)
                  ? "text-accent after:start-[19%] after:w-3/5"
                  : "text-soft",
              )}
            >
              {t(`Nav.${item.key}`)}
            </Link>
          ))}
        </nav>

        <div className="ms-auto flex items-center gap-2 lg:ms-3">
          <ThemeToggle />
          <LocaleSwitcher />
          <button
            type="button"
            onClick={() => openFinder()}
            data-testid="finder-open"
            className={cn(
              buttonVariants({ variant: "cta", size: "sm" }),
              "hidden py-2 sm:inline-flex",
            )}
          >
            {t("Nav.findService")}
          </button>
          <button
            type="button"
            className="grid size-10 place-items-center rounded-full border border-border text-soft transition-colors hover:border-accent hover:text-accent lg:hidden"
            aria-label={menuOpen ? t("Header.closeMenu") : t("Header.openMenu")}
            aria-expanded={menuOpen}
            data-testid="mobile-menu-button"
            onClick={() => setMenuOpen((open) => !open)}
          >
            {menuOpen ? (
              <X className="size-5" aria-hidden="true" />
            ) : (
              <Menu className="size-5" aria-hidden="true" />
            )}
          </button>
        </div>
      </div>

      {menuOpen ? (
        <nav
          className="border-t border-hairline bg-background-2 px-4 pb-6 pt-3 lg:hidden"
          data-testid="mobile-menu"
        >
          <ul className="flex flex-col">
            {NAV.map((item) => (
              <li key={item.key}>
                <Link
                  href={item.href}
                  onClick={() => setMenuOpen(false)}
                  data-testid={`mobile-nav-${item.key}`}
                  className={cn(
                    "block rounded-lg px-3 py-3 text-base",
                    isActive(item.href)
                      ? "font-semibold text-accent"
                      : "text-foreground",
                  )}
                >
                  {t(`Nav.${item.key}`)}
                </Link>
              </li>
            ))}
            <li>
              <Link
                href="/contact"
                onClick={() => setMenuOpen(false)}
                data-testid="mobile-nav-contact"
                className="block rounded-lg px-3 py-3 text-base text-foreground"
              >
                {t("Nav.contact")}
              </Link>
            </li>
          </ul>
          <button
            type="button"
            onClick={() => {
              setMenuOpen(false);
              openFinder();
            }}
            data-testid="mobile-finder-open"
            className={cn(buttonVariants({ variant: "cta" }), "mt-3 w-full")}
          >
            {t("Nav.findService")}
          </button>
        </nav>
      ) : null}
    </header>
  );
}
