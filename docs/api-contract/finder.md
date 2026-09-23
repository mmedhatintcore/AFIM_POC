# "Find your service" Finder

A client-side wizard (no submission is stored — the frontend computes the
recommendation locally). Questions, their answer options, and each option's
points toward a recommendation are fully admin-managed under **Find
Services → Questions** in Filament — add, edit, reorder, or delete any
question or option freely; nothing is hard-coded to a fixed 3-question shape.

## `GET /api/v1/finder/questions`

Ordered by `sort`.

```json
{
  "data": [
    {
      "id": 1,
      "key": "persona",
      "question": "What best describes you?",
      "options": [
        {
          "index": 0,
          "icon": "user",
          "label": "An individual investor",
          "description": "Saving or growing personal wealth",
          "votes": { "portfolio": 0, "liquidity": 0, "subscription": 0, "funds": 0 }
        }
      ]
    }
  ]
}
```

`key` is just an optional admin-facing label (not unique, not required) —
purely for telling rows apart in the list; nothing reads it. `votes` **is**
exposed to the client (unlike the Survey's vote weights) because the
recommendation is computed client-side, not server-side.

## Result routing

`frontend/src/lib/constants/finder.ts` sums each answered option's `votes`
per recommendation key across every question the visitor answered, and
returns whichever of `subscription` | `liquidity` | `portfolio` | `funds` has
the highest total (defaults to `funds` if every total is 0 — e.g. a visitor
who only picked purely-informational options). This works for any number of
questions/options, so admin edits can never desync it from a hard-coded
decision tree the way the original implementation could have.

The result's name/description come from the matching `Service` record
(`GET /v1/services`, matched on `Service.key`) for `subscription` /
`liquidity` / `portfolio`, or from the `Finder.results.funds` translation key
for `funds` (no backing Service record for that one).
