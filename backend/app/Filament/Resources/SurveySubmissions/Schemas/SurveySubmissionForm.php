<?php

namespace App\Filament\Resources\SurveySubmissions\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SurveySubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('locale')->disabled(),
                Bilingual::json('answers', 'Answers (read-only)')->disabled(),
                Bilingual::json('result', 'Scoring result (read-only)')->disabled(),
            ]);
    }
}
