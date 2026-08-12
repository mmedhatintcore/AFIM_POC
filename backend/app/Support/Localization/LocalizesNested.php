<?php

namespace App\Support\Localization;

final class LocalizesNested
{
    /**
     * Recursively replace every `{en: ..., ar: ...}` leaf with the value for
     * the given locale (falling back to the other locale when missing).
     */
    public static function localize(mixed $value, ?string $locale = null): mixed
    {
        $locale = $locale ?: app()->getLocale();

        if (! is_array($value)) {
            return $value;
        }

        if (self::isTranslationLeaf($value)) {
            return $value[$locale] ?? $value['en'] ?? $value['ar'] ?? null;
        }

        return array_map(fn ($item) => self::localize($item, $locale), $value);
    }

    private static function isTranslationLeaf(array $value): bool
    {
        if ($value === []) {
            return false;
        }

        $keys = array_keys($value);

        return array_diff($keys, ['en', 'ar']) === []
            && array_intersect($keys, ['en', 'ar']) !== [];
    }
}
