import { getTranslations } from "next-intl/server";
import { FundIllustration } from "@/components/icons/FundIllustration";
import { RiskBadge } from "@/components/ui/RiskBadge";
import { Link } from "@/i18n/navigation";
import type { Locale } from "@/i18n/routing";
import { cn } from "@/lib/utils/cn";
import { formatDate, formatNumber } from "@/lib/utils/format";
import type { Fund } from "@/types/api";
import { ChannelBadge } from "./ChannelBadge";
import { FundQuickView } from "./FundQuickView";
import { PlatformsList } from "./PlatformsList";

/**
 * Fund card — a large rank numeral, the fund logo, category + risk, the name,
 * a tinted 1-month-return panel (red up / blue down) and a NAV / inception
 * strip. `detailed` (/funds) adds the how-to-subscribe channel + platforms.
 */
export async function FundCard({
  fund,
  locale,
  rank,
  detailed = false,
  className,
}: {
  fund: Fund;
  locale: Locale;
  /** 1-based position shown as the big numeral; omitted when not ranked. */
  rank?: number;
  detailed?: boolean;
  className?: string;
}) {
  const t = await getTranslations({ locale, namespace: "FundCard" });
  const tf = await getTranslations({ locale, namespace: "Funds" });

  const returnValue =
    fund.return_1m !== null ? Number.parseFloat(fund.return_1m) : Number.NaN;
  const hasReturn = !Number.isNaN(returnValue);
  const isGain = hasReturn && returnValue >= 0;
  const navValue =
    fund.nav_price !== null ? Number.parseFloat(fund.nav_price) : Number.NaN;
  const hasNav = !Number.isNaN(navValue);

  return (
    <article
      className={cn(
        "group relative flex flex-col overflow-hidden rounded-card-lg border border-border bg-surface p-6 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-2 hover:border-accent/50 hover:shadow-elev-3",
        className,
      )}
      data-testid={`fund-card-${fund.slug}`}
    >
      {rank !== undefined ? (
        <span
          className="tnum pointer-events-none absolute -top-2 end-5 select-none text-[5.5rem] font-black leading-none text-foreground/[0.07] transition-colors duration-300 group-hover:text-accent/25"
          data-testid={`fund-rank-${fund.slug}`}
        >
          {String(rank).padStart(2, "0")}
        </span>
      ) : null}

      <div className="relative flex items-center gap-3">
        <span className="grid size-[88px] shrink-0 place-items-center rounded-3xl border border-border bg-white shadow-elev-1 transition-colors duration-300 group-hover:border-accent/60">
          <FundIllustration name={fund.illustration} className="size-[68px]" />
        </span>
        <div className="min-w-0">
          <span className="block text-[0.68rem] uppercase leading-tight tracking-wide text-muted">
            {fund.category_label}
          </span>
          {detailed || fund.risk_level !== 0 ? (
            <RiskBadge
              level={fund.risk_level}
              label={fund.risk_label}
              className="mt-1.5"
            />
          ) : null}
        </div>
      </div>

      <h3 className="relative mt-5 min-h-[3.6em] text-[1.2rem] font-bold uppercase leading-snug">
        {fund.name}
        <Link
          href={`/funds/${fund.slug}`}
          className="sr-only"
          data-testid={`fund-link-${fund.slug}`}
        >
          {fund.name}
        </Link>
      </h3>

      <div
        className={cn(
          "relative mt-5 rounded-2xl px-4 py-3",
          !hasReturn ? "bg-hairline" : isGain ? "bg-gain/10" : "bg-loss/10",
        )}
      >
        <div className="text-[0.72rem] text-muted">{t("return1m")}</div>
        <div className="mt-0.5 flex flex-wrap items-end justify-between gap-x-2 gap-y-1.5">
          <div
            className={cn(
              "tnum flex items-center gap-2 text-[2.2rem] font-extrabold leading-none",
              !hasReturn ? "text-muted" : isGain ? "text-gain" : "text-loss",
            )}
            dir="ltr"
            data-testid={`fund-return-${fund.slug}`}
          >
            {hasReturn ? (
              <>
                <span className="text-base" aria-hidden="true">
                  {isGain ? "▲" : "▼"}
                </span>
                {`${formatNumber(returnValue, locale, {
                  minimumFractionDigits: 1,
                  maximumFractionDigits: 2,
                })}%`}
              </>
            ) : (
              "—"
            )}
          </div>
          {fund.price_date ? (
            <span
              className="tnum shrink-0 rounded-full mb-1 bg-surface/70 px-2 py-0.5 text-[0.66rem] font-medium"
              data-testid={`fund-date-${fund.slug}`}
            >
              {t("asOf", {
                date: formatDate(fund.price_date, locale, {
                  year: "numeric",
                  month: "short",
                  day: "numeric",
                  timeZone: "UTC",
                }),
              })}
            </span>
          ) : null}
        </div>
      </div>

      <dl className="relative mt-5 grid grid-cols-2 gap-4 border-t border-dashed border-border pt-4">
        <div>
          <dt className="text-[0.68rem] uppercase tracking-wider text-muted">
            {t("nav")}
          </dt>
          <dd
            className="mt-1 text-[1rem] font-semibold leading-tight"
            dir="ltr"
          >
            {hasNav ? (
              <>
                <span className="tnum">
                  {formatNumber(navValue, locale, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                  })}
                </span>{" "}
                <span className="text-[0.75rem] font-medium text-muted">
                  {fund.currency || t("currency")}
                </span>
              </>
            ) : (
              <span className="text-[0.85rem] font-normal text-muted">
                {t("noPricing")}
              </span>
            )}
          </dd>
        </div>
        <div>
          <dt className="text-[0.68rem] uppercase tracking-wider text-muted">
            {t("inceptionDate")}
          </dt>
          <dd
            className="tnum mt-1 text-[1rem] font-semibold leading-tight"
            data-testid={`fund-inception-${fund.slug}`}
          >
            {fund.inception_date
              ? formatDate(fund.inception_date, locale, {
                  year: "numeric",
                  month: "short",
                  day: "numeric",
                  timeZone: "UTC",
                })
              : "—"}
          </dd>
        </div>
      </dl>

      {detailed ? (
        <div className="relative z-2 mt-5 border-t border-hairline pt-4">
          <div className="mb-1.5 text-[0.68rem] uppercase tracking-widest text-muted">
            {tf("howToSubscribe")}
          </div>
          <ChannelBadge
            channel={fund.order_channel}
            label={fund.order_channel_label}
          />
          <p className="mt-2 text-[0.78rem] font-light leading-relaxed text-soft rtl:font-normal">
            {fund.how_to}
          </p>
          <PlatformsList
            platforms={fund.platforms}
            label={tf("platformsToggle", { count: fund.platforms.length })}
          />
        </div>
      ) : null}

      <FundQuickView fund={fund} />

      <span
        className="pointer-events-none absolute inset-x-0 bottom-0 h-1 origin-start scale-x-0 bg-gradient-to-r from-accent to-accent-dark transition-transform duration-500 ease-out-soft group-hover:scale-x-100 rtl:origin-right"
        aria-hidden="true"
      />
    </article>
  );
}
