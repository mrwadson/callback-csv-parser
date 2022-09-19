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
}
