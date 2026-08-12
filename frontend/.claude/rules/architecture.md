# Architecture & Layering

**Scope**: Next.js 16 with the App Router. React Server Components (RSC) by default; opt into client components explicitly.

---

## Folder Structure

```
src/
  app/                       # App Router (routes, layouts, pages)
    [locale]/                # i18n root — "en" or "ar"
      (marketing)/           # public website route group
        page.tsx
        events/
          page.tsx
          [slug]/page.tsx
      (portal)/              # authenticated vendor/customer portal
        layout.tsx
        dashboard/page.tsx
      layout.tsx             # locale-aware root layout (sets dir="rtl|ltr")
    api/                     # Next.js route handlers (webhooks, OG images)
    globals.css              # Tailwind layers + CSS vars only

  components/
    ui/                      # primitives (Button, Input, Card, Dialog, …)
    layout/                  # Header, Footer, Sidebar, Container
    features/                # domain widgets (EventCard, TicketTierPicker)
    forms/                   # composed form blocks with react-hook-form + zod

  hooks/                     # reusable hooks (useEvents, useDebounce, …)
  lib/
    api/                     # axios instance, network logger, endpoints
    utils/                   # cn(), formatters, guards
    constants/               # enums, static maps

  stores/                    # zustand stores (one file per domain)
  types/                     # shared TS types (ApiEnvelope, Event, …)
  messages/                  # i18n translation JSON (en.json, ar.json)

  styles/                    # design tokens (CSS vars) — see design-system.md
```

---

## App Router — RSC vs Client Components

**Default to Server Components.** Only mark a file `'use client'` when it genuinely needs:

- React state or effects (`useState`, `useEffect`, `useReducer`)
- Browser-only APIs (`window`, `localStorage`, `IntersectionObserver`)
- Event handlers on interactive elements (`onClick`, `onChange`)
- A third-party client-only library (charts, maps, rich editors)
- Zustand store subscription

Everything else — data fetching, layouts, static markup, composition — stays server-rendered. This keeps bundles small and streams faster.

### Composition rule

A server component can render a client component. A client component **cannot** render a server component directly, but it **can** accept one as a `children` / slot prop. Use this to keep interactive shells thin:

```tsx
// components/ui/Drawer.tsx  ('use client')
export function Drawer({ children }: { children: ReactNode }) {
  const [open, setOpen] = useState(false);
  return open ? <div className="…">{children}</div> : null;
}

// app/[locale]/events/page.tsx  (server)
export default async function EventsPage() {
  const events = await fetchEvents();
  return (
    <Drawer>
      <EventList events={events} /> {/* server component passed as child */}
    </Drawer>
  );
}
```

---

## Data Fetching

- **Server components** — `await` directly in the component, using the server-side axios instance from `lib/api/server.ts` (forwards cookies + Accept-Language from `headers()`).
- **Client components** — call client axios via a typed hook in `hooks/` (e.g. `useEvents`). For anything with caching/revalidation needs, prefer SWR or TanStack Query.
- **Mutations** — always client-side via a hook that wraps the axios call, handles the response envelope, and surfaces errors to a toast (see [api-client.md](./api-client.md)).

### Server fetch pattern

```tsx
// app/[locale]/events/page.tsx
import { serverApi } from "@/lib/api/server";

export default async function EventsPage() {
  const { data: events } = await serverApi
    .get("/v1/events")
    .then((r) => r.data);
  return <EventList events={events} />;
}
```

Never mix client-side store state with SSR data without a hydration step — it produces mismatch warnings.

---

## Routing

- All routes live under `[locale]` so every URL carries `/en/...` or `/ar/...`. Middleware rewrites `/` → `/{defaultLocale}`.
- **Route groups** `(marketing)`, `(portal)`, `(auth)` keep URLs clean while isolating layouts.
- **Dynamic segments** — `[slug]`, `[id]`. Prefer slugs for public, IDs for authenticated CRUD.
- **Parallel / intercepting routes** — use sparingly for modals over a list page (e.g. `@modal/(.)events/[slug]`).

---

## Component Reuse — Decision Ladder

Before writing new JSX, walk the ladder:

