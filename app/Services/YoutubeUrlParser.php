<?php

namespace App\Services;

class YoutubeUrlParser
{
    /**
     * Parsing ID Video YouTube dari berbagai format URL (watch, youtu.be, embed, shorts, dll).
     */
    public static function parseId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // Jika string sudah berupa ID 11 karakter (contoh: dQw4w9WgXcQ)
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        // Regex parsing YouTube ID
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';

        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Memeriksa apakah URL berasal dari domain YouTube yang sah (youtube.com atau youtu.be).
     */
    public static function isValidYoutubeUrl(?string $url): bool
    {
        if (empty($url) || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        return str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be');
    }
}
