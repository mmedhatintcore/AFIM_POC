import { getTranslations } from "next-intl/server";
import { FundIllustration } from "@/components/icons/FundIllustration";
import { RiskBadge } from "@/components/ui/RiskBadge";
import { Link } from "@/i18n/navigation";
import type { Locale } from "@/i18n/routing";
import { cn } from "@/lib/utils/cn";
import { formatDate, formatNumber } from "@/lib/utils/format";
import type { Fund } from "@/types/api";
import { ChannelBadge } from "./ChannelBadge";
import { PlatformsList } from "./PlatformsList";

/**
 * Fund card — rank, icon disc, uppercase name, 1-month return (red when
 * up, blue when down) and NAV + inception date. `detailed` (/funds) adds
 * the risk badge and the how-to-subscribe channel + platforms.
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
  /** 1-based position shown top-left; omitted when the card isn't ranked. */
  rank?: number;
  detailed?: boolean;
  className?: string;
}) {
  const t = await getTranslations({ locale, namespace: "FundCard" });
  const tf = await getTranslations({ locale, namespace: "Funds" });

  const returnValue =
    fund.return_1m !== null ? Number.parseFloat(fund.return_1m) : Number.NaN;
  const hasReturn = !Number.isNaN(returnValue);
  const navValue =
    fund.nav_price !== null ? Number.parseFloat(fund.nav_price) : Number.NaN;
  const hasNav = !Number.isNaN(navValue);

  return (
    <article
      className={cn(
        "group relative flex flex-col overflow-hidden rounded-[2rem] border border-border bg-surface p-7 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-2 hover:border-accent/50 hover:shadow-elev-3",
        className,
      )}
      data-testid={`fund-card-${fund.slug}`}
    >
      <div className="flex items-center justify-between gap-3">
        <span
          className="tnum text-lg font-medium leading-none"
          data-testid={`fund-rank-${fund.slug}`}
        >
          {rank ?? ""}
        </span>
        {detailed || fund.risk_level !== 0 ? (
          <RiskBadge level={fund.risk_level} label={fund.risk_label} />
        ) : null}
      </div>

      <span className="mt-4 grid size-[74px] shrink-0 place-items-center rounded-full bg-yellow">
        <FundIllustration name={fund.illustration} className="size-10" />
      </span>

      <h3 className="mt-5 min-h-[4.2em] text-[1.35rem] font-semibold uppercase leading-tight">
        <Link
          href={`/funds/${fund.slug}`}
          className="after:absolute after:inset-0"
          data-testid={`fund-link-${fund.slug}`}
        >
          {fund.name}
        </Link>
      </h3>

      <div className="mt-6 text-end">
        <div className="text-[0.8rem] text-muted">{t("return1m")}</div>
        <div
          className={cn(
            "tnum mt-1 text-[2.6rem] font-light leading-none",
            !hasReturn
              ? "text-muted"
              : returnValue >= 0
                ? "text-gain"
                : "text-loss",
          )}
          dir="ltr"
          data-testid={`fund-return-${fund.slug}`}
        >
          {hasReturn
            ? `${formatNumber(returnValue, locale, {
                minimumFractionDigits: 1,
                maximumFractionDigits: 2,
              })}%`
            : "—"}
        </div>
      </div>

      <div className="mt-5 flex items-end justify-between gap-4">
        <div className="text-[0.95rem] leading-tight">
          <div className="font-medium">{t("nav")}</div>
          <div className="mt-0.5 text-muted">{t("inceptionDate")}</div>
        </div>
        <div className="text-end leading-tight">
          {hasNav ? (
            <div dir="ltr">
              <span className="tnum text-[1.05rem] font-medium">
                {formatNumber(navValue, locale, {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })}
              </span>{" "}
              <span className="text-[0.8rem] font-medium">
                {fund.currency || t("currency")}
              </span>
            </div>
          ) : (
            <div className="text-[0.85rem] text-muted">{t("noPricing")}</div>
          )}
          <div
            className="tnum mt-0.5 text-[0.95rem] text-muted"
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
          </div>
        </div>
      </div>

      {detailed ? (
        <div className="relative z-1 mt-6 border-t border-hairline pt-4">
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
    </article>
  );
}
