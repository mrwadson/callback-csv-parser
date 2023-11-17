<?php

namespace mrwadson;

class Helper
{
    /**
     * Check if passed string has at least one bad word
     *
     * @param array $words
     * @param string $string
     *
     * @return int
     */
    public static function matchBadWords(array $words, string $string): int
    {
        if (preg_match_all('/\b(' . implode('|', $words) . ')\b/i', $string, $matches)) {
            return count(array_unique($matches[0]));
        }

        return false;
    }

    /**
     * Check if passed value is URL
     *
     * @param string $url
     * @param array $flags
     *
     * @return bool
     */
    public static function isUrl(string $url, array $flags = []): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL, $flags) !== false;
    }

    /**
     * Check if passed value is domain
     *
     * @param string $domain
     * @param bool $includeTopLevelDomain
     *
     * @return bool
     */
    public static function isDomain(string $domain, bool $includeTopLevelDomain = true): bool
    {
        return filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false
            && (!$includeTopLevelDomain || strpos($domain, '.', true) !== false);
    }

    /**
     * Get last redirection URL from the passed url parameter.
     *
     * @param string $url
     * @param int $maxRedirects
     *
     * @return string|null
     */
    public static function getRedirectLocation(string $url, int $maxRedirects = 3): ?string
    {
        $ch = curl_init($url);
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => $maxRedirects,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_AUTOREFERER => true,
        ];
        curl_setopt_array($ch, $options);
        curl_exec($ch);

        if ($curlErr = curl_error($ch)) {
            echo "$curlErr\n";
            return null;
        }

        $header = curl_getinfo($ch);

        curl_close($ch);

        return $header['url'] ?? null;
    }

    /**
     * Get the cache data from the file
     *
     * @param string $file
     *
     * @return array|null
     */
    public static function getCache(string $file): ?array
    {
        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true);
        }
        return null;
    }

    /**
     * Save the cache data to the file
     *
     * @param string $file
     * @param array $data
     *
     * @return void
     */
    public static function saveCache(string $file, array $data): void
    {
        file_put_contents($file, json_encode($data));
    }
}
