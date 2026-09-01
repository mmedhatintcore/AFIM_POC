<?php

namespace App\Http\Resources;

use App\Support\Localization\LocalizesNested;
use Illuminate\Http\Resources\Json\JsonResource;

final class CommitteeResource extends JsonResource
{
    public function toArray($request): array
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'name' => $this->getTranslation('name', $locale),
            'mission' => $this->getTranslation('mission', $locale),
            'members' => LocalizesNested::localize($this->members, $locale) ?? [],
            'responsibilities' => LocalizesNested::localize($this->responsibilities, $locale) ?? [],
        ];
    }
}
