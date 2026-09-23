<?php

namespace App\Filament\Resources\FinderQuestions;

use App\Filament\Resources\FinderQuestions\Pages\CreateFinderQuestion;
use App\Filament\Resources\FinderQuestions\Pages\EditFinderQuestion;
use App\Filament\Resources\FinderQuestions\Pages\ListFinderQuestions;
use App\Filament\Resources\FinderQuestions\Schemas\FinderQuestionForm;
use App\Filament\Resources\FinderQuestions\Tables\FinderQuestionsTable;
use App\Models\FinderQuestion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FinderQuestionResource extends Resource
{
    protected static ?string $model = FinderQuestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static string|\UnitEnum|null $navigationGroup = 'Find Services';

    protected static ?string $navigationLabel = 'Questions';

    protected static ?string $recordTitleAttribute = 'question';

    public static function form(Schema $schema): Schema
    {
        return FinderQuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinderQuestionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFinderQuestions::route('/'),
            'create' => CreateFinderQuestion::route('/create'),
            'edit' => EditFinderQuestion::route('/{record}/edit'),
        ];
    }
}
