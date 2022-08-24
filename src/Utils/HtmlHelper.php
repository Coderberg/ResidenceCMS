<?php

declare(strict_types=1);

namespace App\Utils;

final class HtmlHelper
{
    public static function html2Text(string $html): string
    {
        $text = preg_replace('#<br\s*/?>#i', "\n", $html);

        return strip_tags($text);
    }

    public static function text2Html(string $text): string
    {
        return preg_replace("/\r\n|\r|\n/", '<br>', $text);
    }
}
