import Image from "next/image";
import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import type { Locale } from "@/i18n/routing";
import { cn } from "@/lib/utils/cn";
import { formatDate } from "@/lib/utils/format";
import type { NewsItem } from "@/types/api";

const DOT_COLOR: Record<NewsItem["type"], string> = {
  press: "bg-accent",
  media: "bg-grey",
  social: "bg-[oklch(0.58_0.18_258)]",
};

export async function NewsCard({
  item,
  locale,
}: {
  item: NewsItem;
  locale: Locale;
}) {
  const t = await getTranslations({ locale, namespace: "News" });

  return (
    <article
      className="group relative flex flex-col gap-3.5 rounded-card border border-border bg-surface p-7 shadow-elev-1 transition-all duration-300 ease-out-soft hover:-translate-y-1.5 hover:border-accent/50 hover:shadow-elev-2"
      data-testid={`news-card-${item.slug}`}
    >
      {item.image_url ? (
        <div className="-mx-7 -mt-7 mb-1 overflow-hidden rounded-t-card">
          <Image
            src={item.image_url}
            alt=""
            width={480}
            height={240}
            className="h-40 w-full object-cover transition-transform duration-500 group-hover:scale-105"
          />
        </div>
      ) : null}
      <div className="flex items-center gap-2.5 text-[0.72rem] tracking-wide text-muted">
        <span
          className={cn(
            "size-2.5 rounded-[3px] transition-all duration-500 group-hover:scale-125 group-hover:rounded-full",
            DOT_COLOR[item.type],
          )}
          aria-hidden="true"
        />
        <span className="truncate">
          {item.source} · {formatDate(item.published_at, locale)}
        </span>
        <span className="ms-auto shrink-0 rounded-full border border-border px-2.5 py-0.5 text-[0.64rem] uppercase tracking-widest">
          {item.type_label}
        </span>
      </div>
      <h3 className="text-[1.04rem] font-semibold leading-normal rtl:font-bold">
        <Link
          href={`/news/${item.slug}`}
          className="after:absolute after:inset-0"
          data-testid={`news-link-${item.slug}`}
        >
          {item.title}
        </Link>
      </h3>
      <p className="flex-1 text-[0.85rem] font-light leading-relaxed text-soft rtl:font-normal rtl:leading-loose">
        {item.excerpt}
      </p>
      <span className="text-[0.78rem] font-semibold text-accent">
        {t("readMore")}
      </span>
    </article>
  );
}
