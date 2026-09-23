<?php

namespace App\Filament\Resources\FinderQuestions\Pages;

use App\Filament\Resources\FinderQuestions\FinderQuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFinderQuestion extends CreateRecord
{
    protected static string $resource = FinderQuestionResource::class;
}
