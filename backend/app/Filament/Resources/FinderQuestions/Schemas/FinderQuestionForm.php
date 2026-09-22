<?php

namespace App\Filament\Resources\FinderQuestions\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FinderQuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label('Question slot')
                    ->required()
                    ->disabled()
                    ->helperText('Fixed — this identifies which step of the 3-question wizard this is. Cannot be changed here.'),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0)
                    ->helperText('Controls the order the 3 questions are asked in.'),
                Bilingual::tabs([
                    ['name' => 'question', 'label' => 'Question', 'required' => true],
                ]),
                Repeater::make('options')
                    ->label('Answer options')
                    ->schema([
                        TextInput::make('tag')
                            ->label('Tag (do not change)')
                            ->required()
                            ->helperText('Internal value the "Find your service" result logic matches on — changing it will break the recommendation for this option.'),
                        TextInput::make('icon')
                            ->helperText('Icon key, e.g. shield, sprout, crescent, coins, briefcase, pie, swap…'),
                        TextInput::make('label.en')->label('Label (English)')->required(),
                        TextInput::make('label.ar')->label('Label (Arabic)')->required(),
                        TextInput::make('description.en')->label('Description (English)'),
                        TextInput::make('description.ar')->label('Description (Arabic)'),
                    ])
                    ->columns(2)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->columnSpanFull()
                    ->helperText('The set of answer options for this question is fixed — only their text and icon can be edited here.'),
            ]);
    }
}
