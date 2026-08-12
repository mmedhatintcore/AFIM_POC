"use client";

import { CircleCheck, LoaderCircle } from "lucide-react";
import { useTranslations } from "next-intl";
import { useState, type FormEvent } from "react";
import { z } from "zod";
import { Button } from "@/components/ui/Button";
import { api } from "@/lib/api/client";
import { endpoints } from "@/lib/api/endpoints";
import { isApiError } from "@/lib/api/errors";
import { cn } from "@/lib/utils/cn";
import type { ContactMessagePayload } from "@/types/api";

type FieldName = "name" | "email" | "phone" | "subject" | "message";
type FieldErrors = Partial<Record<FieldName, string>>;

export function ContactForm() {
  const t = useTranslations("Contact");
  const [values, setValues] = useState<Record<FieldName, string>>({
    name: "",
    email: "",
    phone: "",
    subject: "",
    message: "",
  });
  const [errors, setErrors] = useState<FieldErrors>({});
  const [formError, setFormError] = useState<string | null>(null);
  const [status, setStatus] = useState<"idle" | "submitting" | "success">(
    "idle",
  );

  const schema = z.object({
    name: z
      .string()
      .trim()
      .min(1, t("errors.nameRequired"))
      .max(120, t("errors.nameMax")),
    email: z
      .string()
      .trim()
      .min(1, t("errors.emailRequired"))
      .max(190, t("errors.emailMax"))
      .pipe(z.email(t("errors.emailInvalid"))),
    phone: z.string().trim().max(30, t("errors.phoneMax")),
    subject: z.string().trim().max(190, t("errors.subjectMax")),
    message: z
      .string()
      .trim()
      .min(1, t("errors.messageRequired"))
      .max(5000, t("errors.messageMax")),
  });

  const setValue = (field: FieldName, value: string) => {
    setValues((previous) => ({ ...previous, [field]: value }));
    setErrors((previous) => ({ ...previous, [field]: undefined }));
  };

  const onSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setFormError(null);

    const parsed = schema.safeParse(values);
    if (!parsed.success) {
      const next: FieldErrors = {};
      for (const issue of parsed.error.issues) {
        const field = issue.path[0];
        if (typeof field === "string" && !(field in next)) {
          next[field as FieldName] = issue.message;
        }
      }
      setErrors(next);
      return;
    }

    const payload: ContactMessagePayload = {
      name: parsed.data.name,
      email: parsed.data.email,
      message: parsed.data.message,
      ...(parsed.data.phone ? { phone: parsed.data.phone } : {}),
      ...(parsed.data.subject ? { subject: parsed.data.subject } : {}),
    };

    setStatus("submitting");
    try {
      await api.post(endpoints.contactMessages, payload);
      setStatus("success");
    } catch (error) {
      setStatus("idle");
      if (isApiError(error)) {
        if (error.code === "validation") {
          const next: FieldErrors = {};
          for (const [field, messages] of Object.entries(error.fieldErrors)) {
            if (messages[0]) next[field as FieldName] = messages[0];
          }
          setErrors(next);
          return;
        }
        if (error.code === "throttled") {
          setFormError(t("errors.throttled"));
          return;
        }
      }
      setFormError(t("errors.generic"));
    }
  };

  if (status === "success") {
    return (
      <div
        className="flex flex-col items-center gap-4 rounded-card-lg border border-up/40 bg-surface p-10 text-center shadow-elev-1"
        data-testid="contact-success"
        role="status"
      >
        <CircleCheck className="size-10 text-up" aria-hidden="true" />
        <h2 className="text-xl font-bold">{t("successTitle")}</h2>
        <p className="max-w-sm text-sm text-soft">{t("successBody")}</p>
        <Button
          variant="ghost"
          onClick={() => {
            setValues({ name: "", email: "", phone: "", subject: "", message: "" });
            setStatus("idle");
          }}
          data-testid="contact-send-another"
        >
          {t("sendAnother")}
        </Button>
      </div>
    );
  }

  const inputClass = (invalid: boolean) =>
    cn(
      "w-full rounded-[12px] border bg-surface px-4 py-3 text-[0.92rem] text-foreground transition-colors placeholder:text-muted focus:border-accent focus:outline-2 focus:outline-accent/40",
      invalid ? "border-down" : "border-border",
    );

  const field = (
    name: FieldName,
    options: { type?: string; optional?: boolean; textarea?: boolean } = {},
  ) => (
    <div className={options.textarea ? "sm:col-span-2" : undefined}>
      <label
        htmlFor={`contact-${name}`}
        className="mb-1.5 block text-[0.82rem] font-semibold"
      >
        {t(`form.${name}`)}
        {options.optional ? (
          <span className="ms-1.5 font-normal text-muted">
            ({t("form.optional")})
          </span>
        ) : null}
      </label>
      {options.textarea ? (
        <textarea
          id={`contact-${name}`}
          rows={6}
          value={values[name]}
          onChange={(event) => setValue(name, event.target.value)}
          placeholder={t(`form.${name}Placeholder`)}
          aria-invalid={Boolean(errors[name])}
          aria-describedby={errors[name] ? `contact-${name}-error` : undefined}
          data-testid={`contact-field-${name}`}
          className={inputClass(Boolean(errors[name]))}
        />
      ) : (
        <input
          id={`contact-${name}`}
          type={options.type ?? "text"}
          value={values[name]}
          onChange={(event) => setValue(name, event.target.value)}
          placeholder={t(`form.${name}Placeholder`)}
          aria-invalid={Boolean(errors[name])}
          aria-describedby={errors[name] ? `contact-${name}-error` : undefined}
          data-testid={`contact-field-${name}`}
          dir={name === "email" || name === "phone" ? "ltr" : undefined}
          className={inputClass(Boolean(errors[name]))}
        />
      )}
      {errors[name] ? (
        <p
          id={`contact-${name}-error`}
          className="mt-1.5 text-[0.78rem] text-down"
          data-testid={`contact-error-${name}`}
        >
          {errors[name]}
        </p>
      ) : null}
    </div>
  );

  return (
    <form
      onSubmit={onSubmit}
      noValidate
      className="grid gap-5 sm:grid-cols-2"
      data-testid="contact-form"
    >
      {field("name")}
      {field("email", { type: "email" })}
      {field("phone", { type: "tel", optional: true })}
      {field("subject", { optional: true })}
      {field("message", { textarea: true })}
      {formError ? (
        <p
          className="sm:col-span-2 rounded-[12px] border border-down/40 bg-down/10 px-4 py-3 text-sm text-down"
          role="alert"
          data-testid="contact-form-error"
        >
          {formError}
        </p>
      ) : null}
      <div className="sm:col-span-2">
        <Button
          type="submit"
          size="lg"
          disabled={status === "submitting"}
          data-testid="contact-submit"
        >
          {status === "submitting" ? (
            <>
              <LoaderCircle className="size-4 animate-spin" aria-hidden="true" />
              {t("form.sending")}
            </>
          ) : (
            t("form.submit")
          )}
        </Button>
      </div>
    </form>
  );
}
