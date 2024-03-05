<?php

declare(strict_types=1);

namespace App\Controller\Ajax\Admin;

use App\Controller\Ajax\AjaxController;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController implements AjaxController
{
    /**
     * Sort menu items.
     */
    #[Route(path: '/admin/menu/sort', name: 'admin_menu_sort', methods: ['POST'])]
    public function sort(Request $request, MenuRepository $repository): JsonResponse
    {
        $items = $request->getPayload()->all('items');
        $repository->reorderItems($items);

        return new JsonResponse(['status' => 'ok']);
    }
}
