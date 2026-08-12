# API Client

**Axios** is the only HTTP client. Never use raw `fetch` for backend calls — the interceptor chain (auth, locale, logger, error normalization) depends on going through the shared instance.

---

## File Layout

```
lib/api/
  client.ts          # browser axios instance
  server.ts          # server-side axios (reads cookies/headers)
  logger.ts          # network logger (terminal + devtools)
  endpoints.ts       # typed endpoint map (/v1/events, /v1/orders, …)
  errors.ts          # normalized ApiError class + guards
  envelope.ts        # unwrap { data, message, errors }
hooks/api/
  useEvents.ts
  useOrder.ts
  ...
```

One axios instance per runtime (browser, Node/RSC). Both share logger + error normalization.

---

## Client Instance

```ts
// lib/api/client.ts
import axios from "axios";
import { attachLogger } from "./logger";
import { normalizeError } from "./errors";

export const api = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL, // e.g. https://api.AFIM.sa
  timeout: 15_000,
  headers: { Accept: "application/json" },
});

api.interceptors.request.use((config) => {
  // Locale from i18n cookie or URL
  config.headers["Accept-Language"] = getLocale();

  // Browser timezone — backend uses it for transactional email / WhatsApp
  // formatting and persists it on users.timezone (see i18n.md → Timezones).
  if (typeof window !== "undefined") {
    config.headers["X-Timezone"] =
      Intl.DateTimeFormat().resolvedOptions().timeZone;
  }

  // Auth — token persisted via zustand store with sessionStorage
  const token = useAuthStore.getState().token;
  if (token) config.headers.Authorization = `Bearer ${token}`;

  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => Promise.reject(normalizeError(error)),
);

attachLogger(api);
```

---

## Server Instance (RSC / Route Handlers)

```ts
// lib/api/server.ts
import "server-only";
import axios from "axios";
import { cookies, headers } from "next/headers";
import { attachLogger } from "./logger";
import { normalizeError } from "./errors";

export const serverApi = axios.create({
  baseURL: process.env.API_URL_INTERNAL ?? process.env.NEXT_PUBLIC_API_URL,
  timeout: 15_000,
  headers: { Accept: "application/json" },
});

serverApi.interceptors.request.use(async (config) => {
  const h = await headers();
  const c = await cookies();

  config.headers["Accept-Language"] = h.get("accept-language")?.startsWith("ar")
    ? "ar"
    : "en";
  const token = c.get("auth_token")?.value;
  if (token) config.headers.Authorization = `Bearer ${token}`;

  return config;
});

serverApi.interceptors.response.use(
  (response) => response,
  (error) => Promise.reject(normalizeError(error)),
);

attachLogger(serverApi);
```

The `'server-only'` import guarantees this never ships to the client bundle.

---

## Network Logger

Logs **every** request and response to the terminal during development (and to the browser console in dev only). In production, logs the failures at `warn` / `error` level.

```ts
// lib/api/logger.ts
import type { AxiosInstance, AxiosError } from "axios";

const isDev = process.env.NODE_ENV !== "production";
const isServer = typeof window === "undefined";

function fmt(method?: string, url?: string, status?: number): string {
  const tag = isServer ? "[api:server]" : "[api:client]";
  return `${tag} ${method?.toUpperCase() ?? "?"} ${url ?? ""}${status ? ` → ${status}` : ""}`;
}

export function attachLogger(api: AxiosInstance): void {
  api.interceptors.request.use((config) => {
    (config as any).__startedAt = Date.now();

    if (isDev) {
      console.log(fmt(config.method, config.url));
      if (config.params) console.log("  params:", config.params);
      if (config.data) console.log("  body:  ", redact(config.data));
    }
    return config;
  });

  api.interceptors.response.use(
    (response) => {
      const ms =
        Date.now() - ((response.config as any).__startedAt ?? Date.now());
      if (isDev) {
        console.log(
          `${fmt(response.config.method, response.config.url, response.status)} (${ms}ms)`,
        );
      }
      return response;
    },
    (error: AxiosError) => {
      const ms =
        Date.now() - ((error.config as any)?.__startedAt ?? Date.now());
      const line = fmt(
        error.config?.method,
        error.config?.url,
        error.response?.status,
      );
      const payload = error.response?.data;

      if (isDev || (error.response?.status ?? 500) >= 500) {
        console.error(`${line} (${ms}ms)`, payload ?? error.message);
      }
      return Promise.reject(error);
    },
  );
}

function redact(body: unknown): unknown {
  if (!body || typeof body !== "object") return body;
  const clone: Record<string, unknown> = { ...(body as object) };
  for (const key of [
    "password",
    "password_confirmation",
    "token",
    "otp",
    "card_number",
    "cvv",
  ]) {
    if (key in clone) clone[key] = "***";
  }
  return clone;
}
```

Terminal output during `pnpm dev` looks like:

```
[api:server] GET /v1/events?filters[is_featured]=1 → 200 (47ms)
[api:client] POST /v1/auth/login → 401 (312ms) { message: 'invalid_credentials' }
```

**Rules:**

