# Postman Collection Sync

The repo's Postman collection lives at `docs/postman/AFIM.postman_collection.json` and auto-syncs to the team's Postman cloud workspace via a post-commit hook (see `docs/postman/README.md`). The JSON is the source of truth — keeping it accurate keeps the cloud workspace accurate.

## When to update the collection

Update the JSON in the **same slice** as the route change. A backend PR that adds, modifies, or removes a route is incomplete without a matching collection update.

| Backend change                            | Collection update required                                                |
| ----------------------------------------- | ------------------------------------------------------------------------- |
| New route added in `routes/api.php`       | Add a request entry (method, URL, headers, body, tests, example response) |
| Route URL or HTTP method changed          | Update the existing request — never duplicate                             |
| Form Request rules changed                | Update request body example + the validation example response             |
| Response envelope shape changed           | Update saved example responses (en + ar where applicable)                 |
| Route deleted                             | Remove the request from the collection                                    |
| Throttle group / auth requirement changed | Update headers and any 401/403/429 example responses                      |

## How to update via the Postman MCP

The `.mcp.json` registers `https://mcp.postman.com/mcp`. With the MCP loaded:

1. Make the route/Form Request/Resource change.
2. Ask: _"Add the new `POST /api/v1/<resource>/<action>` request to the Postman collection. Validation rules: …; success envelope: …; example responses for 200, 401, 422."_
3. Verify the JSON diff before committing.
4. Commit — the post-commit hook pushes to Postman cloud automatically.

## How to update by hand

Edit `docs/postman/AFIM.postman_collection.json` directly (Postman v2.1 schema). Mirror the structure of an existing request in the same folder. Conventions:

- URL uses `{{base_url}}/api/v1/...`
- `X-API-Key: {{api_key}}` is inherited from the collection auth — don't repeat it per request
- `Accept-Language` defaults to `en`; add an `ar` example for any translatable endpoint
- Tests assert: status, envelope shape (`data` / `message`), pagination keys (`links`, `meta`) for index endpoints, field-level invariants

## What NOT to do

- **Don't run `scripts/postman-sync.sh` yourself.** The post-commit hook fires it after the user's commit lands. If you push from inside a task, you'll publish half-finished work to the shared workspace, and the user can't review the diff before it propagates. Edit the JSON, stop there, let the user commit.
- **Don't call Postman API write tools via the MCP to push collection changes mid-task** for the same reason. Read tools (list workspaces, fetch a collection for diffing) are fine; writes wait for commit.
- **Don't export from Postman over the JSON.** Postman's export reorders nodes and rewrites IDs, producing huge noisy diffs. Edit the JSON directly, then let the post-commit hook push your version up.
- **Don't skip the collection update.** A "follow-up PR for Postman" almost never lands. Keep them together.
- **Don't add ad-hoc filter/search variants as separate requests** when a single request with query params demonstrates the same surface. Use Postman's request-level examples for distinct response shapes.
- **Don't update the Postman JSON without also updating `docs/api-contract/<resource>.md`.** The markdown contract is the source of truth that the frontend reads; the Postman collection is the executable mirror. They must agree.
