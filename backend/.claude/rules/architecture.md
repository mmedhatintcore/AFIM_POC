# Architecture & Layering

**Scope**: Laravel 12+ backend for the Sight Scape event ticketing platform.

- **REST APIs** (Sanctum auth) consumed by the Next.js frontend
- **Filament 5 admin dashboard** for platform management

**Languages Supported**: Arabic (ar) & English (en) — RTL support required on the frontend, translatable content on the backend.

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

## Backend Flow

```
Route (api.php) → Controller → DTO → Service → Repository (IF + Eloquent) → API Resource → DataResponse/ErrorResponse
```

**Assistant behavior:**

- Keep controllers thin (validate → DTO → service → response)
- Business logic only in services/repositories
- Use Form Requests for validation; never trust raw input
- Transactions for multi-write operations
- Map domain exceptions to HTTP (see `error-handling.md`)
- Use Spatie Translatable for all translatable fields (see `spatie-translatable.md`)
- Use Spatie Media Library for all file uploads (see `spatie-media.md`)
- Use `MediaResource` for image responses (never raw URL strings)

---

## How Claude should behave

- Prefer **Laravel 12+** conventions, **PHP 8.3+** features (typed properties, return types, enums, `readonly` where sensible), and **PSR-12** style
- Follow the **thin controller** rule: Controllers only validate, construct DTOs, call Services, and return Response wrappers
- **Never** put business logic in Controllers, Form Requests, or API Resources. Keep heavy queries in Repositories
- Use **Form Request** classes for validation. **Never** use `$request->all()` or trust raw input
- Use our wrappers: `DataResponse($data, ?$message)` on success, `ErrorResponse($message, $errors, $status)` on failures
- For authentication use **Sanctum** with the `api` guard. Create tokens in Services via `$user->createToken('auth_token')->plainTextToken`
- For API output use **Resource** classes; collections via `Resource::collection($paginator)`
- Prefer **transactions** (`DB::transaction`) when a use case writes to multiple tables
- **Exceptions → HTTP**: map domain exceptions to appropriate HTTP codes (see `error-handling.md`). Bubble unexpected errors as 500 with a generic message
- Use `app('custom.logger')->error(__METHOD__, $exception)` for error logging
- In Filament, keep **Resources** focused (see `filament.md`)

---

## Folder & Layering Conventions

```
app/
  DTOs/
    Common/AbstractDTO.php
    V1/Event/StoreEventDTO.php
    V1/Event/UpdateEventDTO.php
    V1/Ticket/...
    ...
  Services/
    Event/EventService.php
    Ticket/TicketService.php
    Order/OrderService.php
    User/Auth/UserService.php
  Repositories/
    Interfaces/EventRepositoryInterface.php
    EventRepository.php
  Http/
    Controllers/Api/V1/...
    Requests/V1/...
    Resources/... (API Resources)
    Resources/MediaResource.php
    Responses/DataResponse.php
    Responses/ErrorResponse.php
  Models/
    Event.php
    Ticket.php
    Order.php
    Venue.php
    User.php
  Filament/
    Resources/...
    Pages/...
routes/
  api.php
lang/
  en/
  ar/
```

---

## Naming Conventions

- **Models** — singular PascalCase (`Event`, `TicketTier`, `Order`)
- **Tables** — snake_case plural (`events`, `ticket_tiers`, `orders`)
- **Controllers** — `{Resource}Controller` (`EventController`)
- **Form Requests** — `{Action}{Resource}Request` (`StoreEventRequest`, `IndexUsersRequest`)
- **DTOs** — `{Action}{Resource}DTO` (`StoreEventDTO`, `UpdateEventDTO`, `IndexUsersDTO`)
- **Services** — `{Resource}Service` (`EventService`)
- **Repositories** — `{Resource}Repository` + `{Resource}RepositoryInterface`
- **Resources** — `{Resource}Resource` (`EventResource`); never append `Collection` — use `Resource::collection($paginator)`
- **Exceptions** — `{Reason}Exception` (`EventSoldOutException`, `InvalidCredentialsException`)

---

## Repository Pattern

Always define an interface and bind the implementation in a Service Provider (`AppServiceProvider` or a dedicated `RepositoryServiceProvider`).

```php
namespace App\Repositories\Interfaces;

use App\DTOs\V1\Event\StoreEventDTO;
use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface
{
    public function paginate(array $filters, string $sort, int $perPage): LengthAwarePaginator;
    public function findOrFail(int $id): Event;
    public function create(StoreEventDTO $dto): Event;
    public function update(Event $event, array $attributes): Event;
    public function delete(Event $event): bool;
}
```

Keep heavy queries here — never in Controllers, Services, or Resources. Repositories return models/collections/paginators, not arrays.

---

## Service Pattern

