"use client";

import axios from "axios";
import { normalizeError } from "./errors";
import { attachLogger } from "./logger";

function getLocale(): string {
  if (typeof document !== "undefined") {
    const lang = document.documentElement.lang;
    if (lang === "ar" || lang === "en") return lang;
  }
  return "ar";
}

/** Browser axios instance — used by client mutations (contact, survey). */
export const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL,
  timeout: 15_000,
  headers: { Accept: "application/json" },
});

api.interceptors.request.use((config) => {
  config.headers["Accept-Language"] = getLocale();
  if (typeof window !== "undefined") {
    config.headers["X-Timezone"] =
      Intl.DateTimeFormat().resolvedOptions().timeZone;
  }
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => Promise.reject(normalizeError(error)),
);

attachLogger(api);
