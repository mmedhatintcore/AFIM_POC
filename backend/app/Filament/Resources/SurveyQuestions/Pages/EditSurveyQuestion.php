<?php

namespace App\Filament\Resources\SurveyQuestions\Pages;

use App\Filament\Resources\SurveyQuestions\Schemas\SurveyQuestionForm;
use App\Filament\Resources\SurveyQuestions\SurveyQuestionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSurveyQuestion extends EditRecord
{
    protected static string $resource = SurveyQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['options'] = SurveyQuestionForm::optionsToRepeaterFormat($data['options'] ?? []);

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['options'] = SurveyQuestionForm::optionsToStorageFormat($data['options'] ?? []);

        return $data;
    }
}
