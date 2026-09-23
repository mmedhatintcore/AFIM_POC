<?php

namespace App\Filament\Resources\FinderQuestions\Pages;

use App\Filament\Resources\FinderQuestions\FinderQuestionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFinderQuestion extends EditRecord
{
    protected static string $resource = FinderQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
