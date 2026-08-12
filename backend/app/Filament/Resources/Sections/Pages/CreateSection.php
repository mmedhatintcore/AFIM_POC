<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Resources\Sections\Concerns\PrunesSectionData;
use App\Filament\Resources\Sections\SectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSection extends CreateRecord
{
    use PrunesSectionData;

    protected static string $resource = SectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->pruneSectionData($data);
    }
}
