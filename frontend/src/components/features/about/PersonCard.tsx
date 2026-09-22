"use client";

import Image from "next/image";
import { useTranslations } from "next-intl";
import { useState } from "react";
import { Dialog } from "@/components/ui/Dialog";
import type { TeamMember } from "@/types/api";

function Avatar({
  member,
  size,
}: {
  member: TeamMember;
  size: number;
}) {
  if (!member.photo_url) return null;

  return (
    <Image
      src={member.photo_url}
      alt={member.name}
      width={size}
      height={size}
      className="mx-auto mb-4 rounded-full object-cover"
      style={{ width: size, height: size }}
    />
  );
}

export function PersonCard({ member }: { member: TeamMember }) {
  const [open, setOpen] = useState(false);
  const t = useTranslations("Common");

  return (
    <>
      <button
        type="button"
        onClick={() => setOpen(true)}
        className="w-full rounded-card border border-border bg-surface p-7 text-center shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/50 hover:shadow-elev-2"
        data-testid={`person-${member.id}`}
      >
        <Avatar member={member} size={84} />
        <div className="font-semibold">{member.name}</div>
        <div className="mt-1 text-[0.78rem] text-accent">{member.role}</div>
      </button>

      <Dialog open={open} onClose={() => setOpen(false)} closeLabel={t("close")}>
        <div className="text-center">
          <Avatar member={member} size={96} />
          <div className="text-lg font-bold">{member.name}</div>
          <div className="mt-1 text-[0.85rem] text-accent">{member.role}</div>
        </div>
        {member.bio ? (
          <p className="mt-5 text-[0.92rem] font-light leading-relaxed text-soft rtl:font-normal">
            {member.bio}
          </p>
        ) : null}
      </Dialog>
    </>
  );
}
