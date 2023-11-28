<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class EmailVerifier
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), (string) $user->getId(), $user->getEmail());

        $user->setEmailVerifiedAt(new \DateTime('now'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
