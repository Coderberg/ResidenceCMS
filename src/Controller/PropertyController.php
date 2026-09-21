<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Repository\FilterRepository;
use App\Repository\PropertyRepository;
use App\Repository\SimilarRepository;
use App\Service\URLService;
use App\Transformer\RequestToArrayTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PropertyController extends BaseController
{
    #[Route(path: '/', name: 'property', defaults: ['page' => 1], methods: ['GET'])]
    public function search(
        Request $request,
        FilterRepository $repository,
        RequestToArrayTransformer $transformer,
    ): Response {
        $searchParams = $transformer->transform($request);
        $properties = $repository->findByFilter($searchParams);

        return $this->render(
            'property/index.html.twig',
            [
                'site' => $this->site($request),
                'properties' => $properties,
                'searchParams' => $searchParams,
            ]
        );
    }

    #[Route(path: '/map', name: 'map_view', methods: ['GET'])]
    public function mapView(Request $request, PropertyRepository $repository): Response
    {
        $site = $this->site($request);
        $currency = $site['currency'];
        $markers = [];

        foreach ($repository->findAllPublished() as $property) {
            $latitude = $this->parseCoordinate($property->getLatitude());
            $longitude = $this->parseCoordinate($property->getLongitude());

            if (null === $latitude || null === $longitude) {
                continue;
            }

            $markers[] = [
                'lat' => $latitude,
                'lng' => $longitude,
                'price' => trim(\sprintf(
                    '%s%s %s',
                    $currency->getSymbolLeft() ?? '',
                    (string) $property->getPrice(),
                    $currency->getSymbolRight() ?? '',
                )),
                'category' => $property->getCategory()?->getName() ?? '',
                'url' => $this->generateUrl('property_show', [
                    'id' => $property->getId(),
                    'citySlug' => $property->getCity()?->getSlug(),
                    'slug' => $property->getSlug(),
                ]),
            ];
        }

        return $this->render(
            'property/map.html.twig',
            [
                'site' => $site,
                'markers' => $markers,
                'map_center' => $this->parseMapCenter($site['map_center'] ?? null),
                'map_zoom' => (int) ($site['map_zoom'] ?? 7),
            ]
        );
    }

    private function parseCoordinate(?string $value): ?float
    {
        if (null === $value || '' === trim($value)) {
            return null;
        }

        if (!preg_match('/^-?\d{1,3}(\.\d{1,15})?$/', trim($value))) {
            return null;
        }

        return (float) $value;
    }

    /**
     * @return array{0: float, 1: float}
     */
    private function parseMapCenter(?string $value): array
    {
        $default = [27.188534, -81.128735];

        if (null === $value || '' === trim($value)) {
            return $default;
        }

        $parts = array_map('trim', explode(',', $value, 2));

        if (2 !== \count($parts)) {
            return $default;
        }

        $latitude = $this->parseCoordinate($parts[0]);
        $longitude = $this->parseCoordinate($parts[1]);

        if (null === $latitude || null === $longitude) {
            return $default;
        }

        return [$latitude, $longitude];
    }

    #[Route(
        path: '/{citySlug}/{slug}/{id}',
        name: 'property_show',
        requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET'])
    ]
    #[IsGranted(
        'PROPERTY_VIEW',
        subject: 'property',
        message: 'Properties can only be shown to their owners.'
    )]
    public function propertyShow(
        Request $request,
        URLService $url,
        Property $property,
        SimilarRepository $repository,
    ): Response {
        if (!$url->isCanonical($property, $request)) {
            return $this->redirect($url->generateCanonical($property), Response::HTTP_MOVED_PERMANENTLY);
        } elseif ($url->isRefererFromCurrentHost($request)) {
            $showBackButton = true;
        }

        return $this->render(
            'property/show.html.twig',
            [
                'site' => $this->site($request),
                'property' => $property,
                'properties' => $repository->findSimilarProperties($property),
                'number_of_photos' => \count($property->getPhotos()),
                'show_back_button' => $showBackButton ?? false,
            ]
        );
    }
}
