<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('group')
                    ->options(['board' => 'Board of Directors', 'leadership' => 'Executive Leadership'])
                    ->required(),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->default(true),
                Bilingual::tabs([
                    ['name' => 'name', 'label' => 'Name', 'required' => true],
                    ['name' => 'role', 'label' => 'Role', 'required' => true],
                ]),
            ]);
    }
}
