# Contact

## `POST /api/v1/contact-messages`

Throttled (`throttle:contact`, 5/min per IP).

Body:

```json
{ "name": "…", "email": "…", "phone": "+20…", "subject": "…", "message": "…" }
```

| Field | Rules |
| --- | --- |
| `name` | required, string, max 120 |
| `email` | required, email, max 190 |
| `phone` | nullable, string, max 30 |
| `subject` | nullable, string, max 190 |
| `message` | required, string, max 5000 |

Response `201`: `{ "data": { "id": 1 }, "message": "We received your message and will reply within 24 hours." }`
(message localized). 422 on validation failure.

Messages are stored and browsable in Filament → Inbox → Contact messages.
