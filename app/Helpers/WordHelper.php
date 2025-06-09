<?php

namespace App\Helpers;

class WordHelper
{
    public static function isEnglishWord($word)
    {
        $csvPath = base_path('english5letterwords.csv');
        if (!file_exists($csvPath)) {
            return false;
        }
        $words = array_map('strtoupper', array_map('trim', file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
        return in_array(strtoupper($word), $words);
    }
}
