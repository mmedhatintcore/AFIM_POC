# AFIM API Contract — Index

Base URL: `/api/v1`. All endpoints are public reads unless marked otherwise.

## Common headers

| Header            | Values      | Notes                                             |
| ----------------- | ----------- | ------------------------------------------------- |
| `Accept-Language` | `en` / `ar` | Drives every translatable string in the response. |
| `Accept`          | `application/json` | Always.                                    |

## Response envelope

- **Success**: `{ "data": ..., "message"?: string }`
- **Paginated**: `{ "data": [...], "links": {...}, "meta": {...} }` (flat — never re-wrapped)
- **Error**: `{ "message": string, "errors"?: { field: string[] } }`

Dates are ISO 8601 UTC. Numbers that carry money/NAV are decimal strings with 2 fraction digits (`"142.83"`).

## Resources

| Resource | Endpoints | Contract file |
| --- | --- | --- |
| Sections (CMS blocks) | `GET /sections`, `GET /sections/{key}` | [sections.md](sections.md) |
| Services | `GET /services`, `GET /services/{slug}` | [services.md](services.md) |
| Funds | `GET /funds` (`filters[is_featured]`, `filters[group_key]`), `GET /funds/{slug}` | [funds.md](funds.md) |
| Fund categories | `GET /fund-categories` | [funds.md](funds.md) |
| News | `GET /news` (`filters[type]`, `search`, paginated), `GET /news/{slug}` | [news.md](news.md) |
| FAQs | `GET /faqs` | [faqs.md](faqs.md) |
| About | `GET /timeline-milestones`, `GET /team-members` (`filters[group]=board\|leadership`), `GET /committees` | [about.md](about.md) |
| Survey | `GET /survey/questions`, `POST /survey/submissions` | [survey.md](survey.md) |
| Contact | `POST /contact-messages` | [contact.md](contact.md) |

## Section keys (CMS singleton blocks)

`announcement`, `ticker`, `hero`, `advisor`, `trust`, `goals`, `prices_intro`,
`services_intro`, `why`, `figures`, `steps`, `cta`, `about_brief`, `footer`,
`news_intro`, `faqs_intro`, `survey_intro`.

Every section returns:

```json
{
  "key": "hero",
  "is_enabled": true,
  "title": "…localized…",
  "subtitle": "…",
  "body": "…",
  "items": [ { "icon": "…", "title": "…", "text": "…", "value": "…", "href": "…" } ],
  "cta": { "label": "…", "href": "…", "secondary_label": "…", "secondary_href": "…" },
  "extra": { }
}
```

`items` / `cta` / `extra` shapes per key are documented in [sections.md](sections.md).
