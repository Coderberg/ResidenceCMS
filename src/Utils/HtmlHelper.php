<?php

declare(strict_types=1);

namespace App\Utils;

final class HtmlHelper
{
    public static function html2Text(string $html): string
    {
        $text = preg_replace('#<br\s*/?>#i', "\n", $html);

        return strip_tags((string) $text);
    }

    public static function text2Html(string $text): string
    {
        return preg_replace("/\r\n|\r|\n/", '<br>', $text);
    }

    public static function removeScriptsFromHtml(string $html): string
    {
        $sanitizedHtml = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
        $sanitizedHtml = preg_replace('# on\w+="[^"]*"#i', '', (string) $sanitizedHtml);

        return preg_replace("# on\w+='[^']*'#i", '', (string) $sanitizedHtml);
    }
}
