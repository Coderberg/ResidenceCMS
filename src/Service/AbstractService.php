<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Cache\ClearCache;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

abstract class AbstractService
{
    use ClearCache;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager, SessionInterface $session)
    {
        $this->tokenManager = $tokenManager;
        $this->session = $session;
    }

    /**
     * Checks the validity of a CSRF token.
     */
    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->tokenManager->isTokenValid(new CsrfToken($id, $token));
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @throws \LogicException
     */
    protected function addFlash(string $type, string $message): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
