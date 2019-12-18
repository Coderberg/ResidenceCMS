<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 30.08.2018
 * Time: 16:33.
 */

namespace App\Utils;

final class Slugger implements SluggerInterface
{
    public static function slugify(string $string): string
    {
        $string = preg_replace('~[^\\pL\d]+~u', '-', $string);

        $string = Transliterator::transliterate($string);

        $string = trim($string, '-');

        $string = mb_strtolower($string);

        return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
    }
}
