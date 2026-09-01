# Services

Licensed lines of business (Fund Management, Portfolio Management, Liquidity
Management, Subscription & Redemption).

## `GET /api/v1/services`

```json
{
  "data": [
    {
      "id": 1,
      "key": "funds",
      "slug": "fund-management",
      "name": "Fund Management",
      "description": "A diversified family of licensed mutual funds…",
      "body": "…long form for the detail page…",
      "icon": "fundmanagement",
      "sort": 1
    }
  ]
}
```

Ordered by `sort`. Only published services are returned.

## `GET /api/v1/services/{slug}`

Single service, same shape. 404 when unknown or unpublished.
