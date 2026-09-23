<?php

namespace App\Filament\Resources\FinderQuestions\Pages;

use App\Filament\Resources\FinderQuestions\FinderQuestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinderQuestions extends ListRecords
{
    protected static string $resource = FinderQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
