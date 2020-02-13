<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Service\User\AccountService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class AccountController extends BaseController
{
    /**
     * @Route("/user/account", defaults={"page": "1"}, methods={"GET"}, name="user_account")
     */
    public function index(Request $request, AccountService $service): Response
    {
        $properties = $service->getUserProperties($request);

        return $this->render('user/account/index.html.twig', [
            'properties' => $properties,
            'site' => $this->site(),
        ]);
    }
}
