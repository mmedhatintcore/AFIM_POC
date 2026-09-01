<?php

namespace App\Filament\Resources\NewsPosts\Schemas;

use App\Filament\Support\Bilingual;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NewsPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()
                    ->helperText('URL segment on the website.'),
                Select::make('type')
                    ->options(['press' => 'Press release', 'media' => 'Media coverage', 'social' => 'Social media'])
                    ->required(),
                TextInput::make('source')
                    ->required()
                    ->helperText('e.g. "AFIM Press Release", "Al-Dostor", "LinkedIn · @afpm".'),
                DateTimePicker::make('published_at'),
                Toggle::make('is_published')
                    ->default(true),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->disk('public')
                    ->directory('news')
                    ->image()
                    ->imageEditor()
                    ->helperText('Optional thumbnail shown on the news feed and article page.'),
                Bilingual::tabs([
                    ['name' => 'title', 'label' => 'Title', 'required' => true],
                    ['name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea', 'rows' => 3, 'required' => true],
                    ['name' => 'body', 'label' => 'Body', 'type' => 'textarea', 'rows' => 10, 'required' => true],
                ]),
            ]);
    }
}
