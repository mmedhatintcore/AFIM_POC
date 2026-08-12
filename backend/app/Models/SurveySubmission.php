<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveySubmission extends Model
{
    protected $fillable = ['answers', 'result', 'locale'];

    protected function casts(): array
    {
        return ['answers' => 'array', 'result' => 'array'];
    }
}
