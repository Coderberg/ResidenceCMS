<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Property;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class URLService
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    // Check slugs.
    public function isCanonical(Property $property, string $citySlug = '', string $slug = ''): bool
    {
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
}
