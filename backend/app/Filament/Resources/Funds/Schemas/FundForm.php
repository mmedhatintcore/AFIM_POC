<?php

namespace App\Filament\Resources\Funds\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()
                    ->helperText('URL segment on the website, e.g. dahab-gold-fund.'),
                Select::make('group_key')
                    ->label('Fund group')
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
                Select::make('order_channel')
                    ->options(['nbe' => 'NBE branches', 'afim' => 'Directly via AFIM'])
                    ->required(),
                Select::make('risk_level')
                    ->options([0 => 'Low', 1 => 'Medium', 2 => 'High']),
                Select::make('illustration')
                    ->options([
                        'moneymarket' => 'Money market',
                        'fixedincome' => 'Fixed income',
                        'balanced' => 'Balanced',
                        'equity' => 'Equity',
                        'islamic' => 'Islamic',
                        'gold' => 'Gold',
                    ]),
                TextInput::make('nav_price')
                    ->label('NAV price (EGP)')
                    ->numeric(),
                TextInput::make('daily_change')
                    ->label('Daily change (%)')
                    ->numeric(),
                TextInput::make('yield_1y')
                    ->label('1-year yield (%)')
                    ->numeric(),
                TagsInput::make('spark')
                    ->label('Sparkline points')
                    ->placeholder('Add a number and press Enter')
                    ->helperText('The mini price-trend chart, drawn left to right (e.g. 6, 6.4, 6.9…).')
                    ->dehydrateStateUsing(fn (?array $state) => array_values(array_map(
                        'floatval',
                        array_filter($state ?? [], fn ($point) => is_numeric($point)),
                    )))
                    ->columnSpanFull(),
                Repeater::make('platforms')
                    ->label('Other trading platforms')
                    ->schema([
                        TextInput::make('en')->label('Name (English)')->required(),
                        TextInput::make('ar')->label('Name (Arabic)')->required(),
                    ])
                    ->columns(2)
                    ->default([])
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->label('Featured (shown in home prices carousel)'),
                Toggle::make('is_published')
                    ->default(true),
                TextInput::make('sort')
                    ->numeric()
                    ->default(0),
                Bilingual::tabs([
                    ['name' => 'name', 'label' => 'Name', 'required' => true],
                    ['name' => 'category_label', 'label' => 'Category label'],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4],
                ]),
            ]);
    }
}
