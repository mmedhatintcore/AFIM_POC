import type { Metadata } from "next";
import { Mail, MapPin, Phone } from "lucide-react";
import { getTranslations } from "next-intl/server";
import { ContactForm } from "@/components/features/contact/ContactForm";
import { Container } from "@/components/ui/Container";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { assertLocale } from "@/lib/utils/locale";
import { extraString } from "@/lib/utils/sections";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type { Section } from "@/types/api";

type Props = { params: Promise<{ locale: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("contact.title"),
    description: t("contact.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/contact"),
      languages: languageAlternates("/contact"),
    },
    openGraph: {
      title: t("contact.title"),
      description: t("contact.description"),
      url: absoluteUrl(locale, "/contact"),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function ContactPage({ params }: Props) {
  const locale = assertLocale((await params).locale);

  const [footer, t] = await Promise.all([
    fetchData<Section>(locale, endpoints.section("footer")),
    getTranslations({ locale, namespace: "Contact" }),
  ]);

  const officeLines = (footer?.items ?? []).filter(
    (item) =>
      item.text && (item.group === "office" || (!item.group && !item.href)),
  );
  const phone = extraString(footer, "phone");
  const email = extraString(footer, "email");

  return (
    <Container className="py-14">
      <SectionHeader title={t("title")} subtitle={t("subtitle")} />
      <div className="grid gap-10 lg:grid-cols-[0.85fr_1.15fr]">
        <aside className="h-fit rounded-card-lg border border-border bg-surface p-8 shadow-elev-1">
          <h2 className="mb-5 text-[0.8rem] font-bold uppercase tracking-widest text-accent">
            {extraString(footer, "office_title") ?? t("officeTitle")}
          </h2>
          <ul className="space-y-4 text-[0.9rem] leading-relaxed text-soft">
            {officeLines.map((line, index) => (
              <li key={index} className="flex gap-3">
                <MapPin
                  className="mt-1 size-4 shrink-0 text-accent"
                  aria-hidden="true"
                />
                <span>{line.text}</span>
              </li>
            ))}
            {phone ? (
              <li className="flex items-center gap-3">
                <Phone className="size-4 shrink-0 text-accent" aria-hidden="true" />
                <span dir="ltr">{phone}</span>
              </li>
            ) : null}
            {email ? (
              <li className="flex items-center gap-3">
                <Mail className="size-4 shrink-0 text-accent" aria-hidden="true" />
                <a
                  href={`mailto:${email}`}
                  className="transition-colors hover:text-accent"
                >
                  {email}
                </a>
              </li>
            ) : null}
          </ul>
        </aside>
        <div>
          <ContactForm />
        </div>
      </div>
    </Container>
  );
}
