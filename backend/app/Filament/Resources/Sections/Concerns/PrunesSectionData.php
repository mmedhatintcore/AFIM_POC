<?php

namespace App\Filament\Resources\Sections\Concerns;

trait PrunesSectionData
{
    /**
     * Hidden form containers leave `[]` / null husks behind (only the fields
     * for the record's `key` are rendered). Strip them so the stored JSON
     * holds exactly what the section uses.
     */
    protected function pruneSectionData(array $data): array
    {
        foreach (['items', 'cta', 'extra'] as $column) {
            if (array_key_exists($column, $data) && is_array($data[$column])) {
                $data[$column] = $this->pruneEmpty($data[$column]);
            }
        }

        return $data;
    }

    private function pruneEmpty(array $value): array
    {
        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $item = $this->pruneEmpty($item);
            }

            if ($item === null || $item === [] || $item === '') {
                unset($value[$key]);
            } else {
                $value[$key] = $item;
            }
        }

        return $value;
    }
}
