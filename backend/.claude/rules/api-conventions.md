# API Conventions

## Route Groups

- Version routes with prefix `/v1`
- Throttle groups (`throttle:auth`, `throttle:otp`, `throttle:upload`, `throttle:checkout`)
- Protected groups use `['middleware' => ['auth:api', 'is_user_active']]` as appropriate
- Language middleware to set locale from `Accept-Language` header

```php
Route::prefix('v1')->middleware('set.locale')->group(function () {
    Route::middleware('throttle:auth')->prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Public (no auth)
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{event}', [EventController::class, 'show']);

    // Protected
    Route::middleware(['auth:api', 'is_user_active'])->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'get']);
            Route::put('/', [ProfileController::class, 'update']);
            Route::post('/avatar', [ProfileController::class, 'updateAvatar']);
        });

        Route::middleware('throttle:checkout')->prefix('orders')->group(function () {
            Route::post('/', [OrderController::class, 'store']);
            Route::get('/{order}', [OrderController::class, 'show']);
        });
    });
});
```

---

## Locale Middleware

Set the app locale from the `Accept-Language` header so Spatie Translatable returns the correct language automatically.

```php
// app/Http/Middleware/SetLocale.php
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language', 'en');

        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
```

---

## Authentication

- Use **Sanctum** with the `api` guard for all authenticated endpoints
- Create tokens inside **Services** (not Controllers) via:
  ```php
  $token = $user->createToken('auth_token')->plainTextToken;
  ```
- Protect routes with `auth:api` and `is_user_active` middleware as appropriate

---

## Response Envelope

- **Success** — return `DataResponse($data, ?$message)`:
  ```php
  return (new DataResponse(new EventResource($event), __('messages.created')))->toJson();
  ```
- **Failure** — return `ErrorResponse($message, $errors, $status)`:
  ```php
  return (new ErrorResponse($e->getMessage(), [], Response::HTTP_BAD_REQUEST))->toJson();
  ```
- For collections use `Resource::collection($paginator)` to preserve pagination metadata.

### Success shape

```json
{
  "data": { ... } | [ ... ],
  "message": "optional human-readable string"
}
```

### Paginated shape

`Resource::collection($paginator)` produces:

```json
{
  "data": [ ... ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 12,
    "per_page": 20,
    "to": 20,
    "total": 234
  }
}
```

Do **not** wrap this again in another `data` key. The frontend relies on the flat `data / links / meta` shape for paginated responses.

