<?php

declare(strict_types=1);

namespace App\Controller\Traits;

use App\Entity\Menu;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

trait MenuTrait
{
    protected ManagerRegistry $doctrine;

    protected function setDoctrine(ManagerRegistry $doctrine): void
    {
        $this->doctrine = $doctrine;
    }

    protected function menu(Request $request): array
    {
        return [
            'menu' => $this->doctrine->getRepository(Menu::class)
                ->findBy([
                    'locale' => $request->getLocale(),
                ], ['sort_order' => 'ASC']),
        ];
    }
}