1. **Use a primitive from `components/ui/`** — Button, Input, Card, Dialog, Tabs, etc. These are the design system (see [design-system.md](./design-system.md)).
2. **Compose primitives into a feature component** in `components/features/`. Name it after the domain concept (`EventCard`, `VenueMap`, `TicketTierPicker`).
3. **If a new primitive is needed** — add it to `components/ui/` with variant props (CVA / class-variance-authority), not a new one-off.
4. **If a pattern appears 3+ times** across features, extract it. Two is coincidence; three is a pattern.

Anti-pattern: copy-pasting a card with tweaked classes into five feature folders. Extract the shared shell and pass content via children/props.

---

## Naming Conventions

- **Files** — `PascalCase` for components (`EventCard.tsx`), `camelCase` for hooks/utils (`useEvents.ts`, `formatCurrency.ts`), `kebab-case` for routes (`app/events/[slug]`).
- **Components** — named exports for primitives (`export function Button`), default exports allowed for page/layout files only.
- **Hooks** — always `useSomething`. One concern per hook.
- **Stores** — `useSomethingStore` (Zustand), one file per domain.
- **Types** — `Event`, `EventListItem`, `ApiEnvelope<T>`, `Paginated<T>`.

---

## TypeScript Rules

- `strict: true` in `tsconfig.json`. No `any`, no `@ts-ignore` without a comment.
- Prefer `type` for data shapes, `interface` for extensible contracts / component props.
- Never widen literal unions to `string` unless you must — `type Status = 'draft' | 'published'` beats `string`.
- API types live in `types/api.ts` and are generated from the backend contract in [docs/api-contract/](../../../docs/api-contract/) when possible.

---

## SSR & SEO — Non-Negotiable

Sight Scape depends on organic search, social shares, and AI-powered answer engines for customer acquisition. If a page doesn't render meaningful HTML on the server, it doesn't exist to Google, Bing, ChatGPT, Perplexity, or a Twitter / WhatsApp preview unfurler. This is a hard product constraint, not a nice-to-have.

### Rules

1. **Server Components by default.** A page whose content comes from the API must `await` that data in the server component and return complete HTML — never spinner → `useEffect` → fetch. Spinners are for client-side interactions after the page loads.
2. **No content-in-useEffect.** If removing `'use client'` breaks the content, the architecture is wrong. Move fetching up to the page/layout.
3. **`generateMetadata` on every public page.** Title, description, canonical, OG tags, Twitter card, `hreflang` alternates. Pull dynamic data (event title, venue) from the same source the page body uses so the two cannot drift.
4. **Structured data (JSON-LD).** Every public page outputs one or more relevant schemas: `Event`, `BreadcrumbList`, `Organization`, `Product`, `FAQPage`, `WebSite` (with `SearchAction`). Render inline via a `<script type="application/ld+json">` in the server component.
5. **Canonical URLs are absolute.** `https://AFIM.sa/en/events/luna-park` — never relative. Use a shared `lib/utils/urls.ts` helper that reads `NEXT_PUBLIC_SITE_URL`.
6. **`robots.ts` + `sitemap.ts`** live at `src/app/robots.ts` and `src/app/sitemap.ts`. Sitemap is generated from the backend (events, venues, vendor pages) and rebuilt on revalidation; never hand-maintained.
7. **Image SEO.** `next/image` with descriptive `alt` in the current locale (both `alt_en` and `alt_ar` come from `MediaResource.custom_properties` — see backend rule file). Never empty `alt=""` for content images.
8. **Core Web Vitals are a budget, not a goal.** Target LCP < 2.5s, INP < 200ms, CLS < 0.1 on mobile. PRs that regress these without a justification don't merge.

### `generateMetadata` template

```tsx
// app/[locale]/events/[slug]/page.tsx
import type { Metadata } from "next";
import { serverApi } from "@/lib/api/server";

export async function generateMetadata({
  params,
}: {
  params: Promise<{ locale: "en" | "ar"; slug: string }>;
}): Promise<Metadata> {
  const { locale, slug } = await params;
  const { data: event } = (await serverApi.get(`/v1/events/${slug}`)).data;

  const title = `${event.title} — Sight Scape`;
  const description = event.description?.slice(0, 160) ?? "";
  const url = `${process.env.NEXT_PUBLIC_SITE_URL}/${locale}/events/${slug}`;
  const image = event.cover_image?.url;

  return {
    title,
    description,
    alternates: {
      canonical: url,
      languages: {
        en: `${process.env.NEXT_PUBLIC_SITE_URL}/en/events/${slug}`,
        ar: `${process.env.NEXT_PUBLIC_SITE_URL}/ar/events/${slug}`,
        "x-default": `${process.env.NEXT_PUBLIC_SITE_URL}/en/events/${slug}`,
      },
    },
    openGraph: {
      title,
      description,
      url,
      locale: locale === "ar" ? "ar_SA" : "en_US",
      siteName: "Sight Scape",
      images: image
        ? [{ url: image, width: 1200, height: 630, alt: event.title }]
        : [],
      type: "website",
    },
    twitter: {
      card: "summary_large_image",
      title,
      description,
      images: image ? [image] : [],
    },
  };
}
```

