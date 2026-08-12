<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Resources\Sections\Concerns\PrunesSectionData;
use App\Filament\Resources\Sections\SectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSection extends EditRecord
{
    use PrunesSectionData;

    protected static string $resource = SectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->pruneSectionData($data);
    }
}
