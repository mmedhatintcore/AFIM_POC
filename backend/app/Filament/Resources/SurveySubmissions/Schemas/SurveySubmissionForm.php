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
                TextInput::make('locale')
                    ->disabled()
                    ->helperText('Language the visitor was using when they took the survey.'),
                Bilingual::json('answers', 'Answers (read-only)')
                    ->disabled()
                    ->helperText('Each Survey Question\'s ID mapped to the option index the visitor picked (0 = first option shown, 1 = second, and so on).'),
                Bilingual::json('result', 'Scoring result (read-only)')
                    ->disabled()
                    ->helperText('The outcome this submission scored: category_key is the winning Fund Category\'s "Key" (highest total votes); alternative_key is the runner-up shown as "also worth a look"; is_islamic / is_corporate are flags from the Sharia-preference and individual-vs-corporate questions; scores is every category\'s raw point total, for reference.'),
            ]);
    }
}
