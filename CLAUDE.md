# AFIM — Workspace

Saudi Arabia experience & activity booking platform. Two-sided marketplace
(customers + vendors) with Filament admin, Arabic/English RTL support, and
Saudi-specific compliance (Mada/STC Pay, ZATCA VAT).

- **Backend** — Laravel 12 REST API (Sanctum auth) + Filament 5 admin dashboard
- **Frontend** — Next.js 16 (App Router) public website & customer experience
- **Vendor Dashboard** — Next.js 16 (App Router) private vendor portal, entered via admin impersonation
- **Scanner App** — Expo (React Native) staff-only QR check-in app; used by
  listing-assigned scanner users whose accounts are admin-created in Filament
  (no self-registration)

The four are separate GitHub repos included here as **git submodules**. This
root repo holds everything that belongs to the project as a whole: the map,
docs, specs, dev scripts, and onboarding.

---

## How to Use This File

Before starting work, identify which side is involved:

- **Backend changes** → read `backend/CLAUDE.md` first
- **Frontend changes** (public site) → read `frontend/CLAUDE.md` first
- **Vendor dashboard changes** → read `vendor-dashboard/CLAUDE.md` first
- **API contract changes** → read the relevant file in `docs/api-contract/` —
  all consumers must stay in sync
- **Scope or priority questions** → `docs/backlog.md` is authoritative

For any feature, consult `docs/backlog.md` for priority and scope, and
`docs/user-stories/` for intended behavior before writing code. When the
behavior is already specified there, prefer the spec over inferring from code.

---

## Scope & Priorities — READ FIRST

`docs/backlog.md` is the **master feature backlog** — the authoritative source
of truth for what is and isn't in scope. It lists all 130 features across 16
modules with explicit priorities:

- **✦ Must Have** → in MVP (Month 1–3)
- **◆ Should Have** → important but not launch-blocking (Month 3–3.5)
- **◇ Could Have** → only if time allows (post-launch)
- **○ Phase 2** → **explicitly deferred — do not build**

**Rules:**
- Never build Phase 2 features in MVP, even if they seem easy wins
- Always cite the backlog feature ID in PR descriptions, e.g.
  `Implements 6.6 (M6A — IBAN & Bank Details)`
- Don't invent features not in the backlog — ask the user first
- If code contradicts the backlog, the backlog is correct

---

## Applications

| App              | Path                | Stack                                                                |
| ---------------- | ------------------- | -------------------------------------------------------------------- |
| Backend          | `backend/`          | Laravel 12, Filament 5, PHP 8.3+, MySQL 8                            |
| Frontend         | `frontend/`         | Next.js 16 (App Router), TypeScript, Tailwind — public website       |
| Vendor Dashboard | `vendor-dashboard/` | Next.js 16 (App Router), TypeScript, Tailwind — private vendor portal |
| Scanner App      | `scanner-app/`      | Expo SDK 57 (React Native), TypeScript, NativeWind — staff QR check-in app |
| Testing          | `testing/`          | Playwright end-to-end tests for **all** apps (shared stub backend)   |

`testing/` is one of the five git submodules (`AFIM/AFIM-testing`). It holds
the browser E2E suites for `frontend/` and `vendor-dashboard/` — the apps carry
**no** Playwright of their own. See **Testing — the `testing/` project** below.

## Technical Documentation

All cross-cutting specs live in `docs/`:

| Document        | Path                               | Description                                       |
| --------------- | ---------------------------------- | ------------------------------------------------- |
| **Backlog**     | **`docs/backlog.md`**              | **Master feature backlog — scope truth (130 items)** |
| Backend Spec    | `docs/backend-spec.md`             | Architecture, layering, conventions               |
| Frontend Spec   | `docs/frontend-spec.md`            | App Router, state, styling, data fetching         |
| API Contracts   | `docs/api-contract/`               | Per-resource request/response shapes              |
| API Index       | `docs/api-contract/README.md`      | Response envelope, common headers, index          |
| User Stories    | `docs/user-stories/`               | Gherkin scenarios, split by backend/frontend      |
| BRD             | `docs/brd.md`                      | Business requirements                             |

