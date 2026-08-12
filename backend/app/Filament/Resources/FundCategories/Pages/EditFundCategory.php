<?php

namespace App\Filament\Resources\FundCategories\Pages;

use App\Filament\Resources\FundCategories\FundCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFundCategory extends EditRecord
{
    protected static string $resource = FundCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
