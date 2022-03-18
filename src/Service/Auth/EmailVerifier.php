<?php

declare(strict_types=1);

namespace App\Service\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

final class EmailVerifier
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private EntityManagerInterface $entityManager;

    public function __construct(VerifyEmailHelperInterface $helper, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->entityManager = $manager;
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), (string) $user->getId(), $user->getEmail());

        $user->setEmailVerifiedAt(new \DateTime('now'));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
