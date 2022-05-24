<?php

declare(strict_types=1);

namespace App\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final class ThrottleRequests
{
    private RateLimiterFactory $authLimiter;

    public function __construct(RateLimiterFactory $authLimiter)
    {
        $this->authLimiter = $authLimiter;
    }

    public function handle(Request $request): void
    {
        $limiter = $this->authLimiter->create(
            $request->getClientIp().$request->getPathInfo().$request->getMethod()
        );

        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }
    }
}
