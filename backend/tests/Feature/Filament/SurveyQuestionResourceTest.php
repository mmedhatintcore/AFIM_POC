<?php

namespace Tests\Feature\Filament;

use App\Filament\Pages\SurveyScoringMatrix;
use App\Filament\Resources\SurveyQuestions\Pages\EditSurveyQuestion;
use App\Filament\Resources\SurveyQuestions\Pages\ListSurveyQuestions;
use App\Models\SurveyQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

final class SurveyQuestionResourceTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function admin(): User
    {
        return User::where('email', 'admin@afim.com.eg')->firstOrFail();
    }

    public function test_list_shows_whether_each_question_affects_the_result(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(ListSurveyQuestions::class)
            ->assertOk()
            ->assertCanSeeTableRecords(SurveyQuestion::query()->ordered()->take(10)->get(), inOrder: true)
            ->assertCountTableRecords(13);
    }

    public function test_scoring_matrix_page_renders_every_question(): void
    {
        $this->actingAs($this->admin());

        Livewire::test(SurveyScoringMatrix::class)
            ->assertOk()
            ->assertSee('Score(category)')
            ->assertSee('Investment Objective')
            ->assertSee('Client seeks capital preservation');
    }

    public function test_business_meaning_and_votes_survive_an_edit(): void
    {
        $this->actingAs($this->admin());
        $question = SurveyQuestion::where('key', 'objective')->firstOrFail();

        Livewire::test(EditSurveyQuestion::class, ['record' => $question->getKey()])
            ->call('save')
            ->assertHasNoFormErrors();

        $option = $question->fresh()->options[0];
        $this->assertSame(['mm_acc' => 3], $option['votes']);
        $this->assertStringContainsString('capital preservation', $option['business_meaning']);
    }
}
