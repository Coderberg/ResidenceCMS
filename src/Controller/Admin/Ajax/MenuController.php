<?php

declare(strict_types=1);

namespace App\Controller\Admin\Ajax;

use App\Controller\AjaxController;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class MenuController extends AbstractController implements AjaxController
{
    /**
     * Sort menu items.
     */
    #[Route(path: '/admin/menu/sort', name: 'admin_menu_sort', methods: ['POST'])]
    public function sort(Request $request, MenuRepository $repository): JsonResponse
    {
        $items = $request->request->all('items');
        $repository->reorderItems($items);

        return new JsonResponse(['status' => 'ok']);
    }
}
