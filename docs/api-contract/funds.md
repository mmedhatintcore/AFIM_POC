# Funds & Fund Categories

## `GET /api/v1/funds`

Query params: `filters[is_featured]=1` (prices slider), `filters[group_key]=mm`,
`search=…`. Ordered by `sort`. Not paginated (bounded set, < 30 rows).

```json
{
  "data": [
    {
      "id": 1,
      "slug": "al-ahly-money-market-fund",
      "name": "Al Ahly Money Market Fund",
      "category_label": "Money Market",
      "group_key": "mm",
      "risk_level": 0,
      "risk_label": "Low risk",
      "nav_price": "142.83",
      "daily_change": "0.03",
      "yield_1y": "21.4",
      "spark": [6, 6.4, 6.9, 7.3, 7.8, 8.3, 8.8, 9.4, 9.9, 10.5],
      "illustration": "moneymarket",
      "order_channel": "afim",
      "order_channel_label": "Directly via AFIM",
      "platforms": ["Ahly Pharos", "Mubasher"],
      "description": "…",
      "is_featured": true
    }
  ]
}
```

- `risk_level`: `0` low, `1` medium, `2` high; `risk_label` is localized.
- `order_channel`: `"nbe"` (subscribe at NBE branches) or `"afim"` (direct).
- `platforms`: localized platform names, may be empty.
- Market fields (`nav_price`, `daily_change`, `yield_1y`, `spark`) are `null`
  for funds without published pricing.

## `GET /api/v1/funds/{slug}`

Single fund, same shape. 404 when unknown or unpublished.

## `GET /api/v1/fund-categories`

The 8 recommendation categories used by the investment survey.

```json
{
  "data": [
    {
      "key": "mm_acc",
      "name": "Money Market Fund — Daily Accumulated Return",
      "description": "Daily accumulated return with high liquidity…",
      "risk_level": 0,
      "risk_label": "Low risk",
      "fund_group": "mm",
      "illustration": "moneymarket"
    }
  ]
}
```
