<?php

namespace App\Filament\Resources\TimelineMilestones\Pages;

use App\Filament\Resources\TimelineMilestones\TimelineMilestoneResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTimelineMilestone extends EditRecord
{
    protected static string $resource = TimelineMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