- Always redact `password`, `otp`, `token`, `card_*`, `cvv` from logged bodies.
- Never log full bearer tokens — first 6 chars max if absolutely needed.
- Production: only `status >= 500` and network errors are logged (stdout / platform log sink).

---

## Response Envelope

Backend wraps every response. Unwrap at the hook boundary so UI code never sees the envelope shape.

**Success** (single):

```json
{ "data": { … }, "message": "optional" }
```

**Success** (paginated):

```json
{ "data": [ … ], "links": { … }, "meta": { "current_page": 1, "per_page": 20, "total": 234 } }
```

**Error**:

```json
{ "message": "…", "errors": { "field": ["…"] } }
```

```ts
// lib/api/envelope.ts
export type ApiEnvelope<T> = { data: T; message?: string };
export type Paginated<T> = {
  data: T[];
  links: {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
    from: number;
    to: number;
  };
};

export const unwrap = <T>(e: ApiEnvelope<T>): T => e.data;
```

---

## Typed Hook Pattern

```ts
// hooks/api/useEvents.ts
import { useCallback, useState, useEffect } from "react";
import { api } from "@/lib/api/client";
import type { Paginated } from "@/lib/api/envelope";
import type { Event } from "@/types/api";

type Filters = { search?: string; is_featured?: boolean; venue_id?: number };

export function useEvents(filters: Filters, page = 1) {
  const [state, setState] = useState<{
    loading: boolean;
    data: Paginated<Event> | null;
  }>({
    loading: true,
    data: null,
  });

  useEffect(() => {
    let cancelled = false;
    api
      .get<Paginated<Event>>("/v1/events", {
        params: { page, filters, sort: "-starts_at" },
      })
      .then((r) => {
        if (!cancelled) setState({ loading: false, data: r.data });
      });
    return () => {
      cancelled = true;
    };
  }, [JSON.stringify(filters), page]);

  return state;
}
```

For production-grade caching/revalidation, wrap with SWR or TanStack Query. Keep the axios call inside the fetcher so the logger and interceptors still run.

---

## Filter / Sort / Pagination Query Params

The backend contract is **one endpoint per resource**, with `search`, `filters[field]`, `sort`, `page`, `per_page` as query params. Serialize as bracketed arrays:

```ts
// axios + qs-style serializer
api.get("/v1/events", {
  params: {
    search: "jeddah",
    filters: { is_featured: 1, venue_id: 3 },
    sort: "-starts_at",
    page: 2,
    per_page: 20,
  },
  paramsSerializer: { indexes: null }, // filters[is_featured]=1 style
});
```

Resulting URL: `/v1/events?search=jeddah&filters[is_featured]=1&filters[venue_id]=3&sort=-starts_at&page=2&per_page=20`.

**Never** call a non-existent sub-endpoint like `/v1/events/featured` — the backend rejects it. Add the filter instead.

---

## Error Normalization

```ts
// lib/api/errors.ts
import type { AxiosError } from "axios";

export class ApiError extends Error {
  constructor(
    public readonly status: number,
    public readonly code: string,
    message: string,
    public readonly fieldErrors: Record<string, string[]> = {},
  ) {
    super(message);
  }
}

export function normalizeError(
  error: AxiosError<{ message?: string; errors?: Record<string, string[]> }>,
): ApiError {
  if (!error.response) {
    return new ApiError(
      0,
      "network_error",
      "Network error. Please check your connection.",
    );
  }
  const { status, data } = error.response;
  return new ApiError(
    status,
    mapStatusToCode(status),
    data?.message ?? "Request failed",
    data?.errors ?? {},
  );
}

function mapStatusToCode(status: number): string {
  switch (status) {
    case 401:
      return "unauthenticated";
    case 403:
      return "forbidden";
    case 404:
      return "not_found";
    case 409:
      return "conflict";
    case 422:
      return "validation";
    case 429:
      return "throttled";
    default:
      return status >= 500 ? "server_error" : "request_failed";
  }
}
```

Every caller uses `ApiError` — no one touches `AxiosError` directly outside `lib/api/`.

### Handling common cases

- **401 on protected routes** — clear auth store, redirect to `/[locale]/login?next=…`. Do this in a **response interceptor**, not in every hook.
- **422 validation** — pass `fieldErrors` to `react-hook-form` via `setError` so fields highlight inline.
- **429 throttled** — show a toast with the backend message (already localized via `Accept-Language`).
- **5xx** — generic toast ("Something went wrong"); the server logs the real reason.

---

## Auth Tokens

- Token is held in a Zustand store (see [state-management.md](./state-management.md)) and mirrored to an **httpOnly cookie** set by a Next.js route handler at login. This lets the server axios instance read the cookie in RSC, while the client instance reads the in-memory copy.
- **Never** store tokens in `localStorage` — XSS risk.
- On logout: call `POST /v1/auth/logout`, clear store, delete cookie, redirect to home.

---

## Idempotency

Any mutation that charges money or creates an order must send an `Idempotency-Key` header (UUID v4, generated client-side, held in the Zustand store until the response is final):

```ts
api.post("/v1/orders", payload, {
  headers: { "Idempotency-Key": crypto.randomUUID() },
});
```

Retry on network failure reuses the **same** key so the backend replays the stored response.
