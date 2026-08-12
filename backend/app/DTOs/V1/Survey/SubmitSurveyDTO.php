<?php

namespace App\DTOs\V1\Survey;

use App\DTOs\Common\AbstractDTO;

final class SubmitSurveyDTO extends AbstractDTO
{
    /** @var array<int, int> question_id => option_index */
    protected array $answers = [];

    protected function map(array $data): bool
    {
        foreach ($data['answers'] as $answer) {
            $this->answers[(int) $answer['question_id']] = (int) $answer['option_index'];
        }

        return true;
    }

    /** @return array<int, int> */
    public function getAnswers(): array
    {
        return $this->answers;
    }
}
