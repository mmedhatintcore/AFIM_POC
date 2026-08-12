<?php

namespace App\Filament\Resources\FundCategories\Pages;

use App\Filament\Resources\FundCategories\FundCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFundCategory extends CreateRecord
{
    protected static string $resource = FundCategoryResource::class;
}
