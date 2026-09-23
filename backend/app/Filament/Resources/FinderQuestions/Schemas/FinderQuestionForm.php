<?php

namespace App\Filament\Resources\FinderQuestions\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FinderQuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Internal label (optional)')
                    ->helperText('Just for your own reference in the list below — not shown to visitors.'),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0)
                    ->helperText('Controls the order questions are asked in — lower numbers first.'),
                Bilingual::tabs([
                    ['name' => 'question', 'label' => 'Question', 'required' => true],
                ]),
                Section::make('Answer options & routing')
                    ->description(
                        'How the match is picked: each option below can award points toward one or more '.
                        'of the 4 possible recommendations. Once the visitor answers every question, points '.
                        'are summed and whichever recommendation has the highest total is shown. Leave all '.
                        'points at 0 on an option that is purely informational and shouldn\'t sway the result.',
                    )
                    ->schema([
                        Repeater::make('options')
                            ->hiddenLabel()
                            ->schema([
                                TextInput::make('icon')
                                    ->helperText('Icon key, e.g. shield, sprout, crescent, coins, briefcase, pie, swap, user, corp, trend…'),
                                TextInput::make('label.en')->label('Label (English)')->required(),
                                TextInput::make('label.ar')->label('Label (Arabic)')->required(),
                                TextInput::make('description.en')->label('Description (English)'),
                                TextInput::make('description.ar')->label('Description (Arabic)'),
                                TextInput::make('votes.portfolio')
                                    ->label('Points → Portfolio Management')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                TextInput::make('votes.liquidity')
                                    ->label('Points → Liquidity Management')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                TextInput::make('votes.subscription')
                                    ->label('Points → Subscription & Redemption')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                TextInput::make('votes.funds')
                                    ->label('Points → AFIM Mutual Funds')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add answer option')
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
