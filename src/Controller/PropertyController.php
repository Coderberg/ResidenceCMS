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
        return $this->render(
            'property/map.html.twig',
            [
                'site' => $this->site($request),
                'properties' => $repository->findAllPublished(),
            ]
        );
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
