"use client";

import { Building2, Lightbulb, LoaderCircle } from "lucide-react";
import { useTranslations } from "next-intl";
import { useState } from "react";
import { ChannelBadge } from "@/components/features/funds/ChannelBadge";
import { PlatformsList } from "@/components/features/funds/PlatformsList";
import { FundIllustration } from "@/components/icons/FundIllustration";
import { IconKey } from "@/components/icons/IconKey";
import { Button, buttonVariants } from "@/components/ui/Button";
import { RiskBadge } from "@/components/ui/RiskBadge";
import { Link } from "@/i18n/navigation";
import { api } from "@/lib/api/client";
import { endpoints } from "@/lib/api/endpoints";
import { cn } from "@/lib/utils/cn";
import type {
  ApiEnvelope,
} from "@/lib/api/envelope";
import type { SurveyQuestion, SurveyResult } from "@/types/api";

type Status = "answering" | "submitting" | "error" | "done";

/** The investment survey: one question per screen → server-scored result. */
export function SurveyFlow({ questions }: { questions: SurveyQuestion[] }) {
  const t = useTranslations("Survey");
  const [step, setStep] = useState(0);
  const [answers, setAnswers] = useState<Record<number, number>>({});
  const [status, setStatus] = useState<Status>("answering");
  const [result, setResult] = useState<SurveyResult | null>(null);

  const total = questions.length;

  const reset = () => {
    setStep(0);
    setAnswers({});
    setResult(null);
    setStatus("answering");
  };

  const submit = async (finalAnswers: Record<number, number>) => {
    setStatus("submitting");
    try {
      const payload = {
        answers: questions.map((question) => ({
          question_id: question.id,
          option_index: finalAnswers[question.id] ?? 0,
        })),
      };
      const response = await api.post<ApiEnvelope<SurveyResult>>(
        endpoints.surveySubmissions,
        payload,
      );
      setResult(response.data.data);
      setStatus("done");
    } catch {
      setStatus("error");
    }
  };

  const pick = (questionId: number, optionIndex: number) => {
    const next = { ...answers, [questionId]: optionIndex };
    setAnswers(next);
    if (step + 1 >= total) {
      void submit(next);
    } else {
      setStep(step + 1);
    }
  };

  if (status === "done" && result) {
    return <SurveyResultScreen result={result} onRetake={reset} />;
  }

  if (status === "submitting") {
    return (
      <div
        className="flex flex-col items-center gap-4 py-20 text-center"
        role="status"
        data-testid="survey-submitting"
      >
        <LoaderCircle className="size-8 animate-spin text-accent" aria-hidden="true" />
        <p className="text-sm text-soft">{t("submitting")}</p>
      </div>
    );
  }

  if (status === "error") {
    return (
      <div
        className="flex flex-col items-center gap-5 py-16 text-center"
        data-testid="survey-error"
      >
        <p className="max-w-sm text-sm text-down">{t("error")}</p>
        <Button onClick={() => void submit(answers)} data-testid="survey-retry">
          {t("retake")}
        </Button>
      </div>
    );
  }

  const question = questions[step];
  const progress = Math.max((step / (total + 1)) * 100, 4);

  return (
    <div
      className="mx-auto max-w-xl rounded-card-xl border border-border bg-surface p-7 shadow-elev-2 sm:p-9"
      data-testid="survey-card"
    >
      <div
        className="mb-6 h-1.5 overflow-hidden rounded-full bg-surface-2"
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

      <div className="mb-2 flex items-center gap-3">
        <span className="h-6 w-2.5 rounded-sm bg-accent" aria-hidden="true" />
        <p
          className="tnum text-xs font-semibold uppercase tracking-widest text-muted"
          data-testid="survey-phase"
        >
          {question.phase} · {t("progress", { current: step + 1, total })}
        </p>
      </div>
      <h2 className="text-xl font-bold sm:text-2xl">{question.question}</h2>
      <p className="mb-6 mt-2 text-sm font-light text-soft rtl:font-normal">
        {step === 0 ? t("firstHint", { total }) : t("pickClosest")}
      </p>

      <div
        className={cn(
          question.layout === "grid"
            ? "grid grid-cols-1 gap-3 sm:grid-cols-2"
            : "flex flex-col gap-3",
        )}
      >
        {question.options.map((option) => (
          <button
            key={option.index}
            type="button"
            onClick={() => pick(question.id, option.index)}
            data-testid={`survey-option-${question.id}-${option.index}`}
            className={cn(
              "rounded-[14px] border border-border bg-background-2 text-start transition-all duration-300 ease-out-soft hover:border-accent hover:shadow-elev-1",
              question.layout === "grid"
                ? "px-4 py-3.5 text-center hover:-translate-y-0.5"
                : "flex items-center gap-4 p-4 hover:translate-x-1 rtl:hover:-translate-x-1",
            )}
          >
            {question.layout === "cards" ? (
              <>
                <IconKey
                  name={option.icon}
                  className="size-6 shrink-0 text-accent"
                />
                <span>
                  <span className="block text-[0.93rem] font-semibold">
                    {option.label}
                  </span>
                  {option.description ? (
                    <span className="mt-0.5 block text-[0.78rem] font-light text-soft rtl:font-normal">
                      {option.description}
                    </span>
                  ) : null}
                </span>
              </>
            ) : (
              <span className="text-[0.9rem] font-semibold">{option.label}</span>
            )}
          </button>
        ))}
      </div>

      {step > 0 ? (
        <button
          type="button"
          onClick={() => setStep(step - 1)}
          data-testid="survey-back"
          className="mt-6 text-sm text-soft transition-colors hover:text-accent"
        >
          ← {t("back")}
        </button>
      ) : null}
    </div>
  );
}

