"use client";

import { useTranslations } from "next-intl";
import { useState } from "react";
import { Dialog } from "@/components/ui/Dialog";
import type { Committee } from "@/types/api";

export function CommitteeCard({ committee }: { committee: Committee }) {
  const [open, setOpen] = useState(false);
  const t = useTranslations("About");
  const tc = useTranslations("Common");

  return (
    <>
      <button
        type="button"
        onClick={() => setOpen(true)}
        className="w-full rounded-card border border-border bg-surface p-7 text-start shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/40 hover:shadow-elev-2"
        data-testid={`committee-${committee.id}`}
      >
        <h3 className="mb-4 flex items-center gap-2.5 text-[1.08rem] font-bold">
          <span
            className="h-6 w-2.5 shrink-0 rounded-sm bg-accent"
            aria-hidden="true"
          />
          {committee.name}
        </h3>
        {committee.mission ? (
          <p className="mb-4 text-[0.86rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
            {committee.mission}
          </p>
        ) : null}
        {committee.members.length > 0 ? (
          <div className="mb-4">
            <div className="mb-2 text-[0.7rem] font-semibold uppercase tracking-widest text-muted">
              {t("membersLabel")}
            </div>
            <ul>
              {committee.members.map((member, index) => (
                <li
                  key={index}
                  className="flex items-baseline justify-between gap-3 border-b border-hairline py-2 text-[0.86rem] last:border-b-0"
                >
                  <span className="font-medium">{member.name}</span>
                  <span className="text-[0.78rem] text-accent">
                    {member.role}
                  </span>
                </li>
              ))}
            </ul>
          </div>
        ) : null}
      </button>

      <Dialog
        open={open}
        onClose={() => setOpen(false)}
        closeLabel={tc("close")}
      >
        <h3 className="mb-3 pe-8 text-xl font-bold">{committee.name}</h3>
        {committee.mission ? (
          <p className="mb-5 text-[0.92rem] font-light leading-relaxed text-soft rtl:font-normal">
            {committee.mission}
          </p>
        ) : null}

        {committee.responsibilities.length > 0 ? (
          <div className="mb-6">
            <div className="mb-2 text-[0.7rem] font-semibold uppercase tracking-widest text-muted">
              {t("responsibilitiesLabel")}
            </div>
            <ul className="list-disc space-y-1 ps-5 text-[0.88rem] font-light leading-relaxed text-soft rtl:font-normal">
              {committee.responsibilities.map((item, index) => (
                <li key={index}>{item}</li>
              ))}
            </ul>
          </div>
        ) : null}

        {committee.members.length > 0 ? (
          <div>
            <div className="mb-2 text-[0.7rem] font-semibold uppercase tracking-widest text-muted">
              {t("membersLabel")}
            </div>
            <ul className="space-y-4">
              {committee.members.map((member, index) => (
                <li key={index} className="border-b border-hairline pb-4 last:border-b-0">
                  <div className="flex items-baseline justify-between gap-3">
                    <span className="font-semibold">{member.name}</span>
                    <span className="text-[0.78rem] text-accent">
                      {member.role}
                    </span>
                  </div>
                  {member.bio ? (
                    <p className="mt-1.5 text-[0.85rem] font-light leading-relaxed text-soft rtl:font-normal">
                      {member.bio}
                    </p>
                  ) : null}
                </li>
              ))}
            </ul>
          </div>
        ) : null}
      </Dialog>
    </>
  );
}
