# Filament 5 Guidelines

Keep Filament **Resources** focused: fields in `form()`, columns in `table()` (on the ListPage), authorization via policies or `->authorize()` calls, eager load as needed.

---

## Resource Structure

**IMPORTANT**: Table definition should be in the ListPage, not in the Resource itself.

```php
// app/Filament/Resources/EventResource.php
namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Events';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Translations')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('English')
                        ->schema([
                            Forms\Components\TextInput::make('title.en')
                                ->label('Title (English)')
                                ->required()
                                ->maxLength(200),
                            Forms\Components\Textarea::make('description.en')
                                ->label('Description (English)')
                                ->maxLength(2000),
                        ]),
                    Forms\Components\Tabs\Tab::make('Arabic')
                        ->schema([
                            Forms\Components\TextInput::make('title.ar')
                                ->label('Title (Arabic)')
                                ->required()
                                ->maxLength(200),
                            Forms\Components\Textarea::make('description.ar')
                                ->label('Description (Arabic)')
                                ->maxLength(2000),
                        ]),
                ])
                ->columnSpanFull(),

            Forms\Components\DateTimePicker::make('starts_at')
                ->label('Starts At')
                ->required(),

            Forms\Components\DateTimePicker::make('ends_at')
                ->label('Ends At')
                ->required()
                ->after('starts_at'),

            Forms\Components\Select::make('venue_id')
                ->relationship('venue', 'name')
                ->required()
                ->searchable()
                ->preload(),

            SpatieMediaLibraryFileUpload::make('cover')
                ->collection('cover')
                ->image()
                ->maxSize(4096),

            Forms\Components\Toggle::make('is_published')
                ->label('Published')
                ->default(false),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
```

---

## List Page with Table

```php
// app/Filament/Resources/EventResource/Pages/ListEvents.php
namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->conversion('thumbnail')
                    ->square()
                    ->size(60),
                TextColumn::make('title')
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', 'en'))
                    ->label('Title (EN)')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title_ar')
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', 'ar'))
                    ->label('Title (AR)'),
                TextColumn::make('venue.name')
                    ->label('Venue')
                    ->searchable(),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueLabel('Published')
                    ->falseLabel('Draft'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('togglePublished')
                        ->label(fn ($record) => $record->is_published ? 'Unpublish' : 'Publish')
                        ->icon(fn ($record) => $record->is_published ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn ($record) => $record->is_published ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn ($record) => $record->is_published ? 'Unpublish Event' : 'Publish Event')
                        ->modalDescription(fn ($record) => $record->is_published
                            ? 'Are you sure you want to unpublish this event? It will no longer be visible to the public.'
                            : 'Are you sure you want to publish this event? It will become visible to the public.')
                        ->action(fn ($record) => $record->update(['is_published' => !$record->is_published])),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation(),
                ])->dropdown(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('starts_at', 'desc');
    }
}
```

---

## Filament Key Rules

1. **Use SpatieMediaLibrary components** instead of Filament's native ones:
   - `SpatieMediaLibraryImageColumn` instead of `ImageColumn`
   - `SpatieMediaLibraryFileUpload` instead of `FileUpload`

2. **Table in ListPage**: Define `table()` method in the ListRecords page, not in the Resource.

3. **Actions in Dropdown**: Group all row actions using `ActionGroup::make([...])->dropdown()`.

4. **Status Toggle**: Use an Action with confirmation for status changes (publish/unpublish, activate/deactivate).

5. **Module Visibility**: Consider showing/hiding resources based on config flags:
   ```php
   public static function shouldRegisterNavigation(): bool
   {
       return config('modules.events.enabled', true);
   }
   ```

6. **Translations in Forms**: Use `Tabs` with an English tab and an Arabic tab — field names are `title.en` / `title.ar` (see `spatie-translatable.md`).

7. **Media in Forms/Tables**: Use `SpatieMediaLibraryFileUpload` and `SpatieMediaLibraryImageColumn` with a matching `->collection('name')` (see `spatie-media.md`).

---

## Authorization & Policies

Every resource must be gated by a Laravel Policy. Filament calls policy methods automatically (`viewAny`, `view`, `create`, `update`, `delete`, plus custom `{action}` abilities for custom actions).

```php
// app/Policies/EventPolicy.php
public function update(User $user, Event $event): bool
{
    return $user->is_admin || $user->id === $event->organizer_id;
}

public function togglePublished(User $user, Event $event): bool
{
    return $user->is_admin;
}
```

Custom Filament actions opt in via `->authorize('togglePublished')`:

```php
Tables\Actions\Action::make('togglePublished')
    ->authorize('togglePublished')
    ->action(fn ($record) => $record->update(['is_published' => !$record->is_published])),
```

Never rely on UI visibility alone — the policy is the security boundary.

---

## Eager Loading in Tables

Filament tables are a classic N+1 source. Override `getEloquentQuery()` on the Resource to eager-load relations and the Spatie media relation:

```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->with(['venue', 'organizer', 'media']);
}
```

If a column calls an accessor that touches another relation, add that relation to the list.

---

## Relation Managers

For one-to-many details (e.g. `TicketTier` under `Event`, `Media` under `Venue`), use Relation Managers instead of nested resources:

```php
// app/Filament/Resources/EventResource.php
public static function getRelations(): array
{
    return [
        RelationManagers\TicketTiersRelationManager::class,
    ];
}
```

Translatable fields inside a Relation Manager use the same `Tabs` pattern with `title.en` / `title.ar`.

---

## Widgets (Stats / Charts)

Dashboard widgets live in `app/Filament/Widgets/`. Keep queries in Repositories and inject them via `app(Repo::class)` or constructor binding — widgets should not build SQL.

```php
class RevenueStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = app(OrderRepositoryInterface::class)->revenueStats();

        return [
            Stat::make('Revenue (SAR)', number_format($stats['revenue_halalas'] / 100, 2)),
            Stat::make('Orders (30d)', $stats['orders_30d']),
        ];
    }
}
```

---

## Global Search

Opt resources into global search by declaring searchable attributes:

```php
protected static ?string $recordTitleAttribute = 'title';

public static function getGloballySearchableAttributes(): array
{
    return ['title', 'venue.name', 'organizer.name'];
}

public static function getGlobalSearchResultDetails(Model $record): array
{
    return [
        'Venue' => $record->venue?->name,
        'Starts' => $record->starts_at?->format('Y-m-d H:i'),
    ];
}
```

For translatable fields, pass both `title->en` and `title->ar` so search hits either language.

---

## Bulk Actions

Bulk actions run a single database statement over many records. Always wrap in a transaction and delegate the actual mutation to a Service:

```php
Tables\Actions\BulkAction::make('publish')
    ->requiresConfirmation()
    ->action(fn ($records) => app(EventService::class)->bulkPublish($records))
    ->deselectRecordsAfterCompletion(),
```

Never delete/update records one-by-one in a bulk action closure — it defeats the point.

---

## Multi-Panel Considerations

If the project later exposes a separate vendor panel (`/vendor`) alongside the admin panel (`/admin`), each panel declares its own `AdminPanelProvider` / `VendorPanelProvider`, and resources are scoped per panel via `shouldRegisterNavigation()` + panel-aware policies. Do **not** reuse admin resources inside a vendor panel — clone and scope them.