function SurveyResultScreen({
  result,
  onRetake,
}: {
  result: SurveyResult;
  onRetake: () => void;
}) {
  const t = useTranslations("Survey");
  const { category, alternative, funds } = result;

  return (
    <div
      className="mx-auto max-w-2xl rounded-card-xl border border-border bg-surface p-7 text-center shadow-elev-2 sm:p-10"
      data-testid="survey-result"
    >
      <div className="pop-in mx-auto mb-5 grid size-24 place-items-center rounded-[26px] border border-border bg-background-2 p-3">
        <FundIllustration name={category.illustration} className="size-16" />
      </div>
      <p className="mb-2 text-[0.82rem] font-semibold uppercase tracking-widest text-accent">
        {t("resultKicker")}
      </p>
      <h2 className="text-2xl font-bold leading-snug">
        {category.name}{" "}
        <RiskBadge
          level={category.risk_level}
          label={category.risk_label}
          variant="solid"
          className="ms-2 align-middle"
        />
      </h2>

      <div className="mb-5 mt-4 flex flex-wrap justify-center gap-2">
        {result.profile.map((chip, index) => (
          <span
            key={index}
            className="rounded-full border border-border bg-background-2 px-3 py-1 text-[0.72rem] text-soft"
          >
            {chip}
          </span>
        ))}
        {result.is_islamic ? (
          <span className="rounded-full border border-up/40 bg-up/10 px-3 py-1 text-[0.72rem] text-up">
            {t("islamicChip")}
          </span>
        ) : null}
      </div>

      <p className="mx-auto mb-8 max-w-lg text-[0.93rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
        {category.description}
      </p>

      {funds.length > 0 ? (
        <>
          <p className="mb-3 text-start text-[0.72rem] font-semibold uppercase tracking-widest text-muted">
            {t("matchingFunds")}
          </p>
          <div className="mb-7 flex flex-col gap-3 text-start">
            {funds.map((fund) => (
              <div
                key={fund.slug}
                className="rounded-[14px] border border-border bg-background-2 p-4"
                data-testid={`survey-fund-${fund.slug}`}
              >
                <div className="flex flex-wrap items-center gap-3">
                  <Link
                    href={`/funds/${fund.slug}`}
                    className="text-[0.92rem] font-bold transition-colors hover:text-accent"
                  >
                    {fund.name}
                  </Link>
                  <ChannelBadge
                    channel={fund.order_channel}
                    label={fund.order_channel_label}
                    className="ms-auto"
                  />
                </div>
                <p className="mt-1.5 text-[0.78rem] font-light leading-relaxed text-soft rtl:font-normal">
                  {fund.how_to}
                </p>
                <PlatformsList
                  platforms={fund.platforms}
                  label={t("platformsToggle", { count: fund.platforms.length })}
                />
              </div>
            ))}
          </div>
        </>
      ) : null}

      {alternative ? (
        <div
          className="mb-4 flex items-start gap-3 rounded-[14px] border border-dashed border-border p-4 text-start text-[0.8rem] leading-relaxed text-soft"
          data-testid="survey-alternative"
        >
          <Lightbulb className="mt-0.5 size-4 shrink-0 text-accent" aria-hidden="true" />
          <span>
            {t("alsoWorth")}{" "}
            <b className="text-foreground">{alternative.name}</b> —{" "}
            {alternative.description}
          </span>
        </div>
      ) : null}

      {result.is_corporate ? (
        <div
          className="mb-4 flex items-start gap-3 rounded-[14px] border border-dashed border-border p-4 text-start text-[0.8rem] leading-relaxed text-soft"
          data-testid="survey-corporate"
        >
          <Building2 className="mt-0.5 size-4 shrink-0 text-accent" aria-hidden="true" />
          <span>
            {t("corporateNote")}{" "}
            <Link
              href="/services"
              className="font-semibold text-accent transition-colors hover:text-accent-dark"
            >
              {t("corporateCta")}
            </Link>
          </span>
        </div>
      ) : null}

      <div className="mt-7 flex flex-wrap justify-center gap-3">
        <Link
          href="/funds"
          data-testid="survey-see-funds"
          className={buttonVariants({ variant: "cta" })}
        >
          {t("seeFunds")}
        </Link>
        <Button variant="ghost" onClick={onRetake} data-testid="survey-retake">
          {t("retake")}
        </Button>
      </div>

      <p className="mt-6 text-[0.7rem] leading-relaxed text-muted">
        {t("disclaimer")}
      </p>
    </div>
  );
}
