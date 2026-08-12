<?php

namespace App\Filament\Resources\TimelineMilestones;

use App\Filament\Resources\TimelineMilestones\Pages\CreateTimelineMilestone;
use App\Filament\Resources\TimelineMilestones\Pages\EditTimelineMilestone;
use App\Filament\Resources\TimelineMilestones\Pages\ListTimelineMilestones;
use App\Filament\Resources\TimelineMilestones\Schemas\TimelineMilestoneForm;
use App\Filament\Resources\TimelineMilestones\Tables\TimelineMilestonesTable;
use App\Models\TimelineMilestone;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TimelineMilestoneResource extends Resource
{
    protected static ?string $model = TimelineMilestone::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'About';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return TimelineMilestoneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimelineMilestonesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimelineMilestones::route('/'),
            'create' => CreateTimelineMilestone::route('/create'),
            'edit' => EditTimelineMilestone::route('/{record}/edit'),
        ];
    }
}
