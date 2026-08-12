import { getTranslations } from "next-intl/server";
import { buttonVariants } from "@/components/ui/Button";
import { Container } from "@/components/ui/Container";
import { Link } from "@/i18n/navigation";

export default async function NotFoundPage() {
  const t = await getTranslations("NotFound");

  return (
    <Container className="flex flex-col items-center py-28 text-center">
      <p
        className="tnum mb-4 text-7xl font-extrabold text-accent"
        aria-hidden="true"
        dir="ltr"
      >
        404
      </p>
      <h1 className="mb-3 text-3xl font-extrabold">{t("title")}</h1>
      <p className="mb-8 max-w-md text-soft">{t("body")}</p>
      <Link
        href="/"
        className={buttonVariants({ variant: "cta", size: "lg" })}
        data-testid="notfound-home"
      >
        {t("cta")}
      </Link>
    </Container>
  );
}
