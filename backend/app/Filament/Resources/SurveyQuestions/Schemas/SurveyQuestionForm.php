<?php

namespace App\Filament\Resources\SurveyQuestions\Schemas;

use App\Filament\Support\Bilingual;
use App\Models\FundCategory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                Section::make('Answer options & scoring')
                    ->description(
                        "How the match is picked: every fund category starts each survey at 0 points. ".
                        "Each answer below can add \"Category votes\" to one or more categories — pick a bigger number ".
                        "for a stronger match. Once the visitor finishes all questions, whichever category has the ".
                        "highest total is recommended to them; the next-highest category (from a different fund group) ".
                        "is shown as \"also worth a look\". Answering \"Yes\" on the Islamic investment question narrows ".
                        "the result to Sharia-compliant categories only — otherwise only conventional ones are considered. ".
                        "Leave \"Category votes\" empty on an option that's purely informational (e.g. age, income) and ".
                        "shouldn't sway the result.",
                    )
                    ->schema([
                        Repeater::make('options')
                            ->hiddenLabel()
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
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),
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
