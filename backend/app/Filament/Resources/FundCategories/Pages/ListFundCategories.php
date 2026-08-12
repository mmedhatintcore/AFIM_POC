<?php

namespace App\Filament\Resources\FundCategories\Pages;

use App\Filament\Resources\FundCategories\FundCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFundCategories extends ListRecords
{
    protected static string $resource = FundCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
