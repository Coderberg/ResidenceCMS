<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends BaseController
{
    #[Route('/user/security', name: 'user_security')]
    public function profile(Request $request): Response
    {
        return $this->render('user/security/security.html.twig', [
            'site' => $this->site($request),
        ]);
    }
}
