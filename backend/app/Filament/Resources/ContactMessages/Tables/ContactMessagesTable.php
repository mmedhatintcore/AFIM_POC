<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('subject')
                    ->limit(40),
                TextColumn::make('message')
                    ->limit(60)
                    ->wrap(),
                IconColumn::make('is_read')
                    ->boolean()
                    ->label('Read'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                TernaryFilter::make('is_read'),
            ])
            ->recordActions([
                EditAction::make()->label('View'),
                Action::make('toggleRead')
                    ->label(fn ($record) => $record->is_read ? 'Mark unread' : 'Mark read')
                    ->icon('heroicon-o-envelope-open')
                    ->action(fn ($record) => $record->update(['is_read' => ! $record->is_read])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
