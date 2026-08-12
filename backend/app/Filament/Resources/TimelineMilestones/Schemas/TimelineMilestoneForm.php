<?php

namespace App\Filament\Resources\TimelineMilestones\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TimelineMilestoneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->required()
                    ->helperText('Display year, e.g. "1994" or "2000s".'),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Bilingual::tabs([
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'body', 'label' => 'Body', 'type' => 'textarea', 'rows' => 3, 'required' => true],
                ]),
            ]);
    }
}
