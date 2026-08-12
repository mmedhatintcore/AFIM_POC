"use client";

import { X } from "lucide-react";
import { useTranslations } from "next-intl";
import { useEffect, useRef, useState } from "react";
import { FundIllustration } from "@/components/icons/FundIllustration";
import { IconKey } from "@/components/icons/IconKey";
import { ServiceIcon } from "@/components/icons/ServiceIcon";
import { buttonVariants } from "@/components/ui/Button";
import { Link } from "@/i18n/navigation";
import {
  FINDER_QUESTIONS,
  recommend,
  type FinderResultKey,
} from "@/lib/constants/finder";
import { useUIStore } from "@/stores/ui";
import type { Service } from "@/types/api";

/**
 * "Find your service" — a 3-question client wizard in a modal. Opened from
 * the header CTA, FAQ chips, footer and CMS `#finder` CTAs (via the UI store).
 * The dialog unmounts on close, so every open starts with fresh state.
 */
export function FinderModal({ services }: { services: Service[] | null }) {
  const open = useUIStore((state) => state.finderOpen);
  const close = useUIStore((state) => state.closeFinder);

  if (!open) return null;
  return <FinderDialog services={services} onClose={close} />;
}

function FinderDialog({
  services,
  onClose,
}: {
  services: Service[] | null;
  onClose: () => void;
}) {
  const t = useTranslations("Finder");
  const [step, setStep] = useState(0);
  const [answers, setAnswers] = useState<string[]>([]);
  const closeButtonRef = useRef<HTMLButtonElement>(null);

  useEffect(() => {
    closeButtonRef.current?.focus();
    const onKey = (event: KeyboardEvent) => {
      if (event.key === "Escape") onClose();
    };
    document.addEventListener("keydown", onKey);
    document.body.style.overflow = "hidden";
    return () => {
      document.removeEventListener("keydown", onKey);
      document.body.style.overflow = "";
    };
  }, [onClose]);

  const total = FINDER_QUESTIONS.length;
  const finished = step >= total;
  const progress = finished ? 100 : Math.max((step / total) * 100, 5);

  const pick = (tag: string) => {
    setAnswers((previous) => {
      const next = [...previous];
      next[step] = tag;
      return next;
    });
    setStep((current) => current + 1);
  };

  const restart = () => {
    setStep(0);
    setAnswers([]);
  };

  return (
    <div
      className="fixed inset-0 z-70 flex items-center justify-center bg-navy/60 p-4 backdrop-blur-sm"
      onClick={(event) => {
        if (event.target === event.currentTarget) onClose();
      }}
      role="presentation"
      data-testid="finder-modal"
    >
      <div
        role="dialog"
        aria-modal="true"
        aria-label={t("title")}
        className="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-card-xl border border-border bg-background-2 p-7 shadow-elev-3 sm:p-9"
      >
        <div className="mb-5 flex items-start justify-between gap-4">
          <div className="flex items-center gap-3">
            <span className="h-6 w-2.5 rounded-sm bg-accent" aria-hidden="true" />
            <p className="text-xs font-semibold uppercase tracking-widest text-muted">
              {finished
                ? t("resultKicker")
                : t("step", { current: step + 1, total })}
            </p>
          </div>
          <button
            ref={closeButtonRef}
            type="button"
            onClick={onClose}
            aria-label={t("closeAria")}
            data-testid="finder-close"
            className="grid size-9 place-items-center rounded-full text-muted transition-all duration-300 hover:rotate-90 hover:bg-surface-2 hover:text-foreground"
          >
            <X className="size-5" aria-hidden="true" />
          </button>
        </div>

        <div
          className="mb-7 h-1.5 overflow-hidden rounded-full bg-surface-2"
          role="progressbar"
          aria-valuenow={Math.round(progress)}
          aria-valuemin={0}
          aria-valuemax={100}
        >
          <div
            className="h-full rounded-full bg-linear-to-r from-accent to-navy-2 transition-all duration-500 ease-out-soft"
            style={{ width: `${progress}%` }}
          />
        </div>

        {finished ? (
          <FinderResult
            resultKey={recommend(answers)}
            services={services}
            onRestart={restart}
            onClose={onClose}
          />
        ) : (
          <FinderStep
            step={step}
            onPick={pick}
            onBack={() => setStep((current) => Math.max(0, current - 1))}
            canGoBack={step > 0}
          />
        )}
      </div>
    </div>
  );
}