```php
namespace App\Services\Event;

use App\DTOs\V1\Event\StoreEventDTO;
use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class EventService
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
    ) {}

    public function create(StoreEventDTO $dto): Event
    {
        return DB::transaction(function () use ($dto) {
            $event = $this->events->create($dto);
            // emit events, enqueue jobs, touch related tables, etc.
            return $event->load('venue');
        });
    }
}
```

Services orchestrate; they call repositories, dispatch events/jobs, and enforce invariants. They never touch `Request` or build `JsonResponse`.

---

## AbstractDTO

All DTOs extend `App\DTOs\Common\AbstractDTO`. The base class accepts an array in its constructor and calls the concrete `map()` method. DTOs are immutable value objects — only getters, no setters.

```php
namespace App\DTOs\Common;

abstract class AbstractDTO
{
    public function __construct(array $data)
    {
        $this->map($data);
    }

    abstract protected function map(array $data): bool;
}
```

---

## Response Wrappers — Shape

Use `DataResponse` / `ErrorResponse` everywhere. They produce a consistent envelope:

- **Success** — `{ "data": ..., "message": "..." }` (message optional)
- **Error**   — `{ "message": "...", "errors": { ... } }` with the appropriate HTTP status

Frontend relies on this shape. Do not return raw arrays or bare `response()->json(...)` from Controllers.

---

## N+1 & Eager Loading

- **Always** eager-load relations used by the API Resource: `$query->with(['venue', 'organizer', 'media'])`.
- For media, load `'media'` (the Spatie relation) so `whenLoaded('media', ...)` inside `MediaResource` hits cache.
- Never loop over a collection and call a relation accessor inside the loop.
- In Filament tables, override `getEloquentQuery()` to add eager loads — tables are a common N+1 source.

---

## Controller Pattern

```php
final class EventController extends Controller
{
    public function __construct(private readonly EventService $service) {}

    public function store(StoreEventRequest $request): JsonResponse
    {
        try {
            $dto = new StoreEventDTO($request->validated());
            $event = $this->service->create($dto);
            return (new DataResponse(new EventResource($event), __('messages.created')))->toJson();
        } catch (CustomException $e) {
            app('custom.logger')->error(__METHOD__, $e);
            return (new ErrorResponse($e->getMessage(), [], Response::HTTP_BAD_REQUEST))->toJson();
        } catch (\Throwable $e) {
            app('custom.logger')->error(__METHOD__, $e);
            return (new ErrorResponse(__('messages.something_went_wrong')))->toJson();
        }
    }
}
```

---

## DTO Pattern

```php
namespace App\DTOs\V1\Event;

use App\DTOs\Common\AbstractDTO;

final class StoreEventDTO extends AbstractDTO
{
    protected string $titleEn;
    protected string $titleAr;
    protected ?string $descriptionEn = null;
    protected ?string $descriptionAr = null;
    protected string $startsAt;
    protected string $endsAt;
    protected int $venueId;

    protected function map(array $data): bool
    {
        $this->titleEn = $data['title_en'];
        $this->titleAr = $data['title_ar'];
        $this->descriptionEn = $data['description_en'] ?? null;
        $this->descriptionAr = $data['description_ar'] ?? null;
        $this->startsAt = $data['starts_at'];
        $this->endsAt = $data['ends_at'];
        $this->venueId = $data['venue_id'];
        return true;
    }

    public function getTitleEn(): string { return $this->titleEn; }
    public function getTitleAr(): string { return $this->titleAr; }
    public function getDescriptionEn(): ?string { return $this->descriptionEn; }
    public function getDescriptionAr(): ?string { return $this->descriptionAr; }
    public function getStartsAt(): string { return $this->startsAt; }
    public function getEndsAt(): string { return $this->endsAt; }
    public function getVenueId(): int { return $this->venueId; }

    public function toTranslatableArray(): array
    {
        return [
            'title' => ['en' => $this->titleEn, 'ar' => $this->titleAr],
            'description' => ['en' => $this->descriptionEn, 'ar' => $this->descriptionAr],
        ];
    }
}
```

---

## Laravel 12 Model Scopes

Use `#[Scope]` attribute instead of `scope` prefix:

```php
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    #[Scope]
    public function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    public function upcoming(Builder $query): void
    {
        $query->where('starts_at', '>', now());
    }

    #[Scope]
    public function byVenue(Builder $query, int $venueId): void
    {
        $query->where('venue_id', $venueId);
    }
}

// Usage
Event::published()->upcoming()->get();
Event::byVenue(1)->get();
```

---

## Claude Chat – Ready Prompts

- **Scaffold a new module**

  > Create endpoints for `Thing` (index, store, show, update, destroy). Use Route→Controller→DTO→Service→Repository→Resource. Include Spatie Translatable for name/description fields. Generate Form Requests, DTOs, interfaces, implementations, and a Filament Resource with SpatieMediaLibrary. Apply sanctum `auth:api` to write endpoints.

- **Add translatable entity**

  > Create a new `Category` model with translatable `name` and `description` fields using Spatie Translatable. Include Spatie Media Library for icon. Generate migration, model, repository, service, API resource with MediaResource for images, and Filament resource with translation tabs.

