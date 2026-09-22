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
                    ->label('Scoring Key')
                    ->required()
                    ->disabledOn('edit')
                    ->helperText('Scoring key used by the survey — do not change after creation.'),
                Select::make('fund_group')
                    ->label('Matching fund category')
                    ->options([
                        'mm' => 'Money Market',
                        'imm' => 'Islamic Money Market',
                        'mixed' => 'Mixed',
                        'balanced' => 'Balanced',
                        'metals' => 'Precious Metals',
                        'equity' => 'Equity',
                        'iequity' => 'Islamic Equity',
                    ])
                    ->required()
                    ->helperText('Not another category — this picks which published funds appear as "matching funds" when the survey recommends this result. Several results can point at the same group (e.g. both money-market results here still show the same money-market funds).'),
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