function FinderStep({
  step,
  onPick,
  onBack,
  canGoBack,
}: {
  step: number;
  onPick: (tag: string) => void;
  onBack: () => void;
  canGoBack: boolean;
}) {
  const t = useTranslations("Finder");
  const question = FINDER_QUESTIONS[step];

  return (
    <div className="fade-view">
      <h3 className="text-xl font-bold">{t(`${question.id}.title`)}</h3>
      <p className="mb-6 mt-2 text-sm font-light text-soft rtl:font-normal">
        {t("pickClosest")}
      </p>
      <div className="flex flex-col gap-3">
        {question.options.map((option) => (
          <button
            key={option.tag}
            type="button"
            onClick={() => onPick(option.tag)}
            data-testid={`finder-option-${option.tag}`}
            className="flex items-center gap-4 rounded-[14px] border border-border bg-surface p-4 text-start transition-all duration-300 ease-out-soft hover:translate-x-1 hover:border-accent hover:shadow-elev-1 rtl:hover:-translate-x-1"
          >
            <IconKey name={option.icon} className="size-6 shrink-0 text-accent" />
            <span>
              <span className="block text-[0.93rem] font-semibold">
                {t(`${question.id}.options.${option.tag}.label`)}
              </span>
              <span className="mt-0.5 block text-[0.78rem] font-light text-soft rtl:font-normal">
                {t(`${question.id}.options.${option.tag}.desc`)}
              </span>
            </span>
          </button>
        ))}
      </div>
      {canGoBack ? (
        <button
          type="button"
          onClick={onBack}
          data-testid="finder-back"
          className="mt-6 text-sm text-soft transition-colors hover:text-accent"
        >
          ← {t("back")}
        </button>
      ) : null}
    </div>
  );
}

function FinderResult({
  resultKey,
  services,
  onRestart,
  onClose,
}: {
  resultKey: FinderResultKey;
  services: Service[] | null;
  onRestart: () => void;
  onClose: () => void;
}) {
  const t = useTranslations("Finder");
  const service =
    resultKey === "funds"
      ? null
      : (services ?? []).find((entry) => entry.key === resultKey);

  const name =
    resultKey === "funds"
      ? t("results.funds.name")
      : (service?.name ?? t(`results.${resultKey}.name`));
  const description =
    resultKey === "funds"
      ? t("results.funds.desc")
      : (service?.description ?? t(`results.${resultKey}.desc`));
  const href =
    resultKey === "funds"
      ? "/funds"
      : service
        ? `/services/${service.slug}`
        : "/services";

  return (
    <div className="fade-view text-center" data-testid="finder-result">
      <div className="pop-in mx-auto mb-5 grid size-24 place-items-center rounded-[26px] border border-border bg-surface p-3">
        {resultKey === "funds" ? (
          <FundIllustration name="funds" className="size-14" />
        ) : (
          <ServiceIcon name={resultKey} />
        )}
      </div>
      <p className="mb-2 text-[0.82rem] font-semibold uppercase tracking-widest text-accent">
        {t("resultKicker")}
      </p>
      <h3 className="text-2xl font-bold">{name}</h3>
      <p className="mx-auto mb-8 mt-3 max-w-md text-[0.93rem] font-light leading-relaxed text-soft rtl:font-normal">
        {description}
      </p>
      <div className="flex flex-wrap items-center justify-center gap-3">
        <Link
          href={href}
          onClick={onClose}
          data-testid="finder-result-link"
          className={buttonVariants({ variant: "cta" })}
        >
          {resultKey === "funds" ? t("seeFunds") : t("seeService")}
        </Link>
        <button
          type="button"
          onClick={onRestart}
          data-testid="finder-restart"
          className={buttonVariants({ variant: "ghost" })}
        >
          {t("startOver")}
        </button>
      </div>
    </div>
  );
}
