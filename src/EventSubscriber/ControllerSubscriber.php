<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Controller\AjaxController;
use App\Middleware\VerifyCsrfToken;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ControllerSubscriber implements EventSubscriberInterface
{
    private VerifyCsrfToken $middleware;

    public function __construct(VerifyCsrfToken $middleware)
    {
        $this->middleware = $middleware;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (\is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AjaxController) {
            $this->middleware->handle($event->getRequest());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
