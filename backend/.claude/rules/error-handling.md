# Error Handling & HTTP Status Mapping

Map domain exceptions to appropriate HTTP codes. Bubble unexpected errors as 500 with a generic message. Log everything via `app('custom.logger')->error(__METHOD__, $exception)`.

## Exception → HTTP Table

| Exception                          | HTTP | Message key (suggested)      |
| ---------------------------------- | ---- | ---------------------------- |
| AuthenticationException            | 401  | `unauthenticated`            |
| InvalidCredentialsException        | 401  | `invalid_credentials`        |
| WrongPasswordException             | 401  | `wrong_password`             |
| TokenNotFoundException             | 401  | `invalid_or_missing_token`   |
| TokenExpiredException              | 401  | `token_expired`              |
| AuthorizationException             | 403  | `forbidden`                  |
| UserSuspendedException             | 403  | `user_suspended`             |
| InvalidProfileException            | 403  | `invalid_profile_access`     |
| InvalidUserException               | 404  | `user_not_found`             |
| ModelNotFoundException             | 404  | `resource_not_found`         |
| EventNotFoundException             | 404  | `event_not_found`            |
| TicketNotAvailableException        | 409  | `ticket_not_available`       |
| EventSoldOutException              | 409  | `event_sold_out`             |
| DifferentSocialMethodException     | 409  | `different_social_method`    |
| CannotUseOldPasswordException      | 409  | `password_reuse_not_allowed` |
| InvalidTokenTypeException          | 422  | `invalid_token_type`         |
| CannotResetPasswordException       | 422  | `cannot_reset_password`      |
| ValidationException (Form Request) | 422  | validation errors payload    |
| ThrottleRequestsException          | 429  | `too_many_requests`          |
| CustomException (generic domain)   | 400  | exception message            |
| Other Throwables                   | 500  | `something_went_wrong`       |

---

## Controller Error-Handling Template

Every Controller method that calls a Service should wrap the call in `try/catch` and produce an `ErrorResponse`:

```php
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
```

---

## Rules

- **Never** leak internal exception messages for 500s. Use `__('messages.something_went_wrong')`.
- **Always** log via `app('custom.logger')->error(__METHOD__, $exception)` — this captures method context for debugging.
- Translation keys live in `lang/en/messages.php` and `lang/ar/messages.php`. Keep both in sync.
- Form Request validation failures are handled by Laravel automatically (422 with errors payload) — do not catch `ValidationException` in the controller.

---

## Custom Exception Pattern

Custom domain exceptions live under `app/Exceptions/Domain/` and extend the shared `CustomException` base (which the controller `try/catch` maps to 400 by default). Override the message key — never hard-code user-facing strings.

```php
namespace App\Exceptions\Domain;

use App\Exceptions\CustomException;

final class EventSoldOutException extends CustomException
{
    public function __construct()
    {
        parent::__construct(__('messages.event_sold_out'));
    }
}
```

Throw them from Services, not Controllers:

```php
if ($event->available_tickets <= 0) {
    throw new EventSoldOutException();
}
```

For exceptions that must return a status other than 400 (e.g. 409 for conflicts), add an explicit `catch` branch in the controller, or wire a dedicated render method in `app/Exceptions/Handler.php`.

---

## Global Exception Handler

`app/Exceptions/Handler.php` is the last line of defense for exceptions that escape a controller `try/catch`. Use it to:

- Render `ModelNotFoundException` / `NotFoundHttpException` as `ErrorResponse(404, 'resource_not_found')`
- Render `AuthenticationException` as `ErrorResponse(401, 'unauthenticated')`
- Render `AuthorizationException` as `ErrorResponse(403, 'forbidden')`
- Render `ThrottleRequestsException` as `ErrorResponse(429, 'too_many_requests')`
- Log the 500s and return the generic message

The controller `try/catch` is for expected domain flow; the Handler is for everything else.

---

## Validation Error Shape (422)

Laravel's default `ValidationException` rendering produces:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title_en": ["The title en field is required."],
    "starts_at": ["The starts at must be a date after now."]
  }
}
```

This matches our `ErrorResponse` envelope — no overriding needed. Localize messages via `lang/{en,ar}/validation.php` and per-field labels via `lang/{en,ar}/validation.php` → `attributes`.

---

## Translation Key Convention

- All user-facing error messages live under a single `messages.*` key (e.g. `messages.event_sold_out`).
- Keep `lang/en/messages.php` and `lang/ar/messages.php` in lock-step — if a key exists in one, it must exist in the other.
- Reference keys via `__('messages.key')` — never construct strings inline.
- Avoid technical jargon in Arabic translations; defer to native-speaker review for any customer-visible message.
