<?php

namespace App\Filament\Resources\FinderQuestions\Pages;

use App\Filament\Resources\FinderQuestions\FinderQuestionResource;
use Filament\Resources\Pages\EditRecord;

class EditFinderQuestion extends EditRecord
{
    protected static string $resource = FinderQuestionResource::class;

    protected function getHeaderActions(): array
    {
        // No DeleteAction — removing a question would break the 3-step wizard.
        return [];
    }
}
