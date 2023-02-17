<?php

namespace App;

class Filter
{
    public static function include(array $filter, string $haystack): bool
    {
        $result = false;
        foreach ($filter as $letter) {
            if (strpos($haystack, $letter) !== false) {
                $result = true;
            }
        }
        return $result;
    }

    public static function isUrl(string $url, array $flags = []): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL, $flags) !== false;
    }

    public static function isDomain(string $domain, bool $includeTopLevelDomain = true): bool
    {
        return filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false
            && (!$includeTopLevelDomain || strpos($domain, '.', true) !== false);
    }
}
