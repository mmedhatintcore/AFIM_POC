<?php

namespace App\Repositories\Interfaces;

use App\Models\SurveySubmission;
use Illuminate\Database\Eloquent\Collection;

interface SurveyRepositoryInterface
{
    public function activeQuestions(): Collection;

    public function storeSubmission(array $answers, array $result, string $locale): SurveySubmission;
}
