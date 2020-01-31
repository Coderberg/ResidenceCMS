<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Message\SendResetPasswordLink;
use App\Repository\ResettingRepository;
use App\Utils\TokenGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

final class ResettingService
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

    public function __construct(ResettingRepository $repository, MessageBusInterface $messageBus, TokenGenerator $generator)
    {
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        $this->generator = $generator;
    }

    public function sendResetPasswordLink(Request $request): string
    {
        /** @var User $user */
        $user = $this->repository->findOneBy(['email' => $request->get('email')]);

        if ($user) {
            if ($user->isPasswordRequestNonExpired(self::RETRY_TTL)) {
                return '';
            }

            $this->updateToken($user);
            $this->messageBus->dispatch(new SendResetPasswordLink($user));

            return 'success';
        }

        return 'error';
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
