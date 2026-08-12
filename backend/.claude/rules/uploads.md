# Uploads — two-step pattern

All file uploads across the platform follow the same shape: **one upload
endpoint** stages the file, **resource endpoints stay JSON-only** and
consume the upload by URL.

## The flow

```
client                            server
  │                                  │
  │ POST /api/v1/upload  (multipart) │
  │  ─ file: <binary>                │
  │ ─────────────────────────────▶   │  → store under uploads/{uuid}/
  │                                  │
  │ ◀───────────────────────────── { data: { url, name, file_name, mime_type, size } }
  │                                  │
  │                                  │
  │ POST /api/v1/<resource>          │
  │   { url: "<from step 1>" , … }   │
  │ ─────────────────────────────▶   │  → validate URL is local
  │                                  │  → addMedia(absolutePath)->toMediaCollection(…)
  │                                  │  → delete temp file
  │                                  │
  │ ◀───────────────────────────── { data: { …updated resource… } }
```

The upload step always returns a **MediaResource-shaped** payload:
`url`, `name`, `file_name`, `mime_type`, `size`.

The resource step always takes a **plain JSON body** with the URL as a
string. No multipart, no separate `Content-Type: multipart/form-data`,
no per-resource upload variants.

## Why this shape

- **Forms compose cleanly.** A profile form with an avatar plus six
  text fields submits *once* as JSON; the avatar is uploaded ahead of
  submit, in parallel with the user typing the rest of the form, and
  the URL is just one more field in the payload.
- **Preview is free.** The URL returned by `/upload` is an immediate
  `<img src>` candidate — no extra round-trip needed to render a
  preview before submit.
- **One contract per channel.** Resource endpoints don't grow a
  multipart variant alongside their JSON variant. If a flow accepts
  files, it accepts them by URL.
- **Same rules apply to every resource.** Mime allowlist, size cap,
  auth, and rate limits live in one place — `UploadController` /
  `UploadMediaRequest` — instead of being re-derived per consumer.

## Endpoints that follow this pattern

| Resource | Endpoint | URL field |
| --- | --- | --- |
| User avatar | `POST /api/v1/auth/me/avatar` | `url` |
| _(future)_ Listing cover | `POST /api/v1/listings` | `cover_url` |
| _(future)_ Listing gallery | `POST /api/v1/listings/{id}/gallery` | `urls[]` |
| _(future)_ Vendor docs | `POST /api/v1/vendors/me/documents` | `cr_certificate_url`, `vat_certificate_url`, `national_id_doc_url` |
| _(future)_ Reviews with photos | `POST /api/v1/listings/{id}/reviews` | `photo_urls[]` |

Add a row when you ship a new resource that takes files.

## Rules of the road

### 1. Upload endpoint MUST require sanctum auth

Public uploads invite spam. Anonymous users have no business writing
to disk.

### 2. Resource endpoints MUST validate the URL is local

This is the SSRF guard. Without it, `{ url: "http://internal-svc:8500/.." }`
would have the server happily fetch and copy whatever lives there.

Use `App\Support\Uploads\UploadUrlResolver::toRelativePath($url)` —
returns the relative path on the public disk if the URL belongs to us
and points inside `uploads/`, or `null` otherwise. Reject `null` with
422 + a `url` field error.

### 3. Resource endpoints MUST consume locally, not refetch via HTTP

The temp file is already on our disk. `Storage::disk('public')->path($relative)`
gives an absolute path, hand it to `$model->addMedia($absolute)->toMediaCollection(…)`.
Don't introduce an HTTP round-trip back to ourselves to download
content we already have.

### 4. Resource endpoints MUST delete the temp file after consume

```php
Storage::disk('public')->delete($relative);
```

Wrap in try/catch and log on failure — the resource is already
attached at this point so a failed cleanup shouldn't 500 the request,
but unattended temp files snowball over time.

### 5. Schedule a janitor for abandoned uploads

Users sometimes pick a file but never submit. A scheduled command
should sweep `storage/app/public/uploads/*` older than 24h on a daily
cron. (TODO: ship `php artisan uploads:purge-stale` and wire into
`bootstrap/app.php`'s `withSchedule()`.)

### 6. Tighter limits live on the consuming resource

`/upload` enforces broad caps (jpeg/png/webp/gif/pdf up to 10 MB; video mp4/mov/webm up to 25 MB) so it can
serve every consumer. The avatar endpoint enforces tighter ones —
e.g. only square-friendly aspects, only image mime types, 4 MB cap —
either via its own validation rules or by checking the
`UploadMediaRequest` response shape. Don't loosen `/upload`'s
allowlist for one consumer; tighten in the consumer instead.

## What NOT to do

- ❌ Add a multipart variant to a resource endpoint — see "one
  contract per channel" above. Anyone tempted to send `multipart` to
  `POST /listings` should be sending it to `POST /upload` first.
- ❌ Validate the URL with `url:` alone and skip the host check. The
  validator only confirms parseable shape, not provenance — SSRF
  ignores you.
- ❌ Refetch the URL via Guzzle inside the resource endpoint. The file
  is already on the public disk; refetching means leaving a temp file
  that won't get cleaned up + an unnecessary network hop +
  reintroducing the SSRF surface area.
- ❌ Reuse the same `/upload` URL across multiple resources without
  re-uploading. The temp file is deleted after first consume; the
  second consumer gets a 422 because the file no longer exists.
  This is intentional — uploads are single-shot tokens.

## Implementation surface

- `app/Http/Controllers/Api/V1/UploadController.php` — the upload endpoint.
- `app/Http/Requests/V1/UploadMediaRequest.php` — mime + size rules.
- `app/Support/Uploads/UploadUrlResolver.php` — host check + relative-path resolution.
- `tests/Feature/Api/V1/UploadControllerTest.php` — endpoint coverage.
- Resource endpoint examples: `app/Http/Controllers/Api/V1/Auth/AvatarController.php`,
  `tests/Feature/Auth/AvatarControllerTest.php`.

## Frontend mirror

The frontend calls these in sequence — see `frontend/src/lib/api/uploads.ts`
(`uploadFile(file)`) and the resource-specific helpers in
`frontend/src/lib/api/auth.ts` (`uploadAvatar(file)` chains them). New
form components that take files should:

1. Call `uploadFile(selected)` on file pick → store the returned URL in
   form state.
2. Show the URL as an inline `<img src>` preview immediately.
3. Submit the form with the URL alongside other fields — single JSON
   request.
