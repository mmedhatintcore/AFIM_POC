<?php

namespace App\Filament\Resources\Funds\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                    ->label('NAV price')
                    ->helperText('Shown on the fund card next to the currency.')
                    ->numeric(),
                TextInput::make('currency')
                    ->label('NAV currency')
                    ->default('EGP')
                    ->maxLength(8)
                    ->required()
                    ->helperText('Currency code shown beside the NAV, e.g. EGP.'),
                TextInput::make('return_1m')
                    ->label('1-month return (%)')
                    ->numeric()
                    ->helperText('Shown on the card as "*1M (Month) Return". Positive shows red, negative blue.'),
                DatePicker::make('inception_date')
                    ->label('Inception date')
                    ->native(false)
                    ->helperText('Shown under the NAV on the fund card.'),
                TextInput::make('daily_change')
                    ->label('Daily change (%)')
                    ->numeric(),
                TextInput::make('yield_1y')
                    ->label('YTD (%)')
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
                Section::make('Performance')
                    ->description('Shown in the fund page performance table. "1 month" and "YTD" are entered above; all values are percentages.')
                    ->schema([
                        DatePicker::make('price_date')
                            ->label('Price date')
                            ->native(false)
                            ->helperText('The date the NAV price above refers to.'),
                        TextInput::make('return_1y')->label('1 year (%)')->numeric(),
                        TextInput::make('return_3y')->label('3 years (%)')->numeric(),
                        TextInput::make('return_5y')->label('5 years (%)')->numeric(),
                        TextInput::make('return_since_inception')->label('Since inception (%)')->numeric(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Asset allocation')
                    ->description('Where the fund is invested — one row per asset type. Percentages should add up to about 100.')
                    ->schema([
                        Repeater::make('asset_allocation')
                            ->hiddenLabel()
                            ->schema([
                                Select::make('type')
                                    ->options([
                                        'tbills' => 'T-Bills',
                                        'bonds' => 'Bonds',
                                        'deposits' => 'Deposits',
                                        'ics' => 'ICS',
                                        'cash' => 'Cash',
                                        'equity' => 'Equity',
                                        'other' => 'Other',
                                    ])
                                    ->required(),
                                TextInput::make('percent')
                                    ->label('Share (%)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add asset type')
                            ->default([]),
                    ])
                    ->columnSpanFull(),
                Section::make('Dividends')
                    ->schema([
                        TextInput::make('dividends_ytd')
                            ->label('Dividends paid this year (per certificate)')
                            ->numeric(),
                        Repeater::make('dividends')
                            ->label('Distribution history')
                            ->schema([
                                DatePicker::make('date')
                                    ->label('Payment month')
                                    ->native(false)
                                    ->required(),
                                TextInput::make('amount')
                                    ->label('Amount per certificate')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Add distribution')
                            ->default([]),
                    ])
                    ->columnSpanFull(),
                Toggle::make('is_featured')
                    ->label('Featured (shown in home prices carousel)'),
                Toggle::make('is_published')
                    ->default(true),
                TextInput::make('sort')
                    ->label('Order / rank')
                    ->numeric()
                    ->default(0)
                    ->helperText('Rank number shown on the fund card (lower = first).'),
                Bilingual::tabs([
                    ['name' => 'name', 'label' => 'Name', 'required' => true],
                    ['name' => 'category_label', 'label' => 'Category label'],
                    ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'rows' => 4],
                ]),
            ]);
    }
}
