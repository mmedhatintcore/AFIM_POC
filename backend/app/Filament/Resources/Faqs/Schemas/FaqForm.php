<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('action_type')
                    ->label('Action link')
                    ->options([
                        'finder' => 'Open service finder',
                        'survey' => 'Open investment survey',
                        'prices' => 'Go to funds & prices',
                        'services' => 'Go to services',
                        'about' => 'Go to about page',
                    ])
                    ->nullable(),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->default(true),
                Bilingual::tabs([
                    ['name' => 'question', 'label' => 'Question', 'required' => true],
                    ['name' => 'answer', 'label' => 'Answer', 'type' => 'textarea', 'rows' => 4, 'required' => true],
                    ['name' => 'action_label', 'label' => 'Action label'],
                ]),
            ]);
    }
}
