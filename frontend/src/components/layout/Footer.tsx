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

function XGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        d="M4 3.5h4.1l4 5.4 4.4-5.4h2.3l-5.6 6.9 6.1 8.2h-4.1l-4.4-5.9-4.8 5.9H3.6l6-7.4L4 3.5Zm2.7 1.6 9.8 13.3h1.7L8.4 5.1H6.7Z"
      />
    </svg>
  );
}

function InstagramGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        fillRule="evenodd"
        clipRule="evenodd"
        d="M8 3.5h8a4.5 4.5 0 0 1 4.5 4.5v8a4.5 4.5 0 0 1-4.5 4.5H8A4.5 4.5 0 0 1 3.5 16V8A4.5 4.5 0 0 1 8 3.5Zm0 1.8A2.7 2.7 0 0 0 5.3 8v8A2.7 2.7 0 0 0 8 18.7h8A2.7 2.7 0 0 0 18.7 16V8A2.7 2.7 0 0 0 16 5.3H8Zm4 3a4.2 4.2 0 1 1 0 8.4 4.2 4.2 0 0 1 0-8.4Zm0 1.8a2.4 2.4 0 1 0 0 4.8 2.4 2.4 0 0 0 0-4.8Zm4.6-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2Z"
      />
    </svg>
  );
}

function YoutubeGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        fillRule="evenodd"
        clipRule="evenodd"
        d="M21.6 8.2a2.8 2.8 0 0 0-1.97-2C17.9 5.7 12 5.7 12 5.7s-5.9 0-7.63.5a2.8 2.8 0 0 0-1.97 2A29 29 0 0 0 2 12a29 29 0 0 0 .4 3.8 2.8 2.8 0 0 0 1.97 2c1.73.5 7.63.5 7.63.5s5.9 0 7.63-.5a2.8 2.8 0 0 0 1.97-2 29 29 0 0 0 .4-3.8 29 29 0 0 0-.4-3.8ZM10 15.2V8.8L15.8 12 10 15.2Z"
      />
    </svg>
  );
}

function TiktokGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        d="M16.6 3.5c.4 2.1 1.7 3.5 3.9 3.7v2.7c-1.4.1-2.7-.3-3.9-1.1v6.1c0 3.1-2.3 5.6-5.6 5.6-3.1 0-5.6-2.5-5.6-5.6 0-3 2.3-5.5 5.5-5.6v2.8a2.8 2.8 0 1 0 2.8 2.8V3.5h2.9Z"
      />
    </svg>
  );
}

function WhatsappGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        d="M12 3.5a8.4 8.4 0 0 0-7.2 12.7L3.5 20.5l4.4-1.3A8.4 8.4 0 1 0 12 3.5Zm0 1.8a6.6 6.6 0 0 1 5.7 9.9l-.2.3.7 2.5-2.6-.7-.3.2A6.6 6.6 0 1 1 12 5.3Zm-3 3c-.2 0-.5.1-.6.4-.2.3-.8.8-.8 2 0 1.2.9 2.3 1 2.5.1.2 1.8 2.8 4.3 3.8 2.1.9 2.6.7 3 .6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2-.1-.2-.3-.2-.6-.4-.3-.2-1.5-.8-1.8-.9-.2-.1-.4-.1-.6.1-.2.3-.6.9-.8 1-.1.2-.3.2-.6.1-.3-.2-1.2-.5-2.3-1.5-.9-.8-1.4-1.7-1.6-2-.1-.3 0-.4.1-.6l.4-.5c.1-.1.2-.3.2-.5.1-.2 0-.4 0-.5L9.6 8.8c-.2-.5-.4-.4-.6-.5h-.5Z"
      />
    </svg>
  );
}

function TelegramGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        d="m4 11.9 15.4-6c.7-.3 1.4.2 1.1 1.2l-2.6 12.3c-.2.9-.7 1.1-1.5.7l-4.1-3-2 1.9c-.2.2-.4.4-.8.4l.3-4.1 7.5-6.8c.3-.3-.1-.4-.5-.2l-9.3 5.8-4-1.2c-.9-.3-.9-.9.2-1.3Z"
      />
    </svg>
  );
}

function SnapchatGlyph({ className, ...rest }: GlyphProps) {
  return (
    <svg viewBox="0 0 24 24" className={className} aria-hidden="true" {...rest}>
      <path
        fill="currentColor"
        d="M12 3.4c2.5 0 4.3 2 4.4 4.5 0 .8 0 1.9.1 2.7.1.3.5.5 1.3.2.4-.1.7-.1.9.1.2.2.2.5-.1.8-.2.3-.9.6-1.3.9-.3.2-.3.4-.2.7.5 1.2 1.6 1.9 2.9 2.2.3 0 .3.4.1.6-.3.4-1.4.7-2.3.9-.2 0-.3.1-.3.3 0 .2-.1.5-.2.7-.1.2-.4.2-.8.2-.6 0-1.1.1-1.9.5-.8.4-1.6 1-2.6 1-1 0-1.8-.6-2.6-1-.8-.4-1.3-.5-1.9-.5-.4 0-.6 0-.8-.2-.1-.2-.1-.5-.2-.7 0-.2-.1-.3-.3-.3-.9-.2-2-.5-2.3-.9-.2-.2-.1-.6.1-.6 1.3-.3 2.4-1 2.9-2.2.1-.3 0-.5-.2-.7-.4-.3-1.1-.6-1.3-.9-.3-.3-.3-.6-.1-.8.2-.2.5-.2.9-.1.8.3 1.2.1 1.3-.2.1-.8.1-1.9.1-2.7C7.7 5.4 9.5 3.4 12 3.4Z"
      />
    </svg>
  );
}

const PLATFORM_GLYPHS: Record<string, (props: GlyphProps) => React.ReactElement> = {
  facebook: FacebookGlyph,
  x: XGlyph,
  instagram: InstagramGlyph,
  linkedin: LinkedinGlyph,
  youtube: YoutubeGlyph,
  tiktok: TiktokGlyph,
  whatsapp: WhatsappGlyph,
  telegram: TelegramGlyph,
  snapchat: SnapchatGlyph,
};

/**
 * Picks a brand glyph for a social link — by its admin-selected `platform`
 * first, falling back to sniffing the URL for older rows that predate the
 * platform field, then a generic share glyph.
 */
function socialIcon(platform: string | null | undefined, href: string | null | undefined) {
  if (platform && PLATFORM_GLYPHS[platform]) return PLATFORM_GLYPHS[platform];

  const url = (href ?? "").toLowerCase();
  if (url.includes("facebook.com")) return FacebookGlyph;
  if (url.includes("linkedin.com")) return LinkedinGlyph;
  if (url.includes("instagram.com")) return InstagramGlyph;
  if (url.includes("twitter.com") || url.includes("x.com")) return XGlyph;
  if (url.includes("youtube.com") || url.includes("youtu.be")) return YoutubeGlyph;
  if (url.includes("tiktok.com")) return TiktokGlyph;
  if (url.includes("wa.me") || url.includes("whatsapp.com")) return WhatsappGlyph;
  if (url.includes("t.me") || url.includes("telegram")) return TelegramGlyph;
  if (url.includes("snapchat.com")) return SnapchatGlyph;
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
  const mapsUrl = extraString(section, "office_maps_url");
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
                    href={
                      mapsUrl ??
                      `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(line.text)}`
                    }
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
              const Icon = socialIcon(link.platform, link.href);
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
