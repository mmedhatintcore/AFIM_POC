import type { AxiosError, AxiosInstance, InternalAxiosRequestConfig } from "axios";

const isDev = process.env.NODE_ENV !== "production";
const isServer = typeof window === "undefined";

type TimedConfig = InternalAxiosRequestConfig & {
  __startedAt?: number;
};

function fmt(method?: string, url?: string, status?: number): string {
  const tag = isServer ? "[api:server]" : "[api:client]";
  return `${tag} ${method?.toUpperCase() ?? "?"} ${url ?? ""}${status ? ` → ${status}` : ""}`;
}

const REDACT_KEYS = [
  "password",
  "password_confirmation",
  "token",
  "otp",
  "card_number",
  "cvv",
];

function redact(body: unknown): unknown {
  if (!body || typeof body !== "object") return body;
  const clone: Record<string, unknown> = {
    ...(body as Record<string, unknown>),
  };
  for (const key of REDACT_KEYS) {
    if (key in clone) clone[key] = "***";
  }
  return clone;
}

export function attachLogger(api: AxiosInstance): void {
  api.interceptors.request.use((config) => {
    (config as TimedConfig).__startedAt = Date.now();
    if (isDev) {
      console.log(fmt(config.method, config.url));
      if (config.params) console.log("  params:", config.params);
      if (config.data) console.log("  body:  ", redact(config.data));
    }
    return config;
  });

  api.interceptors.response.use(
    (response) => {
      const started = (response.config as TimedConfig).__startedAt;
      const ms = Date.now() - (started ?? Date.now());
      if (isDev) {
        console.log(
          `${fmt(response.config.method, response.config.url, response.status)} (${ms}ms)`,
        );
      }
      return response;
    },
    (error: AxiosError) => {
      const started = (error.config as TimedConfig | undefined)?.__startedAt;
      const ms = Date.now() - (started ?? Date.now());
      const line = fmt(
        error.config?.method,
        error.config?.url,
        error.response?.status,
      );
      if (isDev || (error.response?.status ?? 500) >= 500) {
        console.error(`${line} (${ms}ms)`, error.response?.data ?? error.message);
      }
      return Promise.reject(error);
    },
  );
}
