<?php

namespace App\Filament\Resources\SurveyQuestions\Pages;

use App\Filament\Resources\SurveyQuestions\Schemas\SurveyQuestionForm;
use App\Filament\Resources\SurveyQuestions\SurveyQuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSurveyQuestion extends CreateRecord
{
    protected static string $resource = SurveyQuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['options'] = SurveyQuestionForm::optionsToStorageFormat($data['options'] ?? []);

        return $data;
    }
}
