<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Form\Type\LoginFormType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends BaseController
{
    #[Route(path: '/login', name: 'security_login')]
    public function login(Request $request, Security $security, AuthenticationUtils $helper): Response
    {
        // if user is already logged in, don't display the login page again
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        } elseif ($security->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('user_property');
        }

        $form = $this->createForm(LoginFormType::class);

        return $this->render('auth/login.html.twig', [
            'site' => $this->site($request),
            'error' => $helper->getLastAuthenticationError(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/logout', name: 'security_logout')]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
