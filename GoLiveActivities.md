# Go-Live Activities — AFIM CMS

Launch-readiness source of truth. Every change (backend or frontend) that needs a
matching **production** change must be recorded here in the same slice.

## Backend (Laravel + Filament)

- [ ] **Env vars**: `APP_NAME=AFIM`, `APP_ENV=production`, `APP_KEY` (generate),
      `APP_URL=https://api.afim.com.eg`, `DB_*` (MySQL 8 / utf8mb4),
      `FRONTEND_URL=https://afim.com.eg` (CORS allow-list).
- [ ] **Database**: run `php artisan migrate` (additive only) + `php artisan db:seed`
      once for initial CMS content (sections, services, funds, categories, news,
      FAQs, timeline, team, committees, survey questions).
- [ ] **Admin user**: `php artisan db:seed --class=AdminUserSeeder` then rotate the
      seeded password (`admin@afim.com.eg`) immediately.
- [ ] **Filament panel**: served at `/admin` — restrict by IP/VPN if required.
- [ ] **Storage**: `php artisan storage:link`; production media disk (S3/R2) via
      `config/media-library.php` → `MEDIA_DISK` env if media uploads are enabled.
- [ ] **Cache/queue**: `CACHE_STORE=redis` + `QUEUE_CONNECTION=redis` recommended;
      current build has no queued jobs (no worker required yet).
- [ ] **Throttling**: `throttle:contact` (contact form) and `throttle:survey`
      (survey submissions) rate limiters are registered in `bootstrap/app.php` —
      no extra infra, but keep a WAF rule for POST abuse.

## Frontend (Next.js)

- [ ] **Env vars**: `NEXT_PUBLIC_API_URL=https://api.afim.com.eg/api`,
      `API_URL_INTERNAL` (private network URL if applicable),
      `NEXT_PUBLIC_SITE_URL=https://afim.com.eg`.
- [ ] **Build**: `pnpm build` (Node 20+). Pages are server-rendered per request
      (dynamic SSR, no ISR) — CMS edits appear immediately, no revalidation
      webhook needed. If SSR load becomes a concern, add CDN micro-caching
      (e.g. 60–300 s, cache key = path only, per-locale paths already distinct).
- [ ] **Locales**: `/ar` (default) and `/en` — ensure CDN does not cache
      `Accept-Language`-negotiated redirects incorrectly (cache key must include path only; the root `/` redirect is served by Next middleware).

## DNS / Hosting

- [ ] `afim.com.eg` → Next.js host; `api.afim.com.eg` → PHP host (or subpath proxy).
- [ ] TLS on both hosts; HSTS after verification.