### Error shape

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title_en": ["The title en field is required."]
  }
}
```

The `errors` object is present for 422 validation failures and any domain error that returns field-level context. For non-field errors (401, 403, 404, 409, 500) only `message` is required.

---

## HTTP Status Code Usage

| Status | When to use                                                      |
| ------ | ---------------------------------------------------------------- |
| 200    | Successful GET / PUT / PATCH / DELETE (when returning a body)    |
| 201    | Successful POST that created a resource                          |
| 204    | Successful DELETE / PUT with no response body                    |
| 400    | Generic domain error (`CustomException` bucket)                  |
| 401    | Missing or invalid auth                                          |
| 403    | Authenticated but not authorized                                 |
| 404    | Resource not found                                               |
| 409    | State conflict (sold out, duplicate, password reuse, etc.)       |
| 422    | Validation failure                                               |
| 429    | Throttle hit                                                     |
| 500    | Unhandled `Throwable` — message is always generic                |

See [error-handling.md](./error-handling.md) for the full exception → status mapping.

---

## Date & Money Formats

- **Dates in responses** — ISO 8601 strings via `->toIso8601String()` on Carbon instances. UTC is the API contract; the frontend localizes for display.
  ```php
  'starts_at' => $this->starts_at?->toIso8601String(),
  ```
- **Money in responses** — return integers (halalas) plus a display field built at the Resource layer. Never return floats.
  ```php
  'price_halalas' => $this->price_halalas,
  'price'         => number_format($this->price_halalas / 100, 2, '.', '') . ' SAR',
  ```
- **Accept-Language** drives the `SAR` label translation (`sar` in `lang/ar/messages.php`).

---

## Timezones

The platform is operated from Saudi Arabia (`Asia/Riyadh`, UTC+3, no DST), but customers can be anywhere — a European traveler may verify their account, search listings, and book a Saudi experience weeks before flying in. Mishandling timezones produces visible bugs (wrong booking time on the confirmation, expired magic link in the wrong window, "midnight" job firing in the middle of business hours).

**Canonical rules:**

1. **All database timestamps are UTC.** `created_at`, `updated_at`, `expires_at`, `consumed_at`, `email_verified_at`, `starts_at`, etc. — all UTC. `config('app.timezone')` is `'UTC'`.
2. **`now()` server-side is UTC.** Never compute "now" against Riyadh time for storage. Compare UTC to UTC.
3. **Expiry windows are durations**, not wall-clock times. `now()->addMinutes(15)` for a magic link, not "expires at 14:30 Asia/Riyadh".
4. **API responses always emit UTC ISO 8601** (`toIso8601String()`). Localization happens at the presentation layer.
5. **Capture the user's timezone**:
   - Frontend reads it from the browser via `Intl.DateTimeFormat().resolvedOptions().timeZone` (e.g. `'Europe/London'`, `'Asia/Riyadh'`).
   - Frontend sends it on requests via the `X-Timezone` header (alongside `Accept-Language`).
   - Backend stores it on `users.timezone` (default `'Asia/Riyadh'`) at first signup; refresh on subsequent logins if header changed.
6. **Listing time slots** (when an experience actually happens) are stored UTC in the DB. **Display in `Asia/Riyadh` by default** (the experience is in Saudi), with an optional secondary line in the user's timezone for clarity. Booking-confirmation emails always include both: "Your tour starts at **3:00 PM Riyadh time** — that's **12:00 PM in London** for you."
7. **Email / WhatsApp content is timezone-aware**. When you write a transactional template that names a wall-clock time, format the Carbon instance with `->setTimezone($user->timezone)` before rendering. Prefer relative durations ("expires in 15 minutes") over absolute times when the user's timezone isn't reliably known.
8. **Cron jobs use explicit timezones** when they're business-hour-relevant:
   ```php
   Schedule::command(...)->dailyAt('03:00')->timezone('Asia/Riyadh');   // off-hours for ops
   Schedule::command(...)->cron('0 9 * * *')->timezone('Asia/Riyadh'); // 9 AM Riyadh "morning briefing"
   ```
   Pure data-cleanup or analytics aggregation can stay UTC.
9. **Filament admin** renders timestamps in `Asia/Riyadh` (the operations team's timezone), not the requester's. This is a Filament panel-wide default — set it once in the panel provider's `->timezone('Asia/Riyadh')`.
10. **ZATCA invoices** must show times in `Asia/Riyadh` for legal compliance — non-negotiable, hard-coded in the invoice generator regardless of the user's timezone.

**Quick rule of thumb:** if you're storing or comparing → UTC. If you're displaying to a user → user's timezone. If you're displaying to ops / for compliance → Asia/Riyadh.

---

## Idempotency for Writes

Any endpoint that charges money, creates an order, or has expensive side effects should accept an `Idempotency-Key` header. Store the `(user_id, key, response)` tuple for 24h and replay the stored response on retry. Apply this to:

- `POST /v1/orders`
- `POST /v1/orders/{order}/pay`
- `POST /v1/refunds`
- Any webhook-triggered write

Reject requests that reuse a key with a different payload (`409 Conflict`). Payment gateways (Mada, STC Pay) already retry on network failures — idempotency keys are what prevent double charges.

---

## API Versioning

- The `/v1` prefix is permanent for the life of the contract.
- **Never** break an existing `/v1` response shape. Add fields; don't rename or remove them.
- When a breaking change is unavoidable, ship `/v2` alongside `/v1` and deprecate `/v1` with a sunset header:
  ```
  Sunset: Wed, 01 Jan 2027 00:00:00 GMT
  Deprecation: true
  ```
- Document the change in [docs/api-contract/](../../../docs/api-contract/) before writing code. The frontend PR and backend PR must reference the same contract file.

---

## Every Endpoint Ships With a Test

**Every route registered in `routes/api.php` must have a corresponding integration (feature) test.** No endpoint is considered complete without one.

- Test file mirrors the controller path under `tests/Feature/Api/V1/...`
- Minimum coverage per endpoint: happy path, auth (401), authorization (403 if applicable), validation (422), domain error statuses, and Arabic locale (`Accept-Language: ar`).
- Index endpoints must additionally assert that `filters[...]`, `sort`, and `per_page` actually change the returned set — not just return 200.

See the **Testing** section in [architecture.md](./architecture.md) for the full template, rules, and file-layout conventions.

---

## Throttle Groups

| Group              | Use for                                    |
| ------------------ | ------------------------------------------ |
| `throttle:auth`    | `/auth/*` (register, login, forgot, reset) |
| `throttle:otp`     | OTP send/verify endpoints                  |
| `throttle:upload`  | File/image upload endpoints                |
| `throttle:checkout`| Order/payment write endpoints              |

Attach these only on the sub-groups that need them — not globally.

---

## Listing Endpoints — Search, Filter, Sort

**Rule**: A resource has **one** index endpoint. All search, filtering, sorting, and pagination must flow through query parameters on that same endpoint. **Never** create sub-endpoints like `/users/filter`, `/users/featured`, `/users/search`, or `/events/upcoming`.

### Correct

```
GET /v1/users?search=ali&filters[is_featured]=1&filters[role]=vendor&sort=-created_at&page=2&per_page=20
GET /v1/events?search=jeddah&filters[venue_id]=3&filters[is_published]=1&sort=starts_at
```

### Wrong — do not do this

```
GET /v1/users/featured            ❌ use filters[is_featured]=1
GET /v1/users/search?q=ali        ❌ use ?search=ali on /users
GET /v1/events/upcoming           ❌ use filters[starts_after]=now on /events
GET /v1/events/by-venue/3         ❌ use filters[venue_id]=3 on /events
```

### Query Parameter Conventions

| Param                 | Purpose                                       | Example                                        |
| --------------------- | --------------------------------------------- | ---------------------------------------------- |
| `search`              | Free-text search across pre-defined columns   | `?search=ali`                                  |
| `filters[field]`      | Exact-match / boolean / FK filters (array)    | `?filters[is_featured]=1&filters[role]=vendor` |
| `filters[field][op]`  | Comparison operators when needed              | `?filters[price][gte]=100&filters[price][lte]=500` |
| `sort`                | Sort column; prefix with `-` for descending   | `?sort=-created_at`                            |
| `page`                | Page number (1-indexed)                       | `?page=2`                                      |
| `per_page`            | Page size (cap at a sane max, e.g. 100)       | `?per_page=20`                                 |
| `include`             | Eager-loaded relations (comma-separated)      | `?include=venue,organizer`                     |

### Implementation Notes

- Validate all query params in the index's **Form Request** (e.g. `IndexUsersRequest`). Whitelist allowed `filters[...]` keys and allowed `sort` columns — never pass raw input into the query builder.
- Build the query inside the **Repository** using a consistent pattern (e.g. Spatie QueryBuilder, or hand-rolled `when(...)` chains). Keep filter/sort logic out of Controllers and Services.
- The Service receives a DTO (e.g. `IndexUsersDTO`) carrying the validated filters/sort/pagination. The Controller stays thin.
- Responses use `Resource::collection($paginator)` to preserve Laravel pagination metadata (`links`, `meta`).
- If a "virtual" view is genuinely needed for UX (e.g. a "Featured" homepage slider), the frontend hits the same endpoint with the appropriate filter — the backend does **not** expose a separate route.
