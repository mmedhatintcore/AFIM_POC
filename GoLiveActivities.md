# Go-Live Activities — AFIM CMS

Launch-readiness source of truth. Every change (backend or frontend) that needs a
matching **production** change must be recorded here in the same slice.

## Backend (Laravel + Filament)

- [ ] **Env vars**: `APP_NAME=AFIM`, `APP_ENV=production`, `APP_KEY` (generate),
      `APP_URL=https://api.afim.com.eg`, `DB_*` (MySQL 8 / utf8mb4),
      `FRONTEND_URL=https://afim.com.eg` (CORS allow-list).
- [ ] **Database**: run `php artisan migrate` (additive only) + `php artisan db:seed`
      once for initial CMS content (sections, services, funds, categories, news,
      FAQs, timeline, team, committees, survey questions, finder questions).
- [ ] **Admin user**: `php artisan db:seed --class=AdminUserSeeder` then rotate the
      seeded password (`admin@afim.com.eg`) immediately.
- [ ] **Filament panel**: served at `/admin` — restrict by IP/VPN if required.
- [ ] **Storage**: `php artisan storage:link` — **required now**: team-member photos
      and news-post images are uploaded via Filament `FileUpload` to the `public`
      disk (`storage/app/public/team/…`, `storage/app/public/news/…`), served at
      `{APP_URL}/storage/…`. Without the symlink these uploads 404. (Uses the
      plain `public` disk, not `spatie/laravel-medialibrary` — that package is
      installed but unused; no `MEDIA_DISK` env needed.)
- [ ] **Cache/queue**: `CACHE_STORE=redis` + `QUEUE_CONNECTION=redis` recommended;
      current build has no queued jobs (no worker required yet).
- [ ] **Throttling**: `throttle:contact` (contact form) and `throttle:survey`
      (survey submissions) rate limiters are registered in `bootstrap/app.php` —
      no extra infra, but keep a WAF rule for POST abuse.

## Frontend (Next.js)

- [ ] **Env vars**: `NEXT_PUBLIC_API_URL=https://api.afim.com.eg/api`,
      `API_URL_INTERNAL` (private network URL if applicable),
      `NEXT_PUBLIC_SITE_URL=https://afim.com.eg`.
- [ ] **`NEXT_PUBLIC_API_URL` must be set at *build* time, not just runtime**:
      `next.config.ts` reads it to allow-list the API host in
      `images.remotePatterns`, so `next/image` can load uploaded team photos and
      news images from `{API_URL}/storage/…`. A build without this env var only
      allow-lists `localhost:8000` and those images will fail to load in
      production.
- [ ] **Build**: `pnpm build` (Node 20+). Pages are server-rendered per request
      (dynamic SSR, no ISR) — CMS edits appear immediately, no revalidation
      webhook needed. If SSR load becomes a concern, add CDN micro-caching
      (e.g. 60–300 s, cache key = path only, per-locale paths already distinct).
- [ ] **Locales**: `/ar` (default) and `/en` — ensure CDN does not cache
      `Accept-Language`-negotiated redirects incorrectly (cache key must include path only; the root `/` redirect is served by Next middleware).

## DNS / Hosting

- [ ] `afim.com.eg` → Next.js host; `api.afim.com.eg` → PHP host (or subpath proxy).
- [ ] TLS on both hosts; HSTS after verification.
