<?php

namespace App\Filament\Resources\FinderQuestions\Pages;

use App\Filament\Resources\FinderQuestions\FinderQuestionResource;
use Filament\Resources\Pages\ListRecords;

class ListFinderQuestions extends ListRecords
{
    protected static string $resource = FinderQuestionResource::class;

    protected function getHeaderActions(): array
    {
        // No CreateAction — this is a fixed 3-question wizard; only the
        // wording/icons of the existing questions are meant to be edited.
        return [];
    }
}
