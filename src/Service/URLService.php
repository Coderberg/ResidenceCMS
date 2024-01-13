<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Property;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final readonly class URLService
{
    public function __construct(private RouterInterface $router)
    {
    }

    // Check slugs.
    public function isCanonical(Property $property, Request $request): bool
    {
        $citySlug = $request->attributes->get('citySlug', '');
        $slug = $request->attributes->get('slug', '');

        if ($property->getCity()->getSlug() !== $citySlug || $property->getSlug() !== $slug) {
            return false;
        }

        return true;
    }

    // Generate correct canonical URL.
    public function generateCanonical(Property $property): string
    {
        return $this->router->generate('property_show', [
            'id' => $property->getId(),
            'citySlug' => $property->getCity()->getSlug(),
            'slug' => $property->getSlug(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    // Check referer host.
    public function isRefererFromCurrentHost(Request $request): bool
    {
        return (bool) preg_match('/'.$request->getHost().'/', $request->server->getHeaders()['REFERER'] ?? '');
    }
}
