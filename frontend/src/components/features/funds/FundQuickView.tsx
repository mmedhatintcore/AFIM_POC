"use client";

import { useLocale, useTranslations } from "next-intl";
import { useCallback, useRef, useState } from "react";
import { createPortal } from "react-dom";
import { FundIllustration } from "@/components/icons/FundIllustration";
import { buttonVariants } from "@/components/ui/Button";
import { Dialog } from "@/components/ui/Dialog";
import { RiskBadge } from "@/components/ui/RiskBadge";
import { Link } from "@/i18n/navigation";
import type { Locale } from "@/i18n/routing";
import { cn } from "@/lib/utils/cn";
import { formatDate, formatNumber } from "@/lib/utils/format";
import type { Fund } from "@/types/api";
import { ChannelBadge } from "./ChannelBadge";
import { PlatformsList } from "./PlatformsList";

const DRAG_THRESHOLD = 6;

/**
 * Makes a whole fund card open a large details popup. Renders a transparent
 * button that covers the card (place it last inside the `relative` card) and
 * portals the dialog to <body>, so the card's hover transform can't trap it.
 * A drag (e.g. in the prices carousel) never counts as a click.
 */
export function FundQuickView({ fund }: { fund: Fund }) {
  const t = useTranslations("FundQuickView");
  const [open, setOpen] = useState(false);
  const downX = useRef<number | null>(null);
  const close = useCallback(() => setOpen(false), []);

  return (
    <>
      <button
        type="button"
        className="absolute inset-0 z-1 cursor-pointer rounded-[inherit] focus-visible:outline-2 focus-visible:outline-accent"
        aria-label={t("open", { name: fund.name })}
        aria-haspopup="dialog"
        onPointerDown={(event) => {
          downX.current = event.clientX;
        }}
        onClick={(event) => {
          const moved =
            downX.current !== null &&
            Math.abs(event.clientX - downX.current) > DRAG_THRESHOLD;
          downX.current = null;
          if (!moved) setOpen(true);
        }}
        data-testid={`fund-open-${fund.slug}`}
      />
      {open
        ? createPortal(
            <FundDetailsDialog fund={fund} onClose={close} />,
            document.body,
          )
        : null}
    </>
  );
}

