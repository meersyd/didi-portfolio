<?php

namespace App\Support;

class VideoEmbed
{
    /**
     * @return array{type: 'iframe'|'file', src: string}|null
     */
    public static function from(?string $value): ?array
    {
        $url = trim((string) $value);

        if ($url === '') {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?(?:[^&]*&)*v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $matches)) {
            return [
                'type' => 'iframe',
                'src' => 'https://www.youtube-nocookie.com/embed/'.$matches[1],
            ];
        }

        if (preg_match('~loom\.com/(?:share|embed)/([A-Za-z0-9]+)~', $url, $matches)) {
            return [
                'type' => 'iframe',
                'src' => 'https://www.loom.com/embed/'.$matches[1],
            ];
        }

        if (preg_match('~(?:player\.)?vimeo\.com/(?:video/)?(\d+)~', $url, $matches)) {
            return [
                'type' => 'iframe',
                'src' => 'https://player.vimeo.com/video/'.$matches[1],
            ];
        }

        $src = preg_match('#^(https?:)?//#i', $url) === 1
            ? $url
            : asset(ltrim($url, '/'));

        return [
            'type' => 'file',
            'src' => $src,
        ];
    }
}
