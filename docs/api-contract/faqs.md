# FAQs

## `GET /api/v1/faqs`

Ordered by `sort`. Only published rows. Not paginated.

```json
{
  "data": [
    {
      "id": 1,
      "question": "I'm new — how do I start, and what suits me?",
      "answer": "Answer three quick questions and we'll point you…",
      "action_type": "finder",
      "action_label": "Find your service"
    }
  ]
}
```

`action_type`: `finder` (opens the service-finder wizard), `survey`, `prices`,
`services`, `about`, or `null`. The frontend maps it to the matching route/modal.