function FundDetailsDialog({
  fund,
  onClose,
}: {
  fund: Fund;
  onClose: () => void;
}) {
  const locale = useLocale() as Locale;
  const t = useTranslations("FundQuickView");
  const tc = useTranslations("FundCard");
  const tf = useTranslations("Funds");

  const num = (value: number, digits = 2) =>
    formatNumber(value, locale, {
      minimumFractionDigits: digits,
      maximumFractionDigits: 2,
    });
  const dateText = (iso: string, long = false) =>
    formatDate(iso, locale, {
      year: "numeric",
      month: long ? "long" : "short",
      ...(long ? {} : { day: "numeric" }),
      timeZone: "UTC",
    });
  const parse = (value: string | null) => {
    const n = value === null ? Number.NaN : Number.parseFloat(value);
    return Number.isNaN(n) ? null : n;
  };
  const tone = (value: number | null) =>
    value === null ? "text-muted" : value >= 0 ? "text-gain" : "text-loss";
  const pct = (value: number | null) =>
    value === null ? "—" : `${num(value)}%`;

  const nav = parse(fund.nav_price);
  const return1m = parse(fund.return_1m);
  const performance = (
    [
      ["1m", tf("perf1m")],
      ["ytd", tf("perfYtd")],
      ["1y", tf("perf1y")],
      ["3y", tf("perf3y")],
      ["5y", tf("perf5y")],
      ["since_inception", tf("perfInception")],
    ] as const
  ).map(([key, label]) => ({
    key,
    label,
    value: parse(fund.performance[key]),
  }));
  const hasPerformance = performance.some((row) => row.value !== null);
  const hasDividends =
    fund.dividends.ytd !== null || fund.dividends.history.length > 0;

  return (
    <Dialog
      open
      onClose={onClose}
      closeLabel={t("close")}
      className="max-w-3xl p-0"
    >
      <div data-testid={`fund-dialog-${fund.slug}`}>
        <header className="flex flex-wrap items-center gap-5 border-b border-hairline bg-background-2 p-6 pe-16">
          <span className="grid size-[104px] shrink-0 place-items-center rounded-3xl border border-border bg-white shadow-elev-1">
            <FundIllustration
              name={fund.illustration}
              className="size-[80px]"
            />
          </span>
          <div className="min-w-0 flex-1">
            <div className="flex flex-wrap items-center gap-2">
              <span className="text-[0.7rem] uppercase tracking-wide text-muted">
                {fund.category_label}
              </span>
              <RiskBadge level={fund.risk_level} label={fund.risk_label} />
            </div>
            <h2 className="mt-1.5 text-2xl font-extrabold uppercase leading-tight">
              {fund.name}
            </h2>
          </div>
          <div className="text-end" dir="ltr">
            <div className="text-[0.68rem] uppercase tracking-widest text-muted">
              {tf("navPerCertificate")}
            </div>
            {nav !== null ? (
              <div className="mt-0.5 flex items-baseline justify-end gap-1.5">
                <span className="tnum text-4xl font-extrabold tracking-tight">
                  {num(nav)}
                </span>
                <span className="text-sm text-muted">
                  {fund.currency || tc("currency")}
                </span>
              </div>
            ) : (
              <div className="mt-1 text-sm text-muted">{tc("noPricing")}</div>
            )}
            {fund.price_date ? (
              <div className="tnum mt-0.5 text-xs text-muted">
                {tc("asOf", { date: dateText(fund.price_date) })}
              </div>
            ) : null}
          </div>
        </header>

        <div className="space-y-7 p-6">
          <div className="grid gap-3 sm:grid-cols-3">
            <div
              className={cn(
                "rounded-2xl px-4 py-3",
                return1m === null
                  ? "bg-hairline"
                  : return1m >= 0
                    ? "bg-gain/10"
                    : "bg-loss/10",
              )}
            >
              <div className="text-[0.72rem] text-muted">{tc("return1m")}</div>
              <div
                className={cn(
                  "tnum mt-1 text-3xl font-extrabold leading-none",
                  tone(return1m),
                )}
                dir="ltr"
              >
                {return1m === null
                  ? "—"
                  : `${return1m >= 0 ? "▲" : "▼"} ${pct(return1m)}`}
              </div>
            </div>
            <div className="rounded-2xl bg-hairline px-4 py-3">
              <div className="text-[0.72rem] text-muted">{tc("yield1y")}</div>
              <div
                className={cn(
                  "tnum mt-1 text-3xl font-extrabold leading-none",
                  tone(parse(fund.yield_1y)),
                )}
                dir="ltr"
              >
                {pct(parse(fund.yield_1y))}
              </div>
            </div>
            <div className="rounded-2xl bg-hairline px-4 py-3">
              <div className="text-[0.72rem] text-muted">
                {tc("inceptionDate")}
              </div>
              <div className="tnum mt-1 text-2xl font-extrabold leading-none">
                {fund.inception_date ? dateText(fund.inception_date) : "—"}
              </div>
            </div>
          </div>

          {fund.description ? (
            <section>
              <h3 className="mb-2 text-xs font-bold uppercase tracking-widest text-muted">
                {tf("aboutFund")}
              </h3>
              <p className="whitespace-pre-line text-[0.92rem] leading-relaxed text-soft rtl:leading-loose">
                {fund.description}
              </p>
            </section>
          ) : null}

          {hasPerformance ? (
            <section>
              <h3 className="mb-2 text-xs font-bold uppercase tracking-widest text-muted">
                {tf("performance")}
              </h3>
              <div className="overflow-x-auto rounded-2xl border border-border">
                <table className="w-full min-w-[30rem] text-center text-sm">
                  <thead>
                    <tr className="border-b border-hairline text-[0.68rem] uppercase tracking-wider text-muted">
                      {performance.map((row) => (
                        <th key={row.key} className="px-2 py-2.5 font-semibold">
                          {row.label}
                        </th>
                      ))}
                    </tr>
                  </thead>
                  <tbody>
                    <tr className="tnum font-bold" dir="ltr">
                      {performance.map((row) => (
                        <td
                          key={row.key}
                          className={cn("px-2 py-3", tone(row.value))}
                        >
                          {pct(row.value)}
                        </td>
                      ))}
                    </tr>
                  </tbody>
                </table>
              </div>
            </section>
          ) : null}

          {fund.asset_allocation.length > 0 || hasDividends ? (
            <div className="grid gap-7 sm:grid-cols-2">
              {fund.asset_allocation.length > 0 ? (
                <section>
                  <h3 className="mb-2 text-xs font-bold uppercase tracking-widest text-muted">
                    {tf("assetAllocation")}
                  </h3>
                  <ul className="space-y-2.5">
                    {fund.asset_allocation.map((row) => (
                      <li key={row.type}>
                        <div className="mb-1 flex justify-between text-sm">
                          <span>{row.label}</span>
                          <span className="tnum font-semibold" dir="ltr">
                            {num(row.percent, 0)}%
                          </span>
                        </div>
                        <div className="h-1.5 overflow-hidden rounded-full bg-hairline">
                          <div
                            className="h-full rounded-full bg-accent"
                            style={{
                              width: `${Math.min(100, Math.max(0, row.percent))}%`,
                            }}
                          />
                        </div>
                      </li>
                    ))}
                  </ul>
                </section>
              ) : null}
              {hasDividends ? (
                <section>
                  <h3 className="mb-2 text-xs font-bold uppercase tracking-widest text-muted">
                    {tf("dividends")}
                  </h3>
                  {fund.dividends.ytd !== null ? (
                    <div className="flex justify-between border-b border-hairline pb-2 text-sm">
                      <span className="text-muted">{tf("dividendsYtd")}</span>
                      <span className="tnum font-bold" dir="ltr">
                        {num(Number.parseFloat(fund.dividends.ytd))}{" "}
                        {fund.currency}
                      </span>
                    </div>
                  ) : null}
                  <ul className="mt-2 space-y-1.5 text-sm">
                    {fund.dividends.history.map((row) => (
                      <li key={row.date} className="flex justify-between">
                        <span>{dateText(row.date, true)}</span>
                        <span className="tnum font-semibold" dir="ltr">
                          {num(row.amount)} {fund.currency}
                        </span>
                      </li>
                    ))}
                  </ul>
                </section>
              ) : null}
            </div>
          ) : null}

          <section className="rounded-2xl border border-border bg-background-2 p-4">
            <h3 className="mb-2 text-xs font-bold uppercase tracking-widest text-muted">
              {tf("howToSubscribe")}
            </h3>
            <ChannelBadge
              channel={fund.order_channel}
              label={fund.order_channel_label}
            />
            <p className="mt-2 text-[0.85rem] leading-relaxed text-soft">
              {fund.how_to}
            </p>
            <PlatformsList
              platforms={fund.platforms}
              label={tf("platformsToggle", { count: fund.platforms.length })}
            />
          </section>
        </div>

        <footer className="sticky bottom-0 flex flex-wrap items-center justify-end gap-3 border-t border-hairline bg-surface p-4">
          <Link
            href="/funds"
            onClick={onClose}
            className={buttonVariants({ variant: "ghost", size: "md" })}
            data-testid="fund-dialog-all"
          >
            {t("seeAll")}
          </Link>
          <Link
            href={`/funds/${fund.slug}`}
            onClick={onClose}
            className={buttonVariants({ variant: "cta", size: "md" })}
            data-testid={`fund-dialog-explore-${fund.slug}`}
          >
            {t("exploreMore")}
          </Link>
        </footer>
      </div>
    </Dialog>
  );
}
