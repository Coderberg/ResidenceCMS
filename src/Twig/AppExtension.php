<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('page', [$this, 'showPageNumber']),
        ];
    }

    public function showPageNumber($number = 1)
    {
        return ($number > 1) ? ' - Page '.$number : '';
    }
}
