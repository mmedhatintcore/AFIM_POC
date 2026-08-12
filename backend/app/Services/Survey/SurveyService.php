<?php

namespace App\Services\Survey;

use App\DTOs\V1\Survey\SubmitSurveyDTO;
use App\Models\FundCategory;
use App\Repositories\Interfaces\FundCategoryRepositoryInterface;
use App\Repositories\Interfaces\FundRepositoryInterface;
use App\Repositories\Interfaces\SurveyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SurveyService
{
    private const ISLAMIC_GROUPS = ['imm', 'iequity'];

    public function __construct(
        private readonly SurveyRepositoryInterface $survey,
        private readonly FundCategoryRepositoryInterface $categories,
        private readonly FundRepositoryInterface $funds,
    ) {}

    public function questions(): Collection
    {
        return $this->survey->activeQuestions();
    }

    /**
     * Score the answers, persist the submission and return the
     * recommendation payload (mirrors the source page's algorithm —
     * see docs/api-contract/survey.md).
     */
    public function submit(SubmitSurveyDTO $dto): array
    {
        $questions = $this->survey->activeQuestions();
        $answers = $dto->getAnswers();

        // Every active question must be answered with a valid option index.
        $errors = [];
        foreach ($questions as $question) {
            $index = $answers[$question->id] ?? null;
            if ($index === null || ! isset($question->options[$index])) {
                $errors["answers.{$question->id}"] = [__('messages.survey_answer_missing')];
            }
        }
        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        $categories = $this->categories->allKeyed();

        // 1. Sum category votes across the chosen options.
        $scores = $categories->map(fn () => 0)->all();
        $keyedAnswers = [];
        foreach ($questions as $question) {
            $option = $question->options[$answers[$question->id]];
            $keyedAnswers[$question->key ?? "q{$question->id}"] = $option;
            foreach ($option['votes'] ?? [] as $categoryKey => $votes) {
                $scores[$categoryKey] = ($scores[$categoryKey] ?? 0) + $votes;
            }
        }

        // 2. Islamic "Yes" (first option) restricts the pool to Islamic categories.
        $islamicQuestion = $questions->firstWhere('key', 'islamic');
        $isIslamic = $islamicQuestion !== null && $answers[$islamicQuestion->id] === 0;

        $pool = $categories->keys()
            ->filter(fn (string $key) => $isIslamic
                ? in_array($categories[$key]->fund_group, self::ISLAMIC_GROUPS, true)
                : ! in_array($categories[$key]->fund_group, self::ISLAMIC_GROUPS, true))
            ->sortByDesc(fn (string $key) => $scores[$key] ?? 0)
            ->values();

        /** @var FundCategory $top */
        $top = $categories[$pool->first()];

        // 3. Alternative: next scored category pointing at a different fund group.
        $alternativeKey = $pool->first(fn (string $key) => $key !== $top->key
            && ($scores[$key] ?? 0) > 0
            && $categories[$key]->fund_group !== $top->fund_group);
        $alternative = $alternativeKey !== null ? $categories[$alternativeKey] : null;

        // Corporate flag: first question ("entity"), second option.
        $entityQuestion = $questions->firstWhere('key', 'entity');
        $isCorporate = $entityQuestion !== null && $answers[$entityQuestion->id] === 1;

        // Profile chips: objective / duration / risk labels (+ Sharia badge).
        $locale = app()->getLocale();
        $profile = [];
        foreach (['objective', 'duration', 'risk'] as $key) {
            if (isset($keyedAnswers[$key])) {
                $label = $keyedAnswers[$key]['label'] ?? [];
                $profile[] = $label[$locale] ?? $label['en'] ?? '';
            }
        }
        if ($isIslamic) {
            $profile[] = __('messages.sharia_badge');
        }

        $matchingFunds = $this->funds->byGroup($top->fund_group);

        $result = [
            'category_key' => $top->key,
            'alternative_key' => $alternative?->key,
            'is_islamic' => $isIslamic,
            'is_corporate' => $isCorporate,
            'scores' => $scores,
        ];

        DB::transaction(function () use ($dto, $result, $locale) {
            $this->survey->storeSubmission(
                collect($dto->getAnswers())
                    ->map(fn (int $index, int $questionId) => ['question_id' => $questionId, 'option_index' => $index])
                    ->values()
                    ->all(),
                $result,
                $locale,
            );
        });

        return [
            'category' => $top,
            'alternative' => $alternative,
            'is_islamic' => $isIslamic,
            'is_corporate' => $isCorporate,
            'profile' => $profile,
            'funds' => $matchingFunds,
        ];
    }
}