---

## Market-Specific Constraints (Saudi Arabia)

These are **legal or cultural non-negotiables**, not preferences. They cut
across many backlog items:

- **Payment methods**: Mada + STC Pay are mandatory, alongside Visa/MC/Apple Pay
  (all via the AlinmaPay gateway). **Tamara BNPL ("Split in 4")** is a second
  gateway (client-directed, backlog 3.5b), routed by `method:tamara` — see
  `docs/payments/Tamara_Integration_Notes.md`.
- **VAT**: 15% with ZATCA Phase 2 e-invoicing format
- **Language**: Arabic + English with RTL support — built from Day 1, not retrofitted
- **Currency**: SAR primary
- **Communication preference**: WhatsApp > Email for transactional notifications

## Shared Conventions

- **API base**: `/api/v1` (versioned; always include the version prefix)
- **Locale**: driven by `Accept-Language` header (`en` or `ar`)
- **Dates**: ISO 8601 in API responses (`toIso8601String()` on backend)
- **Money**: integer smallest currency units (halalas for SAR), never floats
- **Response envelope**: `{ data, message? }` for success, `{ message, errors? }` for errors

---

## Core Domain Concepts

AFIM is a **two-sided marketplace**. Understanding the vocabulary
matters:

- **Customer** — end user who books experiences
- **Vendor** — either Organization (CR + VAT + Business Name) or Individual (National ID)
- **Listing** — vendor's bookable product (adventure, yacht, restaurant, stay, etc.)
- **Service Type** — admin-configured category (Adventure, Desert Safari, Yacht, etc.)
- **Booking** — customer's purchase of a listing slot/date
- **Ticket** — digital deliverable from a confirmed booking (QR code)
- **Dispatch** — Uber-style broadcast to multiple Individual vendors when a
  customer books an Individual-type service
- **Commission** — platform fee deducted from vendor payouts (set per-account
  globally or per-listing override)
- **Payment Model** — vendor settlement style: Instant (auto-transfer) or
  Periodic (accrual + withdrawal request)

Detailed definitions: see `docs/backlog.md` module descriptions.

---

## Working Across the Apps

When a change touches the backend and a client (the frontend, the
vendor-dashboard, or the scanner-app) together:

1. Update or add the API contract in `docs/api-contract/<resource>.md` first
2. Implement the backend side: route, controller, DTO, service, repository, resource, tests
3. Update the Postman collection (`docs/postman/AFIM.postman_collection.json`)
   in the same slice — request, tests, example response. The post-commit hook
   pushes it to the team's Postman workspace automatically. See
   `backend/.claude/rules/postman-sync.md` for the full rule.
4. Implement the client side: API hook, UI, integration — in `frontend/` for the
   public site, `vendor-dashboard/` for the vendor portal, `scanner-app/` for the staff scanner app
5. Every PR in the change references the same contract file and the same
   backlog feature ID

Never let the frontend call an endpoint that doesn't exist on the backend yet.
Never let `routes/api.php` change without a matching update to the contract
markdown and the Postman collection.

The **vendor dashboard** is a third consumer of the same `/api/v1` (vendor-scoped
endpoints under `/api/v1/vendor/*`). The same contract-first rule applies — update
`docs/api-contract/` and the Postman collection before wiring a new endpoint into
`vendor-dashboard/`. It reaches the API under an **admin-impersonation** session
(see `vendor-dashboard/CLAUDE.md`), not a customer login.

The **scanner app** is a fourth consumer of the same `/api/v1` (scanner-scoped
endpoints under `/api/v1/scanner/*`, see `docs/api-contract/scanner.md`). Same
contract-first rule. It authenticates its own listing-assigned staff users
under a dedicated `scanner` Sanctum guard — not a customer login and not the
vendor-dashboard's admin-impersonation session.

