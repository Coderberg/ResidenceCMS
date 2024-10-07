<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Entity\User;
use App\Message\SendResetPasswordLink;
use App\Repository\ResettingRepository;
use App\Service\AbstractService;
use App\Utils\TokenGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class ResettingService extends AbstractService
{
    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        RequestStack $requestStack,
        private readonly ResettingRepository $repository,
        private readonly MessageBusInterface $messageBus,
        private readonly TokenGenerator $generator,
    ) {
        parent::__construct($tokenManager, $requestStack);
    }

    public function sendResetPasswordLink(Request $request): void
    {
        /** @var User $user */
        $user = $this->repository->findOneBy(['email' => $request->get('user_email')['email']]);

        if (!$user->isPasswordRequestNonExpired($user::RETRY_TTL)) {
            $this->updateToken($user);
            $this->messageBus->dispatch(new SendResetPasswordLink($user));
            $this->addFlash('success', 'message.emailed_reset_link');
        }
    }

    /**
     * Generating a Confirmation Token.
     */
    private function generateToken(): string
    {
        return $this->generator->generateToken();
    }

    /**
     * Refreshing a Confirmation Token.
     */
    private function updateToken(User $user): void
    {
        $this->repository->setToken($user, $this->generateToken());
    }
}
