<?php

namespace App\Support;

class RichText
{
    /**
     * @return list<string>
     */
    public static function paragraphs(?string $body): array
    {
        if (! is_string($body) || trim($body) === '') {
            return [];
        }

        $normalized = str_replace(["\r\n", "\r"], "\n", $body);
        $parts = preg_split('/\n\s*\n/', $normalized) ?: [];

        return array_values(array_filter(array_map('trim', $parts)));
    }

    public static function inline(string $text): string
    {
        $escaped = e($text);

        return preg_replace('/\*\*(.+?)\*\*/s', '<span class="font-semibold text-ink">$1</span>', $escaped) ?? $escaped;
    }
}
