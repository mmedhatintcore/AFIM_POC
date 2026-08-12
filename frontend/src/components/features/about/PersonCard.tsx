import type { TeamMember } from "@/types/api";

function initialsFor(name: string): string {
  if (!/[A-Za-z]/.test(name)) return "◈";
  return name
    .split(" ")
    .slice(0, 2)
    .map((word) => word[0])
    .join("")
    .toUpperCase();
}

export function PersonCard({ member }: { member: TeamMember }) {
  return (
    <div
      className="rounded-card border border-border bg-surface p-7 text-center shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/50 hover:shadow-elev-2"
      data-testid={`person-${member.id}`}
    >
      <div
        className="mx-auto mb-4 grid size-[84px] place-items-center rounded-full bg-linear-140 from-navy to-navy-2 text-2xl font-extrabold text-white"
        aria-hidden="true"
      >
        {initialsFor(member.name)}
      </div>
      <div className="font-semibold">{member.name}</div>
      <div className="mt-1 text-[0.78rem] text-accent">{member.role}</div>
    </div>
  );
}
