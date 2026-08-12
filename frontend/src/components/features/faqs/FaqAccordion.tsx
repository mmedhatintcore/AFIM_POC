"use client";

import { ChevronDown } from "lucide-react";
import { useState } from "react";
import { Link } from "@/i18n/navigation";
import { cn } from "@/lib/utils/cn";
import { useUIStore } from "@/stores/ui";
import type { Faq, FaqActionType } from "@/types/api";

const ACTION_ROUTES: Partial<Record<NonNullable<FaqActionType>, string>> = {
  survey: "/survey",
  prices: "/funds",
  services: "/services",
  about: "/about",
};

export function FaqAccordion({ faqs }: { faqs: Faq[] }) {
  const [openIds, setOpenIds] = useState<Set<number>>(new Set());
  const openFinder = useUIStore((state) => state.openFinder);

  const toggle = (id: number) => {
    setOpenIds((previous) => {
      const next = new Set(previous);
      if (next.has(id)) {
        next.delete(id);
      } else {
        next.add(id);
      }
      return next;
    });
  };

  return (
    <div className="flex flex-col gap-3" data-testid="faq-list">
      {faqs.map((faq) => {
        const open = openIds.has(faq.id);
        const route = faq.action_type ? ACTION_ROUTES[faq.action_type] : undefined;
        return (
          <div
            key={faq.id}
            className={cn(
              "overflow-hidden rounded-[14px] border bg-surface transition-colors",
              open ? "border-accent/45" : "border-border",
            )}
            data-testid={`faq-item-${faq.id}`}
          >
            <button
              type="button"
              onClick={() => toggle(faq.id)}
              aria-expanded={open}
              aria-controls={`faq-answer-${faq.id}`}
              data-testid={`faq-question-${faq.id}`}
              className="flex w-full items-center gap-3 px-5 py-4 text-start text-[0.92rem] font-semibold"
            >
              {faq.question}
              <ChevronDown
                className={cn(
                  "ms-auto size-4 shrink-0 text-accent transition-transform duration-300",
                  open && "rotate-180",
                )}
                aria-hidden="true"
              />
            </button>
            <div
              id={`faq-answer-${faq.id}`}
              className={cn(
                "grid transition-[grid-template-rows] duration-300 ease-out-soft",
                open ? "grid-rows-[1fr]" : "grid-rows-[0fr]",
              )}
            >
              <div className="overflow-hidden">
                <div className="px-5 pb-5 text-[0.87rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                  {faq.answer}
                  {faq.action_label ? (
                    <div className="mt-3">
                      {faq.action_type === "finder" || !route ? (
                        <button
                          type="button"
                          onClick={() => openFinder()}
                          data-testid={`faq-action-${faq.id}`}
                          className="text-[0.82rem] font-semibold text-accent transition-colors hover:text-accent-dark"
                        >
                          {faq.action_label}
                        </button>
                      ) : (
                        <Link
                          href={route}
                          data-testid={`faq-action-${faq.id}`}
                          className="text-[0.82rem] font-semibold text-accent transition-colors hover:text-accent-dark"
                        >
                          {faq.action_label}
                        </Link>
                      )}
                    </div>
                  ) : null}
                </div>
              </div>
            </div>
          </div>
        );
      })}
    </div>
  );
}
