import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { extraString } from "@/lib/utils/sections";
import type { Locale } from "@/i18n/routing";
import type { Section, Service } from "@/types/api";

export async function Footer({
  locale,
  section,
  services,
}: {
  locale: Locale;
  section: Section | null;
  services: Service[] | null;
}) {
  const t = await getTranslations({ locale, namespace: "Footer" });

  const items = section?.items ?? [];
  const officeLines = items.filter(
    (item) => item.text && (item.group === "office" || (!item.group && !item.href)),
  );
  const socialLinks = items.filter(
    (item) => item.text && (item.group === "social" || (!item.group && item.href)),
  );
  const phone = extraString(section, "phone");
  const email = extraString(section, "email");
  const copyright =
    extraString(section, "copyright") ??
    t("copyright", { year: new Date().getFullYear() });

  return (
    <footer className="border-t border-hairline bg-background-2">
      <div className="mx-auto grid max-w-6xl gap-10 px-5 py-14 sm:px-8 md:grid-cols-3">
        <div>
          <h4 className="mb-4 text-[0.8rem] font-bold uppercase tracking-widest text-accent">
            {extraString(section, "office_title") ?? t("officeTitle")}
          </h4>
          <ul className="space-y-2">
            {officeLines.map((line, index) => (
              <li
                key={index}
                className="text-[0.87rem] font-light leading-loose text-soft rtl:font-normal"
              >
                {line.text}
              </li>
            ))}
            {phone ? (
              <li className="text-[0.87rem] text-soft" dir="ltr">
                {phone}
              </li>
            ) : null}
            {email ? (
              <li className="text-[0.87rem] text-soft">
                <a
                  href={`mailto:${email}`}
                  className="transition-colors hover:text-accent"
                >
                  {email}
                </a>
              </li>
            ) : null}
          </ul>
        </div>

        <div>
          <h4 className="mb-4 text-[0.8rem] font-bold uppercase tracking-widest text-accent">
            {extraString(section, "services_title") ?? t("servicesTitle")}
          </h4>
          <ul className="space-y-2">
            {(services ?? []).map((service) => (
              <li key={service.id}>
                <Link
                  href={`/services/${service.slug}`}
                  data-testid={`footer-service-${service.key}`}
                  className="text-[0.87rem] font-light text-soft transition-colors hover:text-foreground rtl:font-normal"
                >
                  {service.name}
                </Link>
              </li>
            ))}
          </ul>
        </div>

        <div>
          <h4 className="mb-4 text-[0.8rem] font-bold uppercase tracking-widest text-accent">
            {extraString(section, "follow_title") ?? t("followTitle")}
          </h4>
          <ul className="space-y-2">
            {socialLinks.map((link, index) => (
              <li key={index}>
                <a
                  href={link.href ?? "#"}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-[0.87rem] font-light text-soft transition-colors hover:text-foreground rtl:font-normal"
                >
                  {link.text}
                </a>
              </li>
            ))}
            <li>
              <Link
                href="/contact"
                data-testid="footer-contact"
                className="text-[0.87rem] font-semibold text-accent transition-colors hover:text-accent-dark"
              >
                {t("contactLink")}
              </Link>
            </li>
          </ul>
        </div>
      </div>

      <p className="border-t border-hairline px-5 py-6 text-center text-xs leading-relaxed text-muted">
        {copyright}
      </p>
    </footer>
  );
}
