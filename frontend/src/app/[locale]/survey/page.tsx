import type { Metadata } from "next";
import { getTranslations } from "next-intl/server";
import { SurveyFlow } from "@/components/features/survey/SurveyFlow";
import { Container } from "@/components/ui/Container";
import { EmptyState } from "@/components/ui/EmptyState";
import { SectionHeader } from "@/components/ui/SectionHeader";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData } from "@/lib/api/server";
import { assertLocale } from "@/lib/utils/locale";
import { extraString } from "@/lib/utils/sections";
import { absoluteUrl, languageAlternates } from "@/lib/utils/urls";
import type { Section, SurveyQuestion } from "@/types/api";

type Props = { params: Promise<{ locale: string }> };

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const locale = assertLocale((await params).locale);
  const t = await getTranslations({ locale, namespace: "Meta" });
  return {
    title: t("survey.title"),
    description: t("survey.description"),
    alternates: {
      canonical: absoluteUrl(locale, "/survey"),
      languages: languageAlternates("/survey"),
    },
    openGraph: {
      title: t("survey.title"),
      description: t("survey.description"),
      url: absoluteUrl(locale, "/survey"),
      siteName: "AFIM",
      type: "website",
    },
  };
}

export default async function SurveyPage({ params }: Props) {
  const locale = assertLocale((await params).locale);

  const [questions, intro, t] = await Promise.all([
    fetchData<SurveyQuestion[]>(locale, endpoints.surveyQuestions),
    fetchData<Section>(locale, endpoints.section("survey_intro")),
    getTranslations({ locale, namespace: "Survey" }),
  ]);

  const note = extraString(intro, "note");

  return (
    <Container className="py-14">
      <SectionHeader
        title={intro?.title ?? t("title")}
        subtitle={intro?.subtitle ?? intro?.body ?? t("intro")}
        center
      />
      {questions && questions.length > 0 ? (
        <>
          <SurveyFlow questions={questions} />
          {note ? (
            <p className="mx-auto mt-6 max-w-xl text-center text-[0.72rem] leading-relaxed text-muted">
              {note}
            </p>
          ) : null}
        </>
      ) : (
        <EmptyState message={t("empty")} />
      )}
    </Container>
  );
}
