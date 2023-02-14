<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class AppExtension extends AbstractExtension
{
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('page', $this->showPageNumber(...)),
        ];
    }

    public function showPageNumber($number = 1): string
    {
        return ($number > 1) ? ' - '.$this->translator->trans('page').' '.$number : '';
    }
}
