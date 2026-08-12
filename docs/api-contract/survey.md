# Investment Survey ("Need help in investment?")

Questions/scoring come from the Intcore Data workbook (Survey Questions +
Sheet1). Scoring is **server-side** — the frontend never sees vote weights.

## `GET /api/v1/survey/questions`

Ordered by `sort`. Only active questions.

```json
{
  "data": [
    {
      "id": 5,
      "key": "objective",
      "phase": "Your goals",
      "question": "What's your primary investment objective?",
      "layout": "cards",
      "options": [
        { "index": 0, "icon": "shield", "label": "Saving", "description": "Preserve capital, high liquidity, accumulated return" }
      ]
    }
  ]
}
```

`layout`: `"cards"` (icon + description) or `"grid"` (compact label-only grid).
`key` is non-null for the questions that drive scoring/profile chips
(`objective`, `duration`, `risk`, `islamic`, `multi`).

## `POST /api/v1/survey/submissions`

Body:

```json
{ "answers": [ { "question_id": 1, "option_index": 0 }, … ] }
```

Validation: every active question must be answered with a valid option index →
422 otherwise.

Response `201`:

```json
{
  "data": {
    "category": { "key": "mm_acc", "name": "Money Market Fund — Daily Accumulated Return", "description": "…", "risk_level": 0, "risk_label": "Low risk", "illustration": "moneymarket" },
    "alternative": { "key": "balanced", "name": "Balanced Funds", "description": "…" },
    "is_islamic": false,
    "is_corporate": false,
    "profile": ["Saving", "Under 1 year", "Low"],
    "funds": [
      {
        "name": "Wethaq",
        "slug": "wethaq",
        "order_channel": "afim",
        "channel_label": "Directly via AFIM",
        "how_to": "Subscribe and redeem directly through AFIM.",
        "platforms": ["Ahly Pharos", "Mubasher", "Banque Du Caire"]
      }
    ]
  },
  "message": "…"
}
```

Scoring algorithm (mirrors the source page):
1. Sum each answered option's `votes` map into per-category scores.
2. If the `islamic` answer is "Yes" → candidate pool is `[imm, iequity]`;
   otherwise every category except those two.
3. Winner = highest score in pool; `alternative` = next pool category with
   score > 0 whose `fund_group` differs from the winner's.
4. `funds` = published funds where `group_key = winner.fund_group`.

Submissions are stored (answers + result + locale) and browsable in Filament.
