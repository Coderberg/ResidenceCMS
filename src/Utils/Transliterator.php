<?php

declare(strict_types=1);

namespace App\Utils;

final class Transliterator implements TransliteratorInterface
{
    /**
     * Russian Cyrillic to Latin Transliteration.
     */
    public static function transliterate(string $str): string
    {
        $a = ['зг', 'Зг', 'А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Ґ', 'ґ', 'Д', 'д', 'Е',
            'е', 'Ё', 'ё', 'Є', 'є', 'Ж', 'ж', 'З', 'з', 'И', 'и', 'І', 'і', 'Ї', 'ї', 'Й', 'й', 'К', 'к',
            'Л', 'л', 'М', 'м', 'Н', 'н', 'О', 'о', 'П', 'п', 'Р', 'р', 'С', 'с', 'Т', 'т', 'У', 'у', 'Ф',
            'ф', 'Х', 'х', 'Ц', 'ц', 'Ч', 'ч', 'Ш', 'ш', 'Щ', 'щ', 'Ы', 'ы', 'Э', 'э', 'Ю', 'ю', 'Я', 'я',
            'Ь', 'ь', 'Ъ', 'ъ',
        ];

        $b = ['zgh', 'Zgh', 'A', 'a', 'B', 'b', 'V', 'v', 'G', 'g', 'G', 'g', 'D', 'd', 'E',
            'e', 'E', 'e', 'Ye', 'ie', 'Zh', 'zh', 'Z', 'z', 'I', 'i', 'I', 'i', 'Yi', 'i', 'Y', 'y', 'K', 'k',
            'L', 'l', 'M', 'm', 'N', 'n', 'O', 'o', 'P', 'p', 'R', 'r', 'S', 's', 'T', 't', 'U', 'u', 'F', 'f', 'Kh', 'kh',
            'Ts', 'ts', 'Ch', 'ch', 'Sh', 'sh', 'Shch', 'shch', 'Y', 'y', 'E', 'e', 'Yu', 'iu', 'Ya', 'ya',
            '', '', '', '',
        ];

        return (string) str_replace($a, $b, $str);
    }
}
