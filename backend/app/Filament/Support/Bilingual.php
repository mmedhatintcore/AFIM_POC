<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Builders for the EN/AR translation tabs used across every resource form.
 * Field names use Spatie Translatable dot notation (`title.en` / `title.ar`).
 */
final class Bilingual
{
    /**
     * @param array<int, array{name: string, label: string, type?: 'text'|'textarea', required?: bool, rows?: int}> $fields
     */
    public static function tabs(array $fields): Tabs
    {
        return Tabs::make('Translations')
            ->tabs([
                Tab::make('English')->schema(self::fieldSet($fields, 'en')),
                Tab::make('Arabic')->schema(self::fieldSet($fields, 'ar')),
            ])
            ->columnSpanFull();
    }

    private static function fieldSet(array $fields, string $locale): array
    {
        $suffix = $locale === 'en' ? 'English' : 'Arabic';

        return array_map(function (array $field) use ($locale, $suffix) {
            $type = $field['type'] ?? 'text';
            $name = "{$field['name']}.{$locale}";
            $label = "{$field['label']} ({$suffix})";

            $input = $type === 'textarea'
                ? Textarea::make($name)->rows($field['rows'] ?? 4)
                : TextInput::make($name);

            return $input
                ->label($label)
                ->required($field['required'] ?? false);
        }, $fields);
    }

    /** JSON blob editor for structured columns whose shape varies per row. */
    public static function json(string $name, string $label, int $rows = 8): Textarea
    {
        return Textarea::make($name)
            ->label($label)
            ->rows($rows)
            ->formatStateUsing(fn ($state) => $state !== null && ! is_string($state)
                ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : $state)
            ->dehydrateStateUsing(fn (?string $state) => filled($state) ? json_decode($state, true) : null)
            ->rule(function () {
                return function (string $attribute, $value, \Closure $fail) {
                    if (filled($value) && is_string($value) && json_decode($value, true) === null && strtolower(trim($value)) !== 'null') {
                        $fail('The :attribute field must contain valid JSON.');
                    }
                };
            })
            ->columnSpanFull();
    }
}
