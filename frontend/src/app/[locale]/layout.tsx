import type { Metadata } from "next";
import { Cairo, Sora } from "next/font/google";
import { notFound } from "next/navigation";
import { hasLocale, NextIntlClientProvider } from "next-intl";
import { getMessages, getTranslations } from "next-intl/server";
import { FinderModal } from "@/components/features/finder/FinderModal";
import { Footer } from "@/components/layout/Footer";
import { Header } from "@/components/layout/Header";
import { isRtl, routing } from "@/i18n/routing";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { siteUrl } from "@/lib/utils/urls";
import type { Section, Service } from "@/types/api";
import "../globals.css";

const sora = Sora({
  subsets: ["latin"],
  variable: "--font-sora",
  display: "swap",
});

const cairo = Cairo({
  subsets: ["arabic", "latin"],
  variable: "--font-cairo",
  display: "swap",
});

/** Applies the persisted theme before first paint (no flash). Defaults to light; only dark if the user explicitly opted in. */
const THEME_SCRIPT = `(function(){try{var s=localStorage.getItem('afim-ui');var t=s?JSON.parse(s).state.theme:null;if(t==='dark'){document.documentElement.classList.add('dark');}}catch(e){}})();`;

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: string }>;
}): Promise<Metadata> {
  const { locale } = await params;
  if (!hasLocale(routing.locales, locale)) notFound();
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    metadataBase: new URL(siteUrl()),
    title: t("home.title"),
    description: t("home.description"),
  };
}

export default async function LocaleLayout({
  children,
  params,
}: {
  children: React.ReactNode;
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  if (!hasLocale(routing.locales, locale)) notFound();

  const [messages, common, footerSection, services] = await Promise.all([
    getMessages({ locale }),
    getTranslations({ locale, namespace: "Common" }),
    fetchData<Section>(locale, endpoints.section("footer")),
    fetchData<Service[]>(locale, endpoints.services),
  ]);

  return (
    <html
      lang={locale}
      dir={isRtl(locale) ? "rtl" : "ltr"}
      className={`${sora.variable} ${cairo.variable}`}
      suppressHydrationWarning
    >
      <body className="flex min-h-dvh flex-col overflow-x-hidden bg-background font-sans text-foreground antialiased">
        <script dangerouslySetInnerHTML={{ __html: THEME_SCRIPT }} />
        <NextIntlClientProvider locale={locale} messages={messages}>
          <a
            href="#content"
            className="sr-only focus:not-sr-only focus:absolute focus:start-4 focus:top-4 focus:z-100 focus:rounded-lg focus:bg-accent focus:px-4 focus:py-2 focus:text-accent-on"
          >
            {common("skipToContent")}
          </a>
          <Header />
          <main id="content" className="flex-1">
            {children}
          </main>
          <Footer locale={locale} section={footerSection} services={services} />
          <FinderModal services={services} />
        </NextIntlClientProvider>
      </body>
    </html>
  );
}
