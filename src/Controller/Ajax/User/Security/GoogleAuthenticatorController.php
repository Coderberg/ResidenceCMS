<?php

declare(strict_types=1);

namespace App\Controller\Ajax\User\Security;

use App\Controller\Ajax\AjaxController;
use App\Entity\User;
use App\Service\User\GoogleAuthenticatorService;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class GoogleAuthenticatorController extends AbstractController implements AjaxController
{
    public function __construct(private readonly GoogleAuthenticatorService $service)
    {
    }

    #[Route(path: '/user/google_authenticator_code', name: 'get_auth_code', methods: ['GET'])]
    public function getAuthCode(): JsonResponse
    {
        try {
            /** @var User|TwoFactorInterface $user */
            $user = $this->getUser();

            return new JsonResponse($this->service->generateSecret($user));
        } catch (\Throwable $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    #[Route(path: '/user/google_authenticator_code', name: 'set_auth_code', methods: ['PUT'])]
    public function setAuthCode(Request $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            $authenticationCode = $request->get('authentication_code');
            $secret = $request->get('secret');

            $this->service->setSecret($user, $secret, $authenticationCode);

            return new JsonResponse();
        } catch (\Throwable $exception) {
            return new JsonResponse([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    #[Route(path: '/user/google_authenticator_code', name: 'delete_auth_code', methods: ['DELETE'])]
    public function deleteAuthCode(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->service->deleteSecret($user);
        } catch (\Throwable) {
            $this->addFlash('danger', '2fa.errors.cannot_disable_ga');
        }

        return new JsonResponse();
    }
}
