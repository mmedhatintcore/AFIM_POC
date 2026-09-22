# "Find your service" Finder

A fixed 3-question client-side wizard (no submission is stored — the
frontend runs `recommend()` locally over the picked tags). Questions and
their answer options are admin-managed under **Find Services → Questions**
in Filament; the tag → result mapping itself is a fixed function in
`frontend/src/lib/constants/finder.ts` and is not admin-editable.

## `GET /api/v1/finder/questions`

Ordered by `sort`. Always exactly 3 questions (`key`: `q1`, `q2`, `q3`).

```json
{
  "data": [
    {
      "id": 1,
      "key": "q1",
      "question": "What best describes you?",
      "options": [
        { "tag": "ind", "icon": "user", "label": "An individual investor", "description": "Saving or growing personal wealth" }
      ]
    }
  ]
}
```

`tag` is the value the frontend's `recommend(answers: string[])` switches on
— unlike the Survey's vote weights, this **is** exposed to the client, since
scoring happens client-side here. Admins can edit `label`/`description`/`icon`
freely; changing `tag` breaks the recommendation for that option (the admin
form disables `key` and warns against editing `tag`, but doesn't hard-block
it — there is no server-side scoring to protect).

## Result routing (unchanged, not API-driven)

`recommend()` maps the 3 picked tags to one of `subscription` | `liquidity` |
`portfolio` | `funds`. The result name/description come from the matching
`Service` record (`GET /v1/services`) for the first three, or from the
`Finder.results.funds` translation key for `funds` (no backing Service).
