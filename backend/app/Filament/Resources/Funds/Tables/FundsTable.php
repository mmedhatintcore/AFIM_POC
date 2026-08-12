<?php

namespace App\Filament\Resources\Funds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name (EN)')
                    ->getStateUsing(fn ($record) => $record->getTranslation('name', 'en'))
                    ->searchable(query: fn ($query, string $search) => $query
                        ->where('name->en', 'like', "%{$search}%")
                        ->orWhere('name->ar', 'like', "%{$search}%")),
                TextColumn::make('group_key')
                    ->label('Group')
                    ->badge(),
                TextColumn::make('order_channel')
                    ->label('Channel')
                    ->badge()
                    ->color(fn (string $state) => $state === 'nbe' ? 'info' : 'warning'),
                TextColumn::make('nav_price')
                    ->label('NAV')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('daily_change')
                    ->label('Δ %')
                    ->numeric()
                    ->color(fn ($state) => $state === null ? null : ((float) $state >= 0 ? 'success' : 'danger')),
                TextColumn::make('yield_1y')
                    ->label('1Y %')
                    ->numeric(),
                IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured'),
                IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
                TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('sort')
            ->filters([
                SelectFilter::make('group_key')
                    ->options([
                        'mm' => 'Money Market',
                        'imm' => 'Islamic Money Market',
                        'mixed' => 'Mixed',
                        'balanced' => 'Balanced',
                        'metals' => 'Precious Metals',
                        'equity' => 'Equity',
                        'iequity' => 'Islamic Equity',
                    ]),
                TernaryFilter::make('is_featured'),
                TernaryFilter::make('is_published'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
