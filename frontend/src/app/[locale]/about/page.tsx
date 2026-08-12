import type { Metadata } from "next";
import { getTranslations } from "next-intl/server";
import { AboutTabs } from "@/components/features/about/AboutTabs";
import { PersonCard } from "@/components/features/about/PersonCard";
import { IconKey } from "@/components/icons/IconKey";
import { LogoMark } from "@/components/icons/LogoMark";
import { Container } from "@/components/ui/Container";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { assertLocale } from "@/lib/utils/locale";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type {
  Committee,
  Section,
  TeamMember,
  TimelineMilestone,
} from "@/types/api";

type Props = { params: Promise<{ locale: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("about.title"),
    description: t("about.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/about"),
      languages: languageAlternates("/about"),
    },
    openGraph: {
      title: t("about.title"),
      description: t("about.description"),
      url: absoluteUrl(locale, "/about"),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function AboutPage({ params }: Props) {
  const locale = assertLocale((await params).locale);

  const [brief, milestones, board, leadership, committees, t] =
    await Promise.all([
      fetchData<Section>(locale, endpoints.section("about_brief")),
      fetchData<TimelineMilestone[]>(locale, endpoints.timelineMilestones),
      fetchData<TeamMember[]>(locale, endpoints.teamMembers, {
        "filters[group]": "board",
      }),
      fetchData<TeamMember[]>(locale, endpoints.teamMembers, {
        "filters[group]": "leadership",
      }),
      fetchData<Committee[]>(locale, endpoints.committees),
      getTranslations({ locale, namespace: "About" }),
    ]);

  const briefParagraphs = (brief?.body ?? "")
    .split(/\n{2,}/)
    .map((paragraph) => paragraph.trim())
    .filter(Boolean);

  const briefPanel = brief ? (
    <div>
      <div className="grid items-center gap-10 lg:grid-cols-[0.8fr_1.2fr]">
        <div className="grid place-items-center rounded-card-xl bg-linear-150 from-navy to-navy-2 p-12 shadow-elev-2 transition-all duration-500 ease-out-soft hover:-rotate-1 hover:scale-[1.02]">
          <LogoMark className="size-40 drop-shadow-xl" />
        </div>
        <div className="space-y-4">
          {briefParagraphs.map((paragraph, index) => (
            <p
              key={index}
              className="font-light leading-loose text-soft rtl:font-normal"
            >
              {paragraph}
            </p>
          ))}
        </div>
      </div>
      {(brief.items ?? []).length > 0 ? (
        <div className="mt-10 grid gap-5 md:grid-cols-3">
          {(brief.items ?? []).map((card, index) => (
            <div
              key={index}
              className="rounded-card border border-border bg-surface p-7 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/40 hover:shadow-elev-2"
            >
              <IconKey name={card.icon} className="mb-3 size-7 text-accent" />
              <h3 className="mb-2 text-[1.05rem] font-bold text-accent">
                {card.title}
              </h3>
              <p className="text-[0.87rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                {card.text}
              </p>
            </div>
          ))}
        </div>
      ) : null}
    </div>
  ) : (
    <EmptyState message={t("empty")} />
  );

  const timelinePanel =
    milestones && milestones.length > 0 ? (
      <div className="relative mx-auto max-w-3xl">
        <span
          className="absolute bottom-0 top-0 w-0.5 bg-linear-to-b from-accent to-navy-2 ltr:left-[88px] rtl:right-[88px]"
          aria-hidden="true"
        />
        {milestones.map((milestone) => (
          <div
            key={milestone.id}
            className="group relative grid grid-cols-[88px_1fr] gap-6 py-4 sm:gap-8"
            data-testid={`timeline-${milestone.id}`}
          >
            <span
              className="absolute top-6 size-3.5 rounded-full border-[3px] border-background bg-accent transition-transform duration-300 group-hover:scale-150 ltr:left-[82px] rtl:right-[82px]"
              aria-hidden="true"
            />
            <div className="tnum pe-6 text-end text-lg font-extrabold text-accent">
              {milestone.year}
            </div>
            <div className="ps-6">
              <h3 className="mb-1 text-[1.05rem] font-bold">
                {milestone.title}
              </h3>
              <p className="text-[0.87rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
                {milestone.body}
              </p>
            </div>
          </div>
        ))}
      </div>
    ) : (
      <EmptyState message={t("empty")} />
    );

  const peopleGrid = (members: TeamMember[] | null, intro: string) =>
    members && members.length > 0 ? (
      <div>
        <p className="mx-auto mb-9 max-w-xl text-center text-[0.96rem] text-soft">
          {intro}
        </p>
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          {members.map((member) => (
            <PersonCard key={member.id} member={member} />
          ))}
        </div>
      </div>
    ) : (
      <EmptyState message={t("empty")} />
    );

  const committeesPanel =
    committees && committees.length > 0 ? (
      <div>
        <p className="mx-auto mb-9 max-w-xl text-center text-[0.96rem] text-soft">
          {t("committeesIntro")}
        </p>
        <div className="grid gap-5 md:grid-cols-3">
          {committees.map((committee) => (
            <div
              key={committee.id}
              className="rounded-card border border-border bg-surface p-7 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/40 hover:shadow-elev-2"
              data-testid={`committee-${committee.id}`}
            >
              <h3 className="mb-4 flex items-center gap-2.5 text-[1.08rem] font-bold">
                <span
                  className="h-6 w-2.5 shrink-0 rounded-sm bg-accent"
                  aria-hidden="true"
                />
                {committee.name}
              </h3>
              <ul>
                {committee.responsibilities.map((line, index) => (
                  <li
                    key={index}
                    className="border-b border-hairline py-2 text-[0.86rem] font-light leading-normal text-soft last:border-b-0 rtl:font-normal"
                  >
                    {line}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
      </div>
    ) : (
      <EmptyState message={t("empty")} />
    );

  return (
    <Container className="py-14">
      <SectionHeader title={t("title")} center className="mb-8" />
      <AboutTabs
        panels={{
          brief: briefPanel,
          timeline: timelinePanel,
          board: peopleGrid(board, t("boardIntro")),
          committees: committeesPanel,
          leadership: peopleGrid(leadership, t("leadershipIntro")),
        }}
      />
    </Container>
  );
}
