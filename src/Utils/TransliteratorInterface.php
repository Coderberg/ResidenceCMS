<?php

declare(strict_types=1);

namespace App\Utils;

interface TransliteratorInterface
{
    public static function transliterate(string $str): string;
}
