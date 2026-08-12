# Sections

CMS-managed singleton content blocks. Every home/about/footer surface that is not
a repeatable entity lives here. Managed in Filament → Content → Sections.

## `GET /api/v1/sections`

Returns **all enabled** sections keyed by `key`. No pagination (bounded set).

```json
{
  "data": [
    { "key": "hero", "is_enabled": true, "title": "Egypt's first asset manager.", "subtitle": "Three decades of trust.", "body": "AFIM manages institutional and individual wealth…", "items": [...], "cta": {...}, "extra": {...} }
  ]
}
```

`GET /api/v1/sections/{key}` returns a single section (404 if unknown/disabled).

All of `title`, `subtitle`, `body` and every `title`/`text`/`label` inside
`items`/`cta` are localized by `Accept-Language`.

## Per-key shapes

| key | title/subtitle/body | items[] | cta | extra |
| --- | --- | --- | --- | --- |
| `announcement` | body = banner text | — | `{label, href}` = "Read more" link | — |
| `ticker` | — | `{text}` highlight chips | — | — |
| `hero` | title, subtitle (accent line), body = lead | live-NAV pulse chips `{title, value, trend: "up"\|"down"\|null}` | primary + secondary | `{eyebrow}` |
| `advisor` | title (may contain `<span class=\"accent\">`), body = lead | chips `{icon, text}` | primary ("Find my fund") + secondary | `{note, preview: {...}}` |
| `trust` | title = strip label | `{text}` partner names | — | — |
| `goals` | title, subtitle | `{icon, title, text, link_label, href}` | — | — |
| `prices_intro` | title, subtitle | — | — | `{live_label, disclaimer}` |
| `services_intro` | title, subtitle | — | — | — |
| `why` | title, body | `{title, text}` numbered features | — | `{kicker}` |
| `figures` | — | `{value, suffix, label, decimals}` stat rows; first item is the hero stat with `unit` | — | `{kicker}` |
| `steps` | title | `{title, text}` 3 steps | `{label, href}` | — |
| `cta` | title, body | — | primary + secondary (`mailto:`) | — |
| `about_brief` | title, body (two paragraphs joined by `\n\n`) | vision/mission/values `{icon, title, text}` | — | — |
| `footer` | — | office lines `{text}` + social `{text, href}` (grouped via `extra`) | — | `{office_title, services_title, follow_title, phone, email, copyright}` |
| `news_intro` | title, subtitle | — | — | — |
| `faqs_intro` | title, subtitle | — | — | — |
| `survey_intro` | title, subtitle/body | — | — | `{note}` |
