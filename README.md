# AFIM — CMS Platform

Full CMS conversion of the AFIM (Al Ahly Financial Investments Management)
website: a Laravel backend serving a Filament admin dashboard + REST API, and a
Next.js frontend consuming it. Bilingual (Arabic default + English) with full RTL.

| App | Path | Stack | URL (dev) |
| --- | --- | --- | --- |
| Backend | `backend/` | Laravel 13, Filament 5, PHP 8.3, MySQL | http://localhost:8000 (admin at `/admin`) |
| Frontend | `frontend/` | Next.js 16 (App Router), TypeScript, Tailwind 4, next-intl | http://localhost:3000 |

## Quick start

### Backend

```bash
cd backend
composer install
cp .env.example .env && php artisan key:generate
# point DB_* at a MySQL database (default: afim on 127.0.0.1 root/no password)
php artisan migrate --seed        # seeds ALL site content + admin user
php artisan serve                 # http://localhost:8000
```

Filament admin: http://localhost:8000/admin — `admin@afim.com.eg` / `password`
(change immediately on any shared environment).

### Frontend

```bash
cd frontend
pnpm install
cp .env.example .env.local        # NEXT_PUBLIC_API_URL=http://localhost:8000/api
pnpm dev                          # http://localhost:3000 → redirects to /ar
```

### Tests

```bash
cd backend && php artisan test    # feature tests for every API endpoint (SQLite in-memory)
cd frontend && pnpm lint && pnpm build
```

## What is CMS-managed

Everything content-bearing is editable in Filament:

- **Content → Sections** — every home/about/footer block (announcement banner,
  ticker, hero, advisor, trust strip, goals, why, figures, steps, CTA, footer, …)
  with EN/AR fields and JSON `items` for repeatable entries.
- **Content → Services / Funds / Fund categories / News posts / FAQs**
- **About → Timeline / Team members / Committees**
- **Survey → Questions** (options + scoring votes) and read-only **Submissions**
- **Inbox → Contact messages** (from the website contact form)

## Docs

- `docs/api-contract/` — per-resource API contracts (source of truth for the frontend)
- `docs/postman/AFIM.postman_collection.json` — executable mirror of the contracts
- `GoLiveActivities.md` — production launch checklist (env vars, migrations, hosts)
- `index.html` — the original static page this CMS was converted from (reference)
