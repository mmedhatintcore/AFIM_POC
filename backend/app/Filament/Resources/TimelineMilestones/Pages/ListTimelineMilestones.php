<?php

namespace App\Filament\Resources\TimelineMilestones\Pages;

use App\Filament\Resources\TimelineMilestones\TimelineMilestoneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTimelineMilestones extends ListRecords
{
    protected static string $resource = TimelineMilestoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
