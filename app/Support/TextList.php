<?php

namespace App\Support;

class TextList
{
    /**
     * @return list<string>
     */
    public static function from(null|array|string $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(
                static fn (mixed $item): string => trim((string) $item),
                $value,
            )));
        }

        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $parts = preg_split('/[\r\n,]+/', $value) ?: [];

        return array_values(array_filter(array_map('trim', $parts)));
    }

    /**
     * Split pasted text on line breaks only, so commas inside sentences stay intact.
     *
     * @return list<string>
     */
    public static function fromLines(null|array|string $value): array
    {
        if (is_array($value)) {
            return self::from($value);
        }

        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        $parts = preg_split('/\r\n|\n|\r/', $value) ?: [];

        return array_values(array_filter(array_map('trim', $parts), static fn (string $item): bool => $item !== ''));
    }

    /**
     * @param  list<string>|null  $value
     */
    public static function toText(?array $value, string $separator = "\n"): string
    {
        return implode($separator, $value ?? []);
    }
}
