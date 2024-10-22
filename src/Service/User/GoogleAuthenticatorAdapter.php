<?php

declare(strict_types=1);

namespace App\Service\User;

use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final readonly class GoogleAuthenticatorAdapter implements GoogleAuthenticatorInterface
{
    public function __construct(
        private GoogleAuthenticatorInterface $authenticator,
        private RequestStack $requestStack,
    ) {
    }

    public function checkCode(TwoFactorInterface $user, string $code): bool
    {
        return $this->authenticator->checkCode($user, $code);
    }

    public function getQRContent(TwoFactorInterface $user): string
    {
        return str_replace(
            'issuer_placeholder',
            ucfirst($this->requestStack->getCurrentRequest()->getHost()),
            $this->authenticator->getQRContent($user)
        );
    }

    public function generateSecret(): string
    {
        return $this->authenticator->generateSecret();
    }
}
