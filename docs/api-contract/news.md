# News

## `GET /api/v1/news`

Query params: `filters[type]=press|media|social`, `search=…`, `page`, `per_page`
(default 9, max 50), `sort` (default `-published_at`). **Paginated.**

```json
{
  "data": [
    {
      "id": 1,
      "slug": "aum-surpasses-egp-93-billion",
      "type": "press",
      "type_label": "Press",
      "source": "AFIM Press Release",
      "title": "AUM surpasses EGP 93 billion",
      "excerpt": "Assets under management exceeded EGP 93bn…",
      "image_url": null,
      "published_at": "2025-12-15T00:00:00.000000Z"
    }
  ],
  "links": { "first": "…", "last": "…", "prev": null, "next": "…" },
  "meta": { "current_page": 1, "per_page": 9, "total": 8, "last_page": 1, "from": 1, "to": 8 }
}
```

`image_url` (nullable, absolute URL on the `public` disk —
`{APP_URL}/storage/news/…`) is an optional thumbnail, uploaded in Filament.
`null` when no image was uploaded.

## `GET /api/v1/news/{slug}`

Adds `body` (full localized article text) and `related` (up to 3 posts of the
same type, list shape, including `image_url`). 404 when unknown or unpublished.
