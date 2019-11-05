<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PropertyController extends BaseController
{
    /**
     * @Route("/", defaults={"page": "1"}, methods={"GET"}, name="property")
     * @Route("/page/{page<[1-9]\d*>}", methods={"GET"}, name="property_paginated")
     */
    public function index(?int $page): Response
    {
        $properties = $this->getDoctrine()
            ->getRepository(Property::class)->findLatest($page ?? 1);

        return $this->render('property/index.html.twig',
            [
                'site' => $this->site(),
                'properties' => $properties,
                'page' => $page,
            ]
        );
    }

    /**
     * @Route("/property/{id<\d+>}", methods={"GET"}, name="property_show")
     */
    public function propertyShow(Property $property): Response
    {
        return $this->render('property/show.html.twig',
            [
                'site' => $this->site(),
                'property' => $property,
                'number_of_photos' => \count($property->getPhotos()),
            ]
        );
    }

    /**
     * @Route("/search", defaults={"page": "1"}, methods={"GET"}, name="property_search")
     * @Route("/search/page/{page<[1-9]\d*>}", methods={"GET"}, name="property_search_paginated")
     */
    public function search(Request $request, PropertyRepository $properties, ?int $page): Response
    {
        $city_id = (int) ($request->query->get('city', 0));
        $operation_id = (int) ($request->query->get('operation', 0));
        $category_id = (int) ($request->query->get('category', 0));

        return $this->render('property/search.html.twig',
            [
                'site' => $this->site(),
                'properties' => $properties->findByFilter($city_id, $operation_id, $category_id, ($page ?? 1)),
                'page' => $page,
            ]
        );
    }

    /**
     * @Route("/map", methods={"GET"}, name="map_view")
     */
    public function mapView(Request $request, PropertyRepository $repository): Response
    {
        return $this->render('property/map.html.twig',
            [
                'site' => $this->site(),
                'properties' => $repository->findAll(),
            ]
        );
    }
}
