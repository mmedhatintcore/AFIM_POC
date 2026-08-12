<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->disabledOn('edit'),
                TextInput::make('slug')
                    ->required()
                    ->helperText('URL segment on the website, e.g. portfolio-management.'),
                Select::make('icon')
                    ->options([
                        'portfolio' => 'Portfolio',
                        'liquidity' => 'Liquidity',
                        'underwriting' => 'Underwriting',
                        'subscription' => 'Subscription',
                    ]),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->default(true),
                Bilingual::tabs([
                    ['name' => 'name', 'label' => 'Name', 'required' => true],
                    ['name' => 'description', 'label' => 'Short description', 'type' => 'textarea', 'rows' => 3, 'required' => true],
                    ['name' => 'body', 'label' => 'Detail page body', 'type' => 'textarea', 'rows' => 7],
                ]),
            ]);
    }
}
