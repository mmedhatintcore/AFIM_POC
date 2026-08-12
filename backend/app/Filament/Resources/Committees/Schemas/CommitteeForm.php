<?php

namespace App\Filament\Resources\Committees\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CommitteeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Bilingual::tabs([
                    ['name' => 'name', 'label' => 'Name', 'required' => true],
                ]),
                Repeater::make('responsibilities')
                    ->schema([
                        TextInput::make('en')->label('Responsibility (English)')->required(),
                        TextInput::make('ar')->label('Responsibility (Arabic)')->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
