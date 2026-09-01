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
                    ['name' => 'mission', 'label' => 'Mission', 'type' => 'textarea', 'rows' => 3],
                ]),
                Repeater::make('members')
                    ->schema([
                        TextInput::make('name.en')->label('Member name (English)')->required(),
                        TextInput::make('name.ar')->label('Member name (Arabic)')->required(),
                        TextInput::make('role.en')->label('Member role (English)')->required(),
                        TextInput::make('role.ar')->label('Member role (Arabic)')->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->helperText('Committee members shown on the About page.'),
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
