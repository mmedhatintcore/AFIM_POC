<?php

namespace App\Filament\Resources\SurveySubmissions;

use App\Filament\Resources\SurveySubmissions\Pages\CreateSurveySubmission;
use App\Filament\Resources\SurveySubmissions\Pages\EditSurveySubmission;
use App\Filament\Resources\SurveySubmissions\Pages\ListSurveySubmissions;
use App\Filament\Resources\SurveySubmissions\Schemas\SurveySubmissionForm;
use App\Filament\Resources\SurveySubmissions\Tables\SurveySubmissionsTable;
use App\Models\SurveySubmission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SurveySubmissionResource extends Resource
{
    protected static ?string $model = SurveySubmission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Survey';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return SurveySubmissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SurveySubmissionsTable::configure($table);
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
            'index' => ListSurveySubmissions::route('/'),
            'create' => CreateSurveySubmission::route('/create'),
            'edit' => EditSurveySubmission::route('/{record}/edit'),
        ];
    }
}
