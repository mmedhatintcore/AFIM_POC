# Project Instructions — AFIM Backend

**Scope**: Laravel 12+ backend for the AFIM event ticketing platform.

- **REST APIs** (Sanctum auth) consumed by the Next.js frontend
- **Filament 5 admin dashboard** for platform management

**Languages Supported**: Arabic (ar) & English (en) — RTL support required on the frontend, translatable content on the backend.

---

## 🚨 Database Safety — ABSOLUTE, NON-NEGOTIABLE

**NEVER run `php artisan migrate:fresh`, `migrate:fresh --seed`, `migrate:reset`, `migrate:refresh`, `db:wipe`, or any command that drops, recreates, or wipes the dev (or any real) database.** The dev DB holds real, hand-created data that seeders cannot reproduce (e.g. admin accounts with roles); wiping it has caused unrecoverable data loss. Treat the dev DB as production.

- **Applies to humans, Claude, and every AI subagent.** Prompts that touch the DB must explicitly forbid destructive resets.
- **Tests are the only sanctioned "reset"**: the suite uses an isolated in-memory SQLite DB (`phpunit.xml` / `RefreshDatabase`). `php artisan test` / `pest` never touches the dev MySQL DB.
- **Additive migrations** (`php artisan migrate` that only CREATE tables/columns) are allowed.
- **Destructive migrations** (drop columns/tables, even within a normal `migrate`) require, every time: (1) a `mysqldump` backup first, and (2) explicit per-migration confirmation. Prior consent never carries over.
- If a task seems to need a reset, **STOP and ask** — never improvise. Use the SQLite test path instead.

---

## 🚀 Production Readiness — update `GoLiveActivities.md`

Any backend change that requires a matching **production** change or update MUST be
recorded in the root **`GoLiveActivities.md`** (the launch-readiness source of truth)
in the **same slice** — e.g. a new env var / secret, a database migration, a
queue-worker requirement (any queued job/notification), an external webhook or
integration, a third-party credential, or an approved WhatsApp/Meta template.
Applies to humans, Claude, **and every AI subagent I dispatch**. A production-
affecting change that leaves `GoLiveActivities.md` stale is **incomplete** — the
same bar as a `routes/api.php` change without a contract + Postman update.

---

## Technology Stack

| Package              | Version | Purpose                |
| -------------------- | ------- | ---------------------- |
| Laravel              | 12+     | PHP Framework          |
| PHP                  | 8.3+    | Runtime                |
| Filament             | 5.x     | Admin Dashboard        |
| Sanctum              | Latest  | API Authentication     |
| Spatie Media Library | Latest  | File/Image Management  |
| Spatie Translatable  | Latest  | Multi-language Content |
| MySQL                | 8.0+    | Database               |

---

## Rule Files

Detailed patterns, templates, and conventions live in [.claude/rules/](.claude/rules/). Consult the matching file before writing code in that area:

| Topic                           | File                                                           | Use when…                                                                                    |
| ------------------------------- | -------------------------------------------------------------- | -------------------------------------------------------------------------------------------- |
| Architecture & layering         | [architecture.md](.claude/rules/architecture.md)               | Scaffolding modules, writing Controllers/DTOs/Services/Repositories, model scopes, checklist |
| API conventions                 | [api-conventions.md](.claude/rules/api-conventions.md)         | Defining routes, middleware, locale, auth guards, response envelope, throttle groups         |
| Error handling                  | [error-handling.md](.claude/rules/error-handling.md)           | Mapping exceptions → HTTP, controller `try/catch`, logging via `custom.logger`               |
| Filament 5 admin                | [filament.md](.claude/rules/filament.md)                       | Building a Resource, ListPage table, actions dropdown, module visibility                     |
| Spatie Media Library            | [spatie-media.md](.claude/rules/spatie-media.md)               | File/image uploads, `MediaResource`, conversions, Filament media components                  |
| Spatie Translatable             | [spatie-translatable.md](.claude/rules/spatie-translatable.md) | Translatable fields, `HasTranslations`, Filament translation tabs                            |
| Postman collection sync         | [postman-sync.md](.claude/rules/postman-sync.md)               | **Adding/changing/removing any API route** — update collection JSON in the same slice        |
| File uploads (two-step pattern) | [uploads.md](.claude/rules/uploads.md)                         | **Any endpoint that accepts a file** — use POST /upload first, resource takes a URL          |

