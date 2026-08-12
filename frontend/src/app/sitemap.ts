import type { MetadataRoute } from "next";
import { routing } from "@/i18n/routing";
import { endpoints } from "@/lib/api/endpoints";
import { fetchData, fetchPaginated } from "@/lib/api/server";
import { absoluteUrl } from "@/lib/utils/urls";
import type { Fund, NewsItem, Service } from "@/types/api";

const STATIC_PATHS = [
  "/",
  "/funds",
  "/services",
  "/about",
  "/news",
  "/faqs",
  "/contact",
  "/survey",
];

const MAX_NEWS_PAGES = 10;

async function newsSlugs(): Promise<string[]> {
  const slugs: string[] = [];
  let page = 1;
  let lastPage = 1;
  do {
    const result = await fetchPaginated<NewsItem>(
      routing.defaultLocale,
      endpoints.news,
      { per_page: 50, page },
    );
    if (!result) break;
    slugs.push(...result.data.map((item) => item.slug));
    lastPage = result.meta.last_page;
    page += 1;
  } while (page <= lastPage && page <= MAX_NEWS_PAGES);
  return slugs;
}

function entry(path: string): MetadataRoute.Sitemap[number] {
  return {
    url: absoluteUrl(routing.defaultLocale, path),
    lastModified: new Date(),
    alternates: {
      languages: {
        ar: absoluteUrl("ar", path),
        en: absoluteUrl("en", path),
        "x-default": absoluteUrl("ar", path),
      },
    },
  };
}

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const locale = routing.defaultLocale;
  const [funds, services, news] = await Promise.all([
    fetchData<Fund[]>(locale, endpoints.funds),
    fetchData<Service[]>(locale, endpoints.services),
    newsSlugs(),
  ]);

  const paths = [
    ...STATIC_PATHS,
    ...(funds ?? []).map((fund) => `/funds/${fund.slug}`),
    ...(services ?? []).map((service) => `/services/${service.slug}`),
    ...news.map((slug) => `/news/${slug}`),
  ];

  return paths.map(entry);
}