- **Add event-specific module**

  > Create the `TicketTier` module for Sight Scape. A TicketTier belongs to an Event and has translatable `name` and `description`, plus `price_cents`, `quantity`, `sale_starts_at`, `sale_ends_at`. Follow the full stack: migration, model with HasTranslations, repository interface + implementation, service with transactions, DTO, API resource, Form Requests, controller with error handling, routes, and Filament resource.

---

## Checklist for New Features

- [ ] Migration with proper indexes (foreign keys, `WHERE`/`ORDER BY` columns)
- [ ] Model with `HasTranslations` trait (if translatable)
- [ ] Model with `HasMedia` trait (if media needed)
- [ ] Model with `#[Scope]` attributes for common queries
- [] Repository Interface + Implementation
- [ ] Service with transactions for multi-write operations
- [ ] DTO with translation methods (where applicable)
- [ ] API Resource with `MediaResource` for images
- [ ] Form Request with validation (never trust raw input)
- [ ] Controller with error handling (try/catch + `custom.logger`)
- [ ] Routes with appropriate middleware and throttle groups
- [ ] Filament Resource with table defined in ListPage
- [ ] Translation files (en/ar) for messages
- [ ] **Integration (feature) test for every endpoint** — see "Testing" below
- [ ] Unit tests for Service / Repository logic where meaningful

---

## Testing

**Every API endpoint must ship with an integration (feature) test.** A new endpoint without a corresponding test file is not considered complete, regardless of how simple it looks. This is non-negotiable.

### File layout

Mirror the controller path under `tests/Feature/`:

```
app/Http/Controllers/Api/V1/EventController.php
  → tests/Feature/Api/V1/EventControllerTest.php

app/Http/Controllers/Api/V1/Auth/AuthController.php
  → tests/Feature/Api/V1/Auth/AuthControllerTest.php
```

One test file per controller. One test method per endpoint path + status-code combination.

### Required coverage per endpoint

For each route the test file must cover, at minimum:

1. **Happy path** — correct status code, response envelope shape (`data` / `message` / pagination `links` + `meta`), and representative field values.
2. **Authentication** — a 401 test for protected routes when no token is sent.
3. **Authorization** — a 403 test when the authenticated user lacks the policy ability (if the route has a policy).
4. **Validation (422)** — at least one failing-field test per Form Request (missing required, wrong type, over max length).
5. **Domain errors** — map each thrown exception from the Service to its expected HTTP status (e.g. 404, 409).
6. **Translation** — send `Accept-Language: ar` and assert the response text comes back in Arabic for at least one translatable endpoint per module.
7. **Pagination / filters / sort** — for index endpoints, assert `filters[...]`, `sort`, and `per_page` actually affect the result set (not just return 200).

### Template

```php
namespace Tests\Feature\Api\V1;

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

final class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_events(): void
    {
        Event::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/events');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'title', 'starts_at']],
                'links',
                'meta' => ['current_page', 'per_page', 'total'],
            ]);
    }

    public function test_index_filters_by_venue(): void
    {
        Event::factory()->create(['venue_id' => 1]);
        Event::factory()->create(['venue_id' => 2]);

        $response = $this->getJson('/api/v1/events?filters[venue_id]=1');

        $response->assertOk()->assertJsonCount(1, 'data');
    }

    public function test_store_requires_authentication(): void
    {
        $this->postJson('/api/v1/events', [])->assertUnauthorized();
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/events', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['title_en', 'title_ar', 'starts_at']);
    }

    public function test_store_returns_arabic_message_when_requested(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('/api/v1/events', [/* valid payload */]);

        $response->assertCreated()
            ->assertJsonPath('message', __('messages.created', [], 'ar'));
    }
}
```

### Rules

- Use **`RefreshDatabase`** — no shared state between tests.
- Use **factories** for fixtures (`User::factory()`, `Event::factory()`) — never hand-craft arrays in assertions.
- For endpoints that touch media, use `Storage::fake('public')` / `UploadedFile::fake()->image(...)` — never hit the real disk.
- For endpoints that dispatch queued jobs/events, use `Bus::fake()` / `Event::fake()` and assert dispatch.
- Do **not** mock Repositories in feature tests — feature tests exercise the real stack end-to-end. Mocking belongs in unit tests only.
- Run the suite on every PR: `php artisan test --parallel`. A failing or missing endpoint test blocks merge.

### Unit tests (optional but encouraged)

Add `tests/Unit/Services/{Resource}ServiceTest.php` when a Service contains non-trivial logic (branching, pricing math, state transitions). Mock the Repository interface here. Feature tests stay as the source of truth for HTTP contract.

---

## Fin

If any ambiguity arises, prefer the patterns and templates in these rule files. Keep controllers lean, services orchestrated, repositories focused, and resources clean. Always use `MediaResource` for images and proper translations for multi-language support.