---

## Backend Flow

```
Route (api.php) → Controller → DTO → Service → Repository (IF + Eloquent) → API Resource → DataResponse/ErrorResponse
```

**Non-negotiables:**

- Thin controllers (validate → DTO → service → response) — business logic lives in services/repositories
- Form Requests for validation; never `$request->all()`
- `DB::transaction` for multi-write use cases
- Domain exceptions mapped to HTTP per [error-handling.md](.claude/rules/error-handling.md)
- `MediaResource` for image responses — never raw URL strings
- `HasTranslations` for every translatable field; both `en` and `ar` required

For the full behavior checklist, see [architecture.md](.claude/rules/architecture.md).

---

## Verifying Library APIs — Use Live Docs

The stack (Laravel 12, Filament 5, Spatie Media Library, Spatie Translatable, Sanctum) moves fast. **Do not rely on training-data memory for API shapes, method signatures, or configuration keys.** Before writing non-trivial library code, verify against the current docs via **Context7** (CLI or `find-docs` skill). This is cheap insurance against renamed methods, moved configs, and deprecations.

Typical triggers:

- Any Filament v5 component signature (forms, tables, actions, relation managers)
- Spatie package method names (media collections, translations, queue config)
- Laravel 12 framework APIs (`#[Scope]`, route model binding, new cache/queue features)
- Anything you haven't touched in 3+ months

Prefer live docs over guessing. If Context7 is unavailable, note that and proceed cautiously — don't invent APIs.

---

## Payment Gateways

There are **three** payment gateways, all behind the same
`POST /bookings/{reference}/pay` → `payment_transactions` ledger →
`/payments/*/return` (+ `/payments/*/webhook` where one exists) reconciliation
flow:

- **HyperPay** (default for card methods) — OPPWA COPYandPAY: Mada / Visa /
  MC / Amex via an embedded widget (`payment.widget{...}` on `/pay`, no
  redirect). `HyperPayClient` + `HyperPayReconciler`. Selected by
  `config('payments.card_gateway')` (env `PAYMENTS_CARD_GATEWAY`, default
  `hyperpay`); follow-up ops (capture/refund/void) route off the
  transaction's `gateway` column, not `method`. Apple Pay + STC Pay via
  HyperPay are gated (not live). Details:
  `docs/payments/HyperPay_Integration_Notes.md` and the "HyperPay (OPPWA
  COPYandPAY)" section of `docs/api-contract/payments.md`.
- **AlinmaPay** — retained but **parked**: `PAYMENTS_CARD_GATEWAY=alinmapay`
  re-selects it for card methods with no code change. Mada / Visa / MC / Amex
  / Apple Pay (hosted page + pre-auth) and STC Pay / SADAD (capture-now).
  `AlinmaPayClient`. See `docs/payments/AlinmaPay_PARKED.md`.
- **Tamara** (`method: tamara`) — BNPL "Split in 4", redirect hosted checkout,
  unaffected by the card-gateway flag. `TamaraClient` + `TamaraReconciler` +
  `TamaraWebhookVerifier`.

The three clients **do not share an interface** (their APIs differ
structurally) — gateway routing lives at the service layer: `PaymentService`,
`RefundService`, and `BookingConfirmationService` branch on the transaction's
`method` (Tamara, and the card-gateway switch at `/pay`) or its `gateway`
column (HyperPay vs. AlinmaPay follow-up ops, since both can serve the same
card `method`s). Amounts to Tamara go through `App\Support\Payment\TamaraMoney`
(halalas ↔ SAR decimal); amounts to HyperPay go through
`App\Support\Payment\HyperPayMoney`. Follow-up ops (capture/refund/cancel)
reference the **root gateway reference** (the authorize row's
`gateway_reference`) via `RefundService::rootGatewayReference`, never a
capture receipt id. Full details: `docs/payments/Tamara_Integration_Notes.md`
/ `docs/payments/HyperPay_Integration_Notes.md` and the "Tamara (BNPL)" /
"HyperPay (OPPWA COPYandPAY)" sections of `docs/api-contract/payments.md`.

---

## Fin

If any ambiguity arises, prefer the patterns in the rule files over inferring from code. Keep controllers lean, services orchestrated, repositories focused, and resources clean. Always use `MediaResource` for images and proper translations for multi-language support.