### JSON-LD example (Event page)

```tsx
export default async function EventPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const event = await fetchEvent(slug);

  const ld = {
    "@context": "https://schema.org",
    "@type": "Event",
    name: event.title,
    startDate: event.starts_at,
    endDate: event.ends_at,
    eventStatus: "https://schema.org/EventScheduled",
    eventAttendanceMode: "https://schema.org/OfflineEventAttendanceMode",
    location: {
      "@type": "Place",
      name: event.venue.name,
      address: event.venue.address,
    },
    image: event.cover_image?.url,
    offers: event.ticket_tiers.map((t) => ({
      "@type": "Offer",
      name: t.name,
      price: (t.price_halalas / 100).toFixed(2),
      priceCurrency: "SAR",
      availability: t.is_sold_out
        ? "https://schema.org/SoldOut"
        : "https://schema.org/InStock",
      url: `${process.env.NEXT_PUBLIC_SITE_URL}/events/${slug}`,
    })),
  };

  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(ld) }}
      />
      <EventDetail event={event} />
    </>
  );
}
```

### Caching strategy

- **Public content (events, venues)** — server-rendered with `revalidate` on the fetch call, so Google always hits fresh HTML but we don't re-fetch on every request:
  ```ts
  await fetch(url, { next: { revalidate: 300, tags: [`event:${slug}`] } });
  ```
- **Revalidate on write.** Backend webhooks (or a small route handler) call `revalidateTag('event:luna-park')` when an event changes.
- **Authenticated portal pages** — `dynamic = 'force-dynamic'`; these are not indexable and `robots.ts` disallows the `(portal)` route group.

### Do not

- ❌ `'use client'` on a public landing / listing / detail page.
- ❌ Fetching content inside `useEffect` on a page that should rank.
- ❌ Client-only routing for content URLs — every canonical URL must 200 from a cold request with JS disabled.
- ❌ Returning an empty `<body>` and hydrating later. Crawlers and preview unfurlers don't execute JS.
- ❌ Hiding content behind a tab / accordion and lazy-fetching it; include critical content in the initial HTML.

### Indexation hygiene

- `/[locale]/(portal)/**` → `robots.ts` disallow + `noindex` via metadata.
- Query-parameter variants of listing pages (`?filters[...]`) use `rel="canonical"` pointing to the base URL unless the filter is SEO-worthy (e.g. a city page — then it has its own route).
- Paginated listings declare `rel="prev" / "next"` via `alternates` metadata.
- 404s return a real 404 (via `notFound()`), not a soft-200.

---

## Performance Defaults

- **Images** — always `next/image`. Use the `MediaResource.url` for `src` and `thumbnail_url` as a blur/preview placeholder.
- **Fonts** — `next/font/google` or `next/font/local`. Load Arabic + Latin subsets together for LCP.
- **Code splitting** — dynamic-import heavy client-only libs (`dynamic(() => import('…'), { ssr: false })`).
- **RSC first** — any component that doesn't need state stays a server component, regardless of how trivial it looks.
- **No `useEffect` for data fetching** on page load — use a server component instead.

---

## Testing

Every page with side effects (forms, checkout, auth) gets an integration test. Use **Playwright** for end-to-end against a seeded local backend, and **Vitest + React Testing Library** for component-level tests.

- `e2e/` — Playwright specs per user journey (browse-event, checkout, vendor-publish-listing)
- `src/**/*.test.tsx` — component tests colocated with the component
- `pnpm test` runs unit/component; `pnpm e2e` runs Playwright

Test against **real** axios hitting a dev backend (`./scripts/dev.sh`) for integration — don't mock the HTTP layer in e2e.
