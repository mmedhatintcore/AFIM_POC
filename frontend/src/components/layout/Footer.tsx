import { MapPin, Share2 } from "lucide-react";
import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { extraString } from "@/lib/utils/sections";
import type { Locale } from "@/i18n/routing";
import type { Section, Service } from "@/types/api";

/**
 * lucide-react dropped brand/logo glyphs, so social platform marks are drawn
 * by hand here (single-color, `currentColor`-filled — same approach as
 * `ServiceIcon`'s hand-drawn glyphs) rather than pulling in a brand-icon
 * package for two icons.
 */
type GlyphProps = { className?: string; "aria-hidden"?: boolean | "true" | "false" };

function FacebookGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        d="M13.5 21v-8h2.7l.4-3.1h-3.1V8c0-.9.25-1.5 1.55-1.5H16.7V3.7C16.4 3.66 15.4 3.57 14.24 3.57c-2.4 0-4.04 1.47-4.04 4.16V9.9H7.5V13h2.7v8h3.3Z"
      />
    </svg>
  );
}

function LinkedinGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        d="M6.94 8.5H3.56V20h3.38V8.5ZM5.25 3.5a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92ZM20.45 20h-3.37v-6.02c0-1.44-.03-3.28-2-3.28-2.01 0-2.32 1.57-2.32 3.18V20H9.4V8.5h3.24v1.57h.05c.45-.86 1.56-1.77 3.21-1.77 3.43 0 4.06 2.26 4.06 5.2V20Z"
      />
    </svg>
  );
}

/** Picks a brand glyph for a social link from its URL — falls back to a generic share glyph. */
function socialIcon(href: string | null | undefined) {
  const url = (href ?? "").toLowerCase();
  if (url.includes("facebook.com")) return FacebookGlyph;
  if (url.includes("linkedin.com")) return LinkedinGlyph;
  return Share2;
}

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
                {line.text ? (
                  <a
                    href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(line.text)}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-start gap-1.5 transition-colors hover:text-accent"
                  >
                    <MapPin
                      className="mt-0.5 size-3.5 shrink-0"
                      aria-hidden="true"
                    />
                    <span>{line.text}</span>
                  </a>
                ) : null}
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
            {socialLinks.map((link, index) => {
              const Icon = socialIcon(link.href);
              return (
                <li key={index}>
                  <a
                    href={link.href ?? "#"}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-2 text-[0.87rem] font-light text-soft transition-colors hover:text-foreground rtl:font-normal"
                  >
                    <Icon className="size-4 shrink-0" aria-hidden="true" />
                    <span>{link.text}</span>
                  </a>
                </li>
              );
            })}
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
