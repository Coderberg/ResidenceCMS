<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\Type\PasswordType;
use App\Form\Type\UserEmailType;
use App\Repository\ResettingRepository;
use App\Service\Auth\ResettingService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ResetPasswordController extends BaseController implements AuthController
{
    #[Route(path: '/password/reset', name: 'password_reset', methods: ['GET|POST'])]
    public function passwordReset(ResettingService $service, Request $request): Response
    {
        $form = $this->createForm(UserEmailType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->sendResetPasswordLink($request);
        }

        return $this->render('auth/passwords/password_reset.html.twig', [
            'robots' => 'noindex',
            'site' => $this->site($request),
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/password/reset/{token}', name: 'password_reset_confirm', methods: ['GET|POST'])]
    public function passwordResetConfirm(ResettingRepository $repository, Request $request, string $token): Response
    {
        /** @var User $user */
        $user = $repository->findOneBy(['confirmation_token' => $token]);

        if (!$user) {
            // Token not found.
            return new RedirectResponse($this->generateUrl('security_login'));
        } elseif (!$user->isPasswordRequestNonExpired($user::TOKEN_TTL)) {
            // Token has expired.
            $this->addFlash('danger', 'message.token_expired');

            return new RedirectResponse($this->generateUrl('password_reset'));
        }

        $form = $this->createForm(PasswordType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->setPassword($user, $form->getNormData()['password']);
            $this->addFlash('success', 'message.password_has_been_reset');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('auth/passwords/password_change.html.twig', [
            'robots' => 'noindex',
            'site' => $this->site($request),
            'form' => $form->createView(),
        ]);
    }
}
