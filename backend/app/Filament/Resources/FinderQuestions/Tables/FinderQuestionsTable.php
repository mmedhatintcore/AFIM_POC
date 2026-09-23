<?php

namespace App\Filament\Resources\FinderQuestions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FinderQuestionsTable
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
                TextColumn::make('key')
                    ->label('Internal label')
                    ->placeholder('—'),
                TextColumn::make('sort')
                    ->label('Order')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
