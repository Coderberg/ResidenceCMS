<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Message\SendResetPasswordLink;
use App\Repository\ResettingRepository;
use App\Utils\TokenGenerator;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

final class ResettingService extends AbstractService
{
    const RETRY_TTL = 3600;

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
        ContainerInterface $container,
        ResettingRepository $repository,
        MessageBusInterface $messageBus,
        TokenGenerator $generator
    ) {
        parent::__construct($container);
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        $this->generator = $generator;
    }

    public function sendResetPasswordLink(Request $request): void
    {
        /** @var User $user */
        $user = $this->repository->findOneBy(['email' => $request->get('user_email')['email']]);

        if (!$user->isPasswordRequestNonExpired(self::RETRY_TTL)) {
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
