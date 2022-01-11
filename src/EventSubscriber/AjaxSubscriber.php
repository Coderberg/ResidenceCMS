<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\AjaxController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\Exception\TokenNotFoundException;

final class AjaxSubscriber implements EventSubscriberInterface
{
    /**
     * @var CsrfTokenManagerInterface
     */
    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (\is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AjaxController) {
            if (!$this->isCsrfTokenValid($this->getToken($event))) {
                throw new TokenNotFoundException('Sorry, your session has expired. Please refresh and try again.');
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    private function isCsrfTokenValid(?string $token): bool
    {
        return $this->tokenManager->isTokenValid(new CsrfToken('csrf_token', $token));
    }

    private function getToken($event)
    {
        return $event->getRequest()->query->get('csrf_token')
            ?? $event->getRequest()->get('csrf_token');
    }
}
