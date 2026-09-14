<?php

namespace App\Filament\Resources\SurveyQuestions\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\KeyValue;
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
                        KeyValue::make('votes')
                            ->keyLabel('Category key')
                            ->valueLabel('Votes')
                            ->helperText('Optional. How many points picking this answer adds toward each Fund Category\'s score. After all questions are answered, the category with the highest total is recommended to the visitor. Category key here must match a Fund Category\'s "Key" field exactly (e.g. mm_acc, mm_dist, imm, mixed, balanced, metals, equity, iequity) — not its "Show funds from" field. Leave empty if this answer shouldn\'t sway the result.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
