<?php

declare(strict_types=1);

namespace App\Utils;

interface SluggerInterface
{
    public static function slugify(string $string): string;
}
