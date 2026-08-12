<?php

namespace Tests\Feature\Api\V1;

use App\Models\SurveyQuestion;
use App\Models\SurveySubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SurveyControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_questions_are_returned_without_vote_weights(): void
    {
        $response = $this->getJson('/api/v1/survey/questions');

        $response->assertOk()->assertJsonCount(13, 'data')
            ->assertJsonStructure(['data' => [['id', 'key', 'phase', 'question', 'layout', 'options' => [['index', 'icon', 'label', 'description']]]]]);

        $this->assertArrayNotHasKey('votes', $response->json('data.4.options.0'));
    }

    private function answersFor(callable $pick): array
    {
        return SurveyQuestion::query()->active()->ordered()->get()
            ->map(fn (SurveyQuestion $q) => ['question_id' => $q->id, 'option_index' => $pick($q)])
            ->values()
            ->all();
    }

    public function test_conservative_islamic_profile_recommends_islamic_money_market(): void
    {
        $answers = $this->answersFor(fn (SurveyQuestion $q) => $q->key === 'multi' ? 1 : 0);

        $response = $this->postJson('/api/v1/survey/submissions', ['answers' => $answers]);

        $response->assertCreated()
            ->assertJsonPath('data.category.key', 'imm')
            ->assertJsonPath('data.is_islamic', true)
            ->assertJsonPath('data.is_corporate', false);

        $this->assertSame(['islamic-money-market-fund'], collect($response->json('data.funds'))->pluck('slug')->all());
        $this->assertDatabaseCount('survey_submissions', 1);
        $this->assertSame('imm', SurveySubmission::first()->result['category_key']);
    }

    public function test_aggressive_conventional_profile_recommends_equity(): void
    {
        // Objective "Aggressive growth", long duration, high risk, no Sharia preference.
        $answers = $this->answersFor(function (SurveyQuestion $q) {
            return match ($q->key) {
                'objective' => 3,
                'duration' => 3,
                'risk' => 2,
                'islamic' => 1,
                'multi' => 1,
                default => $q->layout === 'grid' ? 3 : (count($q->options) - 1),
            };
        });

        $response = $this->postJson('/api/v1/survey/submissions', ['answers' => $answers]);

        $response->assertCreated()->assertJsonPath('data.category.key', 'equity');
        $this->assertNotEmpty($response->json('data.funds'));
        $this->assertTrue(collect($response->json('data.funds'))->every(fn ($fund) => $fund['group_key'] === 'equity'));
    }

    public function test_missing_answers_fail_validation(): void
    {
        $first = SurveyQuestion::query()->active()->ordered()->first();

        $this->postJson('/api/v1/survey/submissions', [
            'answers' => [['question_id' => $first->id, 'option_index' => 0]],
        ])->assertStatus(422);
    }

    public function test_out_of_range_option_index_fails_validation(): void
    {
        $answers = $this->answersFor(fn () => 0);
        $answers[0]['option_index'] = 99;

        $this->postJson('/api/v1/survey/submissions', ['answers' => $answers])->assertStatus(422);
    }

    public function test_arabic_locale_returns_arabic_profile_chips(): void
    {
        $answers = $this->answersFor(fn (SurveyQuestion $q) => $q->key === 'multi' ? 1 : 0);

        $response = $this->withHeaders(['Accept-Language' => 'ar'])
            ->postJson('/api/v1/survey/submissions', ['answers' => $answers]);

        $response->assertCreated()->assertJsonPath('data.profile.0', 'الادخار');
    }
}
