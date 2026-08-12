# About — Timeline, Team, Committees

## `GET /api/v1/timeline-milestones`

```json
{ "data": [ { "id": 1, "year": "1994", "title": "First in Egypt", "body": "Established as the first…" } ] }
```

Ordered by `sort`.

## `GET /api/v1/team-members`

Query params: `filters[group]=board|leadership`. Ordered by `sort`.

```json
{ "data": [ { "id": 1, "group": "board", "name": "Karim Abou El Naga", "role": "Chairman (Non-Executive)" } ] }
```

## `GET /api/v1/committees`

```json
{ "data": [ { "id": 1, "name": "Audit Committee", "responsibilities": ["Reviews financial reporting integrity", "…"] } ] }
```
