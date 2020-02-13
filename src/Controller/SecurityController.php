<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(Security $security, AuthenticationUtils $helper): Response
    {
        // if user is already logged in, don't display the login page again
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        } elseif ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_account');
        }

        return $this->render('security/login.html.twig', [
            'site' => $this->site(),
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
