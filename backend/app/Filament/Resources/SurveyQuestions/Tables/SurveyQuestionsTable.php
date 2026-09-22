<?php

namespace App\Filament\Resources\SurveyQuestions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SurveyQuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label('Question')
                    ->getStateUsing(fn ($record) => $record->getTranslation('question', 'en'))
                    ->searchable()
                    ->wrap(),
                TextColumn::make('phase')
                    ->label('Phase')
                    ->getStateUsing(fn ($record) => $record->getTranslation('phase', 'en'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('key')
                    ->label('Scoring key')
                    ->placeholder('— profile only —')
                    ->searchable(),
                TextColumn::make('layout')
                    ->searchable(),
                TextColumn::make('sort')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
