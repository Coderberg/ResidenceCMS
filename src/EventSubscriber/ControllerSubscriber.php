<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\Ajax\AjaxController;
use App\Controller\Auth\AuthController;
use App\Middleware\ThrottleRequests;
use App\Middleware\VerifyCsrfToken;
use Scheb\TwoFactorBundle\Controller\FormController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class ControllerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private VerifyCsrfToken $verifyCsrfToken,
        private ThrottleRequests $throttleRequests,
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (\is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AjaxController) {
            $this->verifyCsrfToken->handle($event->getRequest());
        } elseif ($controller instanceof AuthController || $controller instanceof FormController) {
            $this->throttleRequests->handle($event->getRequest());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
