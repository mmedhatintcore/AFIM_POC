<?php

namespace App\Filament\Pages;

use App\Models\FundCategory;
use App\Models\SurveyQuestion;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;

/**
 * Read-only view of the recommendation "equation": which category each answer
 * votes for, and by how many points. Edit the points on the question itself.
 */
class SurveyScoringMatrix extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    protected static string|\UnitEnum|null $navigationGroup = 'Survey';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Scoring Matrix';

    protected static ?string $title = 'Recommendation scoring matrix';

    protected string $view = 'filament.pages.survey-scoring-matrix';

    /** @return Collection<int, FundCategory> */
    public function categories(): Collection
    {
        return FundCategory::query()->ordered()->get();
    }

    /** @return Collection<int, SurveyQuestion> */
    public function questions(): Collection
    {
        return SurveyQuestion::query()->active()->ordered()->get();
    }
}
