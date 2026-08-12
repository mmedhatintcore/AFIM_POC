<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FundCategory extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = ['key', 'name', 'description', 'risk_level', 'fund_group', 'illustration', 'sort'];

    protected function casts(): array
    {
        return ['risk_level' => 'integer', 'sort' => 'integer'];
    }

    #[Scope]
    public function ordered(Builder $query): void
    {
        $query->orderBy('sort');
    }

    public function funds()
    {
        return Fund::query()->where('group_key', $this->fund_group);
    }
}
