# About — Timeline, Team, Committees

## `GET /api/v1/timeline-milestones`

```json
{ "data": [ { "id": 1, "year": "1994", "title": "First in Egypt", "body": "Established as the first…" } ] }
```

Ordered by `sort`.

## `GET /api/v1/team-members`

Query params: `filters[group]=board|leadership`. Ordered by `sort`.

```json
{ "data": [ { "id": 1, "group": "board", "name": "Karim Abou El Naga", "role": "Chairman (Non-Executive)", "bio": null, "photo_url": null } ] }
```

`bio` (localized, nullable) and `photo_url` (nullable, absolute URL on the
`public` disk — `{APP_URL}/storage/team/…`) are optional and admin-editable in
Filament; a member with no uploaded photo has `photo_url: null` and the
frontend falls back to initials.

## `GET /api/v1/committees`

```json
{
  "data": [
    {
      "id": 1,
      "name": "Audit Committee",
      "mission": "Oversees the integrity of financial reporting, internal controls, and the internal and external audit functions.",
      "members": [ { "name": "Independent Director", "role": "Chair" } ],
      "responsibilities": ["Reviews financial reporting integrity", "…"]
    }
  ]
}
```

`mission` (localized, nullable) and `members[]` (`{name, role}`, both
localized) are admin-editable in Filament alongside the existing
`responsibilities[]`. The About page now displays `mission` + `members`
instead of the raw `responsibilities` bullet list (the column is kept for
backward compatibility and remains editable in Filament).
