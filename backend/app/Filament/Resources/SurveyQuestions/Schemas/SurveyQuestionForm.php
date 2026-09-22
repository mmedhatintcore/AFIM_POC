<?php

namespace App\Filament\Resources\SurveyQuestions\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\FundCategory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SurveyQuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('key')
                    ->label('Scoring key')
                    ->options([
                        'entity' => 'entity (individual vs corporate)',
                        'objective' => 'objective (profile chip)',
                        'duration' => 'duration (profile chip)',
                        'risk' => 'risk (profile chip)',
                        'islamic' => 'islamic (restricts pool)',
                        'multi' => 'multi (diversification)',
                    ])
                    ->nullable()
                    ->helperText('Only special questions carry a key; plain profile questions leave it empty.'),
                Select::make('layout')
                    ->options(['cards' => 'Cards (icon + description)', 'grid' => 'Compact grid'])
                    ->default('cards')
                    ->required(),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->default(true),
                Bilingual::tabs([
                    ['name' => 'phase', 'label' => 'Phase label', 'required' => true],
                    ['name' => 'question', 'label' => 'Question', 'required' => true],
                ]),
                Repeater::make('options')
                    ->schema([
                        TextInput::make('icon')
                            ->helperText('Icon key, e.g. shield, payout, scales, launch, crescent, check…'),
                        TextInput::make('label.en')->label('Label (English)')->required(),
                        TextInput::make('label.ar')->label('Label (Arabic)')->required(),
                        TextInput::make('description.en')->label('Description (English)'),
                        TextInput::make('description.ar')->label('Description (Arabic)'),
                        Repeater::make('votes')
                            ->label('Category votes')
                            ->schema([
                                Select::make('category_key')
                                    ->label('Fund category')
                                    ->options(fn () => FundCategory::query()
                                        ->ordered()
                                        ->get()
                                        ->mapWithKeys(fn (FundCategory $category) => [
                                            $category->key => sprintf('%s — %s', $category->key, $category->getTranslation('name', 'en')),
                                        ]))
                                    ->searchable()
                                    ->required(),
                                TextInput::make('points')
                                    ->label('Votes')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add category vote')
                            ->default([])
                            ->helperText('Optional. How many points picking this answer adds toward each Fund Category\'s score. After all questions are answered, the category with the highest total is recommended to the visitor. Leave empty if this answer shouldn\'t sway the result.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Storage shape has `votes` as a { category_key: points } map. The
     * `votes` Repeater above needs a list of {category_key, points} rows
     * instead — convert on the way into the form.
     *
     * @param  array<int, array<string, mixed>>|null  $options
     * @return array<int, array<string, mixed>>
     */
    public static function optionsToRepeaterFormat(?array $options): array
    {
        return collect($options ?? [])
            ->map(function (array $option) {
                if (isset($option['votes']) && is_array($option['votes']) && ! array_is_list($option['votes'])) {
                    $option['votes'] = collect($option['votes'])
                        ->map(fn ($points, $key) => ['category_key' => $key, 'points' => $points])
                        ->values()
                        ->all();
                }

                return $option;
            })
            ->all();
    }

    /**
     * Reverse of {@see self::optionsToRepeaterFormat()} — convert the
     * Repeater's {category_key, points} rows back into a { category_key:
     * points } map for storage.
     *
     * @param  array<int, array<string, mixed>>|null  $options
     * @return array<int, array<string, mixed>>
     */
    public static function optionsToStorageFormat(?array $options): array
    {
        return collect($options ?? [])
            ->map(function (array $option) {
                if (isset($option['votes']) && is_array($option['votes'])) {
                    $option['votes'] = collect($option['votes'])
                        ->filter(fn ($row) => is_array($row) && filled($row['category_key'] ?? null))
                        ->mapWithKeys(fn ($row) => [$row['category_key'] => (int) ($row['points'] ?? 0)])
                        ->all();
                }

                return $option;
            })
            ->all();
    }
}
