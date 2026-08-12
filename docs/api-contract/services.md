# Services

Licensed lines of business (Portfolio Management, Liquidity Management,
Promotion & Underwriting, Subscription & Redemption).

## `GET /api/v1/services`

```json
{
  "data": [
    {
      "id": 1,
      "key": "portfolio",
      "slug": "portfolio-management",
      "name": "Portfolio Management",
      "description": "Tailored discretionary mandates…",
      "body": "…long form for the detail page…",
      "icon": "portfolio",
      "sort": 1
    }
  ]
}
```

Ordered by `sort`. Only published services are returned.

## `GET /api/v1/services/{slug}`

Single service, same shape. 404 when unknown or unpublished.
