import { getTranslations } from "next-intl/server";
import { FundIllustration } from "@/components/icons/FundIllustration";
import { RiskBadge } from "@/components/ui/RiskBadge";
import { Sparkline } from "@/components/ui/Sparkline";
import { Link } from "@/i18n/navigation";
import type { Locale } from "@/i18n/routing";
import { cn } from "@/lib/utils/cn";
import { signedChange } from "@/lib/utils/format";
import type { Fund } from "@/types/api";
import { ChannelBadge } from "./ChannelBadge";
import { PlatformsList } from "./PlatformsList";

/**
 * Fund price card — carousel variant (home) and detailed variant (/funds,
 * which adds the how-to-subscribe channel + platforms).
 */
export async function FundCard({
  fund,
  locale,
  detailed = false,
  className,
}: {
  fund: Fund;
  locale: Locale;
  detailed?: boolean;
  className?: string;
}) {
  const t = await getTranslations({ locale, namespace: "FundCard" });
  const tf = await getTranslations({ locale, namespace: "Funds" });

  const change = fund.daily_change ? signedChange(fund.daily_change) : null;

  return (
    <article
      className={cn(
        "group relative flex flex-col overflow-hidden rounded-card border border-border bg-surface p-6 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-2 hover:border-accent/50 hover:shadow-elev-3",
        className,
      )}
      data-testid={`fund-card-${fund.slug}`}
    >
      <div className="mb-4 flex items-center gap-3">
        <FundIllustration name={fund.illustration} className="size-[46px] shrink-0" />
        <span className="text-[0.68rem] uppercase leading-tight tracking-wide text-muted">
          {fund.category_label}
        </span>
        <RiskBadge
          level={fund.risk_level}
          label={fund.risk_label}
          className="ms-auto"
        />
      </div>

      <h3 className="mb-3 min-h-[2.7em] text-[1.04rem] font-bold leading-snug">
        <Link
          href={`/funds/${fund.slug}`}
          className="after:absolute after:inset-0"
          data-testid={`fund-link-${fund.slug}`}
        >
          {fund.name}
        </Link>
      </h3>

      {fund.nav_price ? (
        <>
          <div className="flex items-baseline gap-2" dir="ltr">
            <span className="tnum text-3xl font-extrabold tracking-tight">
              {fund.nav_price}
            </span>
            <span className="text-[0.78rem] text-muted">{t("currency")}</span>
          </div>
          {change ? (
            <div
              className={cn(
                "tnum mt-1 text-[0.83rem] font-semibold",
                change.up ? "text-up" : "text-down",
              )}
            >
              <span dir="ltr">
                {change.up ? "▲" : "▼"} {change.signed}%
              </span>{" "}
              <span className="font-normal text-muted">{t("today")}</span>
            </div>
          ) : null}
          <div className="mt-4 flex items-end justify-between gap-4 border-t border-hairline pt-4">
            <div>
              <div className="tnum text-xl font-extrabold text-accent" dir="ltr">
                {fund.yield_1y ? `+${fund.yield_1y}%` : "—"}
              </div>
              <div className="mt-0.5 text-[0.64rem] uppercase tracking-wider text-muted">
                {t("yield1y")}
              </div>
            </div>
            {fund.spark && fund.spark.length > 1 ? (
              <Sparkline
                data={fund.spark}
                up={change?.up ?? true}
                id={fund.slug}
              />
            ) : null}
          </div>
        </>
      ) : (
        <p className="mt-2 text-sm text-muted">{t("noPricing")}</p>
      )}

      {detailed ? (
        <div className="relative z-1 mt-5 border-t border-hairline pt-4">
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
      ) : (
        <span className="mt-5 inline-block text-[0.8rem] font-semibold text-accent opacity-85 transition-all duration-300 group-hover:translate-x-1 group-hover:opacity-100 rtl:group-hover:-translate-x-1">
          {t("view")}
        </span>
      )}
    </article>
  );
}
