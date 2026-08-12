# Spatie Translatable Integration

Use Spatie Translatable for all translatable fields (event titles, descriptions, venue names, ticket tier names, etc.). Both Arabic (ar) and English (en) must be supported from Day 1.

---

## Model Setup

All models with translatable fields must use the `HasTranslations` trait.

Typical Sight Scape translatable fields:

- `Event`: `title`, `description`
- `Venue`: `name`, `address`, `description`
- `TicketTier`: `name`, `description`
- `Category`: `name`, `description`

```php
use Spatie\Translatable\HasTranslations;

class Event extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'description'];

    protected $fillable = ['title', 'description', 'starts_at', 'ends_at', 'venue_id', 'is_published'];
}
```

---

## Storing Translations

```php
// In Repository
public function create(StoreEventDTO $dto): Event
{
    return Event::create([
        'title' => [
            'en' => $dto->getTitleEn(),
            'ar' => $dto->getTitleAr(),
        ],
        'description' => [
            'en' => $dto->getDescriptionEn(),
            'ar' => $dto->getDescriptionAr(),
        ],
        'starts_at' => $dto->getStartsAt(),
        'venue_id' => $dto->getVenueId(),
    ]);
}
```

For DTO helpers that return the translatable shape directly, see the `toTranslatableArray()` pattern in `architecture.md`.

---

## API Resource with Translations

```php
final class EventResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'title' => $this->getTranslation('title', $locale),
            'description' => $this->getTranslation('description', $locale),
            // Or include all translations:
            'translations' => [
                'title' => $this->getTranslations('title'),
                'description' => $this->getTranslations('description'),
            ],
        ];
    }
}
```

The resource's locale is driven by the `Accept-Language` header via the `SetLocale` middleware (see `api-conventions.md`).

---

## Filament Form with Translations

Use `Tabs` with one tab per locale. Field names use dot notation (`title.en`, `title.ar`).

```php
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

public static function form(Form $form): Form
{
    return $form->schema([
        Tabs::make('Translations')
            ->tabs([
                Tabs\Tab::make('English')
                    ->schema([
                        TextInput::make('title.en')
                            ->label('Title (English)')
                            ->required()
                            ->maxLength(200),
                        Textarea::make('description.en')
                            ->label('Description (English)')
                            ->maxLength(2000),
                    ]),
                Tabs\Tab::make('Arabic')
                    ->schema([
                        TextInput::make('title.ar')
                            ->label('Title (Arabic)')
                            ->required()
                            ->maxLength(200),
                        Textarea::make('description.ar')
                            ->label('Description (Arabic)')
                            ->maxLength(2000),
                    ]),
            ])
            ->columnSpanFull(),
    ]);
}
```

For Filament table columns that display a specific locale, use `getStateUsing`:

```php
TextColumn::make('title')
    ->getStateUsing(fn ($record) => $record->getTranslation('title', 'en'))
    ->label('Title (EN)'),
```

---

## Migration Column Type

Translatable fields must be `json` (not `text` or `string`). Spatie Translatable stores the value as a JSON object `{ "en": "...", "ar": "..." }`:

```php
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->json('title');
    $table->json('description')->nullable();
    $table->timestamp('starts_at');
    // ...
});
```

Never try to index a `json` column with a plain `INDEX`. If you need search performance on a translated field, add a **generated column** per locale and index that:

```php
$table->string('title_en')->storedAs("JSON_UNQUOTE(JSON_EXTRACT(title, '$.en'))")->index();
```

---

## Fallback Locale

Configure in `config/translatable.php`:

```php
'fallback_locale' => 'en',
'fallback_any'    => true, // return any translation if fallback_locale is also empty
```

With these settings, `getTranslation('title', 'ar')` returns the English value when Arabic is missing, instead of an empty string. Prefer `fallback_any = true` for customer-facing endpoints so a missing translation is never a blank screen.

---

## Validation

Form Requests must validate **both** locales explicitly. A single `title` rule is insufficient:

```php
public function rules(): array
{
    return [
        'title_en'       => ['required', 'string', 'max:200'],
        'title_ar'       => ['required', 'string', 'max:200'],
        'description_en' => ['nullable', 'string', 'max:2000'],
        'description_ar' => ['nullable', 'string', 'max:2000'],
    ];
}
```

Arabic strings often contain characters that fail `alpha` / `alpha_num` rules — use `string` plus a custom regex if script validation is genuinely needed. In most cases `string` + `max` is enough.

---

## Searching Translatable Fields

Plain `where('title', 'like', "%{$term}%")` **does not work** — the column holds JSON. Use `->` (MySQL JSON path) or a scope:

```php
// app/Models/Event.php
#[Scope]
public function search(Builder $query, string $term): void
{
    $like = '%' . $term . '%';
    $query->where(function (Builder $q) use ($like) {
        $q->where('title->en', 'like', $like)
          ->orWhere('title->ar', 'like', $like)
          ->orWhere('description->en', 'like', $like)
          ->orWhere('description->ar', 'like', $like);
    });
}
```

For production-grade search across Arabic text (diacritics, alef variants `ا/أ/إ/آ`, ta marbuta `ة/ه`), reach for a real search engine (Meilisearch, Algolia, Typesense) rather than LIKE — they handle Arabic tokenization and normalization out of the box.

---

## Arabic Text Considerations

- **Direction** is a frontend concern. The backend stores and returns strings verbatim — never re-order, re-encode, or strip RTL marks.
- **Normalization**: if users type `القاهرة` vs `القاهره` (different letter endings), LIKE won't match. Normalize before indexing if you control both sides; otherwise use a search engine (see above).
- **Collation**: ensure the database is `utf8mb4` with `utf8mb4_unicode_ci` or `utf8mb4_0900_ai_ci` collation (Laravel 12 default). `utf8` (3-byte) won't store all Arabic characters correctly.
- **Trimming**: Arabic whitespace includes U+00A0 and U+200F; `trim()` alone may miss them. Use a unicode-aware trim if you strictly validate length.

---

## What NOT to Translate

Keep these as plain columns, **never** `json`:

- Identifiers / slugs / SKUs / codes (`event_code`, `ticket_serial`)
- Email, phone, national ID, IBAN, CR number
- Enum-like values stored as strings (`status`, `payment_method`)
- URLs, file paths
- Anything that ever appears inside a `WHERE code = ?` query

Slugs specifically should be ASCII/URL-safe. If you need a localized slug, use a separate `slug_en` / `slug_ar` column (two plain strings), not a translatable JSON column.
