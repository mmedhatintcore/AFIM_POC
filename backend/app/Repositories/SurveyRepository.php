<?php

namespace App\Repositories;

use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use App\Repositories\Interfaces\SurveyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SurveyRepository implements SurveyRepositoryInterface
{
    public function activeQuestions(): Collection
    {
        return SurveyQuestion::query()->active()->ordered()->get();
    }

    public function storeSubmission(array $answers, array $result, string $locale): SurveySubmission
    {
        return SurveySubmission::create([
            'answers' => $answers,
            'result' => $result,
            'locale' => $locale,
        ]);
    }
}
