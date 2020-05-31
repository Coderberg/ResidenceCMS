<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Message\SendResetPasswordLink;
use App\Repository\ResettingRepository;
use App\Service\AbstractService;
use App\Utils\TokenGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class ResettingService extends AbstractService
{
    /**
     * @var ResettingRepository
     */
    private $repository;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var TokenGenerator
     */
    private $generator;

    public function __construct(
        CsrfTokenManagerInterface $tokenManager,
        SessionInterface $session,
        ResettingRepository $repository,
        MessageBusInterface $messageBus,
        TokenGenerator $generator
    ) {
        parent::__construct($tokenManager, $session);
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        $this->generator = $generator;
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
