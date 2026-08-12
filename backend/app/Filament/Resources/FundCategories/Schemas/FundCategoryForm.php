<?php

namespace App\Filament\Resources\FundCategories\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FundCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->disabledOn('edit')
                    ->helperText('Scoring key used by the survey — do not change after creation.'),
                Select::make('fund_group')
                    ->options([
                        'mm' => 'Money Market',
                        'imm' => 'Islamic Money Market',
                        'mixed' => 'Mixed',
                        'balanced' => 'Balanced',
                        'metals' => 'Precious Metals',
                        'equity' => 'Equity',
                        'iequity' => 'Islamic Equity',
                    ])
                    ->required(),
                Select::make('risk_level')
                    ->options([0 => 'Low', 1 => 'Medium', 2 => 'High'])
                    ->required(),
                Select::make('illustration')
                    ->options([
                        'moneymarket' => 'Money market',
                        'fixedincome' => 'Fixed income',
                        'balanced' => 'Balanced',
                        'equity' => 'Equity',
                        'islamic' => 'Islamic',
                        'gold' => 'Gold',
                    ]),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Bilingual::tabs([
                    ['name' => 'name', 'label' => 'Name', 'required' => true],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 3, 'required' => true],
                ]),
            ]);
    }
}