---

## Production Readiness — `GoLiveActivities.md` (root, STRICT)

`GoLiveActivities.md` (repo root) is the **launch-readiness source of truth** — the
cross-cutting list of production config, credentials, migrations, workers,
webhooks, deploy-host/CDN rules, and third-party/Meta setup needed to go live.

> **Non-negotiable:** any change — in ANY app — that requires a matching
> **production** change or update MUST be recorded in `GoLiveActivities.md` in the
> same slice. This includes: a new env var or secret; a database migration; a
> queue/worker requirement; an external webhook or integration; a third-party
> credential or an approved WhatsApp/Meta template; a deploy-host, CDN, or caching
> rule; or a new public host. Applies to me directly **and to every subagent I
> dispatch** — a subagent making a production-affecting change must be told to
> update `GoLiveActivities.md`.

A change that needs production setup but leaves `GoLiveActivities.md` stale is
**incomplete** — exactly like a `routes/api.php` change without a contract +
Postman update.

---

## Testing — the `testing/` project (STRICT)

All **browser end-to-end tests live in the root `testing/` Playwright project**
(submodule `AFIM/AFIM-testing`) — **never** inside `frontend/` or
`vendor-dashboard/`. The apps keep only their unit/component tests (Vitest) and
the `data-testid` selector hooks the E2E suites rely on.

**The rule — non-negotiable:**

> **Every change to `frontend/` or `vendor-dashboard/` that adds, removes, or
> alters user-visible behavior, a page/flow, a component's interaction, or how
> the app consumes the API MUST add or update the corresponding Playwright
> test(s) in `testing/` in the same slice.**

A PR that changes app behavior without a matching test add/update in `testing/`
is **incomplete** — exactly like a backend route change is incomplete without a
contract + Postman update. This applies to me directly **and to every subagent I
dispatch** — a subagent told to change an app surface must be told to update the
matching `testing/` spec.

- **Where tests live:** vendor-dashboard specs → `testing/vendor-dashboard/specs/`;
  frontend specs → `testing/frontend/specs/`. Shared stub, fixtures, and Page
  Object Models → `testing/shared/`. (The frontend suite is being built out as its
  own slice; as each frontend surface lands, its E2E lands with it.)
- **Selector hooks:** `data-testid` attributes (and the `testId` props on shared
  primitives) live in the **app** components. Keep them stable — do not rename or
  remove a hook without updating the `testing/` specs that use it.
- **Add a new app surface → add its spec.** Change a flow → update its spec.
  Remove a surface → remove/adjust its spec.
- **Run:** `cd testing && pnpm install && pnpm e2e:install && pnpm e2e`. Tests run
  against a shared in-memory **stub** backend — never a real DB. See
  `testing/CLAUDE.md`.

Never let an app-behavior change merge with the `testing/` suite left stale.

**Documented exception — `scanner-app/`.** Its E2E suite is **Maestro**,
living inside `scanner-app/maestro/`, not in the root `testing/` project.
`testing/` is browser-Playwright and cannot drive a native mobile binary;
Maestro is the native equivalent, so it stays in the app's own repo instead.
Same strictness applies: any change to `scanner-app/`'s screens, `testID`
hooks, or how it consumes the API MUST add or update the matching flow in
`scanner-app/maestro/` (and its jest-expo/RNTL coverage) in the same slice —
binding on me directly and on every subagent dispatched into that repo.

## Running Locally

```bash
./scripts/setup.sh     # one-time setup after fresh clone (installs all submodules)
./scripts/dev.sh       # run backend + frontend + vendor-dashboard together
```

- Backend → `http://localhost:8000`
- Frontend → `http://localhost:3000`
- Vendor Dashboard → `http://localhost:3001`

See `README.md` for full onboarding.
