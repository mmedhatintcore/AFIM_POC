import "server-only";
import axios, { type AxiosInstance } from "axios";
import type { Locale } from "@/i18n/routing";
import type { ApiEnvelope, Paginated } from "./envelope";
import { normalizeError } from "./errors";
import { attachLogger } from "./logger";

/**
 * Server-side axios — one instance per locale. `Accept-Language` comes from
 * the `[locale]` route param (passed explicitly), NOT from request headers,
 * so the API always returns content matching the URL's locale.
 */
const instances = new Map<Locale, AxiosInstance>();

export function serverApi(locale: Locale): AxiosInstance {
  const cached = instances.get(locale);
  if (cached) return cached;

  const api = axios.create({
    baseURL: process.env.API_URL_INTERNAL ?? process.env.NEXT_PUBLIC_API_URL,
    timeout: 10_000,
    headers: {
      Accept: "application/json",
      "Accept-Language": locale,
    },
  });

  api.interceptors.response.use(
    (response) => response,
    (error) => Promise.reject(normalizeError(error)),
  );
  attachLogger(api);

  instances.set(locale, api);
  return api;
}

type Params = Record<string, string | number | boolean | undefined>;

/**
 * Graceful fetch for `{ data: T }` envelopes: returns `null` on ANY failure
 * (network down, 404, 5xx) so SSR pages can render fallbacks instead of
 * crashing. Detail pages treat `null` as `notFound()`.
 */
export async function fetchData<T>(
  locale: Locale,
  url: string,
  params?: Params,
): Promise<T | null> {
  try {
    const response = await serverApi(locale).get<ApiEnvelope<T>>(url, {
      params,
    });
    return response.data.data;
  } catch {
    return null;
  }
}

/** Graceful fetch for flat paginated responses `{ data, links, meta }`. */
export async function fetchPaginated<T>(
  locale: Locale,
  url: string,
  params?: Params,
): Promise<Paginated<T> | null> {
  try {
    const response = await serverApi(locale).get<Paginated<T>>(url, {
      params,
    });
    return response.data;
  } catch {
    return null;
  }
}
