<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\AjaxController;
use App\Controller\Auth\AuthController;
use App\Middleware\ThrottleRequests;
use App\Middleware\VerifyCsrfToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ControllerSubscriber implements EventSubscriberInterface
{
    private VerifyCsrfToken $verifyCsrfToken;
    private ThrottleRequests $throttleRequests;

    public function __construct(VerifyCsrfToken $verifyCsrfToken, ThrottleRequests $throttleRequests)
    {
        $this->verifyCsrfToken = $verifyCsrfToken;
        $this->throttleRequests = $throttleRequests;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (\is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AjaxController) {
            $this->verifyCsrfToken->handle($event->getRequest());
        } elseif ($controller instanceof AuthController) {
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
